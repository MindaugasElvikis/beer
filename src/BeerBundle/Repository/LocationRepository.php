<?php

namespace BeerBundle\Repository;

use BeerBundle\Model\Location;
use Doctrine\ORM\EntityRepository;

/**
 * Class LocationRepository.
 */
class LocationRepository extends EntityRepository
{
    /**
     * @return $this
     */
    public function eraseAllRecords()
    {
        $this->createQueryBuilder('l')
            ->delete()
            ->getQuery()
            ->execute();

        return $this;
    }

    /**
     * @param Location $location
     * @param float    $limit
     *
     * @return \Doctrine\ORM\Query
     */
    public function getLocationsInArea(Location $location, $limit)
    {
        $query = $this->createQueryBuilder('location');

        return $query
            ->addSelect('distance(location.latitude, location.longitude, ' . $location->getLatitude() . ', ' . $location->getLongitude() . ') as hidden distance')
            ->addSelect('count(beers.id) as hidden beer_count')
            ->join('location.brewery', 'brewery')->addSelect('brewery')
            ->join('brewery.beers', 'beers')->addSelect('beers')
            ->having($query->expr()->lte('distance', ':limit'))
            ->setParameter('limit', $limit)
            ->addOrderBy($query->expr()->desc('beer_count'))
            ->addOrderBy($query->expr()->asc('distance'))
            ->addGroupBy('location.id')
            ->getQuery();
    }
}
