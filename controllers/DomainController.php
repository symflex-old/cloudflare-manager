<?php

namespace app\controllers;

use app\cloudflare\sdk\Endpoints\Zones;
use app\models\Account;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\User;


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
            $dns = new \Cloudflare\API\Endpoints\DNS($adapter);

            foreach ($zones->listZones()->result as $zone) {

                $settings = $zones->getAllZoneSettings($zone->id);

                var_dump($settings);

                $record = $dns->listRecords($zone->id, 'A', $zone->name);

                $result[] = [
                    'id' => $zone->id,
                    'account' => $zone->account->name,
                    'domain' => $zone->name,
                    'dev' => $zone->development_mode,
                    'ns' => $zone->name_servers,
                    'dns' => !empty($record->result[0]->content) ? $record->result[0]->content : null
                ];
            }
        }

        return $this->render('index', ['zones' => $result]);
    }

    public function actionCreate()
    {
        return $this->render('create');
    }
}
