<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Master;
use App\Model\Service\ServiceProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class MasterController
{
    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $masters = ServiceProvider::getInstance()->getMasterService()->getMasters();
        $view = Twig::fromRequest($request);

        return $view->render($response, 'master_list.twig', [
            'quantity' => count($masters),
            'masters' => array_map(fn($master) => $this->getRowData($master), $masters),
        ]);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $id = (int)$args['id'];
        ServiceProvider::getInstance()->getMasterService()->deleteMaster($id);
        $view = Twig::fromRequest($request);

        return $view->render($response, 'redirect.twig', [
            'url' => '/',
        ]);
    }

    public function addForm(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'master_add.twig', []);
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
            return $view->render($response, 'master_add.twig', [
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
            $master = new Master(null, $firstName, $lastName, $phone, null);
            ServiceProvider::getInstance()->getMasterService()->addMaster($master);

            return $view->render($response, 'redirect.twig', [
                'url' => '/',
            ]);
        }
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'home.twig', [
            'message' => 'edit master form',
        ]);
    }

    private function getRowData(Master $data): array
    {
        return [
            'id' => $data->getId(),
            'first_name' => $data->getFirstName(),
            'last_name' => $data->getLastName(),
            'phone' => $data->getPhone(),
        ];
    }

    public function addEvent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'home.twig', [
            'message' => 'hello world!',
        ]);
    }
}