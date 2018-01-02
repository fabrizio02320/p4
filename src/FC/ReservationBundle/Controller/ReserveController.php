<?php

namespace FC\ReservationBundle\Controller;

use FC\ReservationBundle\Entity\Commande;
use FC\ReservationBundle\Entity\Ticket;
use FC\ReservationBundle\Form\CommandeType;
use FC\ReservationBundle\Form\MultiTicketType;
use FC\ReservationBundle\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReserveController extends Controller {

    /**
     * Page d'accueil et reset une commande si demandé
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
//        echo '<br /><br /><br /><br /><br /><br /><br /><br />';
//        print_r($_GET);
        if(isset($_GET['resetCommande']) && $_GET['resetCommande'] === 1){
            $servCommande = $this->get('fc_reserve.servcommande');
            $servCommande->resetCommande();
        }
        return $this->render('FCReservationBundle:Home:index.html.twig');
    }

    public function infoVisiteAction(Request $request) {
        // récupération des outils d'une commande et l'initialise si nécessaire
        $servCommande = $this->get('fc_reserve.servcommande');

        // récupération d'une commande déjà existante, ou en créer une nouvelle
        $commande = $servCommande->initCommande();

        // création du formulaire
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        // vérification du formulaire reçu
        if($form->isSubmitted() && $form->isValid()){

            echo '<br /><br /><br /><br /><br /><br />';
            echo $commande->getRef();
            // si formulaire ok, redirige vers la deuxième étape
            if($servCommande->validCommande($commande)){

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
        // récupération des outils d'une commande
        $servCommande = $this->get('fc_reserve.servcommande');

        // Récupération des informations concernant la commande rempli à l'étape 1
        $commande = $servCommande->initCommande();

        // si pb dans la commande, redirige à l'étape précédente
        if(!$servCommande->validCommande($commande)){
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
            return $this->redirectToRoute('info-paiement');
        } else {

            // si pas de formulaire reçu ou si formulaire reçu pas ok,
            // redirige à l'étape précédente
            return $this->render('FCReservationBundle:Reserve:infoTicket.html.twig', array(
                'form' => $form->createView(),
                'commande' => $commande,
            ));
        }
    }
}