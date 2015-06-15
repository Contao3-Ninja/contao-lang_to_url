<?php 




/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'useAddToUrl';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{AddToUrl_legend:hide},useAddToUrl;';

/**
 * Add to subpalette
 */
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['useAddToUrl'] = 'useAddToUrlByDomain';


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['useAddToUrl'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_settings']['useAddToUrl'],
	'inputType'     => 'checkbox',
	'eval'          => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['useAddToUrlByDomain'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_settings']['useAddToUrlByDomain'],
	'default'       => '',
	'exclude'       => true,
	'inputType'     => 'text',
	'eval'          => array('decodeEntities'=>true, 'mandatory'=>true, 'maxlength'=>255)
);
