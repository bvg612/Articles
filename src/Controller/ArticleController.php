<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;


class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles",methods={"GET"})
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();


        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/article/new", name="articleNew")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Please select a category',
                'choice_label' => 'name',
            ])
            ->add('image', FileType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('summary', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $file = $form->get('image')->getData();
            if(isset($file)) {
                $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'), $fileName
                );
                $article->setImage($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles');
        }


        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/{id}", name="articleShow")
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/delete/{id}",methods={"DELETE"}, name="articleDelete")
     * @param Request $request
     * @param $id
     */
    public function delete(Request $request, $id): void
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'required' => true

        ]);
    }




}


