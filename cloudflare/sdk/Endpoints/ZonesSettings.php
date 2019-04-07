<?php

namespace app\cloudflare\sdk\Endpoints;

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Traits\BodyAccessorTrait;

class ZonesSettings
{
    use BodyAccessorTrait;

    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getSettingsByZone($zoneID)
    {
        $response = $this->adapter->get('zones/' . $zoneID . '/settings');
        $this->body = $response->getBody();
        return json_decode($this->body)->result;
    }
}
