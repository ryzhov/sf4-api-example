<?php

namespace App\Controller;

use App\DTO\ArtistDto;
use App\Entity\Artist;
use App\Entity\User;
use App\Mapper\DTOMapper;
use App\Repository\ArtistRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("/artist")
 */
class ArtistController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ArtistRepository $artistRepository) : JsonResponse
    {
        return $this->json($artistRepository->findAll());
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(ArtistDto $artistDto, DTOMapper $mapper) : JsonResponse
    {
        $mapper->map($artistDto, $artist = new Artist());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($artist);
        $entityManager->flush();

        return $this->json(['artist' => $artist]);
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, requirements={"id": "\d+"})
     * @ParamConverter("artist", class="App\Entity\Artist")
     */
    public function delete(Artist $artist) : JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($artist);
        $entityManager->flush();

        return $this->json([]);
    }

    /**
     * @Route("/{id}", methods={"PUT"}, requirements={"id": "\d+"})
     * @ParamConverter("artist", class="App\Entity\Artist")
     */
    public function update(Artist $artist, ArtistDto $artistDto, DTOMapper $mapper) : JsonResponse
    {
        $mapper->map($artistDto, $artist);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($artist);
        $entityManager->flush();

        return $this->json([['artist' => $artist]]);
    }
}