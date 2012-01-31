<?php

require_once(PATH_tslib . 'interfaces/interface.tslib_content_cobjgetsinglehook.php');

class tx_weccontentelements_cobj implements tslib_content_cObjGetSingleHook {
	protected $cObj;

	/**
	 * Renders a single cObject, returning its output.
	 *
	 * @param	string		$contentObjectName: The name of the cObject.
	 * @param	array		$configuration: The Typoscript configuration.
	 * @param	string		$TypoScriptKey: The key assigned to the cObject.
	 * @param	tslib_ccObj	$parentObject: Back reference to parent cObject.
	 * @return	string
	 */
	public function getSingleContentObject($contentObjectName, array $configuration, $TypoScriptKey, tslib_cObj &$parentObject) {
		$this->cObj =& $parentObject;
		switch($contentObjectName) {
			case 'FFSECTION':
				$content = $this->FFSECTION($configuration);
				break;
			case 'HEADERDATA':
				$content = $this->HEADERDATA($configuration);
				break;
		}

		return $content;
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
			foreach ($sectionArray as $index => $section) {
				$this->cObj->sectionRootPath = $conf['rootPath'] . '/' . $index;
				$content .= $this->cObj->cObjGet($conf);
			}

			if ($conf['wrap']) {
				$content = $this->cObj->wrap($content, $conf['wrap']);
			}
			if ($conf['stdWrap.']) {
				$content = $this->cObj->stdWrap($content, $conf['stdWrap.']);
			}
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_contentelements/class.tx_weccontentelements_cobj.php']){
     include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_contentelements/class.tx_weccontentelements_cobj.php']);
}

?>
