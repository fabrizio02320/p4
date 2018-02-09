<?php


namespace FC\ReservationBundle\Tests\Entity;

use FC\ReservationBundle\Entity\Ticket;
use PHPUnit\Framework\TestCase;
use FC\ReservationBundle\Entity\Commande;


class CommandeTest extends TestCase
{
    public function testAddTicket(){
        $commande = new Commande();
        $ticket = new Ticket();

        $commande->addTicket($ticket);
        $nbTicket = count($commande->getTickets());

        $this->assertEquals(1, $nbTicket);

        $this->assertEquals('FC\ReservationBundle\Entity\Ticket', get_class($commande->getTickets()[0]));
    }

    public function testRemoveTicket(){
        $commande = new Commande();
        $ticket = new Ticket();

        $commande->addTicket($ticket);
        $commande->removeTicket($ticket);

        $nbTicket = count($commande->getTickets());

        $this->assertEquals(0, $nbTicket);
    }
}