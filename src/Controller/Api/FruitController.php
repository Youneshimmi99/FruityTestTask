<?php

namespace App\Controller\Api;

use App\Entity\Fruit;
use App\Repository\FruitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class FruitController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/fruits", name="api_fruits", methods={"GET"})
     */
    public function getAllFruits(FruitRepository $fruitRepository): JsonResponse
    {
        $fruits = $fruitRepository->findAll();
        
        $serializedFruits = $this->serializer->serialize(
            $fruits,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['nutrition']]
        );
        
        return $this->json(json_decode($serializedFruits, true), Response::HTTP_OK);
    }

        /**
     * @Route("/fruits/favorite", name="api_favorite_fruits", methods={"GET"})
     */
    public function getFavoriteFruits(FruitRepository $fruitRepository): JsonResponse
    {
        $fruits = $fruitRepository->findFavoriteFruits();
        $sumNutrition = $fruitRepository->sumNutritionFavorite();

        $sumNutrition = [
            'carbohydrates' => round($sumNutrition['carbohydrates'], 2),
            'protein' => round($sumNutrition['protein'], 2),
            'fat' => round($sumNutrition['fat'], 2),
            'calories' => round($sumNutrition['calories'], 2),
            'sugar' => round($sumNutrition['sugar'], 2),
        ];

        $serializedFruits = $this->serializer->serialize(
            $fruits,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['nutrition']]
        );

        $serializedSumNutrition = $this->serializer->serialize(
            $sumNutrition,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['nutrition']]
        );
        
        return $this->json([
            'fruits' => json_decode($serializedFruits, true),
            'sumNutrition' => json_decode($serializedSumNutrition, true),
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fruits/{id}/toggle-favorite", name="api_fruits_toggle_favorite", methods={"POST"})
     */
    public function toggleFavorite(FruitRepository $fruitRepository, int $id): JsonResponse
    {
        $fruit = $fruitRepository->findById($id);

        // check if the fruit is already marked as a favorite
        $isFavorite = $fruit->getFavorite();

        // get the number of favorite fruits
        $favoriteCount = $fruitRepository->countFavorites();

        // check if the number of favorite fruits is already 10 and the current fruit is not already marked as a favorite
        if ($favoriteCount >= 10 && !$isFavorite) {
            return $this->json(['message' => 'Maximum number of favorite fruits reached.'], Response::HTTP_BAD_REQUEST);
        }
        
        $fruit->setFavorite(!$fruit->getFavorite());
        $this->entityManager->flush();

        if ($fruit->getFavorite()) {
            return $this->json(['message' => $fruit->getName() . ' was successfully added to favorites.'], Response::HTTP_OK);
        } else {
            return $this->json(['message' => $fruit->getName() . ' was successfully Removed from favorites.'], Response::HTTP_OK);
        }
    }
}