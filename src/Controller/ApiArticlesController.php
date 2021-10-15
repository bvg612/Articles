<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiArticlesController extends AbstractController
{
    /**
     *  For this to work you need to
     * comment the following line
     * - { path: ^(?!/(login|register)), roles: ROLE_USER }
     * in security.yaml file .
     *
     * @Route("/articles", name="api_articles", methods={"GET"})
     */
    public function returnArticles(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $serializer = $this->get('serializer');

        $jsonContent = $serializer->serialize($articles, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonContent, Response::HTTP_OK,
            ['content_type' => 'application/json']
        );
    }

    /**
     * @Route("/article/{id}", name="api_articlesId", methods={"GET"})
     * @param $id
     * @return Response
     */
    public function articleAction($id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if (null === $article) {
            return new Response(json_encode(array('error' => 'resource not found')),
                Response::HTTP_NOT_FOUND,
                array('content-type' => 'application/json'));
        }
        $serializer = $this->get('serializer');
        $articleJson = $serializer->serialize($article, 'json',[
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($articleJson,
            Response::HTTP_OK,
            array('content-type' => 'application/json')
        );
    }
}
