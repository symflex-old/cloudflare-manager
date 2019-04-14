<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "name_server".
 *
 * @property int $id
 * @property string $server
 *
 * @property ZoneNameServer[] $zoneNameServers
 * @property Zones[] $zones
 */
class NameServer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'name_server';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['server'], 'required'],
            [['server'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'server' => 'Server',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZoneNameServers()
    {
        return $this->hasMany(ZoneNameServer::className(), ['server_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZones()
    {
        return $this->hasMany(Zones::className(), ['id' => 'zone_id'])->viaTable('zone_name_server', ['server_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return NameServerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NameServerQuery(get_called_class());
    }

    public static function dropDown()
    {
        $dropdown = [];
        $servers = static::find()->groupBy('server')->all();

        foreach ($servers as $server) {
            $dropdown[$server->server] = $server->server;
        }
        return $dropdown;
    }
}
