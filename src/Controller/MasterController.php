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

    private function getRowData(Master $data): array
    {
        return [
            'id' => $data->getId(),
            'first_name' => $data->getFirstName(),
            'last_name' => $data->getLastName(),
            'phone' => $data->getPhone(),
        ];
    }


    public function new(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'home.twig', [
            'message' => 'hello world!',
        ]);
    }

    public function addEvent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
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
}