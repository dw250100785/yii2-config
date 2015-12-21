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

        // Note the default data value will not store to database.
        $this->data = array_merge($this->loadData(), $this->data);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value = null)
    {
        if (!is_array($key)) {
            $rootKey = $this->getRootKey($key);
            $hasRootKey = $this->has($rootKey);
        } else {
            $rootKey = false;
        }

        parent::set($key, $value);

        if ($rootKey) {
            if ($hasRootKey) {
                $this->updateData($rootKey);
            } else {
                $this->insertData($rootKey);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($key)
    {
        parent::delete($key);

        $rootKey = $this->getRootKey($key);
        if (strpos($key, '.') === false) {
            $this->deleteData($rootKey);
        } else {
            $this->updateData($rootKey);
        }
    }

    /**
     * @inheritdoc
     */
    protected function loadData()
    {
        $query = (new Query)->from($this->configTable);
        $data = [];
        foreach ($query->all($this->db) as $row) {
            $data[$row['name']] = unserialize($row['value']);
        }

        return $data;
    }

    /**
     * Get configuration root key.
     *
     * @param $key
     * @return mixed
     */
    protected function getRootKey($key)
    {
        return explode('.', $key)[0];
    }

    /**
     * Insert new configuration value.
     *
     * @param $rootKey
     */
    protected function insertData($rootKey)
    {
        $this->db
            ->createCommand()
            ->insert($this->configTable, [
                'name' => $rootKey,
                'value' => serialize($this->get($rootKey))
            ])
            ->execute();
    }

    /**
     * Update new configuration value.
     *
     * @param $rootKey
     */
    protected function updateData($rootKey)
    {
        $this->db
            ->createCommand()
            ->update($this->configTable, ['value' => serialize($this->get($rootKey))], ['name' => $rootKey])
            ->execute();
    }

    /**
     * Delete configuration value.
     *
     * @param $rootKey
     */
    protected function deleteData($rootKey)
    {
        $this->db
            ->createCommand()
            ->delete($this->configTable, ['name' => $rootKey])
            ->execute();
    }
}