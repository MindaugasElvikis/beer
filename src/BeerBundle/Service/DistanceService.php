<?php

namespace BeerBundle\Service;

use BeerBundle\Entity\Location;
use BeerBundle\Model\LocationModel;

/**
 * Class DistanceService.
 */
class DistanceService
{
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
    public function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));

        return $earth_radius * $c;
    }
}
