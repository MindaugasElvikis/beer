<?php

namespace BeerBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @return $this
     */
    public function eraseAllRecords()
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->getQuery()
            ->execute();

        return $this;
    }
}
