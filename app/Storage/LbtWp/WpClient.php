<?php namespace App\Storage\LbtWp;

use Vnn\WpApiClient\WpClient as Client;

/**
 * Class Posts
 * @package Vnn\WpApiClient\Endpoint
 */
class WpClient extends Client
{
    /**
     * @param $endpoint
     * @param array $args
     * @return Endpoint\AbstractWpEndpoint
     */
    public function __call($endpoint, array $args)
    {
        if (!isset($this->endPoints[$endpoint])) {
            $class = 'Vnn\WpApiClient\Endpoint\\' . ucfirst($endpoint);
           	$lbtClass = 'App\Storage\LbtWp\Endpoint\\' . ucfirst($endpoint);
            if (class_exists($class)) {
                $this->endPoints[$endpoint] = new $class($this);
            } elseif(class_exists($lbtClass)){
            	$this->endPoints[$endpoint] = new $lbtClass($this);
            } else {
                throw new \RuntimeException('Endpoint "' . $endpoint . '" does not exist"');
            }
        }

        return $this->endPoints[$endpoint];
    }    
}