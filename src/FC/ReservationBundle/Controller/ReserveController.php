<?php

namespace FC\ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReserveController extends Controller {
    public function indexAction() {
        $content =
            $this->get('templating')->render('FCReservationBundle:Reserve:index.html.twig');

        return new Response($content);
    }

    public function recapAction() {
        $content =
            $this->get('templating')->render('FCReservationBundle:Reserve:recap.html.twig');

        return new Response($content);
    }
}