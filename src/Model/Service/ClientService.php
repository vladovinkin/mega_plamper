<?php
declare(strict_types=1);

namespace App\Model\Service;

use App\Database\ClientRepository;
use App\Model\Client;
use App\Model\Data\CreateClientParams;
use App\Model\Data\EditClientParams;

class ClientService
{
    public function __construct(
        private readonly ClientRepository $clientRepository
    )
    {
    }

    /**
     * @return Client[]
     */
    public function getClients(): array
    {
        return $this->clientRepository->list();
    }

    /**
     * @param CreateClientParams $params
     * @return int
     */
    public function createClient(CreateClientParams $params): int
    {
        $client = new Client(
            null,
            $params->getFirstName(),
            $params->getLastName(),
            $params->getPhone()
        );

        return $this->clientRepository->save($client);
    }

    public function editClient(EditClientParams $params): void
    {
        $client = $this->getClient($params->getId());
        $client->edit($params->getFirstName(), $params->getLastName(), $params->getPhone());
        $this->clientRepository->save($client);
    }

    public function getClient(int $id): Client
    {
        return $this->clientRepository->findOne($id);
    }
}
