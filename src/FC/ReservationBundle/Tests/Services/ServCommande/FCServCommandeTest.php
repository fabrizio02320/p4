<?php


namespace FC\ReservationBundle\Tests\Services\ServCommande;

use FC\ReservationBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class FCServCommandeTest extends WebTestCase
{
    /**
     * @var \FC\ReservationBundle\Services\ServCommande\FCServCommande
     */
    private $servCommande;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->servCommande = $kernel->getContainer()->get('fc_reserve.servcommande');
    }

    public function testInitCommande(){
        // vérifie qu'une commande est bien initialisé
        $commande = $this->servCommande->initCommande();
        $this->assertEquals('FC\ReservationBundle\Entity\Commande', get_class($commande));
    }

    public function testValidDate(){
        $commande = new Commande();

        // test la fonction validDate sur une date valide
        $commande->setDateVisite(\DateTime::createFromFormat('d/m/Y', '04/03/2020'));
        $this->assertEquals(true, $this->servCommande->validDate($commande));

        // test la fonction validDate sur une date non autorisé (sur un mardi par ex.)
        $commande->setDateVisite(\DateTime::createFromFormat('d/m/Y', '01/12/2020'));
        $this->assertEquals(false, $this->servCommande->validDate($commande));
    }
}