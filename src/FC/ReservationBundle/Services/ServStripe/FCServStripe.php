<?php

namespace FC\ReservationBundle\Services\ServStripe;

use FC\ReservationBundle\Entity\Commande;
use FC\ReservationBundle\Services\ServCommande\FCServCommande;
use Symfony\Component\HttpFoundation\Request;

class FCServStripe
{
    private $session;
    private $servCommande;

    public function __construct(FCServCommande $servCommande, \Symfony\Component\HttpFoundation\Session\Session $session, $stripe_secret_key)
    {
        \Stripe\Stripe::setApiKey($stripe_secret_key);
        $this->session = $session;
        $this->servCommande = $servCommande;
    }

    /**
     * Méthode qui envoie la demande de paiement chez Stripe
     *
     * @param Request $request
     * @param Commande $commande
     * @return bool
     */
    public function charge(Request $request, Commande $commande){
        $token = $request->request->get('stripeToken');

        // Charge the user's card:
//        $charge = \Stripe\Charge::create(array(
//            "amount" => 1000,
//            "currency" => "usd",
//            "description" => "Example charge",
//            "capture" => false,
//            "source" => $token,
//        ));

        try{
            \Stripe\Charge::create(array(
                "amount" => $commande->getPrix() * 100,
                "currency" => "eur",
                "description" => "Louvre-". $commande->getRef(),
                "capture" => false,
                "source" => $token,
            ));

            return true;
        }
        catch(\Stripe\Error\Card $e){

            $this->session->getFlashBag()->add('danger', 'Le paiement n\'a pas été effectué, veuillez réessayer');
            return false;
        }
    }
}