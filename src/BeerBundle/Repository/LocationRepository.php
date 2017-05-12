<?php

namespace BeerBundle\Repository;

use BeerBundle\Entity\Location;
use BeerBundle\Model\LocationModel;
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
     * @param LocationModel $location
     * @param float         $limit
     *
     * @return array|Location[]
     */
    public function getLocationsInArea(LocationModel $location, $limit)
    {
        $query = $this->createQueryBuilder('location');

        $query
            ->addSelect('distance(location.latitude, location.longitude, :lat, :long) as hidden distance')
            ->setParameter('lat', $location->getLatitude())
            ->setParameter('long', $location->getLongitude())
            ->addSelect('count(beers.id) as hidden beer_count')
            ->join('location.brewery', 'brewery')->addSelect('brewery')
            ->join('brewery.beers', 'beers')->addSelect('beers')
            ->having($query->expr()->lte('distance', ':limit'))
            ->setParameter('limit', $limit)
            ->addOrderBy($query->expr()->desc('beer_count'))
            ->addOrderBy($query->expr()->asc('distance'))
            ->addGroupBy('location.id')
            ->addGroupBy('beers.id');

        return $query->getQuery()->getResult();
    }
}
