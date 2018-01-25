<?php

namespace FC\ReservationBundle\Services\ServCommande;

use FC\ReservationBundle\Entity\Commande;
use FC\ReservationBundle\Entity\Ticket;
use FC\ReservationBundle\Services\ServTickets\FCServTickets;
use Symfony\Component\Config\Definition\Exception\Exception;

class FCServCommande
{
    private $session;
    private $servTickets;
    private $em;
    private $nbTicketsMaxJour;
    private $heureDebDemiJournee;
    private $mailer;
    private $templating;
    private $mailerFrom;

    /**
     * FCServCommande constructor.
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Doctrine\ORM\EntityManager $em
     * @param FCServTickets $servTickets
     * @param $nbTicketsMaxJour
     * @param $heureDebDemiJournee
     */
    function __construct
    (
        \Symfony\Component\HttpFoundation\Session\Session $session,
        \Doctrine\ORM\EntityManager $em,
        \FC\ReservationBundle\Services\ServTickets\FCServTickets $servTickets,
        $nbTicketsMaxJour,
        $heureDebDemiJournee,
        \Swift_Mailer $mailer,
        \Symfony\Bundle\TwigBundle\TwigEngine $templating,
        $mailerFrom
    )
    {
        $this->session = $session;
        $this->em = $em;
        $this->servTickets = $servTickets;
        $this->nbTicketsMaxJour = $nbTicketsMaxJour;
        $this->heureDebDemiJournee = $heureDebDemiJournee;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->mailerFrom = $mailerFrom;
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

            // et si une ancienne référence de commande est en mémoire, on la supprime
            $this->session->remove('refCommande');
        }

        return $commande;
    }

    public function validCommande(Commande $commande)
    {
        // todo fonction à implémenter
        $commandeValid = false;

        // vérification de la date de la visite
        if($this->validDate($commande) && $this->verifNbTicket($commande)){

            $commandeValid = true;

        }

        return $commandeValid;
    }

    public function validDate(Commande $commande)
    {
        // vérification sur les jours de fermeture récurrent
        $dateVisite = $commande->getDateVisite();
        $aujourdhui = new \DateTime("now", new \DateTimeZone('Europe/Paris'));

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

        // vérification si la date de visite est pour le jour même,
        // si demi-journée est sélectionné à partir de 14H
        if(!$commande->getDemiJournee()){
            if(
                $dateVisite->format('Ymd') === $aujourdhui->format('Ymd')
                && $aujourdhui->format('H') >= $this->heureDebDemiJournee
            )
            {
                $this->session->getFlashBag()->add('warning', "Vous ne pouvez pas choisir des tickets 'Journée' pour le jour-même après 14H00.");
                return false;
            }
        }

        return true;
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
        $this->session->remove('commande');
//        $this->initCommande();
    }

    /**
     * Enregistre la commande en base de donnée
     * envoie l'email de confirmation
     * et nettoie si enregistrement ok
     *
     * @param Commande $commande
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function enregCommande(Commande $commande){
        try{
            // on persist les tickets et la commande
            foreach($commande->getTickets() as $ticket){
                $this->em->persist($ticket);
            }
            $this->em->persist($commande);
            $this->em->flush();

            // email de confirmation
            $this->sendEmailConfirmation($commande);

            // garde en session la ref de la commande
            $this->session->set('refCommande', $commande->getRef());

            // nettoie les sessions commande et tickets
            $this->resetCommande();

            return true;
        }
        catch(Exception $e){
            $this->session->getFlashBag()->add('danger', 'Une erreur est survenue dans l\'enregistrement de votre commande, 
                veuillez vous rapprocher de notre service client avec votre email et la référence de votre commande : '. $commande->getRef());
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getRefCommande(){
        return $this->session->get('refCommande');
    }

    public function verifNbTicket(Commande $commande){
        // récupération du nombre de place restante sur la date de la visite souhaitée
        $nbTicketReserve = $this->em->getRepository('FCReservationBundle:Commande')
            ->getNbTickets($commande->getDateVisite());
        $nbPlaceRestante = $this->nbTicketsMaxJour - $nbTicketReserve;

        // si pas de place pour le jour choisi
        if($nbPlaceRestante < 1){
            $this->session->getFlashBag()->add("warning", "Il n'y a plus de ticket disponible pour le jour que vous demandez.");
            return false;
        }

        // s'il reste de la place, mais moins que le nombre de ticket demandé
        if($nbPlaceRestante < $commande->getNbTicket()){
            if($nbPlaceRestante === 1){
                $this->session->getFlashBag()->add("warning", "Il ne reste que ". $nbPlaceRestante ." ticket disponible pour le jour demandé.");
            } else {
                $this->session->getFlashBag()->add("warning", "Il ne reste que ". $nbPlaceRestante ." tickets disponibles pour le jour demandé.");
            }
            return false;
        }

        return true;
    }

    /**
     * @param Commande $commande
     * @throws \Twig\Error\Error
     */
    public function sendEmailConfirmation(Commande $commande){
        $contenu = $this->templating->render('@FCReservation/Reserve/ticketEmail.html.twig', array(
            'commande' => $commande
        ));

        $mail = \Swift_Message::newInstance()
            ->setSubject('Confirmation de la commande du musee du Louvre')
//            ->setTo($commande->getCourriel())
            ->setFrom($this->mailerFrom)
//            ->setFrom('contact@yuqi.fr')
//            ->setTo('mairie.publie.1@projet1.yuqi.fr')
            ->setTo('fabien.cagniet@gmail.com')
            ->setContentType('text/html')
            ->setBody($contenu)
        ;

        $this->mailer->send($mail);
    }
}