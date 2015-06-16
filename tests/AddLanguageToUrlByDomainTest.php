<?php

require_once dirname(__FILE__) . '/../src/classes/AddLanguageToUrlByDomain.php';

//require_once 'PHPUnit/Framework/TestCase.php';

define('TL_MODE', 'FE');

/**
 * AddLanguageToUrlByDomain test case.
 */
class AddLanguageToUrlByDomainTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var AddLanguageToUrlByDomain
     */
    private $AddLanguageToUrlByDomain;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->AddLanguageToUrlByDomain = new BugBuster\LangToUrl\AddLanguageToUrlByDomain(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->AddLanguageToUrlByDomain = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // Auto-generated constructor
    }
    
   
    /**
     * Tests AddLanguageToUrlByDomain->setOption()
     */
    public function testSetOptionGlobalActivated()
    {
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = true;
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertTrue($return);
    }
    
    /**
     * Tests AddLanguageToUrlByDomain->setOption()
     */
    public function testSetOptionAddToUrlDeactivated()
    {
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = false;
        
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = false;
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertFalse($GLOBALS['TL_CONFIG']['addLanguageToUrl']);
        
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = '';
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertFalse($GLOBALS['TL_CONFIG']['addLanguageToUrl']);
    }

    /**
     * Tests AddLanguageToUrlByDomain->setOption()
     */
    public function testSetOptionAddToUrlActivated()
    {
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'localhost';
        $_SERVER['SERVER_NAME'] = 'localhost';
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertTrue($GLOBALS['TL_CONFIG']['addLanguageToUrl']);
        
        
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'acme.com, localhost';
        $_SERVER['SERVER_NAME'] = 'localhost';
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertTrue($GLOBALS['TL_CONFIG']['addLanguageToUrl']);
        
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'acme.com, localhost';
        $_SERVER['SERVER_NAME'] = 'c0n7a0.lan';
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertFalse($GLOBALS['TL_CONFIG']['addLanguageToUrl']);
        
    }
    
    
    /**
     * Tests AddLanguageToUrlByDomain->checkDns()
     */
    public function testCheckDns()
    {
        $return = $this->AddLanguageToUrlByDomain->checkDns('localhost');
        $this->assertEquals('localhost',$return);
        
        $return = $this->AddLanguageToUrlByDomain->checkDns(' localhost ');
        $this->assertEquals('localhost',$return);
        
        $return = $this->AddLanguageToUrlByDomain->checkDns('http://localhost');
        $this->assertEquals('localhost',$return);
        
        $return = $this->AddLanguageToUrlByDomain->checkDns('https://localhost');
        $this->assertEquals('localhost',$return);
        
        $return = $this->AddLanguageToUrlByDomain->checkDns('ftp://localhost');
        $this->assertEquals('localhost',$return);
        
    }
}

