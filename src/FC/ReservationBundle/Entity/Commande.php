<?php

namespace FC\ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="FC\ReservationBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCommande", type="datetime")
     * @Assert\DateTime()
     * @Assert\GreaterThanOrEqual("today")
     */
    private $dateCommande;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=5, scale=2)
     */
    private $prix = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateVisite", type="date")
     * @Assert\DateTime()
     * @Assert\GreaterThanOrEqual("today", message = "Veuillez choisir une date au moins égale à aujourd'hui.")
     */
    private $dateVisite;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTicket", type="smallint")
     * @Assert\Range(min = 1, max = 10)
     */
    private $nbTicket = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=255, unique=true)
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="courriel", type="string", length=255)
     * @Assert\Email()
     */
    private $courriel;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(
     *     min = 3,
     *     max = 40,
     *     minMessage="Votre nom doit avoir au moins {{ limit }} caractères.",
     *     maxMessage="Votre nom ne peut pas dépasser {{ limit }} caractères.",
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\Length(
     *     min = 3,
     *     max = 40,
     *     minMessage="Votre prénom doit avoir au moins {{ limit }} caractères.",
     *     maxMessage="Votre prénom ne peut pas dépasser {{ limit }} caractères.",
     * )
     */
    private $prenom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="demiJournee", type="boolean")
     * @Assert\Type(type="boolean")
     */
    private $demiJournee;

    /**
     * @ORM\OneToMany(targetEntity="FC\ReservationBundle\Entity\Ticket", mappedBy="commande")
     * @Assert\Valid
     */
    private $tickets;

    public function __construct(){
        $this->dateCommande = new \DateTime();
        $this->dateVisite = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $str = "ABCDEFGHIJKLMNOPQRSTUVWYZ";
        $str = substr(str_shuffle($str), 0, 4);
        $this->ref = $this->dateVisite->format("ymd") . $str;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Commande
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set dateVisite
     *
     * @param \DateTime $dateVisite
     *
     * @return Commande
     */
    public function setDateVisite($dateVisite)
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    /**
     * Get dateVisite
     *
     * @return \DateTime
     */
    public function getDateVisite()
    {
        return $this->dateVisite;
    }

    /**
     * Set nbTicket
     *
     * @param integer $nbTicket
     *
     * @return Commande
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }

    /**
     * Set ref
     *
     * @param string $ref
     *
     * @return Commande
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set courriel
     *
     * @param string $courriel
     *
     * @return Commande
     */
    public function setCourriel($courriel)
    {
        $this->courriel = $courriel;

        return $this;
    }

    /**
     * Get courriel
     *
     * @return string
     */
    public function getCourriel()
    {
        return $this->courriel;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Commande
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Commande
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Add ticket
     *
     * @param \FC\ReservationBundle\Entity\Ticket $ticket
     *
     * @return Commande
     */
    public function addTicket(\FC\ReservationBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \FC\ReservationBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\FC\ReservationBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set demiJournee
     *
     * @param boolean $demiJournee
     *
     * @return Commande
     */
    public function setDemiJournee($demiJournee)
    {
        $this->demiJournee = $demiJournee;

        return $this;
    }

    /**
     * Get demiJournee
     *
     * @return boolean
     */
    public function getDemiJournee()
    {
        return $this->demiJournee;
    }
}
