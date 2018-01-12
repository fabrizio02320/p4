<?php

namespace FC\ReservationBundle\Services\ServTickets;

use FC\ReservationBundle\Entity\Commande;
use FC\ReservationBundle\Entity\Ticket;

class FCServTickets{
    private $ageMaxEnfant = 12;
    private $ageMaxGratuit = 3;
    private $ageMinSenior = 60;
    private $tarifReduit = 10;
    private $tarifEnfant = 8;
    private $tarifNormal = 16;
    private $tarifSenior = 12;

    /**
     * @param Ticket $ticket
     */
    public function calculPrixTicket(Ticket $ticket,Commande $commande){
        $dateVisite = $commande->getDateVisite();

        // calcul l'age du visiteur du ticket en question
        $age = $this->age($ticket->getDdn(), $dateVisite);

        // tarif gratuit
        if($age <= $this->ageMaxGratuit){
            $prix = 0;
        }

        // tarif enfant
        elseif($age <= $this->ageMaxEnfant){
            $prix = $this->tarifEnfant;
        }

        // tarif réduit
        elseif($age > $this->ageMaxEnfant && $ticket->getTarifReduit()){
            $prix = $this->tarifReduit;
        }

        // tarif senior
        elseif($age >= $this->ageMinSenior){
            $prix = $this->tarifSenior;
        }

        // tarif normal
        else{
            $prix = $this->tarifNormal;
        }

        // si demi-journée
        if($commande->getDemiJournee()){
            $prix /= 2;
        }

        $ticket->setPrix($prix);
    }

    /**
     * Calcul l'age en fonction de la date de naissance et de la date du jour de la visite
     *
     * @param string $ddn
     * @param string $date
     * @return int
     */
    public function age($ddn, $date){
        if(!$ddn){
            return 0;
        }

        // si la date à comparer est inférieur à la date de naissance, retourne 0
        if($date->format('Y') <= $ddn->format('Y')){
            $age = 0;
        } else {
            $age = $date->format('Y') - $ddn->format('Y');
            if($ddn->format('md') < $date->format('md')){
                $age -= 1;
            }
        }
        return $age;
    }
}