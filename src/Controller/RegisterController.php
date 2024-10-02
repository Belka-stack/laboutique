<?php

namespace App\Controller;

use App\Form\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{ 
    // Change le '/register' en '/inscription'
    #[Route('/inscription', name: 'app_register')]

    public function index(Request $request): Response
    {
    
        $form = $this->createForm(RegisterUserType::class);
        
        // on instancie notre formulaire dans une variable $form

        // On demande d'écouter notre formulaire avant d'aller plus loin.

        $form->handleRequest($request);

        return $this->render('register/index.html.twig',[ 
        // enlèvement du paramètre 'controller_name' => 'RegisterController' proposé par défault et j'ajoute la création d'une vue dans une variable registreForm.


        // Si le formulaire est soumis 
        
        //alors tu enregistre les datas en BBD 
        
        //et tu envoies un message de confirmation du compte bien créé.

        'registerForm' => $form->createView()

    ]);
            
    
    }
}
