<?php

$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('LangToUrl\AddLanguageToUrlByDomain', 'setOption');

$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('LangToUrl\AddLanguageToUrlByDomain', 'getSearchablePagesLang');
