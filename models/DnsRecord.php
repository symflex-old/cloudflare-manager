<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dns_record".
 *
 * @property int $id
 * @property string $record_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property int $ttl
 * @property int $status
 *
 * @property ZoneDns[] $zoneDns
 * @property Zones[] $zones
 */
class DnsRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dns_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['record_id', 'type', 'name', 'value', 'ttl', 'status'], 'required'],
            [['value'], 'string'],
            [['ttl', 'status'], 'integer'],
            [['record_id', 'type', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_id' => 'Record ID',
            'type' => 'Type',
            'name' => 'Name',
            'value' => 'Value',
            'ttl' => 'Ttl',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZoneDns()
    {
        return $this->hasMany(ZoneDns::className(), ['dns_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZones()
    {
        return $this->hasMany(Zones::className(), ['id' => 'zone_id'])->viaTable('zone_dns', ['dns_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DnsRecordQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DnsRecordQuery(get_called_class());
    }

    public static function dropDown()
    {
        $dropdown = [];
        $servers = static::find()->groupBy('value')->all();
        foreach ($servers as $server) {
            $dropdown[$server->value] = $server->value;
        }
        return $dropdown;
    }
}
