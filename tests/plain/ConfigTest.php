<?php

namespace weyii\tests\plain;

use Yii;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Yii::$app->set('config', [
            'class' => 'weyii\config\Config',
            'data' => ['project' => 'weyii'],
        ]);
    }

    public function testGet()
    {
        $this->assertEquals(Yii::$app->config->get('project'), 'weyii');
    }

    public function testSet()
    {
        Yii::$app->config->set('nullKey');

        $this->assertTrue(Yii::$app->config->has('nullKey'));
        $this->assertNull(Yii::$app->config->get('nullKey'));

        Yii::$app->config->set(['weyii' => 'nice']);

        $this->assertTrue(Yii::$app->config->has('weyii'));
        $this->assertEquals(Yii::$app->config->get('weyii'), 'nice');

        Yii::$app->config->set('multi', [
            'key' => [
                'beep' => 'deep',
            ],
        ]);

        $this->assertTrue(Yii::$app->config->has('multi'));
        $this->assertEquals(Yii::$app->config->get('multi'), ['key' => ['beep' => 'deep']]);
        $this->assertEquals(Yii::$app->config->get('multi.key.beep'), 'deep');

        return Yii::$app->config;
    }

    public function testNestSet()
    {
        Yii::$app->config->set('foo.bar', 'hello');

        $this->assertTrue(Yii::$app->config->has('foo.bar'));
        $this->assertEquals(Yii::$app->config->get('foo.bar'), 'hello');
    }

    public function testHas()
    {
        $this->assertTrue(Yii::$app->config->has('project'));
    }

    /**
     * @depends testSet
     */
    public function testDelete($config)
    {
        $this->assertTrue($config->has('weyii'));
        $config->delete('weyii');

        $this->assertNotTrue($config->has('weyii'));
    }

    /**
     * @depends testSet
     */
    public function testPrepend($config)
    {
        $config->prepend('multi', 'prepend');
        $this->assertEquals($config->get('multi')[0], 'prepend');

        $config->prepend('multi', ['pre' => 'prepend']);
        $T = $config->get('multi');
        $first = array_shift($T);
        $this->assertEquals($first, ['pre' => 'prepend']);
    }

    /**
     * @depends testSet
     */
    public function testPush($config)
    {
        $config->push('multi', 'pushed');
        $tmp = $config->get('multi');
        $value = array_pop($tmp);
        $this->assertEquals($value, 'pushed');

        $config->push('multi', ['pushed' => 'test']);

        $tmp = $config->get('multi');
        $value = array_pop($tmp);
        $this->assertEquals($value, ['pushed' => 'test']);
    }

    public function testAll()
    {
        $this->assertEquals(['project' => 'weyii'], Yii::$app->config->all());
    }

    public function testArrayAccess()
    {
        $this->assertTrue(isset(Yii::$app->config['project']));
        $this->assertEquals(Yii::$app->config['project'], 'weyii');

        Yii::$app->config['test'] = 'array';
        $this->assertTrue(Yii::$app->config->has('test'));
        $this->assertEquals(Yii::$app->config->get('test'), 'array');

        //delete
        unset(Yii::$app->config['project']);
        $this->assertNotTrue(Yii::$app->config->has('project'));
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testBadConfigSet()
    {
        Yii::$app->set('config', [
            'class' => 'weyii\config\Config',
            'data' => 'string',
        ]);

        Yii::$app->get('config');
    }

}
