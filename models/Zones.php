<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zones".
 *
 * @property int $id
 * @property int $account_id
 * @property string $domain
 * @property int $ssl
 * @property string $tls
 * @property int $rewrite
 * @property int $debug
 * @property int $attack_mode
 *
 * @property ZoneDns[] $zoneDns
 * @property NameServer[] $dns
 * @property ZoneNameServer[] $zoneNameServers
 * @property NameServer[] $servers
 * @property Account $account
 */
class Zones extends \yii\db\ActiveRecord
{
    public const ON_OFF = [
        0 => 'OFF',
        1 => 'ON'
    ];

    const SCENARIO_CREATE = 'create';

    public const TLS = [
        '1.0' => 'TLS 1.0',
        '1.1' => 'TLS 1.1',
        '1.2' => 'TLS 1.2',
        '1.3' => 'TLS 1.3',
    ];

    public const RECORD_TTL = [
        1     => 'Automatic TTL',
        120   => '2 minutes',
        300   => '5 minutes',
        600   => '10 minutes',
        900   => '15 minutes',
        1800  => '30 minutes',
        3600  => '1 hour',
        7200  => '2 hour',
        18000 => '5 hour',
        43200 => '12 hour',
        86400 => '1 day',
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'domain', 'ssl', 'tls', 'rewrite', 'debug', 'attack_mode'], 'required'],
            [['account_id', 'ssl', 'rewrite', 'debug', 'attack_mode'], 'integer'],
            [['domain', 'tls'], 'string', 'max' => 255],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['account_id', 'domain'];

        return $scenarios;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account',
            'domain' => 'Domain',
            'ssl' => 'Ssl',
            'tls' => 'Tls',
            'rewrite' => 'Rewrite',
            'debug' => 'Debug',
            'attack_mode' => 'Attack',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZoneDns()
    {
        return $this->hasMany(ZoneDns::className(), ['zone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDns()
    {
        return $this->hasMany(DnsRecord::className(), ['id' => 'dns_id'])->viaTable('zone_dns', ['zone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZoneNameServers()
    {
        return $this->hasMany(ZoneNameServer::className(), ['zone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServers()
    {
        return $this->hasMany(NameServer::className(), ['id' => 'server_id'])->viaTable('zone_name_server', ['zone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * {@inheritdoc}
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }
}
