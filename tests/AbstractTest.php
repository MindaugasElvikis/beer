<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Start tests.
     */
    public function setUp()
    {
        $this->client = self::createClient();
        $this->container = $this->client->getContainer();
    }

    /**
     * End tests.
     */
    public function tearDown()
    {
        $this->container = null;
        $this->client = null;
    }
}
