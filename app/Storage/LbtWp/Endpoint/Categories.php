<?php namespace App\Storage\LbtWp\Endpoint;

use Vnn\WpApiClient\Endpoint\AbstractWpEndpoint;
use \RuntimeException;

/**
 * Class Posts
 * @package Vnn\WpApiClient\Endpoint
 */
class Categories extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        // return '/wp-json/wp/v2/offers';
         return '/wpr-datahub-api/v1/add_new_brand';
    }
}
