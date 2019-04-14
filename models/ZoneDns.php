<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zone_dns".
 *
 * @property int $zone_id
 * @property int $dns_id
 *
 * @property DnsRecord $dns
 * @property Zones $zone
 */
class ZoneDns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zone_dns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['zone_id', 'dns_id'], 'required'],
            [['zone_id', 'dns_id'], 'integer'],
            [['zone_id', 'dns_id'], 'unique', 'targetAttribute' => ['zone_id', 'dns_id']],
            [['dns_id'], 'exist', 'skipOnError' => true, 'targetClass' => DnsRecord::className(), 'targetAttribute' => ['dns_id' => 'id']],
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
            'dns_id' => 'Dns ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDns()
    {
        return $this->hasOne(DnsRecord::className(), ['id' => 'dns_id']);
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
     * @return ZoneDnsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ZoneDnsQuery(get_called_class());
    }
}
