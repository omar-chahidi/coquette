<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChariotController extends AbstractController
{
    /**
     * @Route("/chariot", name="chariot")
     */
    public function index()
    {
        return $this->render('chariot/index.html.twig', [
            'controller_name' => 'ChariotController',
        ]);
    }
}
