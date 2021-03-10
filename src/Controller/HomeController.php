<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index()
    {
        $name = 'Kiko';
        return $this->render('Home/index.html.twig', [
            'name' => $name
        ]);
    }
}