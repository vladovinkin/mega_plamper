<?php
declare(strict_types=1);

namespace App\Model\Service;

use App\Common\Database\ConnectionProvider;
use App\Common\Database\Synchronization;
use App\Database\MasterRepository;

final class ServiceProvider
{
    private ?MasterService $masterService = null;
    private ?MasterRepository $masterRepository = null;

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
            $synchronization = new Synchronization(ConnectionProvider::getConnection());
            $this->masterService = new MasterService($synchronization, $this->getMasterRepository());
        }
        return $this->masterService;
    }

    private function getMasterRepository(): MasterRepository
    {
        if ($this->masterRepository === null)
        {
            $this->masterRepository = new MasterRepository(ConnectionProvider::getConnection());
        }
        return $this->masterRepository;
    }
}
