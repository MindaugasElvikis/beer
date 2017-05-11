<?php

namespace BeerBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class BeerRepository.
 */
class BeerRepository extends EntityRepository
{
    /**
     * @return $this
     */
    public function eraseAllRecords()
    {
        $this->createQueryBuilder('b')
            ->delete()
            ->getQuery()
            ->execute();

        return $this;
    }
}
