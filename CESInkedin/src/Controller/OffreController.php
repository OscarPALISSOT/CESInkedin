<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\OffreLike;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\OffreFormType;
use App\Repository\OffreLikeRepository;
use Doctrine\Persistence\ObjectManager;

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
        $idCreator = $this->getUser()->getId();
        $offre->setCreator($idCreator);
        if ($form->isSubmitted() && $form->isValid()){

            $address = $form["adresse"]->getData() .',' . $form["ville"]->getData() . ',' . $form["codePostal"]->getData();

            $queryString = http_build_query([
                'access_key' => '31add3370807e163542a5d5c67d10a57',
                'query' => $address,
                'output' => 'json',
                'limit' => 1,
            ]);
            
            $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $json = curl_exec($ch);
            
            curl_close($ch);
            
            $apiResult = json_decode($json, true);

            $offre->setLat($apiResult['data'][0]['latitude']);
            $offre->setLon($apiResult['data'][0]['longitude']);

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
            $this->repository->findBy(array('creator' => $this->getUser()->getId()), array('created_at' => 'DESC')),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('pages/offres/ShowMyOffre.html.twig', [
            'offres' => $offres,
            'loggedUser' => $this->getUser(),
        ]);
    }

    /**
     * @Route ("/Offres", name="show_offres")
     * @return Response
     */

    public function ShowOffre(PaginatorInterface $paginator, Request $request){

        $offres = $paginator->paginate(
            $this->repository->findBy(array(), array('created_at' => 'DESC')),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('pages/offres/ShowOffre.html.twig', [
            'offres' => $offres,
            'loggedUser' => $this->getUser(),
        ]);
    }

    /**
     * @Route ("/Offre/{id}", name="this_offre")
     * @return Response
     */
    public function thisOffre($id){
        
        $offre = $this->repository->find($id);
        return $this->render('pages/offres/thisOffre.html.twig', [
            'offre' => $offre,
            'loggedUser' => $this->getUser(),
        ]);
    }

    /**
     * @Route ("/OffreEdit/{id}", name="edit_offre")
     * @return Response
     */
    public function editOffre(Offre $offre, Request $request){
        $form = $this->createForm(OffreFormType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('MyOffres');
        }
        return $this->render('pages/offres/editOffre.html.twig', [
            'offre' => $offre,
            'loggedUser' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/DeleteOffre/{id}", name="delete_offre")
     * @return Response
     */
    public function deleteOffre(Offre $offre, Request $request){

        if ($this->isCsrfTokenValid("delete", $request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $em->remove($offre);
            $em->flush();
        }
        
        return $this->redirectToRoute('MyOffres');
    }


    
    /**
     * permet de liker ou unliker une offre
     * @Route ("/Offre/{id}/like", name="like_offre")
     * @return Response
     */
    public function like(Offre $offre, OffreLikeRepository $offreLikeRepository): Response {
        $user = $this->getUser();

        if($offre->isLikedByUser($user)) {
            $like = $offreLikeRepository->findOneBy([
                'offre' => $offre,
                'user' => $user
            ]);

            $em = $this->getDoctrine()->getManager();
            $em->remove($like);
            $em->flush();

            return $this->json([
                'code' => 200,
                'message' => 'like supprimé',
                'likes' => $offreLikeRepository->count([
                    'offre' => $offre
                ])
            ], 200);
        }

        $like = new OffreLike();
        $like->setOffre($offre);
        $like->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($like);
        $em->flush();
        return $this->json([
            'code' => 200,
            'message'=>'like ajouté',
            'likes' => $offreLikeRepository->count([
            'offre' => $offre
            ])
        ], 200);
    }
}
?>