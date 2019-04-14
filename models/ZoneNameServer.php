<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zone_name_server".
 *
 * @property int $zone_id
 * @property int $server_id
 *
 * @property NameServer $server
 * @property Zones $zone
 */
class ZoneNameServer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zone_name_server';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['zone_id', 'server_id'], 'required'],
            [['zone_id', 'server_id'], 'integer'],
            [['zone_id', 'server_id'], 'unique', 'targetAttribute' => ['zone_id', 'server_id']],
            [['server_id'], 'exist', 'skipOnError' => true, 'targetClass' => NameServer::className(), 'targetAttribute' => ['server_id' => 'id']],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zones::className(), 'targetAttribute' => ['zone_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'zone_id' => 'Zone ID',
            'server_id' => 'Server ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServer()
    {
        return $this->hasOne(NameServer::className(), ['id' => 'server_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zones::className(), ['id' => 'zone_id']);
    }

    /**
     * {@inheritdoc}
     * @return ZoneNameServerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ZoneNameServerQuery(get_called_class());
    }
}
