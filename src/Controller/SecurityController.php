<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\{Response, Request, JsonResponse};
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


#[Route('/api', name: 'app_api_')]
final class SecurityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private SerializerInterface $serializer)
    {
    }


    #[Route('/register', name: 'register', methods: ['POST'])]
    #[OA\Post(
        path: "/api/register",
        summary: "Inscription d'un nouvel Utilisateur", )]
    #[OA\RequestBody(
        required: true,
        description: "Données de l'Utilisateur à inscrire", )]
    #[OA\JsonContent(
        "object",
        @OA\Property(property: "email", type: "string", example: "adresse@email.com"),
        @OA\Property(property: "password", type: "string", example: "Mot de Passe")
    )]
    #[OA\Response(
        response: 201,
        description: "l'Utilisateur est inscrit avec succès", )]
    #[OA\JsonContent(
        "object",
        @OA\Property(property: "user", type: "string", example: "adresse@email.com"),
        @OA\Property(property: "apiToken", type: "string", example: "24asdc2130dc9w332sd"),
        @OA\Property(property: "roles", type: "array", @OA\Items(type: "string", example: "ROLE_USER"))
    )]

    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse(
            ['user' => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['missing credentials'], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse(
            [
                'user' => $user->getUserIdentifier(),
                'apiToken' => $user->getApiToken(),
                'roles' => $user->getRoles()
            ]
        );
    }
}
