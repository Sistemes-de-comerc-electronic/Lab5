<?php

namespace App\Controller;

use App\Service\RedisCacheManager;
use Sce\PracticaVendorTest\DTO\Entity\CarDTO;
use Sce\PracticaVendorTest\DTO\Requests\CarQuery\CarQueryRequestDTO;
use Sce\PracticaVendorTest\DTO\Requests\CarQuery\CarQueryResponseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CarController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private RedisCacheManager $redisCacheManager
    )
    {

    }
    #[Route('/cars/query', name: 'car_query', methods: ['POST'])]
    public function query(Request $request): JsonResponse
    {
        $request = $this->serializer->deserialize($request->getContent(), CarQueryRequestDTO::class, 'json');

        $jsonMessage = [];
        $jsonMessage['event'] = 'new_user';

        $jsonEncodedMessage = json_encode($jsonMessage);

        $this->redisCacheManager->queue('new_users_notifications', $jsonEncodedMessage);

        //Ara fem una resposta de prova, això hauria d'anar a bd a buscar el cotxe que correspon a la consulta i retornar-lo
        $carDTO = new CarDTO();
        $carDTO->setId(1);
        $carDTO->setName('Test Car');

        $response = new CarQueryResponseDTO();
        $response->setData($carDTO);
        $response->setCode(200);

        $jsonResponse = $this->serializer->serialize($response, 'json');
        return new JsonResponse($jsonResponse, 200, [], true);
    }
}