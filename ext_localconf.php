<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClassDefault'][] = \WebEmpoweredChurch\WecContentelements\Hook\ContentObjectHook::class;
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['getData'][] = \WebEmpoweredChurch\WecContentelements\Hook\GetDataHook::class;

$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_contentelements']);
if (is_array($extConf) && array_key_exists('includeDefaultContentElements', $extConf)) {
	$includeDefaultContentElements = $extConf['includeDefaultContentElements'];
} else {
	$includeDefaultContentElements = TRUE;
}

if ($includeDefaultContentElements) {
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addTyposcript($_EXTKEY, 'youtube');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addTyposcript($_EXTKEY, 'vimeo');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addTyposcript($_EXTKEY, 'localmenu');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addTyposcript($_EXTKEY, 'slideshow');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addTyposcript($_EXTKEY, 'filedownload');
}

?>
