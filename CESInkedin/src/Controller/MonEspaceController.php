<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Admin;
use App\Form\AdminFormType;
use App\Form\ChangePwdType;
use Twig\Environment;
use Symfony\Component\Form\FormError;


class MonEspaceController extends AbstractController {


    public function __construct(Environment $twig, UserPasswordEncoderInterface $encoder)
    {
        $this->twig = $twig;
        $this->encoder = $encoder;
    }

    /**
     * @Route ("Admin/MonEspace/{id}", name="monEspace")
     * @return Response
     */
    public function index(Admin $admin, Request $request) :Response {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminFormType::class, $admin);
        $form->handleRequest($request);
        $formPwd = $this->createForm(ChangePwdType::class, $user);
    	$formPwd->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('monEspace', [
                'id' => $this->getUser()->getUsername()
            ]);
        }

        if ($formPwd->isSubmitted() && $formPwd->isValid()) {

            $oldPassword = $formPwd["oldPassword"]->getData();

            // Si l'ancien mot de passe est bon

            if ($this->encoder->isPasswordValid($user, $oldPassword)) {

                $newEncodedPassword = $this->encoder->encodePassword($user, $formPwd["plainPassword"]->getData());

                $user->setPassword($newEncodedPassword);

                $em->persist($user);

                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('monEspace', [
                    'id' => $this->getUser()->getUsername()
                ]);
            } else {

                $formPwd->addError(new FormError('Ancien mot de passe incorrect'));

            }
        }
        
        return new Response(content:$this->twig->render('pages/monEspace.html.twig', [
            'loggedUser' => $this->getUser(),
            'form' => $form->createView(),
            'formPwd' => $formPwd->createView(),
        ]));

    }
}



?>