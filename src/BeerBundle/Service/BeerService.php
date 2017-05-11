<?php

namespace BeerBundle\Service;

use BeerBundle\Entity\Beer;
use BeerBundle\Entity\Brewery;
use BeerBundle\Entity\Category;
use BeerBundle\Entity\Location;
use BeerBundle\Entity\Style;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BeerService.
 */
class BeerService
{
    use ContainerAwareTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var bool
     */
    protected $fetchFromRemote = false;

    /**
     * BeerService constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return bool
     */
    public function isDatabaseReady()
    {
        return !(
            $this->em->getRepository(Brewery::class)->findOneBy([]) === null ||
            $this->em->getRepository(Beer::class)->findOneBy([]) === null ||
            $this->em->getRepository(Location::class)->findOneBy([]) === null ||
            $this->em->getRepository(Category::class)->findOneBy([]) === null ||
            $this->em->getRepository(Style::class)->findOneBy([]) === null
        );
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function importDatabase()
    {
        if ($this->isDatabaseReady()) {
            $this->clearDatabase();
        }

        $this
            ->importCategories()
            ->importStyles()
            ->importBreweries()
            ->importLocations()
            ->importBeers();

        return $this;
    }

    /**
     * @return $this
     */
    protected function clearDatabase()
    {
        $this->em->getRepository(Location::class)->eraseAllRecords();
        $this->em->getRepository(Beer::class)->eraseAllRecords();
        $this->em->getRepository(Brewery::class)->eraseAllRecords();
        $this->em->getRepository(Style::class)->eraseAllRecords();
        $this->em->getRepository(Category::class)->eraseAllRecords();

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function importBreweries()
    {
        $breweries = $this->csvSourceToEntities(
            $this->container->getParameter('breweries_source'),
            Brewery::class
        );

        /** @var Brewery $brewery */
        foreach ($breweries as $brewery) {
            $this->em->persist($brewery);
        }
        $this->em->flush();

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function importCategories()
    {
        $categories = $this->csvSourceToEntities(
            $this->container->getParameter('categories_source'),
            Category::class
        );

        foreach ($categories as $category) {
            $this->em->persist($category);
        }
        $this->em->flush();

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function importStyles()
    {
        $styles = $this->csvSourceToEntities(
            $this->container->getParameter('styles_source'),
            Style::class
        );

        /** @var Style $style */
        foreach ($styles as $style) {
            $style->setCategory($this->em->getReference(Category::class, $style->getCategory()));
            $this->em->persist($style);
        }
        $this->em->flush();

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function importLocations()
    {
        $locations = $this->csvSourceToEntities(
            $this->container->getParameter('locations_source'),
            Location::class
        );

        /** @var Location $location */
        foreach ($locations as $location) {
            $brewery = $this->em->getRepository(Brewery::class)->find($location->getBrewery());
            if (null !== $brewery) {
                $location->setBrewery($this->em->getReference(Brewery::class, $location->getBrewery()));
                $this->em->persist($location);
            }
        }
        $this->em->flush();

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function importBeers()
    {
        $beers = $this->csvSourceToEntities(
            $this->container->getParameter('beers_source'),
            Beer::class
        );

        /** @var Beer $beer */
        foreach ($beers as $beer) {
            if ($beer->getBrewery() === -1) {
                $beer->setBrewery(null);
            } else {
                $beer->setBrewery($this->em->getReference(Brewery::class, $beer->getBrewery()));
            }
            if ($beer->getCategory() === -1) {
                $beer->setCategory(null);
            } else {
                $beer->setCategory($this->em->getReference(Category::class, $beer->getCategory()));
            }
            if ($beer->getStyle() === -1) {
                $beer->setStyle(null);
            } else {
                $beer->setStyle($this->em->getReference(Style::class, $beer->getStyle()));
            }
            $this->em->persist($beer);
        }
        $this->em->flush();

        return $this;
    }

    /**
     * @param string $source
     * @param string $object
     *
     * @return array
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    protected function csvSourceToEntities($source, $object)
    {
        $fileName = explode('/', $source);
        $fileName = end($fileName);
        $fileSystem = $this->container->get('oneup_flysystem.acme_filesystem');

        if (!$this->fetchFromRemote && $fileSystem->has($fileName)) {
            $data = $fileSystem->get($fileName)->read();
        } else {
            $data = $this->container->get('guzzle_client')->get($source)->getBody()->getContents();
        }

        $serializer = $this->container->get('serializer.csv_decoder');
        $data = $serializer->decode($data, 'csv');
        $serializer = $this->container->get('jms_serializer');

        return $serializer->deserialize(json_encode($data), sprintf('array<%s>', $object), 'json');
    }

    /**
     * @param bool $fetchFromRemote
     *
     * @return $this
     */
    public function setFetchFromRemote($fetchFromRemote)
    {
        $this->fetchFromRemote = $fetchFromRemote;

        return $this;
    }
}
