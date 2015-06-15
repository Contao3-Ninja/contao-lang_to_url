<?php

/**
 * Contao Open Source CMS, Copyright (c) 2005-2015 Leo Feyer
 *
 * @package AddLanguageToUrlByDomain
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'BugBuster',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'BugBuster\LangToUrl\AddLanguageToUrlByDomain' => 'system/modules/lang_to_url/classes/AddLanguageToUrlByDomain.php',
));
