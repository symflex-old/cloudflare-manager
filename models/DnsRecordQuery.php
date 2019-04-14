<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DnsRecord]].
 *
 * @see DnsRecord
 */
class DnsRecordQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DnsRecord[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DnsRecord|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
