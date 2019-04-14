<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Zones;

/**
 * ZonesSearch represents the model behind the search form of `app\models\Zones`.
 */
class ZonesSearch extends Zones
{
    public $servers;
    public $dns;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'ssl', 'rewrite', 'debug', 'attack_mode'], 'integer'],
            [['domain', 'tls', 'servers', 'dns'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Zones::find();
        $query->innerJoinWith('account');
        $query->innerJoinWith('servers')->distinct();
        $query->innerJoinWith('dns')->distinct();


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'account_id' => $this->account_id,
            'ssl' => $this->ssl,
            'rewrite' => $this->rewrite,
            'debug' => $this->debug,
            'attack_mode' => $this->attack_mode,
        ]);


        $query->andFilterWhere(['like', 'name_server.server', $this->servers]);
        $query->andFilterWhere(['like', 'dns_record.value', $this->dns]);
        $query->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'tls', $this->tls]);

        return $dataProvider;
    }
}
