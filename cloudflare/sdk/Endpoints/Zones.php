<?php

namespace app\cloudflare\sdk\Endpoints;

use Cloudflare\API\Adapter\Adapter;

class Zones extends \Cloudflare\API\Endpoints\Zones
{

    public function getAllZoneSettings($zoneID)
    {
        $response = $this->adapter->get('zones/' . $zoneID . '/settings');

        $this->body = $response->getBody();

        return json_decode($this->body)->result;
    }
}