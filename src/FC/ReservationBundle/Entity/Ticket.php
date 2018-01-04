<?php

namespace FC\ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="FC\ReservationBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @var bool
     *
     * @ORM\Column(name="tarifReduit", type="boolean")
     */
    private $tarifReduit = false;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=5, scale=2)
     */
    private $prix;

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
     * @var \DateTime
     *
     * @ORM\Column(name="ddn", type="date")
     * @Assert\DateTime()
     * @Assert\LessThan(
     *     "today",
     *     message = "Veuillez vérifier votre date de naissance."
     * )
     */
    private $ddn;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     * @Assert\Country()
     */
    private $pays;

    /**
     * @ORM\ManyToOne(targetEntity="FC\ReservationBundle\Entity\Commande", inversedBy="billets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

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
     * Set prix
     *
     * @param string $prix
     *
     * @return Ticket
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Ticket
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
     * @return Ticket
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
     * Set ddn
     *
     * @param \DateTime $ddn
     *
     * @return Ticket
     */
    public function setDdn($ddn)
    {
        $this->ddn = $ddn;

        return $this;
    }

    /**
     * Get ddn
     *
     * @return \DateTime
     */
    public function getDdn()
    {
        return $this->ddn;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Ticket
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return Ticket
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return boolean
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set commande
     *
     * @param \FC\ReservationBundle\Entity\Commande $commande
     *
     * @return Ticket
     */
    public function setCommande(\FC\ReservationBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \FC\ReservationBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Unset Commande
     */
//    public function unsetCommande(){
//        $this->commande = null;
//    }
}
