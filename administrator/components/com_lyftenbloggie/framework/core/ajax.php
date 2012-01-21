<?php
	function BloggieAjax($sEncoding='utf-8')
	{
		$this->sEncoding = $sEncoding;
	}

	function addCommand($aAttributes, $mData)
	{
		$aAttributes['d'] = $mData;
		$this->aCommands[] = $aAttributes;
	}

	function assign($sTarget, $sAttribute, $sData)
	{
		$script = '';
		if (preg_match('/\<script/', $sData)) {
			$regexp = '/<script[^>]+>(.*?)<\/script>/isU';
			$matches = array();
			preg_match_all($regexp, $sData, $matches);
			preg_replace($regexp, '', $sData);
			$script = $matches[1][0];
		}

		$this->addCommand(array('n'=>'as','t'=>$sTarget,'p'=>$sAttribute),$sData);

		if ($script!='') {
			$this->addCommand(array('n'=>'js'),$script);
		}
		return $this;
	}

	function script($sJS)
	{
		$sJS = str_replace("\n", '\n', $sJS);
		$sJS = str_replace("\r", '', $sJS);
		$this->addCommand(array('n'=>'js'),$sJS);
		return $this;
	}

	function alert($sMsg)
	{
		$this->addCommand(array('n'=>'al'),$sMsg);
		return $this;
	}
	
	{
		$output = '';
		if (!empty($this->aCommands))
		{
			$output = BloggieAjax::php2js($this->aCommands);
			if (trim($this->sEncoding)) {
		return $output;
	}

	/**
     * Convert a PHP scalar, array or hash to JS scalar/array/hash. This function is 
	function php2js($a=false)
	{
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		if (is_scalar($a)) {
			if (is_float($a)) {
				$a = str_replace(",", ".", strval($a));
			}
			// All scalars are converted to strings to avoid indeterminism.
			// PHP's "1" and 1 are equal for all PHP operators, but
			// JS's "1" and 1 are not. So if we pass "1" or 1 from the PHP backend,
			// we should get the same result in the JS frontend (string).
			// Character replacements for JSON.
			static $jsonReplaces = array(
			array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
			array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
			);
			return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
		}
		$isList = true;
		for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
			if (key($a) !== $i) {
				$isList = false;
				break;
			}
		}
		$result = array();
		if ($isList) {
			foreach ($a as $v) {
				$result[] = BloggieAjax::php2js($v);
			}
			return '[ ' . join(', ', $result) . ' ]';
		} else {
			foreach ($a as $k => $v) {
				$k = BloggieAjax::php2js($k);
				$v = BloggieAjax::php2js($v);
				$result[] = $k . ': ' . $v;
			}
			return '{ ' . join(', ', $result) . ' }';
		}
	}
}
?>