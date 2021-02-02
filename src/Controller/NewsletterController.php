<?php

namespace App\Controller;

use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewsletterController
 * @package App\Controller
 * @Route("/newsletter", name="newsletter_")
 */
class NewsletterController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $newsletters = $this->getDoctrine()->getRepository(Newsletter::class)->findAll();
        return $this->json(['data' => $newsletters]);
    }

    /**
     * @Route("/{newsletterId}", name="show", methods={"GET"})
     * @param int $newsletterId
     * @return JsonResponse
     */
    public function show(int $newsletterId): JsonResponse
    {
        $newsletter = $this->getDoctrine()->getRepository(Newsletter::class)->find($newsletterId);
        return $this->json(['data' => $newsletter]);
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

        $newsletter = new Newsletter();
        $newsletter->setFullName($data['full_name']);
        $newsletter->setEmail($data['email']);
        $newsletter->setTelephone($data['telephone']);
        $newsletter->setMessage($data['message']);

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($newsletter);
        $doctrine->flush();

        return $this->json(['msg' => 'Contato criado com sucesso!']);
    }

    /**
     * @Route("/{newsletterId}", name="update", methods={"PUT", "PATCH"})
     * @param int $newsletterId
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(int $newsletterId, Request $request): JsonResponse
    {
        $data = $request->request->all();
        $doctrine = $this->getDoctrine();

        $newsletter = $doctrine->getRepository(Newsletter::class)->find($newsletterId);

        if ($request->request->has($data['full_name']))
            $newsletter->setFullName($data['full_name']);

        if ($request->request->has($data['email']))
            $newsletter->setEmail($data['email']);

        if ($request->request->has($data['telephone']))
            $newsletter->setTelephone($data['telephone']);

        if ($request->request->has($data['message']))
            $newsletter->setMessage($data['message']);

        $manager = $doctrine->getManager();
        $manager->flush();

        return $this->json(['msg' => 'Contato atualizado com sucesso!']);
    }

    /**
     * @Route("/{newsletterId}", name="delete", methods={"DELETE"})
     * @param int $newsletterId
     * @return JsonResponse
     */
    public function delete(int $newsletterId): JsonResponse
    {
        $doctrine = $this->getDoctrine();
        $newsletter = $doctrine->getRepository(Newsletter::class)->find($newsletterId);

        $manager = $doctrine->getManager();
        $manager->remove($newsletter);
        $manager->flush();

        return $this->json(['msg' => 'Contato removido com sucesso!']);
    }
}
