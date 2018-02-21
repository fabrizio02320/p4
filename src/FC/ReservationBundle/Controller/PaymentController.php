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
     * @throws \Twig\Error\Error
     */
    public function paymentAction(Request $request){
        // ici nous sommes à la 4ème étape

        // récupération des outils d'une commande
        $servCommande = $this->get('fc_reserve.servcommande');

        // Récupération de la commande
        $commande = $servCommande->initCommande();

        if(!$servCommande->validCommande($commande, 4)) {
            return $this->redirectToRoute('recap-commande');
        }

        if($request->isMethod('POST') && $servCommande->validCommande($commande, 4)){
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
            'heureDebDemiJournee' => $servCommande->getHeureDebDemiJournee(),
        ));
    }
}