<?php

namespace FC\ReservationBundle\Services\ServCommande;

use FC\ReservationBundle\Entity\Commande;
use FC\ReservationBundle\Entity\Ticket;

class FCServCommande
{
    private $session;

    public function __construct
    (
        \Symfony\Component\HttpFoundation\Session\Session $session
//        \FC\ReservationBundle\Services\ServTickets\FCServTickets $servTicket,
//        $nbTicketMaxParJour
//        \Doctrine\ORM\EntityManager $em
    )
    {
//        $this->servTicket = $servTicket;
//        $this->nbTicketsMaxParJour = 1000;
//        $this->em = $em;
        $this->session = $session;
    }

    /**
     * Récupère la commande en cours si elle est en mémoire
     * ou en créé une nouvelle
     *
     * @return Commande
     */
    public function initCommande()
    {
        // récupère la commande en session si elle existe
        $commande = $this->session->get('commande');

        // si la commande n'existe pas, on la créée
        if($commande === null){
            $commande = new Commande();
            $this->session->set('commande', $commande);
        }

        // vérifie et ajoute d'éventuel ticket en session
        $this->getTicketSession($commande);

        return $commande;
    }

    public function validCommande(Commande $commande)
    {
        // vérification de la date de la visite
        if(!$this->validDate($commande)){
            return false;
        }

        return true;
    }

    public function validDate(Commande $commande)
    {
        // vérification sur les jours de fermeture récurrent
        $dateVisite = $commande->getDateVisite();
//        $aujourdhui = new \DateTime("now", new \DateTimeZone('Europe/Paris'));

        $jourInterdit = array(
            '01/01',
            '01/05',
            '08/05',
            '14/07',
            '15/08',
            '01/11',
            '11/11',
            '25/12',
            '27/12',
        );
//        $dateInterdite = array(
//            '02/04/2018',
//            '10/05/2018',
//            '21/05/2018',
//        );

        // vérif sur les jours interdits
        if(in_array($dateVisite->format('d/m'), $jourInterdit)){
            $this->session->getFlashBag()->add('warning', "Désolé, le Musée est fermé pour le jour que vous avez choisi, veuillez choisir une autre date.");
            return false;
        }

        return true;
    }

    /**
     * @param Commande $commande
     */
    public function getTicketSession($commande){
        $ticketsSession = $this->session->get('tickets');

        // s'il y a bien des tickets en session
        if(count($ticketsSession) > 0){
            foreach($ticketsSession as $ticket){
                $commande->addTicket($ticket);
            }
        }
    }

    /**
     * Mets à jour les tickets dans une commande
     *
     * @param $commande
     */
    public function updateTickets(Commande $commande){
        // on vérifie si la commande est déjà à jour par rapport au nombre de ticket
        $nbTickets = count($commande->getTickets());
        $diffNbTickets = $commande->getNbTicket() - $nbTickets;
        if($diffNbTickets === 0){
            return;
        }

        // si il manque des tickets dans la commande, on les ajoutes
        if($diffNbTickets > 0){
            for($i = 0; $i < $diffNbTickets; $i++){
                $ticket = new Ticket();
                $ticket->setCommande($commande);
                $commande->addTicket($ticket);
            }
        }

        // s'il y a des tickets en trop, les supprimes en partant du dernier
        if($diffNbTickets < 0){
            for ($j = 0; $j < -$diffNbTickets; $j++){
//                $commande->removeTicket($commande->getTickets()[$nbTickets - (1 + $j)]);
                $ticket = $commande->getTickets()[$nbTickets - (1 + $j)];
                // TODO - probleme de conversion d'array...
//                echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
//                echo 'type '. gettype($ticket);
                $commande->removeTicket($ticket);
            }
        }
    }

    /**
     * Annule la commande en mémoire si elle existe et en initialise une nouvelle
     *
     */
    public function resetCommande(){
        $this->session->remove('tickets');
        $this->session->remove('commande');
        $this->initCommande();
    }
}