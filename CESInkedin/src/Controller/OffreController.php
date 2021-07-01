<?php

namespace App\Controller;

use App\Entity\Offre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\OffreFormType;

class OffreController extends AbstractController {


    public function __construct(Environment $twig, OffreRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }



    /**
     * @Route ("/NouvelleOffre", name="create_offre")
     * @return Response
     */

    public function createOffre(Request $request){

        $offre = new Offre;
        $form = $this->createForm(OffreFormType::class, $offre);
        $form->handleRequest($request);
        $username = $this->getUser()->getUsername();
        $offre->setCreator($username);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute('MyOffres');
        }
        return new Response(content: $this->twig->render('pages/offres/createOffre.html.twig', [
            'offre' => $offre,
            'loggedUser' => $this->getUser(),
            'form' => $form->createView(),
        ]));
    }


    /**
     * @Route ("/MesOffres", name="MyOffres")
     * @return Response
     */

    public function ShowMyOffre(PaginatorInterface $paginator, Request $request){

        $offres = $paginator->paginate(
            $this->repository->findBy(array(), array('created_at' => 'DESC')),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/actus/ShowActus.html.twig', [
            'offres' => $offres,
            'loggedUser' => $this->getUser(),
        ]);
    }


}
?>