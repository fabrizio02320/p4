<?php

namespace FC\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReserveController extends Controller {
    public function indexAction() {
        $content = 
            $this->get('templating')->render('FCReservationBundle:Reserve:index.html.twig');
        
        // ajout un test github
        
        return new Response($content);
    }
}