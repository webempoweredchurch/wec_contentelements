<?php

namespace WebEmpoweredChurch\WecContentelements\Utility;

class ContentElementUtility {

	public function addContentElement($extensionKey, $key, $flexformPath = '', $type = '', $title = '', $description = '', $icon = '', $wizardIcon = '') {
			// Set defaults for title, description, icons, and content element type.
		$locallangPath = 'LLL:EXT:' . $extensionKey . '/' . $key . '/locallang.xml:tt_content.' . $key . '.';
		if (!$title) {
			$title = $locallangPath . 'title';
		}
		if (!$description) {
			$description = $locallangPath . 'description';
		}
		if (!$icon) {
			$icon = 'EXT:' . $extensionKey . '/' . $key . '/icon.gif';
		}
		if (!$wizardIcon) {
			$wizardIcon = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extensionKey) . $key . '/wizard-icon.gif';
		}
		if (!$type) {
			$type = 'special';
		}
		if (!$flexformPath && @file_exists(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . $key . '/flexform.xml')) {
			$flexformPath = 'FILE:EXT:' . $extensionKey . '/' . $key . '/flexform.xml';
		}

		if ($flexformPath) {
			$GLOBALS['TCA']['tt_content']['columns']['pi_flexform']['config']['ds']['*,' . $key] = $flexformPath;
			$GLOBALS['TCA']['tt_content']['types'][$key]['showitem'] = '--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.header;header,
																--div--;' . $title . ',
																pi_flexform;;;;1-1-1,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;frames,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.behaviour,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';
		} else {
			$GLOBALS['TCA']['tt_content']['types'][$key]['showitem'] = '--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.header;header,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
																--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;frames,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.behaviour,
																--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';
		}

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
			$title,
			$key,
			$icon
		), 'CType');


		$TSConfig =
			'wizards.newContentElement.wizardItems.' . $type . ' {
				elements {
					' . $key . ' {
						icon = ' . $wizardIcon . '
						title = ' . $title . '
						description = ' . $description . '
						tt_content_defValues {
							CType = ' . $key .'
						}
					}
				}
				show := addToList(' . $key .')
			}';

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
			'mod.' . $TSConfig . chr(10) .
			'templavoila.' . $TSConfig . chr(10)
		);

		self::addPageTSConfig($extensionKey, $key);
	}

	public function addTyposcript($extensionKey, $key, $typoScriptPath = '') {
		if (!$typoScriptPath) {
			$typoScriptPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . $key . '/content.ts';
		}

		$typoScriptConstantsPath = dirname($typoScriptPath) . '/constants.ts';
		self::addTypoScriptConstants($extensionKey, $key, $typoScriptConstantsPath);

		$type = 'CType';
		$typoScriptContent = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($typoScriptPath);
		if ($typoScriptContent) {
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'setup', '
			# Setting ' . $key . ' TypoScript
			' . $typoScriptContent . '
			', 43);
		}
	}

	protected function addTypoScriptConstants($extensionKey, $key, $typoScriptPath = '') {
		if (!$typoScriptPath) {
			$typoScriptPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . $key . '/constants.ts';
		}

		$typoScriptContent = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($typoScriptPath);
		if ($typoScriptContent) {
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'constants', '
			# Setting ' . $key . ' TypoScript
			' . $typoScriptContent . '
			', 43);
		}
	}

	public function addPageTSConfig($extensionKey, $key, $pageTSConfigPath = '') {
		if (!$pageTSConfigPath) {
			$pageTSConfigPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . $key . '/pagetsconfig.ts';
		}

		$pageTSConfig = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($pageTSConfigPath);
		if ($pageTSConfig) {
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig($pageTSConfig);
		}
	}

	/**
	 * Adds a Fluid-based template file.
	 *
	 * @param string The extension key.
	 * @param string The content element's unique key
	 * @param string Fluid template filename, relative to content element directory.
	 * @param string Path to Fluid partials, relative to content element directory.
	 * @param string Path to Fluid layouts, relative to content element directory.
	 */
	public function addFluid($extensionKey, $key, $file='content.html', $partialRootPath='Partials', $layoutRootPath='Layouts') {
		// Loop over all the flexform variables, converting them into TypoScript cObjects.
		$flexformVariables = self::getFlexformFieldsAsCObjects(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . '/' . $key . '/flexform.xml');

		$typoScriptContent = '
			tt_content.' . $key . ' = COA
			tt_content.' . $key .' {
				10 = < lib.stdheader

				20 = FLUIDTEMPLATE
				20 {
					file = EXT:' . $extensionKey . '/' . $key . '/' . $file . '
					partialRootPath = EXT:' . $extensionKey . '/' . $key . '/' . $partialRootPath . '/
					layoutRootPath = EXT:' . $extensionKey . '/' . $key . '/' . $layoutRootPath . '/
					variables {
						' . $flexformVariables . '
					}
				}
			}
		';

		// If there's a custom TS file, use it instead of the default.
		$customTypoScriptPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . $key . '/content.ts';
		if (file_exists($customTypoScriptPath)) {
			// Set up Fluid variables as temp.fluidVariables and then append the custom TypoScript.
			$typoScriptContent .= chr(10) . \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($customTypoScriptPath);
		}

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'setup', '
		# Setting ' . $key . ' TypoScript
		' . $typoScriptContent . '
		', 43);
	}

	/**
	 * Reads fields from a FlexForm and converts them into TypoScript cObjects.
	 *
	 * @param string Path to the flexform file.
	 * @return string
	 */
	protected function getFlexformFieldsAsCObjects($flexformPath) {
		$flexform = simplexml_load_file($flexformPath);
		$cObjects = array();

		// Loop over all flexform fields and create an array element of TS for each.
		foreach ($flexform->ROOT->el->children() as $el) {
			$name = $el->getName();
			$cObjects[] = '
				' . $name . ' = TEXT
				' . $name . '.data = t3datastructure : pi_flexform->' . $name . '
			';
		}

		$output = implode(chr(10), $cObjects);
		return $output;
	}
}