<?php

namespace app\controllers;

use app\models\Account;

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

        if ($model->load($request) && $model->validate()) {
            $model->save();
        }
    }


    public function actionUpdate($id)
    {
        $account = Account::findOne($id);
        $request = \Yii::$app->request->post();
        if ($account) {
            $account->load($request);
            $account->save();
        }
    }
}
