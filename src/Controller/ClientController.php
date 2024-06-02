<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Client;
use App\Model\Service\ServiceProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ClientController
{
    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $clients = ServiceProvider::getInstance()->getClientService()->getClients();
        $view = Twig::fromRequest($request);

        return $view->render($response, 'client_list.twig', [
            'quantity' => count($clients),
            'clients' => array_map(fn($client) => $this->getRowData($client), $clients),
        ]);
    }

    public function new(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'home.twig', [
            'message' => 'hello world!',
        ]);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'home.twig', [
            'message' => 'hello world!',
        ]);
    }

    private function getRowData(Client $data): array
    {
        return [
            'id' => $data->getId(),
            'first_name' => $data->getFirstName(),
            'last_name' => $data->getLastName(),
            'phone' => $data->getPhone(),
        ];
    }
}
