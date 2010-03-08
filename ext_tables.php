<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

require_once(t3lib_extMgm::extPath('wec_contentelements') . 'class.tx_weccontentelements_lib.php');
tx_weccontentelements_lib::addContentElement($_EXTKEY, 'vimeo');
tx_weccontentelements_lib::addContentElement($_EXTKEY, 'youtube');
tx_weccontentelements_lib::addContentElement($_EXTKEY, 'localmenu');
tx_weccontentelements_lib::addContentElement($_EXTKEY, 'slideshow');

?>
