<?php

namespace app\controllers;

use app\cloudflare\sdk\Endpoints\ZonesDns;
use app\cloudflare\sdk\Endpoints\ZonesSettings;
use app\models\Account;
use app\models\DnsRecord;
use app\models\NameServer;
use app\models\ZoneDns;
use app\models\ZoneNameServer;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\DNS;
use Cloudflare\API\Endpoints\Zones;



class ApiController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $zones = \app\models\Zones::find()->all();

        return $this->render('index', ['model' => $zones]);
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionDebug()
    {
        $request = \Yii::$app->request->post();

        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setDev($zone->zone_id, $request['value'] === 'true' ? 'on' : 'off');

        $zone->debug = $request['value'] === 'true' ? 1 : 0;
        $zone->save();

        return $result;
    }

    public function actionSsl()
    {
        $request = \Yii::$app->request->post();

        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setSsl($zone->zone_id, $request['value'] === 'true' ? 'strict' : 'off');

        $zone->ssl = $request['value'] === 'true' ? 1 : 0;
        $zone->save();

        return $result;
    }

    public function actionRewrite()
    {
        $request = \Yii::$app->request->post();

        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setRewrite($zone->zone_id, $request['value'] === 'true' ? 'on' : 'off');

        $zone->rewrite = $request['value'] === 'true' ? 1 : 0;
        $zone->save();

        return $result;
    }

    public function actionAttack()
    {
        $request = \Yii::$app->request->post();

        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setSecurityLevel($zone->zone_id, $request['value'] === 'false' ? 'essentially_off' : 'under_attack');

        $zone->attack_mode = $request['value'] === 'true' ? 1 : 0;
        $zone->save();

        return $result;
    }


    public function actionTls()
    {
        $request = \Yii::$app->request->post();

        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setTls($zone->zone_id, $request['value']);

        $zone->tls = $request['value'];
        $zone->save();

        return $result;
    }

    public function actionCache()
    {
        $request = \Yii::$app->request->post();
        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->purge($zone->zone_id);

        return $result;

    }

    public function actionDelete()
    {
        $request = \Yii::$app->request->post();
        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $zoneSettings = new ZonesSettings($adapter);
        $result = $zoneSettings->delete($zone->zone_id);

        $zone->delete();

        return $result;
    }

    public function actionGetdns()
    {
        $request = \Yii::$app->request->post();
        $zone = \app\models\Zones::findOne(['id' => $request['id']]);

        $records = [];
        foreach ($zone->dns as $dns) {
            $records[] = [
                'id' => $dns->id,
                'type' => $dns->type,
                'name' => $dns->name,
                'value' => $dns->value,
                'ttl' => $dns->ttl,
                'status' => $dns->status
            ];
        }

        $result['zone'] = $zone->id;
        $result['records'] = $records;

        return $result;
    }


    public function actionInsertDns()
    {
        $request = \Yii::$app->request->post();
        $zone = \app\models\Zones::findOne(['id' => $request['id']]);



        $key = new APIKey($zone->account->email, $zone->account->api_key);
        $adapter = new Guzzle($key);
        $dnsPoint = new ZonesDns($adapter);

        $result = $dnsPoint->addRecord($zone->zone_id, $request['type'], $request['name'], $request['value'], $request['status'] === 'true' ? 1 : $request['ttl'], $request['status'] === 'true' ? true : false);

        $dns = new DnsRecord();
        $dns->record_id = $result;
        $dns->type = $request['type'];
        $dns->name = $request['name'];
        $dns->value = $request['value'];
        $dns->ttl = $request['status'] === 'true' ? 1 : $request['ttl'];
        $dns->status = $request['status'] === 'true' ? 1 : 0;
        $dns->save();

        \Yii::$app->db->createCommand()->insert('zone_dns', [
            'zone_id' => $zone->id,
            'dns_id' => $dns->id
        ])->execute();

        return ['id' => $dns->id];
    }


    public function actionUpdateDns()
    {
        $request = \Yii::$app->request->post();
        $dns = DnsRecord::findOne(['id' => $request['id']]);


        $key = new APIKey($dns->zones[0]->account->email, $dns->zones[0]->account->api_key);
        $adapter = new Guzzle($key);
        $dnsPoint = new DNS($adapter);


        $data = [
            'type' => $dns->type,
            'name' => $request['key'] === 'name' ? $request['value'] : $dns->name,
            'content' => $request['key'] === 'value' ? $request['value'] : $dns->value,
            'proxied' => $request['key'] === 'status' ? $request['value'] === 'true' ? true : false : $dns->status === 1 ? true : false
        ];

        $result = $dnsPoint->updateRecordDetails($dns->zones[0]->zone_id, $dns->record_id, $data);


        if ($request['key'] === 'name') {
            $dns->name = $request['value'];
        }

        if ($request['key'] === 'value') {
            $dns->value = $request['value'];
        }

        if ($request['key'] === 'status') {
            $dns->status = $request['value'] === 'true' ? 1 : 0;
        }

        $dns->save();

        return $result;

        /* $account = Account::findOne(['email' => $account]);
        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $dns = new DNS($adapter);

        $data = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $status === 'true' ? true : false
        ];

        try {
            $dns->updateRecordDetails($zone, $id, $data);
        } catch (\Exception $e) {
            dump($e->getMessage());exit;
        }*/


    }

    public function actionDeleteDns()
    {
        $id = \Yii::$app->request->post('id');
        $dns = DnsRecord::findOne(['id' => $id]);

        $zone = $dns->zones[0];
        $account = $zone->account;

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $dnsPoint = new DNS($adapter);

        $result = $dnsPoint->deleteRecord($zone->zone_id, $dns->record_id);
        $dns->delete();

        return $result;

    }

    public function actionSync()
    {
        $db = \Yii::$app->db;
        $db->createCommand()->checkIntegrity(false)->execute();
        $db->createCommand()->truncateTable(ZoneDns::tableName())->execute();
        $db->createCommand()->truncateTable(DnsRecord::tableName())->execute();
        $db->createCommand()->truncateTable(ZoneNameServer::tableName())->execute();
        $db->createCommand()->truncateTable(NameServer::tableName())->execute();
        $db->createCommand()->truncateTable(\app\models\Zones::tableName())->execute();
        $db->createCommand()->checkIntegrity(true)->execute();

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
