<?php

namespace BeerBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class StyleRepository.
 */
class StyleRepository extends EntityRepository
{
    /**
     * @return $this
     */
    public function eraseAllRecords()
    {
        $this->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute();

        return $this;
    }
}
