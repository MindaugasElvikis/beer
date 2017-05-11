<?php

namespace Tests\BeerBundle\Service;

use Tests\AbstractTest;

/**
 * Class DistanceServiceTest.
 */
class DistanceServiceTest extends AbstractTest
{
    /**
     * @return array
     */
    public function coordinatesProvider()
    {
        return [
            [
                51.742503,
                19.432956,
                49.962200164795,
                20.600299835205,
                214,
            ],
            [
                51.742503,
                19.432956,
                51.742503,
                19.432956,
                0,
            ],
        ];
    }

    /**
     * @dataProvider coordinatesProvider
     *
     * @param float $lat1
     * @param float $long1
     * @param float $lat2
     * @param float $long2
     * @param float $expectedDistance
     */
    public function testDistance($lat1, $long1, $lat2, $long2, $expectedDistance)
    {
        $distanceService = $this->container->get('beer.distance_service');

        $this->assertEquals(
            $expectedDistance,
            number_format($distanceService->calculateDistance($lat1, $long1, $lat2, $long2))
        );
    }
}
