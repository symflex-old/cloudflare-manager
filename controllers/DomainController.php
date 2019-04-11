<?php

namespace app\controllers;

use app\cloudflare\sdk\Endpoints\ZonesSettings;
use app\models\Account;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\DNS;
use Cloudflare\API\Endpoints\User;
use Cloudflare\API\Endpoints\Zones;
use yii\web\Response;


class DomainController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $account = Account::find()->all();

        $result = [];

        foreach ($account as $account) {

            try {
                $key = new APIKey($account->email, $account->api_key);
                $adapter = new Guzzle($key);
                $zones = new Zones($adapter);


                $zonesSettings = new ZonesSettings($adapter);


                $dns = new \Cloudflare\API\Endpoints\DNS($adapter);

                foreach ($zones->listZones()->result as $zone) {
                    $settings = $zonesSettings->getSettingsByZone($zone->id);

                    $record = $dns->listRecords($zone->id, 'A', $zone->name);

                    /*var_dump($record);
                    var_dump($zone);
                    var_dump($settings);
                    exit;*/

                    $result[] = [
                        'id' => $zone->id,
                        'account' => $zone->account->name,
                        'domain' => $zone->name,
                        'dev' => $settings['development_mode'] === 'on' ? true : false,
                        'ns' => $zone->name_servers,
                        'dns' => !empty($record->result[0]->content) ? $record->result[0]->content : null,
                        'dns_id' => !empty($record->result[0]->id) ? $record->result[0]->id : null,
                        'rewrite' => $settings['automatic_https_rewrites'] === 'on' ? true : false,
                        'tls' => $settings['min_tls_version'],
                        'ssl' => $settings['ssl'] === 'full' ? true : false,
                        'sec_level' => $settings['security_level'] === 'under_attack' ? true : false

                    ];
                }

            } catch (\Exception $e) {
                var_dump($e->getMessage());
                continue;
            }
        }

        return $this->render('index', ['zones' => $result]);
    }

    public function actionCreate()
    {
        $accounts = Account::find()->all();


        if (\Yii::$app->request->isPost) {
            $request = \Yii::$app->request->post();

            $account = Account::findOne(['id' => $request['account']]);

            $key = new APIKey($account->email, $account->api_key);
            $adapter = new Guzzle($key);
            $zone = new Zones($adapter);
            $zone->addZone($request['domain'], true);

            return $this->redirect('/domain/index');
        }

        return $this->render('create', [
            'accounts' => $accounts
        ]);
    }

    public function actionApi()
    {
        $request = \Yii::$app->request->post();

        switch ($request['action']) {
            case 'rewrite':
                $this->rewrite($request['account'], $request['id'], $request['value']);
                break;
            case 'dev':
                $this->dev($request['account'], $request['id'], $request['value']);
                break;
            case 'security_level':
                $this->securityLevel($request['account'], $request['id'], $request['value']);
                break;
            case 'ssl':
                $this->ssl($request['account'], $request['id'], $request['value']);
                break;
            case 'tls':
                $this->tls($request['account'], $request['id'], $request['value']);
                break;
            case 'purge':
                $this->purge($request['account'], $request['id']);
                break;
            case 'recordA':
                $this->recordA($request['account'], $request['id'], $request['value'], $request['record'], $request['domain']);
                break;
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => true];
    }

    protected function recordA($account, $id, $value, $record, $domain)
    {
        $account = Account::findOne(['email' => $account]);

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $dns = new DNS($adapter);
        $res = $dns->updateRecordDetails($id, $record, [
            'type' => 'A',
            'name' => $domain,
            'content' => $value
        ]);


    }


    protected function purge($account, $id)
    {
        $account = Account::findOne(['email' => $account]);

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->purge($id);

    }

    protected function tls($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setTls($id, $value);

    }

    protected function ssl($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setSsl($id, $value === 'true' ? 'full' : 'off');

    }

    protected function dev($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setDev($id, $value === 'true' ? 'on' : 'off');

    }

    protected function rewrite($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setRewrite($id, $value === 'true' ? 'on' : 'off');
    }

    protected function securityLevel($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings = new ZonesSettings($adapter);
        $result = $settings->setSecurityLevel($id, $value === 'false' ? 'essentially_off' : 'under_attack');
    }
}
