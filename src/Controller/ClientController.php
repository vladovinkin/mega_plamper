<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\ClientRequestParser;
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

    public function showAddForm(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'client_form.twig', []);
    }

    public function add(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getParsedBody();
        $firstName = $params['first_name'] ?? null;
        $lastName = $params['last_name'] ?? null;
        $phone = $params['phone'] ?? null;

        $view = Twig::fromRequest($request);

        if (!$firstName || !$lastName || !$phone)
        {
            return $view->render($response, 'client_form.twig', [
                'first_name_value' => $firstName,
                'last_name_value' => $lastName,
                'phone_value' => $phone,
                'first_name_error' => !$firstName,
                'last_name_error' => !$lastName,
                'phone_error' => !$phone,
            ]);
        }
        else
        {
            $params = ClientRequestParser::parseCreateClientParams((array)$request->getParsedBody());
            ServiceProvider::getInstance()->getClientService()->createClient($params);

            return $view->render($response, 'redirect.twig', [
                'url' => '/client/list',
            ]);
        }
    }

    public function showEditForm(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $client_id = (int)$args['id'] ?? null;
        $view = Twig::fromRequest($request);

        if ($client_id && $client = ServiceProvider::getInstance()->getClientService()->getClient($client_id)) {
            $firstName = $client->getFirstName() ?? null;
            $lastName = $client->getLastName() ?? null;
            $phone = $client->getPhone() ?? null;

            return $view->render($response, 'client_form.twig', [
                'id' => $client_id,
                'first_name_value' => $firstName,
                'last_name_value' => $lastName,
                'phone_value' => $phone,
            ]);
        }

        return $view->render($response, 'home.twig', [
            'message' => 'Oops! its 404 :(',
        ]);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getParsedBody();
        $client_id = (int)$params['id'] ?? null;

        $view = Twig::fromRequest($request);

        if ($client_id && $client = ServiceProvider::getInstance()->getClientService()->getClient($client_id))
        {
            $firstName = $params['first_name'] ?? null;
            $lastName = $params['last_name'] ?? null;
            $phone = $params['phone'] ?? null;

            if (!$firstName || !$lastName || !$phone)
            {
                return $view->render($response, 'client_form.twig', [
                    'id' => $client_id,
                    'first_name_value' => $firstName,
                    'last_name_value' => $lastName,
                    'phone_value' => $phone,
                    'first_name_error' => !$firstName,
                    'last_name_error' => !$lastName,
                    'phone_error' => !$phone,
                ]);
            }
            else
            {
                $params = ClientRequestParser::parseEditClientParams((array)$request->getParsedBody());
                ServiceProvider::getInstance()->getClientService()->editClient($params);

                return $view->render($response, 'redirect.twig', [
                    'url' => '/client/list',
                ]);
            }
        }

        return $view->render($response, 'home.twig', [
            'message' => 'Oops! its 404 :(',
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
