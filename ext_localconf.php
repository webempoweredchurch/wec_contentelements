<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClassDefault'][] = 'EXT:wec_contentelements/class.tx_weccontentelements_cobj.php:tx_weccontentelements_cobj';
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['getData'][] = 'EXT:wec_contentelements/class.tx_weccontentelements_getxmldata.php:&tx_weccontentelements_getXMLData';

require_once(t3lib_extMgm::extPath('wec_contentelements') . 'class.tx_weccontentelements_lib.php');
tx_weccontentelements_lib::addTyposcript($_EXTKEY, 'youtube');
tx_weccontentelements_lib::addTyposcript($_EXTKEY, 'vimeo');
tx_weccontentelements_lib::addTyposcript($_EXTKEY, 'localmenu');
tx_weccontentelements_lib::addTyposcript($_EXTKEY, 'slideshow');

?>
