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

    public function infoTicketAction(Request $request){
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