<?php
declare(strict_types=1);

namespace App\Model\Service;

use App\Common\Database\Synchronization;
use App\Database\MasterRepository;
use App\Model\Master;
//use App\Model\Exception\MasterNotFoundException;

class MasterService
{
    private Synchronization $synchronization;
    private MasterRepository $masterRepository;

    public function __construct(Synchronization $synchronization, MasterRepository $masterRepository)
    {
        $this->synchronization = $synchronization;
        $this->masterRepository = $masterRepository;
    }

    /**
     * @return Master[]
     */
    public function getMasters(): array
    {
        return $this->masterRepository->listActive();
    }

    public function deleteMaster(int $id): void
    {
        $this->masterRepository->delete([$id]);
    }
}
