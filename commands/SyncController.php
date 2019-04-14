<?php

namespace app\commands;

use app\cloudflare\sdk\Endpoints\ZonesSettings;
use app\models\Account;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\Zones;
use yii\console\Controller;
use yii\console\ExitCode;


class SyncController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {

        $db = \Yii::$app->db;

        $accounts = Account::find()->all();

        foreach ($accounts as $account) {

            $key = new APIKey($account->email, $account->api_key);
            $adapter = new Guzzle($key);
            $zones = new Zones($adapter);

            $zoneSettings = new ZonesSettings($adapter);
            $dns = new \Cloudflare\API\Endpoints\DNS($adapter);

            foreach ($zones->listZones()->result as $zone) {

                $settings = $zoneSettings->getSettingsByZone($zone->id);

                $dnsRecord = $dns->listRecords($zone->id, 'A');

                $res = $db->createCommand()->insert('zones', [
                    'account_id' => $account->id,
                    'zone_id' => $zone->id,
                    'domain' => $zone->name,
                    'debug' => $settings['development_mode'] === 'on' ? true : false,
                    'rewrite' => $settings['automatic_https_rewrites'] === 'on' ? true : false,
                    'attack_mode' => $settings['security_level'] === 'under_attack' ? true : false,
                    'tls' => $settings['min_tls_version'],
                    'ssl' => $settings['ssl'] === 'strict' ? true : false
                ])->execute();

                $zoneId = $db->getLastInsertID();

                foreach ($zone->name_servers as $server) {
                    $db->createCommand()->insert('name_server', [
                        'server' => $server,
                    ])->execute();

                    $db->createCommand()->insert('zone_name_server', [
                        'server_id' => $db->getLastInsertID(),
                        'zone_id' => $zoneId
                    ])->execute();
                }

                foreach ($dnsRecord->result as $record) {

                    $db->createCommand()->insert('dns_record', [
                        'record_id' => $record->id,
                        'type' => $record->type,
                        'name' => $record->name,
                        'value' => $record->content,
                        'ttl' => $record->ttl,
                        'status' => $record->proxied == 'true' ? true : false
                    ])->execute();

                    $db->createCommand()->insert('zone_dns', [
                        'dns_id' => $db->getLastInsertID(),
                        'zone_id' => $zoneId
                    ])->execute();

                }

            }

        }
    }
}
