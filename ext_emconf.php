<?php

########################################################################
# Extension Manager/Repository config file for ext "contentelements".
#
# Auto generated 01-03-2010 09:53
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'WEC Content Elements',
	'description' => 'Provides additional content elements such as a local menu, slideshow, Vimeo video, and YouTube video.',
	'category' => 'fe',
	'author' => 'Web-Empowered Church Team',
	'author_email' => 'developer@webempoweredchurch.org',
	'author_company' => 'Christian Technology Ministries International Inc.',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => 'top',
	'module' => '',
	'state' => 'experimental',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.0',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.3.0-4.3.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:18:{s:9:"ChangeLog";s:4:"2e12";s:10:"README.txt";s:4:"ee2d";s:33:"class.tx_weccontentelements_cobj.php";s:4:"eaea";s:39:"class.tx_weccontentelements_getxmldata.php";s:4:"5370";s:32:"class.tx_weccontentelements_lib.php";s:4:"bb7f";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"b7cd";s:14:"ext_tables.php";s:4:"28f7";s:16:"locallang_db.xml";s:4:"6e8a";s:19:"doc/wizard_form.dat";s:4:"3342";s:20:"doc/wizard_form.html";s:4:"efa2";s:20:"localmenu/content.ts";s:4:"e1eb";s:20:"slideshow/content.ts";s:4:"f55e";s:22:"slideshow/flexform.xml";s:4:"e30a";s:16:"vimeo/content.ts";s:4:"81b5";s:18:"vimeo/flexform.xml";s:4:"83bd";s:18:"youtube/content.ts";s:4:"9c99";s:20:"youtube/flexform.xml";s:4:"e6c4";}',
	'suggests' => array(
	),
);

?>