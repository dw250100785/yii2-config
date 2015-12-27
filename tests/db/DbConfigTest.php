<?php

namespace weyii\tests\db;

use weyii\config\DbConfig;
use Yii;

class DbConfigTest extends TestCase
{
    public function testInit()
    {
        $this->assertTrue(Yii::$app->config instanceof DbConfig);
    }

    public function testHas()
    {
        $this->assertTrue(Yii::$app->config->has('project'));
    }

    public function testGet()
    {
        $this->assertEquals(Yii::$app->config['project'], 'weyii');
    }

    public function testSet()
    {
        Yii::$app->config->set('yii2', 'nice project');
        $this->assertTrue(Yii::$app->config->has('yii2'));

        Yii::$app->config->set('test.foo.bar', 'hello');
        $this->assertTrue(Yii::$app->config->has('test.foo.bar'));
        $this->assertEquals(Yii::$app->config->get('test.foo.bar'), 'hello');
        //update
        Yii::$app->config->set('test.foo.bar', 'world');
        $this->assertTrue(Yii::$app->config->has('test.foo.bar'));
        $this->assertEquals(Yii::$app->config->get('test.foo.bar'), 'world');

        return Yii::$app->config;
    }

    public function testSetArray()
    {
        Yii::$app->config->set([
            'foo' => [
                'bar' => 'hello',
            ],
        ]);

        $this->assertEquals(Yii::$app->config->get('foo.bar'), 'hello');
    }

    /**
     * @depends testSet
     */
    public function testDelete($config)
    {
        $config->delete('yii2');
        $this->assertNotTrue($config->has('yii2'));

        $config->delete('test');

        $this->assertNotTrue($config->has('test.foo.bar'));
    }

    public function testDeleteChildElement()
    {
        Yii::$app->config->set('beep.deep.meep', 'hello');

        Yii::$app->config->delete('beep.deep');
        $this->assertTrue(Yii::$app->config->has('beep'));
        $this->assertNotTrue(Yii::$app->config->has('beep.deep'));
    }

}
