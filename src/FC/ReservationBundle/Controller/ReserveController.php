<?php

namespace FC\ReservationBundle\Controller;

use FC\ReservationBundle\Entity\Commande;
use FC\ReservationBundle\Entity\Ticket;
use FC\ReservationBundle\Form\CommandeType;
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

    public function indexAction() {
        return $this->render('FCReservationBundle:Home:index.html.twig');
    }

    public function infoVisiteAction(Request $request) {
        // récupération des outils d'une commande

        // récupération d'une commande déjà existante, ou en créer une nouvelle

        // création du formulaire

        // vérification du formulaire reçu
            // si formulaire ok, redirige vers la deuxième étape

        // si pas de formulaire reçu ou si formulaire reçu pas ok,
        // réaffiche le formulaire de la première étape


        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            return $this->redirectToRoute('info-ticket');
        } else {
            return $this->render('FCReservationBundle:Reserve:infoVisite.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /*
     * Fonction utilisé pour la deuxième étape -
     */
    public function infoTicketAction(Request $request){
        // récupération des outils d'une commande

        // Récupération des informations concernant la commande rempli à l'étape 1

        // si pb dans la commande, redirige à l'étape précédente

        // création du formulaire pour saisie des info de chaque billet

        // vérification du formulaire reçu
        // si formulaire ok, redirige vers l'étape pour le paiement

        // si pas de formulaire reçu ou si formulaire reçu pas ok,
        // redirige à l'étape précédente


        // partie temporaire pour afficher la deuxième étape
        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(TicketType::class, $ticket);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            return $this->redirectToRoute('info-paiement');
        } else {
            return $this->render('FCReservationBundle:Reserve:infoTicket.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }
}