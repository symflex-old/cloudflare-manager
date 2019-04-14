<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ZoneDns]].
 *
 * @see ZoneDns
 */
class ZoneDnsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ZoneDns[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ZoneDns|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
