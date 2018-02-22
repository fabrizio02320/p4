<?php

namespace FC\ReservationBundle\Controller;

use FC\ReservationBundle\Form\VisiteType;
use FC\ReservationBundle\Form\CommandeType;
use FC\ReservationBundle\Form\MultiTicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReserveController extends Controller {

    /**
     * Page d'accueil et reset une commande si demandé
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $servCommande = $this->get('fc_reserve.servcommande');
        if(isset($_GET['resetCommande']) && $_GET['resetCommande'] == true){
            $servCommande->resetCommande();
        }

        return $this->render('FCReservationBundle:Home:index.html.twig');
    }

    public function infoVisiteAction(Request $request) {
        // ici, nous sommes à la 1ere étape

        // récupération des outils d'une commande et l'initialise si nécessaire
        $servCommande = $this->get('fc_reserve.servcommande');

        // récupération d'une commande déjà existante, ou en créer une nouvelle
        $commande = $servCommande->initCommande();

        // création du formulaire
        $form = $this->createForm(VisiteType::class, $commande);
        $form->handleRequest($request);

        // vérification du formulaire reçu
        if($form->isSubmitted() && $form->isValid()){

            // si formulaire ok, redirige vers la deuxième étape
            if($servCommande->validCommande($commande, 1)){
                return $this->redirectToRoute('info-ticket');
            }
        }

        // si pas de formulaire reçu ou si formulaire reçu pas ok,
        // réaffiche le formulaire de la première étape
        return $this->render('FCReservationBundle:Reserve:infoVisite.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /*
     * Fonction utilisé pour la deuxième étape -
     */
    public function infoTicketAction(Request $request){
        // 2eme étape

        // récupération des outils d'une commande
        $servCommande = $this->get('fc_reserve.servcommande');

        // Récupération de la commande
        $commande = $servCommande->initCommande();

        // si pb dans la commande, redirige à l'étape précédente
        if(!$servCommande->validCommande($commande, 1)){
            return $this->redirectToRoute('info-visite');
        }

        // mets à jour les tickets dans la commande
        $servCommande->updateTickets($commande);

        // création du formulaire pour saisie des info de chaque ticket
        $form = $this->get('form.factory')->create(MultiTicketType::class, $commande);
        $form->handleRequest($request);

        // vérification du formulaire reçu
        // si formulaire ok, redirige vers l'étape pour le paiement
        if($form->isSubmitted() && $form->isValid()){

//            echo 'getEtape : '. $servCommande->getEtapeCommande($commande);
//            echo '<br />Etape indiqué dans la fonction : 2';
//            die;
            if($servCommande->validCommande($commande, 2)){

                return $this->redirectToRoute('recap-commande');
            }

        }

        // si pas de formulaire reçu ou si formulaire reçu pas ok,
        // redirige à l'étape précédente
        return $this->render('FCReservationBundle:Reserve:infoTicket.html.twig', array(
            'form' => $form->createView(),
            'commande' => $commande,
            'pathModifyCommande' => 'info-visite',
        ));
    }

    /*
     * Fonction utilisé pour la troisième étape - Récapitulatif de la commande et info facturation
     */
    public function recapCommandeAction(Request $request){
        // 3eme étape

        // récupération des outils d'une commande
        $servCommande = $this->get('fc_reserve.servcommande');

        // Récupération des informations concernant la commande rempli à l'étape 2
        $commande = $servCommande->initCommande();

        // mets à jour les tickets dans la commande
        $servCommande->updateTickets($commande);

        // si pb dans la commande, redirige à l'étape précédente
        if(!$servCommande->validCommande($commande, 3)){
            return $this->redirectToRoute('info-ticket');
        }

        // création du formulaire pour saisie des info du client qui passe la commande
        $form = $this->get('form.factory')->create(CommandeType::class, $commande);
        $form->handleRequest($request);

        // vérification du formulaire reçu
        // si formulaire ok, redirige vers l'étape pour le paiement
        if($form->isSubmitted() && $form->isValid()){
            if($servCommande->validCommande($commande, 3)) {
                return $this->redirectToRoute('payment');
            }
        } else {

            // si pas de formulaire reçu ou si formulaire reçu pas ok,
            // redirige à l'étape précédente
            return $this->render('@FCReservation/Reserve/recapCommande.html.twig', array(
                'form' => $form->createView(),
                'commande' => $commande,
                'pathModifyCommande' => 'info-ticket',
                'servTicket' => $this->get('fc_reserve.servtickets'),
                'heureDebDemiJournee' => $servCommande->getHeureDebDemiJournee(),
            ));
        }
    }

    public function finCommandeAction(){
        // 5eme étape

        // service commande
        $servCommande = $this->get('fc_reserve.servcommande');

        $commande = $servCommande->initCommande();

        // récupère la référence de la commande
        $refCommande = $servCommande->getRefCommande();

        // si la commande n'est pas finalisé, la refCommande est null...
        if($refCommande === null || !$servCommande->validCommande($commande, 4)){
            return $this->redirectToRoute('payment');
        }

        // la commande a été passé, on peut la ré initialiser
        $servCommande->resetCommande();

        // page de fin de la commande
        return $this->render('@FCReservation/Reserve/finCommande.html.twig', array(
            'refCommande' => $refCommande,
        ));
    }

    public function infoTarifAction(Request $request){
        $servCommande = $this->get('fc_reserve.servcommande');
        $route = $servCommande->referer($request, $this->container->get('router'));

        return $this->render('@FCReservation/Reserve/infoTarif.html.twig', array(
            'route' => $route,
        ));
    }

    public function infoGlobalAction(Request $request){
        $servCommande = $this->get('fc_reserve.servcommande');
        $route = $servCommande->referer($request, $this->container->get('router'));

        return $this->render('@FCReservation/Reserve/infoGlobal.html.twig', array(
            'route' => $route,
        ));
    }
}