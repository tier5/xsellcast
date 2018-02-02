<?php namespace App\Storage\LbtWp\Endpoint;

use Vnn\WpApiClient\Endpoint\AbstractWpEndpoint;
use \RuntimeException;

/**
 * Class Posts
 * @package Vnn\WpApiClient\Endpoint
 */
class Offers extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/wp/v2/offers';
    }
}
