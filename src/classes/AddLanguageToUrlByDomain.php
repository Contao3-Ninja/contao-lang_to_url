<?php

namespace BugBuster\LangToUrl;

class AddLanguageToUrlByDomain 
{

    public function setOption()
    {
        if (TL_MODE == 'BE')
        {
        	return true; //raus, sonst wuerde addLanguageToUrl gleich mit gesetzt werden
        }
        if (true === (bool) $GLOBALS['TL_CONFIG']['addLanguageToUrl']) 
        {
        	return true; //raus, soll ja offenbar bei allen Domains sein
        }
        
        //AddToUrl aktiviert?
        if ( isset($GLOBALS['TL_CONFIG']['useAddToUrl']) &&
             true === (bool) $GLOBALS['TL_CONFIG']['useAddToUrl']
           ) 
        {
            //Domain(s) eingetragen?
        	if ( isset($GLOBALS['TL_CONFIG']['useAddToUrlByDomain']) &&
        	     true === (bool) $GLOBALS['TL_CONFIG']['useAddToUrlByDomain']
        	   ) 
        	{
        	    //Domains einzeln pruefen, falls mehrere angegeben
        	    $arrDomains = explode(",", $GLOBALS['TL_CONFIG']['useAddToUrlByDomain']);
        	    foreach ($arrDomains as $Domain) 
        	    {
        	    	if ( $this->checkDns($Domain) == $_SERVER['SERVER_NAME'] ) 
        	    	{
        	    		$GLOBALS['TL_CONFIG']['addLanguageToUrl'] = true;
        	    	}
        	    }
        	}
        }
        return true;
    }//setOption
    
    /**
     * Check the DNS settings, never trust user input :-)
     * @param string
     * @return string
     */
    public function checkDns($varValue)
    {
        return str_ireplace(array('http://', 'https://', 'ftp://'), '', trim($varValue));
    }
    
}
