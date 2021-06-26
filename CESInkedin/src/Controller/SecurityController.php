<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * @Route ("/login", name="login")
     * @return Response
     */

    public function login(AuthenticationUtils $authenticationUtils) : Response{

        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return new Response(content: $this->twig->render('security/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]));
    }
}

?>