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
        	    	if ( $this->checkDns($Domain) == strtolower( $_SERVER['SERVER_NAME'] ) ) 
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
        return strtolower( str_ireplace(array('http://', 'https://', 'ftp://', '//'), '', trim($varValue) ) );
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
        if (true === (bool) $GLOBALS['TL_CONFIG']['addLanguageToUrl'])
        {
            return $arrPages; //raus, wird ja bereits durch Contao selbst erledigt
        }
        
        
        //no lang ?
        if ($strLanguage === null)
        {
            return $arrPages;
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
                $arrDomains = explode(",", $GLOBALS['TL_CONFIG']['useAddToUrlByDomain']);
                foreach ($arrDomains as $Domain)
                {
                    $arrDomainsClean[] = $this->checkDns($Domain);
                }
                
                $arrPagesLang = array();
            
                foreach ($arrPages as $strUrl)
                {
                    $arrParse = parse_url($strUrl);
                    //Vergleich ob domain = einer der gewünschten ist!
                    if (in_array(strtolower($arrParse['host']), $arrDomainsClean)) 
                    {
                        $arrParse['path'] = '/' . $strLanguage . $arrParse['path'];
                        $arrPagesLang[] = $this->buildUrl($arrParse);
                    }
                    else 
                    {
                        //kompletter Abbruch, Domain passt nicht
                        return $arrPages;
                    }
                }
            }
            else 
            {
                //keine Domain definniert für AddToUrl
                return $arrPages;
            }
        }
        else
        {
            //AddToUrl deaktiviert
            return $arrPages;
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
