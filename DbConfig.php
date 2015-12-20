<?php
namespace weyii\config;

use yii\db\Query;
use yii\di\Instance;
use yii\db\Connection;

/**
 * Class DbConfig
 * @package callmez\config
 */
class DbConfig extends Config
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     */
    public $db = 'db';
    /**
     * The configuration data tablename
     * @var string
     */
    public $configTable = '{{%config}}';

    public function init()
    {
        $this->db = Instance::ensure($this->db, Connection::className());
        parent::init();
    }

    /**
     * load data
     */
    protected function loadData()
    {
        $query = (new Query)->from($this->configTable);
        foreach ($query->all($this->db) as $row) {
            $this->data[$row['name']] = unserialize($row['value']);
        }
    }

    /**
     * @inheritdoc
     */
    protected function saveData($key)
    {
        $value = $this->get($key);
        $command = $this->db->createCommand();
        $condition = ['name' => $key];

        if ($value === null) {
            $command->update($this->configTable, ['value' => serialize($value)], $condition);
        } else {
            $command->delete($this->configTable, $condition);
        }
        $command->execute();
    }

    /**
     * set single data
     *
     * @param $key
     * @param $value
     * @return mixed
     */
     protected function setData($key, $value)
     {

     }
}