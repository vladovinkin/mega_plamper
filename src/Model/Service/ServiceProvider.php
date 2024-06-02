<?php
declare(strict_types=1);

namespace App\Model\Service;

use App\Common\Database\ConnectionProvider;
use App\Database\ClientRepository;
use App\Database\MasterRepository;

final class ServiceProvider
{
    private ?MasterService $masterService = null;
    private ?MasterRepository $masterRepository = null;
    private ?ClientService $clientService = null;
    private ?ClientRepository $clientRepository = null;

    public static function getInstance(): self
    {
        static $instance = null;
        if ($instance === null)
        {
            $instance = new self();
        }
        return $instance;
    }

    public function getMasterService(): MasterService
    {
        if ($this->masterService === null)
        {
            $this->masterService = new MasterService($this->getMasterRepository());
        }
        return $this->masterService;
    }

    public function getClientService(): ClientService
    {
        if ($this->clientService === null)
        {
            $this->clientService = new ClientService($this->getClientRepository());
        }
        return $this->clientService;
    }

    private function getMasterRepository(): MasterRepository
    {
        if ($this->masterRepository === null)
        {
            $this->masterRepository = new MasterRepository(ConnectionProvider::getConnection());
        }
        return $this->masterRepository;
    }

    private function getClientRepository(): ClientRepository
    {
        if ($this->clientRepository === null)
        {
            $this->clientRepository = new ClientRepository(ConnectionProvider::getConnection());
        }
        return $this->clientRepository;
    }
}
