<?php

require_once dirname(__FILE__) . '/../src/classes/AddLanguageToUrlByDomain.php';

//require_once 'PHPUnit/Framework/TestCase.php';

define('TL_MODE', 'FE');
define('TL_PATH', '/subdir');

/**
 * AddLanguageToUrlByDomain test case.
 */
class AddLanguageToUrlByDomainTestPath extends PHPUnit_Framework_TestCase
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
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::setOption
     */
    public function testSetOptionGlobalActivated()
    {
        $GLOBALS['TL_CONFIG']['addLanguageToUrl'] = true;
        $return = $this->AddLanguageToUrlByDomain->setOption();
        $this->assertTrue($return);
    }
    
    /**
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::getSearchablePagesLang
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::buildUrl
     */    
    public function testGetSearchablePagesLangRewriteOn()
    {
        $arrPages[] = 'https://acme.com/subdir/contao.html';
        $arrPages[] = 'https://acme.com/subdir/';
        
        $arrPagesDe[] = 'https://acme.com/subdir/de/contao.html';
        $arrPagesDe[] = 'https://acme.com/subdir/de/';
        
        $GLOBALS['TL_CONFIG']['rewriteURL']          = true;
        $GLOBALS['TL_CONFIG']['addLanguageToUrl']    = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl']         = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'acme.com';
        
        //Mit Sprache. Mit Verzeichnis Ersetzung muss erfolgen
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPagesDe,$arrReturn);
       
    }
    
    /**
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::getSearchablePagesLang
     * @covers BugBuster\LangToUrl\AddLanguageToUrlByDomain::buildUrl
     */
    public function testGetSearchablePagesLangRewriteOff()
    {
        //mit Verzeichnis und Sprache
        //URLs umschreiben = aus + Installation in Unterverzeichnis /pub
        $arrPages[] = 'http://acme.com/subdir/index.php/contao.html';
        $arrPages[] = 'http://user@acme.com/subdir/index.php?a=b#files';
        $arrPages[] = 'http://user@acme.com/subdir/index.php/alias.html?a=b#files';
        
        $arrPagesDe[] = 'http://acme.com/subdir/index.php/de/contao.html';
        $arrPagesDe[] = 'http://user@acme.com/subdir/index.php/de/?a=b#files';
        $arrPagesDe[] = 'http://user@acme.com/subdir/index.php/de/alias.html?a=b#files';
        
        $GLOBALS['TL_CONFIG']['rewriteURL']          = false;
        $GLOBALS['TL_CONFIG']['addLanguageToUrl']    = false;
        $GLOBALS['TL_CONFIG']['useAddToUrl']         = true;
        $GLOBALS['TL_CONFIG']['useAddToUrlByDomain'] = 'acme.com';
        
        //Mit Sprache. Ersetzung muss erfolgen
        $arrReturn  = $this->AddLanguageToUrlByDomain->getSearchablePagesLang($arrPages, 1, true, 'de');
        $this->assertEquals($arrPagesDe,$arrReturn);
        
    }

    
    
    
}

