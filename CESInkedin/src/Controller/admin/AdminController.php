<?php

namespace App\Controller\admin;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;


class AdminController extends AbstractController {


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route ("/Admin", name="admin")
     * @return Response
     */
    public function index() :Response {

        return new Response(content:$this->twig->render('admin/admin.html.twig', [
            'loggedUser' => $this->getUser()
        ]));

    }
}



?>