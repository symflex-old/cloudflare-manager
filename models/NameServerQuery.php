<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[NameServer]].
 *
 * @see NameServer
 */
class NameServerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return NameServer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return NameServer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
