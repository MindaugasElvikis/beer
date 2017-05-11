<?php

namespace BeerBundle\Service;

use \Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use BeerBundle\Model\LocationModel;
use BeerBundle\Entity\Location;

/**
 * Class TripService.
 */
class TripService
{
    /**
     * Maximum flight limit in KM.
     */
    const FLIGHT_LIMIT = 2000;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var float
     */
    protected $kmLeft = self::FLIGHT_LIMIT;

    /**
     * @var LocationModel
     */
    protected $home;

    /**
     * @var Location[]
     */
    protected $points;

    /**
     * @var Location[]
     */
    protected $bestTravelPlan;

    /**
     * TripService constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param LocationModel $startLocation
     *
     * @return array|Location[]
     */
    public function travel(LocationModel $startLocation)
    {
        $this->home = $startLocation;

        /** @var Location[] $locations */
        $this->points = $this->em->getRepository(Location::class)
            ->getLocationsInArea($startLocation, self::FLIGHT_LIMIT / 2);

        return array_values(array_unique($this->fly()));
    }

    /**
     * @param array|Location[] $points
     *
     * @return float|int
     */
    public function sumPointsDistance($points)
    {
        $sum = 0;

        foreach ($points as $key => $point) {
            if ($key === 0 || $key === count($points) - 1) {
                $sum += $this->getDistance($this->home, $point);
            } else {
                $sum += $this->getDistance($points[$key - 1], $point);
            }
        }

        return $sum;
    }

    /**
     * @param Location[] $points
     *
     * @return string[]
     */
    public function getBeersFromPoints($points)
    {
        $beers = [];

        foreach ($points as $point) {
            foreach ($point->getBrewery()->getBeers() as $beer) {
                $beers[] = $beer->getTitle();
            }
        }

        return array_unique($beers);
    }

    /**
     * @param array $path
     *
     * @return array|Location[]
     */
    protected function fly(array $path = [])
    {
        $continue = false;
        foreach ($this->points as $key => $point) {
            if (!in_array($point, $path, true)) {
                if ($key > 1 && !empty($path) && $this->isWorthFlying($path[count($path) - 2], $point)) {
                    $path[] = $point;
                    $this->kmLeft -= $this->getDistance($path[count($path) - 2], $point);
                    $continue = true;
                    break;
                }

                if ($this->isWorthFlying($this->home, $point)) {
                    $path[] = $point;
                    $this->kmLeft -= $this->getDistance($this->home, $point);
                    $continue = true;
                    break;
                }
            }
        }

        if ($continue && $this->kmLeft >= 0) {
            return $this->fly($path);
        }

        return $path;
    }

    /**
     * @param Location|LocationModel $from
     * @param Location|LocationModel $to
     *
     * @return bool
     */
    protected function isWorthFlying($from, $to)
    {
        $distance = $this->getDistance($from, $to);

        return $distance < $this->kmLeft && $this->getDistance($to, $this->home) <= $this->kmLeft - $distance;
    }

    /**
     * @param Location|LocationModel $from
     * @param Location|LocationModel $to
     *
     * @return float
     */
    public function getDistance($from, $to)
    {
        return $this->calculateDistance(
            $from->getLatitude(),
            $from->getLongitude(),
            $to->getLatitude(),
            $to->getLongitude()
        );
    }

    /**
     * @param float $latitude1
     * @param float $longitude1
     * @param float $latitude2
     * @param float $longitude2
     *
     * @return float
     */
    protected function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));

        return $earth_radius * $c;
    }
}
