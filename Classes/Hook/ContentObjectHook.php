<?php
namespace WebEmpoweredChurch\WecContentelements\Hook;

class ContentObjectHook implements \TYPO3\CMS\Frontend\ContentObject\ContentObjectGetSingleHookInterface {

	/**
	 * Local cObject variable.
	 *
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $cObj;

	/**
	 * Renders a single cObject, returning its output.
	 *
	 * @param	string		$contentObjectName: The name of the cObject.
	 * @param	array		$configuration: The Typoscript configuration.
	 * @param	string		$TypoScriptKey: The key assigned to the cObject.
	 * @param	\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer	$parentObject: Back reference to parent cObject.
	 * @return	string
	 */
	public function getSingleContentObject($contentObjectName, array $configuration, $TypoScriptKey, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject) {
		$this->cObj = &$parentObject;
		switch($contentObjectName) {
			case 'FFSECTION':
				$content = $this->FFSECTION($configuration);
				break;
			case 'HEADERDATA':
				$content = $this->HEADERDATA($configuration);
				break;
			case 'INCLUDEJSLIBS':
				$content = $this->INCLUDEJSLIBS($configuration);
				break;
		}

		return $content;
	}

	protected function INCLUDEJSLIBS(array $conf) {
		$pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);

		foreach ($conf as $key => $JSfile) {
			if (!is_array($JSfile)) {
				$ss = $conf[$key . '.']['external'] ? $JSfile : $GLOBALS['TSFE']->tmpl->getFileName($JSfile);
				if ($ss) {
					$type = $conf[$key . '.']['type'];
					if (!$type) {
						$type = 'text/javascript';
					}
					$pageRenderer->addJsLibrary(
						htmlspecialchars($key),
						htmlspecialchars($ss),
						htmlspecialchars($type),
						$conf[$key . '.']['compress'] ? TRUE : FALSE,
						$conf[$key . '.']['forceOnTop'] ? TRUE : FALSE,
						$conf[$key . '.']['allWrap']
					);
				}
			}
		}
	}

	/**
	 * Iterates over a flexform section, returning the combined output of all
	 * elements within the specified section.
	 *
	 * @param	array	$conf: The TypoScript configuration.
	 * @return	string
	 *
	 *
	 * Example:
	 *
	 * 20 = FFSECTION
	 * 20.rootPath = t3datastructure : pi_flexform->images/el
	 * 20 {
	 *   wrap = <ul id="gallery"> | </ul>
	 *   20 = IMAGE
	 *   20.file.import.data = flexformSection : image/el/path
	 *   20.wrap = <li> | </li>
	 * }
	 *
	 *
	 */
	public function FFSECTION(array $conf) {
		$sectionArray = $this->cObj->getData($conf['rootPath'], $this->cObj->data);
		$content = '';
		if ($this->cObj->checkIf($conf['if.'])) {
			$counter = 1;
			foreach ((array) $sectionArray as $index => $section) {
				$GLOBALS['TSFE']->register['FFSECTION_COUNTER'] = $counter++;
				$GLOBALS['TSFE']->register['FFSECTION_ROOTPATH'] = $conf['rootPath'] . '/' . $index;
				$content .= $this->cObj->cObjGet($conf);
			}

			if ($conf['wrap']) {
				$content = $this->cObj->wrap($content, $conf['wrap']);
			}
			if ($conf['stdWrap.']) {
				$content = $this->cObj->stdWrap($content, $conf['stdWrap.']);
			}

			unset($GLOBALS['TSFE']->register['FFSECTION_COUNTER']);
			unset($GLOBALS['TSFE']->register['FFSECTION_ROOTPATH']);
		}

		return $content;
	}

	/**
	 * Rendering the cObject HEADERDATA.
	 * The result will not be outputted but added to the HTML HEAD section.
	 *
	 * @param	array		$conf: The TypoScript configuration.
	 * @return	void
	 */
	public function HEADERDATA(array $conf) {
		$additionalHeaderData = $this->cObj->stdWrap($conf['value'], $conf);

		if ($additionalHeaderData !== '') {
			$identifier = 'cObj.' . md5(trim($additionalHeaderData));
			if (!isset($GLOBALS['TSFE']->additionalHeaderData[$identifier])) {
				$GLOBALS['TSFE']->additionalHeaderData[$identifier] = $additionalHeaderData;
			}
		}
	}
}
