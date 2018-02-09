<?php


namespace FC\ReservationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ReserveControllerTest extends WebTestCase
{

    public function testButtonIndexPage(){
        // Vérifie qu'il y a un lien button
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(1, $crawler->filter('button:contains("Commencer !")')->count());
    }

    public function testExistFormOnInfoVisite(){
        // vérifie qu'il y a bien un formulaire avec 3 champs
        $client = static::createClient();
        $crawler = $client->request('GET', '/info-visite');
        $this->assertEquals(3, $crawler->filter('div.form-group')->count());
    }
}