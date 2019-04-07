<?php

namespace app\cloudflare\sdk\Endpoints;

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Traits\BodyAccessorTrait;

class ZonesSettings
{
    use BodyAccessorTrait;

    public const FLAG_SSL       = ['off', 'full', 'flexible', 'strict'];
    public const FLAG_ON_OFF    = ['on' => 1, 'off' => 0];
    public const EXCLUDE_ON_OFF = ['ssl'];

    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getSettingsByZone($zoneID)
    {
        $response = $this->adapter->get('zones/' . $zoneID . '/settings');
        $this->body = $response->getBody();
        $result = [];
        foreach (json_decode($this->body)->result as $item) {

            $value = $item->value;

            if (is_object($item->value)) {
                $value = $item->value;
            }

            if (!isset(self::EXCLUDE_ON_OFF[$item->id])
                && array_key_exists($item->id, self::FLAG_ON_OFF)) {
                $value = self::FLAG_ON_OFF[$item->value];
            }

            $result[$item->id] = $value;
        }

        return $result;
    }
}
