<?php

namespace BeerBundle\Service;

use \Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use BeerBundle\Model\Location;

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
     * @var Location
     */
    protected $home;

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
     * @param Location $startLocation
     */
    public function startTrip(Location $startLocation)
    {
        $this->home = $startLocation;

        $locations = $this->em->getRepository(\BeerBundle\Entity\Location::class)
            ->getLocationsInArea($startLocation, self::FLIGHT_LIMIT / 2);
    }
}
