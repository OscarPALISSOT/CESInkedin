<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

class HomeController extends AbstractController {


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index() : Response{
        return new Response(content: $this->twig->render('pages/home.html.twig', [
            'loggedUser' => $this->getUser()
        ]));
    }


}
?>