<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $lastUserName = $utils->getLastUserName();

        $form = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('password', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('login', SubmitType::class, [
            'label' => 'Login',
            'attr' => ['class' => 'btn btn-primary mt-3']
             ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('articles');
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'lastUserName' => $lastUserName,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/createUser", name="createUser")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('password', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword(
                $userPasswordEncoder->encodePassword($user, $user->getPassword())
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('createUser');
        }

        return $this->render('security/createUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
