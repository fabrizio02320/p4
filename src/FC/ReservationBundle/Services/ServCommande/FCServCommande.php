<?php

namespace FC\ReservationBundle\Services\ServCommande;

use FC\ReservationBundle\Entity\Commande;
use FC\ReservationBundle\Entity\Ticket;
use FC\ReservationBundle\Services\ServTickets\FCServTickets;
//use Doctrine\ORM\EntityManager;

class FCServCommande
{
    private $session;
    private $servTickets;
//    private $em;

    public function __construct
    (
        \Symfony\Component\HttpFoundation\Session\Session $session,
        \FC\ReservationBundle\Services\ServTickets\FCServTickets $servTickets
//        $nbTicketMaxParJour
//        \Doctrine\ORM\EntityManager $em
    )
    {
        $this->servTickets = $servTickets;
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
        $commandeValid = false;

        // vérification de la date de la visite
        if($this->validDate($commande)){

            $commandeValid = true;

            // si la commande possède des tickets, on les enregistre en session
            // pour les persister qu'à la fin de la commande
//            if($commande->getNbTicket() > 0){
//                $this->enregTicketsSession($commande);
//            }
        }

        // si la commande est valide, on la persiste pour réserver les disponibilités en bd
//        $this->em->persist($commande);
//        $em = $this->getDoctrine()->getManager();

        return $commandeValid;
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

//        $em = $this->get('doctrine.orm.entity_manager');

        return true;
    }

    /**
     * @param Commande $commande
     */
    public function getTicketSession($commande){
        $ticketsSession = $this->session->get('tickets');

        // s'il y a bien des tickets en session
        if(count($ticketsSession) > 0){
//            echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />Ticket en session';
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
        // D'abord, met à jour le nombre de tickets dans la commande
        $this->updateNbTickets($commande);

        // ensuite, met à jour le prix des tickets
        foreach($commande->getTickets() as $ticket){
            $this->servTickets->calculPrixTicket($ticket, $commande);
        }

        // enfin, met à jour le prix de la commande
        $this->calculPrixCommande($commande);
    }

    /**
     * Calcul le prix d'une commande et lui assigne
     *
     * @param Commande $commande
     */
    public function calculPrixCommande(Commande $commande){
        $prix = 0;

        foreach ($commande->getTickets() as $ticket){
            $prix += $ticket->getPrix();
        }

        $commande->setPrix($prix);
    }

    /**
     * Mets à jour le nombre de tickets dans la commande
     *
     * @param Commande $commande
     */
    public function updateNbTickets(Commande $commande){
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
                $commande->addTicket($ticket);
            }
        }

        // s'il y a des tickets en trop, les supprimes en partant du dernier
        if($diffNbTickets < 0){
            for ($j = 0; $j < -$diffNbTickets; $j++){
                $ticket = $commande->getTickets()[$nbTickets - (1 + $j)];
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

    public function test(){
        echo '<br /><br /><br /><br /><br /><br /><br /><br /><br />Test';
    }

    /**
     * Sauvegarde les tickets de la commande en session pour ne les persister qu'à la fin
     *
     * @param Commande $commande
     * @return bool
     */
    public function enregTicketsSession(Commande $commande){
        $listeTickets = array();
        foreach ($commande->getTickets() as $ticket){
//            echo '<br /><br /><br /><br /><br /><br /><br /><br /><br />Enreg Ticket Session ';
            // calcul du prix du ticket

            // garde le ticket de côté
            array_push($listeTickets, $ticket);

            // retire le ticket de la commande (pour le persister qu'à la fin)
            $commande->removeTicket($ticket);
        }

        // on enregistre les tickets en session
        $this->session->set('tickets', $listeTickets);
        return true;
    }
}