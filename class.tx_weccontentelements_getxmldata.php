<?php

class tx_weccontentelements_getXMLData implements tslib_content_getDataHook {

	/**
	 * Extends the getData()-Method of tslib_cObj to process more/other commands
	 *
	 * @param	string		full content of getData-request e.g. "TSFE:id // field:title // field:uid"
	 * @param	array		current field-array
	 * @param	string		currently examined section value of the getData request e.g. "field:title"
	 * @param	string		current returnValue that was processed so far by getData
	 * @param	\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer	parent content object
	 * @return	string		get data result
	 */
	public function getDataExtension($getDataString, array $fields, $sectionValue, $returnValue, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer &$parentObject) {
		$parts = explode(':', $sectionValue, 2);
		$key = trim($parts[1]);
		if ((string) $key!='') {
			$type = strtolower(trim($parts[0]));
			switch ($type) {
				case 't3datastructure':
					$elements = explode('->', $key, 3);
					if (count($elements) === 3) {
						$fieldName = $elements[0];
						$sheet = $elements[1];
						$path = $elements[2];
					} else {
						$fieldName = $elements[0];
						$sheet = 'sDEF';
						$path = $elements[1];
					}
					$flexFormArray = t3lib_div::xml2array($fields[$fieldName]);
					$returnValue = $this->getFlexformValue($flexFormArray, $path, $sheet);
					break;
				case 'flexformsection':
					$rootPath = $GLOBALS['TSFE']->register['FFSECTION_ROOTPATH'];
					$returnValue = $parentObject->getData($rootPath . '/' . $key, $parentObject->data);
					break;
			}
		}
		return $returnValue;
	}

	/**
	 * Return value from somewhere inside a FlexForm structure
	 *
	 * @param	array		FlexForm data
	 * @param	string		Field name to extract. Can be given like "test/el/2/test/el/field_templateObject" where each part will dig a level deeper in the FlexForm data.
	 * @param	string		Sheet pointer, eg. "sDEF"
	 * @param	string		Language pointer, eg. "lDEF"
	 * @param	string		Value pointer, eg. "vDEF"
	 * @return	string		The content.
	 * @see tslib_pibase->pi_getFFvalue()
	 */
	public function getFlexFormValue($flexFormArray, $fieldName, $sheet='sDEF', $lang='lDEF', $value='vDEF') {
		$sheetArray = is_array($flexFormArray) ? $flexFormArray['data'][$sheet][$lang] : '';
		if (is_array($sheetArray)) {
			return self::getFlexFormValueFromSheetArray($sheetArray, explode('/',$fieldName), $value);
		}
	}

	/**
	 * Returns part of $sheetArray pointed to by the keys in $fieldNameArray
	 *
	 * @param	array		Multidimensiona array, typically FlexForm contents
	 * @param	array		Array where each value points to a key in the FlexForms content - the input array will have the value returned pointed to by these keys. All integer keys will not take their integer counterparts, but rather traverse the current position in the array an return element number X (whether this is right behavior is not settled yet...)
	 * @param	string		Value for outermost key, typ. "vDEF" depending on language.
	 * @return	mixed		The value, typ. string.
	 * @access private
	 * @see pi_getFFvalueFromSheetArray()
	 */
	public function getFlexFormValueFromSheetArray($sheetArray, $fieldNameArr, $value) {
		$tempArr = $sheetArray;
		foreach($fieldNameArr as $k => $v)	{
			if (self::canBeInterpretedAsInteger($v)) {
				if (is_array($tempArr))	{
					foreach($tempArr as $index => $values) {
						if ($index==$v)	{
							$tempArr=$values;
							break;
						}
					}
				}
			} else {
				$tempArr = $tempArr[$v];
			}
		}
		if (isset($tempArr[$value])) {
			return $tempArr[$value];
		} else {
			return $tempArr;
		}
	}

	/**
	 * Checks whether the provided value is an integer
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	protected function canBeInterpretedAsInteger($value) {
		if (class_exists('TYPO3\\CMS\\Core\\Utility\\MathUtility')) {
			$result = \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($var);
		} elseif (class_exists('t3lib_utility_Math')) {
			$result = t3lib_utility_Math::canBeInterpretedAsInteger($var);
		} else {
			$result = t3lib_div::testInt($var);
		}
		return $result;
	}
}

?>
