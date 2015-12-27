<?php

namespace weyii\tests\db;

use Yii;
use yii\db\Connection;

abstract class TestCase extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        return $this->createDefaultDBConnection(Yii::$app->db->pdo);
    }

    /**
     * @inheritdoc
     * 定义了每个测试执行之前的数据库初始状态应该是什么样。
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . '/data/test.xml');
    }

    /**
     * setUp() 中会调用一次 getDataSet() 方法来接收基境数据集并将其插入数据库
     */
    protected function setUp()
    {
        if (Yii::$app->get('db', false) === null) {
            $this->markTestSkipped();
        } else {
            Yii::$app->set('config', [
                'class' => 'weyii\config\DbConfig',
            ]);
            parent::setUp();
        }
    }

    public static function setUpBeforeClass()
    {
        try {
            Yii::$app->set('db', [
                'class' => Connection::className(),
                'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=test',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ]);
            Yii::$app->db->open();
            require_once dirname(__DIR__) . '/../migrations/m150106_015855_initDbConfigTable.php';
            $migration = new \m150106_015855_initDbConfigTable();
            $migration->up();
        } catch (\Exception $e) {
            echo $e->getMessage();
            Yii::$app->clear('db');
        }
    }
}
