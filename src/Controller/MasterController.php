<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\MasterRequestParser;
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

    public function showAddForm(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'master_form.twig', []);
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
            return $view->render($response, 'master_form.twig', [
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
            $params = MasterRequestParser::parseCreateMasterParams((array)$request->getParsedBody());
            $masterId = ServiceProvider::getInstance()->getMasterService()->createMaster($params);

            return $view->render($response, 'redirect.twig', [
                'url' => '/master/list',
            ]);
        }
    }

    public function showEditForm(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $master_id = (int)$args['id'] ?? null;
        $view = Twig::fromRequest($request);

        if ($master_id && $master = ServiceProvider::getInstance()->getMasterService()->getMaster($master_id)) {
            $firstName = $master->getFirstName() ?? null;
            $lastName = $master->getLastName() ?? null;
            $phone = $master->getPhone() ?? null;

            return $view->render($response, 'master_form.twig', [
                'id' => $master_id,
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
        $master_id = (int)$params['id'] ?? null;

        $view = Twig::fromRequest($request);

        if ($master_id && $master = ServiceProvider::getInstance()->getMasterService()->getMaster($master_id))
        {
            $firstName = $params['first_name'] ?? null;
            $lastName = $params['last_name'] ?? null;
            $phone = $params['phone'] ?? null;

            if (!$firstName || !$lastName || !$phone)
            {
                return $view->render($response, 'master_form.twig', [
                    'id' => $master_id,
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
                $params = MasterRequestParser::parseEditMasterParams((array)$request->getParsedBody());
                ServiceProvider::getInstance()->getMasterService()->editMaster($params);

                return $view->render($response, 'redirect.twig', [
                    'url' => '/master/list',
                ]);
            }
        }

        return $view->render($response, 'home.twig', [
            'message' => 'Oops! its 404 :(',
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