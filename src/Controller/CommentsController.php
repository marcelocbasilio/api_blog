<?php

namespace App\Controller;

use App\Entity\Comments;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentsController
 * @package App\Controller
 * @Route("/comments", name="comments_")
 */
class CommentsController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $comments = $this->getDoctrine()->getRepository(Comments::class)->findAll();
        return $this->json(['data' => $comments]);
    }

    /**
     * @Route("/{commentId}", name="show", methods={"GET"})
     * @param int $commentId
     * @return JsonResponse
     */
    public function show(int $commentId): JsonResponse
    {
        $comment = $this->getDoctrine()->getRepository(Comments::class)->find($commentId);
        return $this->json(['data' => $comment]);
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $comment = new Comments();
        $comment->setTitle($data['title']);
        $comment->setResume($data['resume']);
        $comment->setComment($data['comment']);
        $comment->setWriter($data['writer']);

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($comment);
        $doctrine->flush();

        return $this->json(['msg' => 'Comentário criado com sucesso!']);
    }

    /**
     * @Route("/{commentId}", name="update", methods={"PUT", "PATCH"})
     * @param int $commentId
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(int $commentId, Request $request): JsonResponse
    {
        $data = $request->request->all();
        $doctrine = $this->getDoctrine();

        $comment = $doctrine->getRepository(Comments::class)->find($commentId);

        if ($request->request->has($data['title']))
            $comment->setTitle($data['title']);

        if ($request->request->has($data['resume']))
            $comment->setResume($data['resume']);

        if ($request->request->has($data['comment']))
            $comment->setComment($data['comment']);

        if ($request->request->has($data['writer']))
            $comment->setWriter($data['writer']);

        $manager = $doctrine->getManager();
        $manager->flush();

        return $this->json(['msg' => 'Comentário atualizado com sucesso!']);
    }

    /**
     * @Route("/{commentId}", name="delete", methods={"DELETE"})
     * @param int $commentId
     * @return JsonResponse
     */
    public function delete(int $commentId): JsonResponse
    {
        $doctrine = $this->getDoctrine();
        $comment = $doctrine->getRepository(Comments::class)->find($commentId);

        $manager = $doctrine->getManager();
        $manager->remove($comment);
        $manager->flush();

        return $this->json(['msg' => 'Comentário removido com sucesso!']);
    }
}
