<?php

use dokuwiki\plugin\attribute\test\helper_plugin_attribute_test as TestHelper;

/**
 * Tests for the attribute plugin
 *
 * @group plugin_attribute
 * @group plugins
 */
class helper_plugin_attribute_getset_test extends DokuWikiTest
{

    const TESTDIR =  __DIR__ . '/data/attribute';
    const TESTUSER = 'unittestuser';
    const TESTNS = 'unittest';

    protected $pluginsEnabled = ['attribute'];

    protected $helper;

    public function setUp() :void
    {
        parent::setUp();

        $_SERVER['REMOTE_USER'] = self::TESTUSER;

        global $conf;
        $conf['plugin']['attribute']['store'] = self::TESTDIR;

        $this->helper = new TestHelper();
    }

    public function tearDown() :void
    {
        $this->helper->purge(self::TESTNS, self::TESTUSER);
    }

    public function testGetSet()
    {
        global $INFO;
        $INFO['isadmin'] = false;

        $actual = $this->helper->set(self::TESTNS, 'foo', 'bar');

        $this->assertEquals(true, $actual);

        $success = true;
        $actual = $this->helper->get(self::TESTNS, 'foo', $success);
        $this->assertEquals('bar', $actual);
    }

    public function testEnumerateUsers()
    {
        $_SERVER['REMOTE_USER'] = self::TESTUSER;

        global $INFO;
        $INFO['isadmin'] = true;

        $this->helper->set(self::TESTNS, 'foo', 'bar', self::TESTUSER . 2);
        $this->helper->set(self::TESTNS, 'foo', 'bar', self::TESTUSER . '@3');
        $this->helper->set(self::TESTNS, 'foo', 'bar', self::TESTUSER . 1);

        $actual = $this->helper->enumerateUsers(self::TESTNS);
        $this->assertEquals([self::TESTUSER . 1, self::TESTUSER . 2, self::TESTUSER . '@3',], $actual);

    }
}
