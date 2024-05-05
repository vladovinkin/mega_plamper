<?php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ArticleApiController
{
    private const HTTP_STATUS_OK = 200;
    private const HTTP_STATUS_BAD_REQUEST = 400;

    public function listArticles(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
	// ... обработка HTTP запроса

        return $this->success($response, [/* Данные */]);
    }

    // Вспомогательный метод, возвращает ответ со статусом 200
    //  и телом ответа в формате JSON
    private function success(ResponseInterface $response, array $responseData): ResponseInterface
    {
        return $this->withJson($response, $responseData)->withStatus(self::HTTP_STATUS_OK);
    }


    // Вспомогательный метод, возвращает ответ с телом в формате JSON
    private function withJson(ResponseInterface $response, array $responseData): ResponseInterface
    {
        try
        {
            $responseBytes = json_encode($responseData, JSON_THROW_ON_ERROR);
            $response->getBody()->write($responseBytes);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (\JsonException $e)
        {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

