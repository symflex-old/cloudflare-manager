<?php

namespace app\cloudflare\sdk\Endpoints;

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Traits\BodyAccessorTrait;

class ZonesDns
{
    use BodyAccessorTrait;

    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param string $zoneID
     * @param string $type
     * @param string $name
     * @param string $content
     * @param int $ttl
     * @param bool $proxied
     * @param string $priority
     * @param array $data
     * @return bool
     */
    public function addRecord(
        string $zoneID,
        string $type,
        string $name,
        string $content,
        int $ttl = 0,
        bool $proxied = true,
        string $priority = '',
        array $data = []
    ): string {
        $options = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $proxied
        ];

        if ($ttl > 0) {
            $options['ttl'] = $ttl;
        }

        if (!empty($priority)) {
            $options['priority'] = (int)$priority;
        }

        if (!empty($data)) {
            $options['data'] = $data;
        }

        $user = $this->adapter->post('zones/' . $zoneID . '/dns_records', $options);

        $this->body = json_decode($user->getBody());

        if (isset($this->body->result->id)) {
            return $this->body->result->id;
        }

        return false;
    }
}
