<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{ 
    // Change le '/register' en '/inscription'
    #[Route('/inscription', name: 'app_register')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig');
            // enlèvement du paramètre 'controller_name' => 'RegisterController' proposé par défault.
            
    
    }
}
