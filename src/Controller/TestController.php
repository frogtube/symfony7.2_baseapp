<?php

namespace App\Controller;

use App\Entity\Test;
use App\Form\TestType;
use App\Repository\TestRepository;
use Doctrine\DBAL\Query\Limit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/test')]
final class TestController extends AbstractController
{
    #[Route(name: 'app_test_index', methods: ['GET'])]
    public function index(Request $request, TestRepository $testRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 2;

        $tests = $testRepository->findAllPaginated($page, $limit);
        
        return $this->render('test/index.html.twig', [
            'tests' => $tests,
        ]);
    }

    #[Route('/new', name: 'app_test_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $test = new Test();
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($test);
            $entityManager->flush();

            $this->addFlash('success', 'Test created successfully');

            return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('test/new.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}-{id}', name: 'app_test_show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9\-]+'], methods: ['GET'])]
    public function show(string $slug, int $id, TestRepository $testRepository): Response
    {
        // S'assure qu'on a bien la bonne url
        $test = $testRepository->find($id);
        if($test->getSlug() !== $slug) {
            return $this->redirectToRoute('app_test_show', ['slug' => $test->getSlug(), 'id' => $test->getId()]);
        }

        return $this->render('test/show.html.twig', [
            'test' => $test,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_test_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Test $test, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            $this->addFlash('success', 'Test updated successfully');
            return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('test/edit.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_test_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Test $test, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$test->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($test);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
    }
}
