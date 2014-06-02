<?php

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
			case 'INCLUDEJSLIBS':
				$content = $this->INCLUDEJSLIBS($configuration);
				break;
			case 'INCLUDEJS':
				$content = $this->INCLUDEJS($configuration);
				break;
			case 'INLINEJS':
				$content = $this->INLINEJS($configuration);
				break;
			case 'INCLUDECSS':
				$content = $this->INCLUDECSS($configuration);
				break;	
			case 'CSSINLINE':
				$content = $this->CSSINLINE($configuration);
				break;	
		}

		return $content;
	}

	protected function INCLUDEJSLIBS(array $conf) {
		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();

		foreach ($conf as $key => $JSfile) {

			// StdWrap the JS file.
			$JSfile = $this->cObj->stdWrap($conf[$key], $conf[$key . '.']);

			// If the key is numeric we should generate a new key to avoid duplication
			if (is_numeric($key)) {
				$key = md5($JSfile);
			}

			if (!is_array($JSfile)) {
				if (isset($conf[$key . '.']['if.']) && !$GLOBALS['TSFE']->cObj->checkIf($conf[($key . '.')]['if.'])) {
					continue;
				}
				$ss = $conf[$key . '.']['external'] ? $JSfile : $GLOBALS['TSFE']->tmpl->getFileName($JSfile);
				if ($ss) {
					$jsFileConfig = $conf[$key . '.'];
					$type = $jsFileConfig['type'];
					if (!$type) {
						$type = 'text/javascript';
					}

					$pageRenderer->addJsLibrary(
						$key,
						$ss,
						$type,
						empty($jsFileConfig['disableCompression']),
						$jsFileConfig['forceOnTop'] ? TRUE : FALSE,
						$jsFileConfig['allWrap'],
						$jsFileConfig['excludeFromConcatenation'] ? TRUE : FALSE,
						$jsFileConfig['allWrap.']['splitChar']
					);
					unset($jsFileConfig);
				}
			}
		}
	}

	protected function INCLUDEJS(array $conf) {
		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();

		foreach ($conf as $key => $JSfile) {

			// StdWrap the JS file.
			$JSfile = $this->cObj->stdWrap($conf[$key], $conf[$key . '.']);

			// If the key is numeric we should generate a new key to avoid duplication
			if (is_numeric($key)) {
				$key = md5($JSfile);
			}

			if (!is_array($JSfile)) {
				if (isset($conf[$key . '.']['if.']) && !$GLOBALS['TSFE']->cObj->checkIf($conf[($key . '.')]['if.'])) {
					continue;
				}
				$ss = $conf[$key . '.']['external'] ? $JSfile : $GLOBALS['TSFE']->tmpl->getFileName($JSfile);
				if ($ss) {
					$jsFileConfig = $conf[$key . '.'];
					$type = $jsFileConfig['type'];
					if (!$type) {
						$type = 'text/javascript';
					}

					$pageRenderer->addJsLibrary(
						$key,
						$ss,
						$type,
						empty($jsFileConfig['disableCompression']),
						$jsFileConfig['forceOnTop'] ? TRUE : FALSE,
						$jsFileConfig['allWrap'],
						$jsFileConfig['excludeFromConcatenation'] ? TRUE : FALSE,
						$jsFileConfig['allWrap.']['splitChar']
					);
					unset($jsFileConfig);
				}
			}
		}
	}

	protected function INLINEJS(array $conf) {
		$content = '';
		if (isset($conf['value'])) {
			$content = $conf['value'];
			unset($conf['value']);
		}
		if (isset($conf['value.'])) {
			$content = $this->cObj->stdWrap($content, $conf['value.']);
			unset($conf['value.']);
		}
		if (count($conf)) {
			$content = $this->cObj->stdWrap($content, $conf);
		}

		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
		$pageRenderer->addJsInlineCode(md5($content), $content, $GLOBALS['TSFE']->config['config']['compressJs']);
	}

	protected function INCLUDECSS(array $conf) {
		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();

		foreach ($conf as $key => $CSSfile) {

			// StdWrap the CSS file.
			$CSSfile = $this->cObj->stdWrap($conf[$key], $conf[$key . '.']);

			// If the key is numeric we should generate a new key to avoid duplication
			if (is_numeric($key)) {
				$key = md5($JSfile);
			}

			if (!is_array($CSSfile)) {
				$cssFileConfig = $conf[$key . '.'];
				if (isset($cssFileConfig['if.']) && !$GLOBALS['TSFE']->cObj->checkIf($cssFileConfig['if.'])) {
					continue;
				}
				$ss = $cssFileConfig['external'] ? $CSSfile : $GLOBALS['TSFE']->tmpl->getFileName($CSSfile);
				if ($ss) {
					if ($cssFileConfig['import']) {
						if (!$cssFileConfig['external'] && $ss[0] !== '/') {
							// To fix MSIE 6 that cannot handle these as relative paths (according to Ben v Ende)
							$ss = GeneralUtility::dirname(GeneralUtility::getIndpEnv('SCRIPT_NAME')) . '/' . $ss;
						}
						$pageRenderer->addCssInlineBlock('import_' . $key, '@import url("' . htmlspecialchars($ss) . '") ' . htmlspecialchars($cssFileConfig['media']) . ';', empty($cssFileConfig['disableCompression']), $cssFileConfig['forceOnTop'] ? TRUE : FALSE, '');
					} else {
						$pageRenderer->addCssFile(
							$ss,
							$cssFileConfig['alternate'] ? 'alternate stylesheet' : 'stylesheet',
							$cssFileConfig['media'] ?: 'all',
							$cssFileConfig['title'] ?: '',
							empty($cssFileConfig['disableCompression']),
							$cssFileConfig['forceOnTop'] ? TRUE : FALSE,
							$cssFileConfig['allWrap'],
							$cssFileConfig['excludeFromConcatenation'] ? TRUE : FALSE,
							$cssFileConfig['allWrap.']['splitChar']
						);
						unset($cssFileConfig);
					}
				}
			}
		}
	}

	protected function CSSINLINE(array $conf) {
		$content = '';
		if (isset($conf['value'])) {
			$content = $conf['value'];
			unset($conf['value']);
		}
		if (isset($conf['value.'])) {
			$content = $this->cObj->stdWrap($content, $conf['value.']);
			unset($conf['value.']);
		}
		if (count($conf)) {
			$content = $this->cObj->stdWrap($content, $conf);
		}

		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
		$pageRenderer->addCssInlineBlock(md5($content), $content);
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
			foreach ($sectionArray as $index => $section) {
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

?>
