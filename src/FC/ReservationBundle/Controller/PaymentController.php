<?php

namespace FC\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller{
    /**
     * Action de la vue payment
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function paymentAction(Request $request){
        // récupération des outils d'une commande
        $servCommande = $this->get('fc_reserve.servcommande');

        // Récupération de la commande
        $commande = $servCommande->initCommande();

        if($request->isMethod('POST')){
            // on utilise le service Stripe, si paiement ok, on finalise la commande,
            // et on affiche un recap
            $servStripe = $this->get('fc_reserve.servstripe');
            if($servStripe->charge($request, $commande)){
                // push la commande et redirige vers une page de remerciement
                if($servCommande->enregCommande($commande)){
                    return $this->redirectToRoute('fin-commande');
                }
            }
        }


        return $this->render('FCReservationBundle:Payment:payment.html.twig', array(
            'commande' => $commande,
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
            'pathModifyCommande' => 'recap-commande',
        ));
    }
}