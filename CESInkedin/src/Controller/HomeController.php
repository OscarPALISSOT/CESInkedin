<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use App\Repository\OffreRepository;

class HomeController extends AbstractController {


    public function __construct(Environment $twig, OffreRepository $offreRepository)
    {
        $this->twig = $twig;
        $this->offreRepository = $offreRepository;
    }

    public function index() : Response{
        $featuredOffre = $this->offreRepository->findFeaturedOffre();
        return new Response(content: $this->twig->render('pages/home.html.twig', [
            'featuredOffres' => $featuredOffre,
            'loggedUser' => $this->getUser()
        ]));
    }


}
?>