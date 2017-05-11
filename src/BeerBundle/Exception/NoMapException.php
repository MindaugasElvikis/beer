<?php

namespace BeerBundle\Exception;

/**
 * Class NoMapException.
 */
class NoMapException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Database not ready, prepare database first.';
}
