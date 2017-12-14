<?php

namespace FC\ReservationBundle\Controller;

use FC\ReservationBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReserveController extends Controller {

    public function indexAction() {
        return $this->render('FCReservationBundle:Home:index.html.twig');
    }

    public function infoVisiteAction() {
        return $this->render('FCReservationBundle:Reserve:infoVisite.html.twig');
    }
}