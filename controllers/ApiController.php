<?php

namespace app\controllers;

use app\cloudflare\sdk\Endpoints\ZonesSettings;
use app\models\Account;
use app\models\DnsRecord;
use app\models\NameServer;
use app\models\ZoneDns;
use app\models\ZoneNameServer;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
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

        $result = [];
        foreach ($zone->dns as $dns) {
            $result[] = [
                'id' => $dns->id,
                'type' => $dns->type,
                'name' => $dns->name,
                'value' => $dns->value,
                'ttl' => $dns->ttl,
                'status' => $dns->status
            ];
        }

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
