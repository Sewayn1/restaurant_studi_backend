<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Response, Request, BadRequestException};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('api/restaurant', name: 'app_api_restaurant_')]
final class RestaurantController extends AbstractController
{
    public function __construct(

        private EntityManagerInterface $manager, 
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    #[Route(methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
        $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json');
        $restaurant->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($restaurant);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($restaurant, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_restaurant_show',
            ['id' => $restaurant->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route(path: '/{id}', name: 'show', methods: 'GET')]
    public function show(int $id): Response
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if (!$restaurant) {
            throw new \Exception("No Restaurant Found for {$id} id");
        }
        return $this->json(
            ["message" => "A restaurant was found : {$restaurant->getName()} for {$restaurant->getId()} id"]
        );
    }

    #[Route(path: '/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id, Request $request): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if ($restaurant) {
            $restaurant = $this->serializer->deserialize(
                $request->getContent(),
                Restaurant::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant]
            );
            $restaurant->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if ($restaurant) {

        $this->manager->remove($restaurant);
        $this->manager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);

    }

}
