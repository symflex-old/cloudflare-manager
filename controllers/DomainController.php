<?php

namespace app\controllers;

use app\cloudflare\sdk\Endpoints\ZonesSettings;
use app\models\Account;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\Zones;


class DomainController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $account = Account::find()->all();

        $result = [];

        foreach ($account as $account) {

            $key     = new APIKey($account->email, $account->api_key);
            $adapter = new Guzzle($key);
            $zones    = new Zones($adapter);
            $zonesSettings = new ZonesSettings($adapter);

            $dns = new \Cloudflare\API\Endpoints\DNS($adapter);

            foreach ($zones->listZones()->result as $zone) {
                $settings = $zonesSettings->getSettingsByZone($zone->id);

                $record = $dns->listRecords($zone->id, 'A', $zone->name);


                var_dump($zone);
                exit;


                $result[] = [
                    'id'        => $zone->id,
                    'account'   => $zone->account->name,
                    'domain'    => $zone->name,
                    'dev'       => $zone->development_mode,
                    'ns'        => $zone->name_servers,
                    'dns'       => !empty($record->result[0]->content) ? $record->result[0]->content : null,
                    'rewrite'   => $settings['automatic_https_rewrites'] === 'on' ? true : false,
                    'tls'       => $settings['min_tls_version'],
                    'ssl'       => $settings['ssl'],
                    'sec_level' => $settings['security_level'] === 'under_attack' ? $settings['security_level'] :  'essentially_off'

                ];
            }
        }

        return $this->render('index', ['zones' => $result]);
    }

    public function actionCreate()
    {
        return $this->render('create');
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
        }
    }

    protected function dev($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key     = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings    = new ZonesSettings($adapter);
        $result = $settings->setDev($id, $value === 'true' ? 'on' : 'off' );
        var_dump($result);


    }

    protected function rewrite($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key     = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings    = new ZonesSettings($adapter);
        $result = $settings->setRewrite($id, $value === 'true' ? 'on' : 'off' );
        var_dump($result);
    }

    protected function securityLevel($account, $id, $value)
    {
        $account = Account::findOne(['email' => $account]);

        $key     = new APIKey($account->email, $account->api_key);
        $adapter = new Guzzle($key);
        $settings    = new ZonesSettings($adapter);
        $result = $settings->setSecurityLevel($id, $value === 'false' ? 'essentially_off' : 'under_attack'  );
        var_dump($result);
    }
}
