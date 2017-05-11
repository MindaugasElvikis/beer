<?php

namespace BeerBundle\Service;

use \Doctrine\ORM\EntityManagerInterface;
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
     * @var DistanceService
     */
    protected $distanceService;

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
     * @param EntityManagerInterface $entityManager
     * @param DistanceService        $distanceService
     */
    public function __construct(EntityManagerInterface $entityManager, DistanceService $distanceService)
    {
        $this->em = $entityManager;
        $this->distanceService = $distanceService;
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
                $sum += $this->distanceService->getDistance($this->home, $point);
            } else {
                $sum += $this->distanceService->getDistance($points[$key - 1], $point);
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
                    $this->kmLeft -= $this->distanceService->getDistance($path[count($path) - 2], $point);
                    $continue = true;
                    break;
                }

                if ($this->isWorthFlying($this->home, $point)) {
                    $path[] = $point;
                    $this->kmLeft -= $this->distanceService->getDistance($this->home, $point);
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
        $distance = $this->distanceService->getDistance($from, $to);

        return $distance < $this->kmLeft &&
            $this->distanceService->getDistance($to, $this->home) <= $this->kmLeft - $distance;
    }
}
