<?php

namespace app\controllers;

use app\models\Account;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\User;

class AccountController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $accounts = Account::find()->all();

        return $this->render('index', [
            'accounts' => $accounts
        ]);
    }


    public function actionCreate()
    {
        $request = \Yii::$app->request->post();
        $model = new Account();

        $error = false;

        if ($model->load($request) && $model->validate()) {
            $request = \Yii::$app->request->post();
            $key = new APIKey($request['Account']['email'], $request['Account']['api_key']);
            $adapter = new Guzzle($key);
            $user = new User($adapter);

            try {
                $user->getUserID();
                $model->save();
            } catch (\Exception $exception) {
                $error = true;
            }


        } else {
            $error = true;
        }


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['error' => $error];
    }


    public function actionUpdate($id)
    {
        $account = Account::findOne($id);
        $error = false;

        if (\Yii::$app->request->isPost) {

            $request = \Yii::$app->request->post();
            $key = new APIKey($request['Account']['email'], $request['Account']['api_key']);
            $adapter = new Guzzle($key);
            $user = new User($adapter);

            try {
                $user->getUserID();

                $account->load($request);
                $account->save();
            } catch (\Exception $exception) {
                $error = true;

            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['error' => $error];
    }

    public function beforeAction($action)
    {


        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        return parent::beforeAction($action);
    }
}
