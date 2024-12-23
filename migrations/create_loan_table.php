<?php

namespace app\models;

use yii\db\Migration;

class create_loan_table extends Migration
{
    /**
     * @var string|null
     */
    protected $tableName;

    public function init()
    {
        $this->tableName = '{{loan}}';

        parent::init();
    }
    /**
     * @param string|null $tableName
     *
     * @return bool
     */
    public function tableExists(string $tableName = null): bool
    {
        if (null === $tableName) {
            return !empty($this->tableSchema);
        }

        return null !== $this->getDb()->getTableSchema($tableName, true);
    }

    public function up()
    {
        if ($this->tableExists()) {
            return;
        }

        $tableOptions = null;

        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(10)->unsigned(),
                'userId' => $this->integer(10)->notNull()->unsigned(),
                'term' => $this->integer(10)->null()->unsigned(),
                'amount' => $this->integer(10)->null()->unsigned(),
                'status' => $this->string()->null(),
            ],
            $tableOptions
        );
    }

    public function down()
    {
        if (!$this->tableExists()) {
            return;
        }

        $this->dropTable($this->tableName);
    }
}
