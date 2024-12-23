<?php

namespace app\models;

use yii\db\ActiveRecord;

class Loan extends ActiveRecord
{
    public const LOAN_STATUS_DECLINED = 'declined';
    public const LOAN_STATUS_APPROVED = 'approved';
    public const LOAN_STATUS_NEW = 'new';
    public const LOAN_STATUS_IN_PROGRESS = 'progress';

    public int $id;
    public ?int $userId;
    public ?int $amount;
    public ?int $term;
    public ?string $status;

    public static function tableName()
    {
        return '{{loan}}';
    }

    public static function primaryKey()
    {
        return ["id"];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->status = $this->oldAttributes['status'];
        $this->amount = $this->oldAttributes['amount'];
        $this->term = $this->oldAttributes['term'];
        $this->userId = $this->oldAttributes['userId'];
    }

    public function beforeSave($insert)
    {
        $this->setAttribute('status', $this->status);
        $this->setAttribute('term', $this->term);
        $this->setAttribute('userId', $this->userId);
        $this->setAttribute('amount', $this->amount);

        return parent::beforeSave($insert);
    }


    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param mixed $term
     */
    public function setTerm($term): void
    {
        $this->term = $term;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

}
