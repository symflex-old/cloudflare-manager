<?php

namespace app\controllers;

use app\models\Account;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\Zones;



class ZonesController extends \yii\web\Controller
{


    public function actionIndex()
    {

        $zones = \app\models\Zones::find()->all();

        return $this->render('index', ['model' => $zones]);
    }

    /*
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

            return $this->redirect('/domains');
        }

        return $this->render('create', [
            'accounts' => $accounts
        ]);
    }*/


    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        return parent::beforeAction($action);
    }
}
