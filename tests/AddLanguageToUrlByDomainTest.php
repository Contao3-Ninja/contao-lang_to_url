<?php
require_once dirname(__FILE__) . '/../src/classes/AddLanguageToUrlByDomain.php';


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
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public static function setUpBeforeClass()
    {
        define('TL_MODE', 'FE');
        define('TL_PATH', '');
    }
    
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
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::setOption
     */
    public function testSetOptionGlobalActivated()
    {
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = true;
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertTrue($return);
    }
    
    /**
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::setOption
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
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::setOption
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::checkDns
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
        
        //Groß Kleinschreibung testen
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'acme.com, LOCalhost';
        $_SERVER['SERVER_NAME'] = 'localHOst';
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
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::checkDns
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
        
        $return = $this->AddLanguageToUrlByDomain->checkDns('//localhost');
        $this->assertEquals('localhost',$return);
        
    }
    
    /**
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::getSearchablePagesLang
     */
    public function testGetSearchablePagesLangOff()
    {
        $arrPages[] = 'http://user@acme.com/pub/index.php?a=b#files';
        $arrPages[] = 'https://acme.com/contao.html';
        $arrPages[] = 'https://acme.com/';

        //Global aktiviert
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = true;
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPages,$arrReturn);

        //Global deaktiviert, AddToUrl deaktiviert
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = false;
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPages,$arrReturn);
        
        //Global deaktiviert, AddToUrl aktiviert, keine Domain definiert
        $GLOBALS['TL_CONFIG']['useAddToUrl'] = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = false;
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPages,$arrReturn);
        
        //Global deaktiviert, AddToUrl aktiviert, falsche Domain definiert
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'falsche.domain.lan';
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPages,$arrReturn);

    }
    
    
    /**
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::getSearchablePagesLang
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::buildUrl
     */    
    public function testGetSearchablePagesLangRewriteOn()
    {
        $arrPages[] = 'https://acme.com/contao.html';
        $arrPages[] = 'https://ACME.com/';
        $arrPages[] = 'http://user@acme.com/?a=b#files';
        
        
        //Expected
        $arrPagesDe[] = 'https://acme.com/de/contao.html';
        $arrPagesDe[] = 'https://ACME.com/de/';
        $arrPagesDe[] = 'http://user@acme.com/de/?a=b#files';
        
        $GLOBALS['TL_CONFIG']['rewriteURL']          = true;
        $GLOBALS['TL_CONFIG']['addLanguageToUrl']    = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl']         = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'acme.com';
        
        //Ohne Sprache, Orginal muss zurück kommmen
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true);
        $this->assertEquals($arrPages,$arrReturn);

        //Mit Sprache. Ersetzung muss erfolgen
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPagesDe,$arrReturn);
    }
    
    /**
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::getSearchablePagesLang
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::buildUrl
     */
    public function testGetSearchablePagesLangRewriteOff()
    {
        //URLs umschreiben = aus
        $arrPages[] = 'http://acme.com/index.php/contao.html';
        $arrPages[] = 'http://user@acme.com/index.php?a=b#files';
        $arrPages[] = 'http://user@acme.com/index.php/alias.html?a=b#files';

    
        //Expected
        $arrPagesDe[] = 'http://acme.com/index.php/de/contao.html';
        $arrPagesDe[] = 'http://user@acme.com/index.php/de/?a=b#files';
        $arrPagesDe[] = 'http://user@acme.com/index.php/de/alias.html?a=b#files';

    
    
        $GLOBALS['TL_CONFIG']['rewriteURL']          = false;
        $GLOBALS['TL_CONFIG']['addLanguageToUrl']    = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl']         = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'acme.com';
    
        //Ohne Sprache, Orginal muss zurück kommmen
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true);
        $this->assertEquals($arrPages,$arrReturn);
    
        //Mit Sprache. Ersetzung muss erfolgen
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPagesDe,$arrReturn);
    }

    /**
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::buildUrl
     */
    public function testBuildUrl()
    {
        $testUrl = 'http://user:pass@www.acme.com:8080/pub/index.php?a=b#files';
        //fwrite(STDOUT,"\n". __METHOD__ . " parse_url: ".print_r(parse_url($testUrl),true)."\n");
        $return  = $this->AddLanguageToUrlByDomain->buildUrl(parse_url($testUrl));
        $this->assertEquals($testUrl,$return);
        
        $testUrl = 'http://user:pass@www.acme.com:8080/index.php/alias.html?a=b#files';
        //fwrite(STDOUT,"\n". __METHOD__ . " parse_url: ".print_r(parse_url($testUrl),true)."\n");
        $return  = $this->AddLanguageToUrlByDomain->buildUrl(parse_url($testUrl));
        $this->assertEquals($testUrl,$return);
        
        $testUrl = 'http://acme.com';
        $return  = $this->AddLanguageToUrlByDomain->buildUrl(parse_url($testUrl));
        $this->assertEquals('http://acme.com/',$return);
        
        $return  = $this->AddLanguageToUrlByDomain->buildUrl('string');
        $this->assertFalse($return);
    }


}
