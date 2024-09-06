<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\BoardAction;
use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[AsController, Route(path: '/secured')]
final class SecuredController extends AbstractController
{
    public function __construct(
        private readonly BoardRepository $boardRepository,
    ) {}

    #[Route(path: '', methods: Request::METHOD_GET)]
    public function index(): Response
    {
        return $this->render('secured/index.html.twig', [
            'boards' => $this->boardRepository->findAll(),
        ]);
    }

    #[Route(path: '/board/{id}', methods: Request::METHOD_GET)]
    public function viewBoard(Uuid $id): Response
    {
        $board = $this->boardRepository->findById($id) ?? throw $this->createNotFoundException();
        $this->denyAccessUnlessGranted(BoardAction::View->value, $board);

        return $this->render('secured/view_board.html.twig', [
            'board' => $board,
        ]);
    }

    #[Route(path: '/board/{id}/modify', methods: Request::METHOD_POST)]
    public function modifyBoard(Uuid $id): Response
    {
        $board = $this->boardRepository->findById($id) ?? throw $this->createNotFoundException();
        $this->denyAccessUnlessGranted(BoardAction::Modify->value, $board);

        $this->addFlash('success', 'Board modified successfully!');

        return $this->redirectToRoute('app_secured_viewboard', ['id' => $id]);
    }
}
