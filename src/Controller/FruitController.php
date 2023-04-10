<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Form\FruitType;
use App\Repository\FruitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    /**
     * @Route("/", name="fruits_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('fruit/fruits.html.twig');
    }

    /**
     * @Route("/favorites", name="favorite_fruits_index", methods={"GET"})
     */
    public function favoriteFruits(): Response
    {
        return $this->render('fruit/favorite_fruits.html.twig');
    }

    /**
     * @Route("/{any}", name="redirect_to_index", requirements={"any"=".+"})
     */
    public function redirectToIndex(): Response
    {
        return $this->redirectToRoute('fruits_index');
    }
}