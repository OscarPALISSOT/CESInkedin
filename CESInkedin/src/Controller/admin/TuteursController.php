<?php

namespace App\Controller\admin;

use App\Entity\Admin;
use App\Form\AdminFormType;
use App\Repository\AdminRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TuteursController extends AbstractController
{
    public function __construct(AdminRepository $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }


    /**
     * @Route ("/Admin/Tuteurs", name="gestion_tuteurs")
     * @return Response
     */
    public function ShowTuteurs(PaginatorInterface $paginator, Request $request): Response
    {

        $admins = $paginator->paginate(
            $this->repository->findByRole('ROLE_TUTEUR'),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/admins/ShowTuteurs.html.twig', [
            'admins' => $admins,
            'loggedUser' => $this->getUser()
        ]);
    }

    /**
     * @Route ("/Admin/NouveauUser", name="create_tuteur")
     * @return Response
     */
    public function createTuteurs(Request $request){

        $NewAdmin = new Admin;
        $login = $request->request->get('username');
        $pwd = bin2hex(random_bytes(5));
        $NewAdmin->setUsername($login);
        $NewAdmin->setPassword($this->encoder->encodePassword($NewAdmin, $pwd));
        $NewAdmin->setRoles(['ROLE_TUTEUR']);
        if ($this->isCsrfTokenValid("createAdmin", $request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $em->persist($NewAdmin);
            $em->flush();
        }

        $jsonData = array(
            'login' => $login,
            'pwd' => $pwd,
        );
        return $this->json($jsonData, 200);
    }


    /**
     * @Route ("/Admin/Tuteurs/{id}", name="edit_tuteur", methods="GET|POST")
     * @return Response
     */
    public function editTuteurs(Admin $admin, Request $request){
        $form = $this->createForm(AdminFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('gestion_tuteurs');
        }
        return $this->render('admin/admins/EditAdmins.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
            'loggedUser' => $this->getUser()
        ]);
    }

    /**
     * @Route ("/Admin/Tuteurs/Delete/{id}", name="delete_tuteur")
     * @return Response
     */
    public function deleteTuteurs(Admin $admin, Request $request){

        if ($this->isCsrfTokenValid("delete", $request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $em->remove($admin);
            $em->flush();
        }
        
        return $this->redirectToRoute('gestion_tuteurs');
    }

}
