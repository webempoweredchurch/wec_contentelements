<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_contentelements']);
if (is_array($extConf) && array_key_exists('includeDefaultContentElements', $extConf)) {
	$includeDefaultContentElements = $extConf['includeDefaultContentElements'];
} else {
	$includeDefaultContentElements = TRUE;
}

if ($includeDefaultContentElements) {
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addContentElement($_EXTKEY, 'vimeo');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addContentElement($_EXTKEY, 'youtube');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addContentElement($_EXTKEY, 'localmenu');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addContentElement($_EXTKEY, 'slideshow');
	\WebEmpoweredChurch\WecContentelements\Utility\ContentElementUtility::addContentElement($_EXTKEY, 'filedownload');
}

?>
