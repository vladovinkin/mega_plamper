<?php
declare(strict_types=1);

namespace App\Model\Service;

use App\Database\MasterRepository;
use App\Model\Data\CreateMasterParams;
use App\Model\Data\EditMasterParams;
use App\Model\Master;

class MasterService
{
    public function __construct(
        private readonly MasterRepository $masterRepository
    )
    {
    }

    /**
     * @return Master[]
     */
    public function getMasters(): array
    {
        return $this->masterRepository->list();
    }

    public function deleteMaster(int $id): void
    {
        $this->masterRepository->delete([$id]);
    }

    public function createMaster(CreateMasterParams $params): int
    {
        $master = new Master(
            null,
            $params->getFirstName(),
            $params->getLastName(),
            $params->getPhone()
        );

        return $this->masterRepository->save($master);
    }

    public function editMaster(EditMasterParams $params): void
    {
        $master = $this->getMaster($params->getId());
        $master->edit($params->getFirstName(), $params->getLastName(), $params->getPhone());
        $this->masterRepository->save($master);
    }

    /**
     * @param int $id
     * @return Master
     */
    public function getMaster(int $id): Master
    {
        return $this->masterRepository->findOne($id);
    }
}
