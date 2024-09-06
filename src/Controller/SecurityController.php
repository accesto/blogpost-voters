<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[AsController]
final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    #[Route(path: '/login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('app_secured_index');
        }

        return $this->render('security/login.html.twig', [
            'username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'availableUsers' => array_map(fn (User $u) => $u->getUserIdentifier(), $this->userRepository->findAll()),
        ]);
    }

    #[Route(path: '/logout', methods: Request::METHOD_GET)]
    public function logout(): void
    {
    }
}
