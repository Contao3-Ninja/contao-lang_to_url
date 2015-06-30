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
    
    /**
     * Hook getSearchablePages (SearchIndex and Sitemap)
     * 
     * @param array $arrPages
     * @param string $intRoot
     * @param string $blnSitemap
     * @param string $strLanguage
     */
    public function getSearchablePagesLang($arrPages, $intRoot=null, $blnSitemap=false, $strLanguage=null)
    {
        //no lang or no hook call for a sitemap?
        if ($strLanguage === null || $blnSitemap === false)
        {
            return $arrPages;
        }
        
        $arrPagesLang = array();
    
        foreach ($arrPages as $strUrl)
        {
            $arrParse = parse_url($strUrl);
            $arrParse['path'] = '/' . $strLanguage . $arrParse['path'];
            $arrPagesLang[] = $this->buildUrl($arrParse);
        }
        
        return $arrPagesLang;
    }
    
    /**
     * Shortened http_build_url
     * 
     * @param array $arrParse       returned array from parse_url
     * @return string               builded url
     */
    public function buildUrl($arrParse)
    {
        //Alternative: jakeasmith/http_build_url

        if (!is_array($arrParse)) 
        {
        	return false;
        }
        
        $newurl = '';
        if (isset($arrParse['scheme'])) 
        {
            $newurl .= $arrParse['scheme'] . '://';
        }
        
        if (isset($arrParse['user'])) 
        {
            $newurl .= $arrParse['user'];
            if (isset($arrParse['pass'])) 
            {
                $newurl .= ':' . $arrParse['pass'];
            }
            $newurl .= '@';
        }
        
        if (isset($arrParse['host'])) 
        {
            $newurl .= $arrParse['host'];
        }
        
        if (isset($arrParse['port'])) 
        {
            $newurl .= ':' . $arrParse['port'];
        }
        
        if (!empty($arrParse['path'])) 
        {
            $newurl .= $arrParse['path'];
        } 
        else 
        {
            $newurl .= '/';
        }
        
        if (isset($arrParse['query'])) 
        {
            $newurl .= '?' . $arrParse['query'];
        }
        
        if (isset($arrParse['fragment'])) 
        {
            $newurl .= '#' . $arrParse['fragment'];
        }
        
        return $newurl;
    }
}
