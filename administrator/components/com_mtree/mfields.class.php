<?php
/**
 * @version		$Id: mfields.class.php 890 2010-06-02 09:25:49Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


defined('_JEXEC') or die('Restricted access');

class mFields {
	
	var $fields = null;
	var $mField = null;
	var $pointer = null;
	var $coresValue = null;
	
	function mFields( $fieldsObjectList=null ) {
		$this->pointer = 0;
		if( !is_null($fieldsObjectList) ) {
			$this->loadFields($fieldsObjectList);
		}
	}

	function loadField( $fieldsObject ) {
		if( $fieldsObject->iscore && !is_null($this->coresValue) ) {
			$name = substr($fieldsObject->field_type,4);
			if( array_key_exists($name,$this->coresValue) ) {
				$fieldsObject->value = $this->coresValue[$name];
			} else {
				$fieldsObject->value = $this->coresValue['link_'.$name];
			}
		}
		$this->fields[] = array(
			'id' => $fieldsObject->cf_id,
			'linkId' => (isset($fieldsObject->link_id)?$fieldsObject->link_id:0),
			'fieldType' => $fieldsObject->field_type,
			'caption' => $fieldsObject->caption,
			'value' => isset($fieldsObject->value)?$fieldsObject->value:'',
			'defaultValue' => $fieldsObject->default_value,
			'prefixTextMod' => $fieldsObject->prefix_text_mod,
			'suffixTextMod' => $fieldsObject->suffix_text_mod,
			'prefixTextDisplay' => $fieldsObject->prefix_text_display,
			'suffixTextDisplay' => $fieldsObject->suffix_text_display,
			'catId' => $fieldsObject->cat_id,
			'ordering' => $fieldsObject->ordering,
			'hidden' => $fieldsObject->hidden,
			'size' => $fieldsObject->size,
			'fieldElements' => $fieldsObject->field_elements,
			'arrayFieldElements' => explode("|",$fieldsObject->field_elements),
			'requiredField' => $fieldsObject->required_field,
			'hideCaption' => $fieldsObject->hide_caption,
			'tagSearch' => $fieldsObject->tag_search,
			'simpleSearch' => $fieldsObject->simple_search,
			'advancedSearch' => $fieldsObject->advanced_search,
			'searchCaption' => $fieldsObject->search_caption,
			'detailsView' => $fieldsObject->details_view,
			'summaryView' => $fieldsObject->summary_view,
			'isCore' => $fieldsObject->iscore,
			'params' => $fieldsObject->params,
			'class' => $fieldsObject->ft_class,
			'attachment' => isset($fieldsObject->attachment)?$fieldsObject->attachment:'',
			'counter' => isset($fieldsObject->counter)?$fieldsObject->counter:0
			);
	}

	function loadFields( $fieldsObjectList ) {
		if( is_null($fieldsObjectList) ) {
			// Do nothing
		} else {
			foreach( $fieldsObjectList AS $fieldsObject ) {
				$this->loadField($fieldsObject);
			}
		}
	}
	
	function setCoresValue( $link_name, $link_desc, $address, $city, $state, $country, $postcode, $telephone, $fax, $email, $website, $price, $link_hits, $link_votes, $link_rating, $link_featured, $link_created, $link_modified, $link_visited, $publish_up, $publish_down, $metakey, $metadesc, $user_id, $username ) {
		$this->coresValue['link_name'] = $link_name;
		$this->coresValue['link_desc'] = $link_desc;
		$this->coresValue['address'] = $address;
		$this->coresValue['city'] = $city;
		$this->coresValue['state'] = $state;
		$this->coresValue['country'] = $country;
		$this->coresValue['postcode'] = $postcode;
		$this->coresValue['telephone'] = $telephone;
		$this->coresValue['fax'] = $fax;
		$this->coresValue['email'] = $email;
		$this->coresValue['website'] = $website;
		$this->coresValue['price'] = $price;
		$this->coresValue['link_hits'] = $link_hits;
		$this->coresValue['link_votes'] = $link_votes;
		$this->coresValue['link_rating'] = $link_rating;
		$this->coresValue['link_featured'] = $link_featured;
		$this->coresValue['link_created'] = $link_created;
		$this->coresValue['link_modified'] = $link_modified;
		$this->coresValue['link_visited'] = $link_visited;
		$this->coresValue['link_publishup'] = $publish_up;
		$this->coresValue['link_publishdown'] = $publish_down;
		$this->coresValue['metakey'] = $metakey;
		$this->coresValue['metadesc'] = $metadesc;
		$this->coresValue['link_user'] = $user_id . '|' . $username;
	}
	
	function loadSearchParams( $post=null ) {
		if( is_null($post) ) {
			$post = JRequest::get( 'post' );
		}
		while( $this->hasNext() ) {
			$field = $this->getField();
			$searchFields = $field->getSearchFields();
			foreach( $searchFields AS $searchField ) {
				if( isset($post[$searchField]) ) {
					$searchParams[$searchField] = $post[$searchField];
				}
			}
			$this->next();
		}
		return $searchParams;
	}
	
	function hasNext() {
		if( count($this->fields) > 0 && array_key_exists($this->pointer,$this->fields) ) {
			return true;
		} else {
			return false;
		}
	}
	
	function resetPointer() { $this->pointer = 0; }
	
	function getCurrentPointer() { return $this->pointer; }
	
	function getTotal() { return count($this->fields); }
	
	function getField() {
		$class = $this->getFieldTypeClassName( $this->fields[$this->pointer] );
		$fieldTypeInstance = new $class( $this->fields[$this->pointer] );
		return $fieldTypeInstance;
	}
	
	function getFieldById( $id ) {
		if( !is_null($this->fields) ) {
			foreach( $this->fields AS $key => $data ) {
				if($data['id'] == $id) {
					$class = $this->getFieldTypeClassName( $data );
					$fieldTypeInstance = new $class( $data );
					return $fieldTypeInstance;
				}
			}
			return null;
		}
		$class = $this->getFieldTypeClassName();
		$fieldTypeInstance = new $class();
		return $fieldTypeInstance;
	}

	function getFieldByCaption( $caption ) {
		if( !is_null($this->fields) ) {
			foreach( $this->fields AS $key => $data ) {
				if($data['caption'] == $caption) {
					$class = $this->getFieldTypeClassName( $data );
					$fieldTypeInstance = new $class( $data );
					return $fieldTypeInstance;
				}
			}
		}
		$class = $this->getFieldTypeClassName();
		$fieldTypeInstance = new $class();
		return $fieldTypeInstance;
	}
	
	function next() { $this->pointer++; }
	
	function getFieldTypeClassName( $data=array() ) {
		if( array_key_exists('fieldType',$data) && !is_null($data['fieldType']) && !empty($data['fieldType']) ) {
			if(class_exists('mFieldType_' . $data['fieldType'])) {
				$class = 'mFieldType_' . $data['fieldType'];
			} else {
				if( isset($data['class']) ) { 
					eval($data['class']);
					$class = 'mFieldType_' . $data['fieldType'];
				} else {
					$class = 'mFieldType';
				}
			}
		} else {
			$class = 'mFieldType';
		}
		return $class;
	}
}

/**
* 
* Abstract mFieldType class.
*
*/
class mFieldType {

	var $id = null;
	var $name = null;
	var $value = null;
	var $size = null;
	var $arrayFieldElements = null;
	var $searchFields = null;
	var $params = null;
	var $isCore = null;
	var $numOfInputFields = 1;
	var $numOfSearchFields = 1;
	var $allowHTML = false;
	var $counter = 0;
	var $isFile = false;
	
	function mFieldType( $data=array() ) {
		if( !is_null($data) ) {
			foreach( $data AS $key => $value ) {
				switch($key){
					case 'fieldElements':
						$this->arrayFieldElements = explode("|",$data[$key]);
						break;
					case 'params':
						$this->params = new JParameter( $value );
						break;
					default:
						$this->$key = $value;
						break;
				}
			}
		}
	}
	
	function isCore() {
		if( $this->isCore == 0 ) {
			return false;
		} else {
			return true;
		}
	}
	
	function isRequired() {
		if($this->requiredField) {
			return true;
		} else {
			return false;
		}
	}
	
	function isFile() {
		if($this->isFile) {
			return true;
		} else {
			return false;
		}	
	}
	
	function inBackEnd() {
		return (substr(dirname($_SERVER['PHP_SELF']),-13) == 'administrator') ? true : false;
	}	
	
	function hasValue() { return (!empty($this->value)) ? true: false; }
	
	function hasInputField() { return ( $this->numOfInputFields <= 0 ) ? false:true; }

	function hasSearchField() { return ( $this->numOfSearchFields <= 0 ) ? false:true; }
	
	function hasCaption() { return (!empty($this->caption) && $this->hideCaption == 0) ? true: false; }
	
	function hasJSValidation() {
		if($this->getJSValidation() != '' && !is_null($this->getJSValidation())) {
			return true;
		} else {
			return false;
		}
	}
	
	function parseValue( $value ) { 
		if ( is_array($value) ) {
			return ($this->allowHTML) ? implode("|",$value) : strip_tags(implode("|",$value));
		} else {
			$value = trim($value);
			return ($this->allowHTML) ? $value : strip_tags($value);
		}
	}
	
	function getId() { return $this->id; }
	
	function getLinkId() { return $this->linkId; }

	function getFieldType() { return $this->fieldType; }
	
	function getValue($arg=null) { 
		if(is_null($arg)) {
			return $this->value;
		} elseif(is_numeric($arg)) {
			$values = explode('|',$this->value);
			if(array_key_exists(($arg-1),$values)) {
				return trim($values[($arg-1)]);
			} else {
				return '';
			}
		} else {
			return '';
		}
	}

	function getSize() { return $this->size; }

	function getFieldElements() { return $this->fieldElements; }
	
	function getArrayFieldElements() { return $this->arrayFieldElements; }
	
	function getInputHTML() {
		if( !empty($this->arrayFieldElements[0]) )
		{
			$html = '<select name="' . $this->getInputFieldName(1) . '" id="' . $this->getInputFieldName(1) . '" class="inputbox text_area">';
			$html .= '<option value="">&nbsp;</option>';
			foreach($this->arrayFieldElements AS $fieldElement) {
				$html .= '<option value="'.$fieldElement.'"';
				if( $fieldElement == $this->getValue() ) {
					$html .= ' selected';
				}
				$html .= '>' . $fieldElement . '</option>';
			}
			$html .= '</select>';
			return $html;
		} else {
			return '<input class="inputbox text_area" type="text" name="' . $this->getInputFieldName(1) . '" id="' . $this->getInputFieldName(1) . '" size="' . ($this->getSize()?$this->getSize():'30') . '" value="' . htmlspecialchars($this->getValue()) . '" />';
		}
	}

	function getSearchHTML() {
		if( !empty($this->arrayFieldElements[0]) )
		{
			$html = '<select name="' . $this->getName() . '" class="inputbox text_area">';
			$html .= '<option value="">&nbsp;</option>';
			foreach($this->arrayFieldElements AS $fieldElement) {
				$html .= '<option value="'.$fieldElement.'"';
				$html .= '>' . $fieldElement . '</option>';
			}
			$html .= '</select>';
			return $html;
		} else {
			return '<input class="inputbox text_area" type="text" name="' . $this->getName() . '" id="' . $this->getName() . '" size="' . $this->getSize() . '" />';
		}
	}
	
	function getJSValidation() {
		return null;
	}

	function getName() { 
		if( empty($this->name) ) {
			return 'cf' . $this->id;
		} else {
			return $this->name; 
		}
	}
	
	function getCaption($forceShow=false) {
		if( empty($this->caption) || ($this->hideCaption && !$forceShow) ) {
			return false;
		} else {
			return $this->caption;
		}
	}
	
	function getFieldTypeAttachmentURL($arg) {
		global $mtconf;
		if(is_int($arg)) {
			return JRoute::_( JURI::root().str_replace('&','&amp;','index.php?option=com_mtree&task=att_download&ft=' . $this->fieldType . '&o=' . $arg) );
		} elseif( !empty($arg) ) {
			return JRoute::_( JURI::root().str_replace('&','&amp;','index.php?option=com_mtree&task=att_download&ft=' . $this->fieldType . '&file=' . $arg) );
		}
	}

	function getDataAttachmentURL() {
		global $mtconf;
		return JRoute::_( JURI::root().str_replace('&','&amp;','index.php?option=com_mtree&task=att_download&link_id=' . $this->getLinkId() . '&cf_id=' . $this->getId()) );
	}
	
	function getModPrefixText() {
		if( empty($this->prefixTextMod) ) {
			return false;
		} else {
			return $this->prefixTextMod;
		}
	}
	
	function getModSuffixText() {
		if( empty($this->suffixTextMod) ) {
			return false;
		} else {
			return $this->suffixTextMod;
		}
	}
	
	function getDisplayPrefixText() {
		if( empty($this->prefixTextDisplay) ) {
			return false;
		} else {
			return $this->prefixTextDisplay;
		}
	}
	
	function getDisplaySuffixText() {
		if( empty($this->suffixTextDisplay) ) {
			return false;
		} else {
			return $this->suffixTextDisplay;
		}
	}
	
	function getParam( $key, $default='' ) {
		return $this->params->get( $key, $default );
	}

	/**
	* Get the search fields' names
	*
	* @access public
	* @return array
	*/
	function getSearchFields() {
		$arrFields = array();
		for($i=1;$i<=$this->numOfSearchFields;$i++) {
			$arrFields[] = $this->getSearchFieldName($i);
		}
		return $arrFields;
	}
	
	function getSearchFieldName($count=1) {
		if($count == 1) {
			return $this->getName();
		} elseif( $count <= $this->numOfSearchFields ) {
			return $this->getName() . '_' . $count;
		}		
	}	
	
	function getInputFieldName($count=1) {
		if($count == 1) {
			return $this->getName();
		} elseif( $count <= $this->numOfInputFields ) {
			return $this->getName() . '_' . $count;
		}		
	}
	
	function getKeepFileName() {
		return 'keep_' . $this->getInputFieldName(1);
	}
	
	function getKeepFileCheckboxHTML($hasAttachment=1) {
		return '<input type="checkbox" name="' . $this->getKeepFileName() . '" value="' . $hasAttachment . '" id="' . $this->getKeepFileName() . '" checked />';
	}
	
	/**
	* Return the formatted output
	* @param int Type of output to return. Especially useful when you need to display expanded 
	*			 information in detailed view and use can use this display a summarized version
	*			 for summary view. $view = 1 for Normal/Details View. $view = 2 for Summary View.
	* @return str The formatted value of the field
	*/
	function getOutput() {
		if( $this->tagSearch && $this->hasValue() )
		{
			$arrTags = explode(',',$this->getValue());
			$countTags = count($arrTags);
			for($i=0;$i<$countTags;$i++)
			{
				$arrTags[$i] = trim($arrTags[$i]);

				$outputTags[$i] = '';
				$outputTags[$i] .= '<a class="tag" href="'.JRoute::_('index.php?option=com_mtree&task=searchby&cf_id='.$this->getId().'&value='.urlencode($arrTags[$i])).'">';
				$outputTags[$i] .= $arrTags[$i];
				$outputTags[$i] .= '</a>';
			}
			$html = '';
			$html .= implode(',&nbsp;',$outputTags);
			return $html;
		} else {
			return $this->getValue();
		}
	}
	
	function getWhereCondition() {
		if( func_num_args() == 0 ) {
			return null;
		} else {
			if( $this->isCore() ) {
				return $this->getName() . ' LIKE \'%' . func_get_arg(0) . '%\'';
			} else {
				return '(cfv#.value LIKE \'%' . func_get_arg(0) . '%\')';
			}
		}
	}
	
	/*
	 * Utility Functions
	 */
	
	function stripTags($value, $allowedTags='u,b,i,a,ul,li,pre,br,blockquote') {
		if(!empty($allowedTags)) {
			$tmp = explode(',',$allowedTags);
			array_walk($tmp,'trim');
			$allowedTags = '<' . implode('><',$tmp) . '>';
		} else {
			$allowedTags = '';
		}
		return strip_tags( $value, $allowedTags );
	}
	
	function parseMambots( &$html ) {
		global $_MAMBOTS, $mtconf;

		$params = new JParameter( '' );
		$link = new stdclass;
		$link->text = $html;
		$link->id = 1;
		$link->title = '';
		$page = 0;

		if(defined('JVERSION')) {
			JPluginHelper::importPlugin('content');
			$dispatcher =& JDispatcher::getInstance();
			$results = $dispatcher->trigger('onPrepareContent', array (&$link, &$params, 0));
		} else {
			$_MAMBOTS->loadBotGroup( 'content' );
			$results = $_MAMBOTS->trigger( 'onPrepareContent', array( &$link, &$params, $page ), true );
		}
		$html = $link->text;			

		return true;
	}
	
	function linkcreator( $matches ) {	
		$url = 'http://';
		$append = '';

		if ( in_array(substr($matches[1],-1), array('.',')')) ) {
			$url .= substr($matches[1], 0, -1);
			$append = substr($matches[1],-1);

		# Prevent cutting off breaks <br />
		} elseif( substr($matches[1],-3) == '<br' ) {
			$url .= substr($matches[1], 0, -3);
			$append = substr($matches[1],-3);

		} elseif( substr($matches[1],-1) == '>' ) {
			$regex = '/<(.*?)>/i';
			preg_match( $regex, $matches[1], $tags );
			if( !empty($tags[1]) ) {
				$append = '<'.$tags[1].'>';
				$url .= $matches[1];
				$url = str_replace( $append, '', $url );
			}
		} else {
			$url .= $matches[1];
		}

		return '<a href="'.$url.'" target="_blank">'.$url.'</a>'.$append.' ';
	}

	function strlen_utf8($str) {
		return strlen(utf8_decode($this->utf8_html_entity_decode($str)));
	}

	function utf8_replaceEntity($result){
		$value = intval($result[1]);
		$string = '';
		$len = round(pow($value,1/8));
		for($i=$len;$i>0;$i--){
		    $part = ($value AND (255>>2)) | pow(2,7);
		    if ( $i == 1 ) $part |= 255<<(8-$len);
		    $string = chr($part) . $string;
		    $value >>= 6;
		}
		return $string;
	}

	function utf8_html_entity_decode($string) {
		return preg_replace_callback('/&#([0-9]+);/u',array($this,'utf8_replaceEntity'),$string);
	}

	function html_cutstr($str, $len) {
		if (!preg_match('/\&#[0-9]*;.*/i', $str)) {
			return substr($str,0,$len);
		}
		$chars = 0;
		$start = 0;
		for($i=0; $i < strlen($str); $i++) {
			if ($chars >= $len) {
				break;
			}
		    $str_tmp = substr($str, $start, $i-$start);
		    if (preg_match('/\&#[0-9]*;.*/i', $str_tmp)) {
				$chars++;
		        $start = $i;
		    }
		}
		$rVal = substr($str, 0, $start);
		if (strlen($str) > $start)
		return $rVal;
	}
	function html_substr($str, $start, $length = NULL) {
		if ($length === 0) return '';

		//check if we can simply use the built-in functions
		if (strpos($str, '&') === false) {
			if ($length === NULL) return substr($str, $start);
			else return substr($str, $start, $length);
		}

		// create our array of characters and html entities
		$chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
		$html_length = count($chars);

		// check if we can predict the return value and save some processing time
		if (
		     ($html_length === 0) /* input string was empty */ or
		     ($start >= $html_length) /* $start is longer than the input string */ or
		     (isset($length) and ($length <= -$html_length)) /* all characters would be omitted */
		) {
		  return '';
		}

		//calculate start position
		if ($start >= 0) {
			$real_start = $chars[$start][1];
		} else { //start'th character from the end of string
			$start = max($start,-$html_length);
			$real_start = $chars[$html_length+$start][1];
		}

		if (!isset($length)) {
			// no $length argument passed, return all remaining characters
			return substr($str, $real_start);
		} else if ($length > 0) {
			// copy $length chars
			if ($start+$length >= $html_length) {
				// return all remaining characters
				return substr($str, $real_start);
			} else {
				//return $length characters
				return substr($str, $real_start, $chars[max($start,0)+$length][1] - $real_start);
			}
		} else { //negative $length. Omit $length characters from end
			return substr($str, $real_start, $chars[$html_length+$length][1] - $real_start);
		}

	}
	
	function html_strlen($str) {
		$chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		return count($chars);
	}
}

class mFieldType_text extends mFieldType {
}

class mFieldType_multitext extends mFieldType {
	function getInputHTML() {
		$html = '<textarea name="' . $this->getInputFieldName(1) . '" id="' . $this->getInputFieldName(1) . '" class="inputbox text_area" cols="60" rows="'.$this->getSize().'">' . $this->getValue() . '</textarea>';
		return $html;
	}
	function getSearchHTML() {
		return '<input class="inputbox text_area" type="text" name="' . $this->getName() . '" size="30" />';
	}
}

class mFieldType_weblink extends mFieldType {

	function getInputHTML() {
		$showGo = $this->getParam('showGo',0);
		$html = '';
		$html .= '<input class="inputbox text_area" type="text" name="' . $this->getInputFieldName(1) . '" id="' . $this->getInputFieldName(1) . '" size="' . ($this->getSize()?$this->getSize():'30') . '" value="' . htmlspecialchars($this->getValue()) . '" />';
		if($showGo && $this->inBackEnd()) {
			$html .= '&nbsp;';
			$html .= '<input type="button" class="button" onclick=\'';
			$html .= 'javascript:window.open("index3.php?option=com_mtree&task=openurl&url="+escape(document.getElementById("' . $this->getInputFieldName(1) . '").value))\'';
			$html .= 'value="' . JText::_( 'Go' ) . '" />';
		}
		return $html;
	}
	
	function parseValue($value) {
		$value = trim(strip_tags($value));
		if(substr($value,0,7) == 'http://' || substr($value,0,8) == 'https://') {
			return $value;
		} elseif(!empty($value)) {
			return 'http://' . $value;
		} else {
			return '';
		}
	}
	function getJSValidation() {
		$js = '';
		$js .= '} else if (form.'.$this->getName().' && form.' .$this->getName() . '.value != "" && /^(http:\/\/|https:\/\/)?([a-zA-Z0-9]+\.[a-zA-Z0-9\-]+|[a-zA-Z0-9\-]+)\.[a-zA-Z\.]{2,6}(\/[a-zA-Z0-9\.\?=\/#%&\+-]+|\/|)/i.test(form.' .$this->getName() . '.value)==false) {'; 
		$js .= 'alert("' . $this->getCaption() . ': ' . JText::_( 'Please enter a valid url' ) . '");';
		return $js;
	}

	function getOutput() {
		$maxUrlLength = $this->getParam('maxUrlLength',60);
		$text = $this->getParam('text','');
		$openNewWindow = $this->getParam('openNewWindow',1);
		$hideProtocolOutput = $this->getParam('hideProtocolOutput',1);
		
		$html = '';
		$html .= '<a href="';
		$html .= $this->getOutputURL();
		$html .= '"';
		if( $openNewWindow == 1 ) {
			$html .= ' target="_blank"';
		}
		$html .= '>';
		if(!empty($text)) {
			$html .= $text;
		} else {
			$value = $this->getValue();
			if(strpos($value,'://') !== false && $hideProtocolOutput) {
				$value = trim(substr($value,(strpos($value,'://')+3)));

				// If $value has a single slash and this is at the end of the string, we can safely remove this.
				if( substr($value,-1) == '/' && substr_count($value,'/') == 1 )
				{
					$value = substr($value,0,-1);
				}
			}
			if( empty($maxUrlLength) || $maxUrlLength == 0 ) {
				$html .= $value;
			} else {
				$html .= substr($value,0,$maxUrlLength);
				if( strlen($value) > $maxUrlLength ) {
					$html .= $this->getParam('clippedSymbol');
				}
			}
		}
		$html .= '</a>';
		return $html;
	}
	
	function getOutputURL() {
		$useInternalRedirect = $this->getParam('useInternalRedirect',0);

		$url = '';
		
		if( $useInternalRedirect ) {
			$url .= JRoute::_( 
				JURI::root()
				. str_replace('&','&amp;','index.php?option=com_mtree&task=visit&link_id=' . $this->getLinkId() . '&cf_id=' . $this->getId()) 
				);
		} else {
			// parseValue always make sure the protocol bits is always prepended before storing to database.
			// We are going to do another check here, just in case the value is stored without going through
			// the check.
			$url .= $this->parseValue($this->getValue());
		}
		return $url;
	}
}

class mFieldType_selectlist extends mFieldType {

	function getInputHTML() {
		$html = '<select name="' . $this->getInputFieldName(1) . '" id="' . $this->getInputFieldName(1) . '" class="inputbox text_area">';
		$html .= '<option value="">&nbsp;</option>';
		foreach($this->arrayFieldElements AS $fieldElement) {
			$html .= '<option value="'.$fieldElement.'"';
			if( $fieldElement == $this->getValue() ) {
				$html .= ' selected';
			}
			$html .= '>' . $fieldElement . '</option>';
		}
		$html .= '</select>';
		return $html;
	}

	function getSearchHTML() {
		$html = '<select name="' . $this->getName() . '" class="inputbox text_area">';
		$html .= '<option value="">&nbsp;</option>';
		foreach($this->arrayFieldElements AS $fieldElement) {
			$html .= '<option value="'.$fieldElement.'"';
			$html .= '>' . $fieldElement . '</option>';
		}
		$html .= '</select>';
		return $html;
	}
	
}

class mFieldType_selectmultiple extends mFieldType {
	
	function getInputHTML() {
		$html = '<select name="' . $this->getInputFieldName(1) . '[]" id="' . $this->getInputFieldName(1) . '" class="inputbox text_area"';
		$html .= ' multiple size="'.($this->getSize() +1).'"';
		$html .= '>';
		$html .= '<option value="">&nbsp;</option>';
		if( $this->getValue() <> '' ) {
			$selectmultiple_values = explode("|",$this->getValue());
		} else {
			$selectmultiple_values = array();
		}
		foreach($this->arrayFieldElements AS $fieldElement) {
			$html .= '<option value="'.$fieldElement.'"';
			if(count($selectmultiple_values)>0 && in_array($fieldElement,$selectmultiple_values)) {
				$html .= ' selected';
			}
			$html .= '>' . $fieldElement . '</option>';
		}
		$html .= '</select>';
		return $html;
	}
	
	function getSearchHTML() {
		$html = '<select id="select-mult" name="' . $this->getName() . '[]" class="inputbox text_area"';
		$html .= ' multiple size="'.($this->getSize() +1).'"';
		$html .= '>';
		$html .= '<option value="">-------------------</option>';
		foreach($this->arrayFieldElements AS $fieldElement) {
			$html .= '<option value="'.$fieldElement.'"';
			$html .= '>' . $fieldElement . '</option>';
		}
		$html .= '</select>';
		return $html;
	}
	
	function getWhereCondition() {
		$args = func_get_arg(0);
		$return = '(';
		
		if( is_array($args) ) {
			foreach( $args AS $arg ) {
				$where[] = 'cfv#.value LIKE \'%' . $arg . '%\'';
			}
		}
		if( count($where) > 1 ) {
			$return .= '((' . implode(') AND (',$where) . '))';
			$return .= ')';
			return $return;
		} else {
			$return .= $where[0] . ')';
			return $return;
		}
	}
	
	function getOutput() {
		$arrayValue = explode('|',$this->value);
		
		if( $this->tagSearch && $this->hasValue() )
		{
			$countTags = count($arrayValue);
			for($i=0;$i<$countTags;$i++)
			{
				$arrTags[$i] = trim($arrayValue[$i]);

				$outputTags[$i] = '';
				$outputTags[$i] .= '<a class="tag" href="'.JRoute::_('index.php?option=com_mtree&task=searchby&cf_id='.$this->getId().'&value='.urlencode($arrTags[$i])).'">';
				$outputTags[$i] .= $arrTags[$i];
				$outputTags[$i] .= '</a>';
			}
			$arrayValue = $outputTags;
		}
		
		$html = '<ul>';
		foreach( $arrayValue AS $value ) {
			$html .= '<li>' . $value . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
		
}

class mFieldType_radiobutton extends mFieldType {
	function getInputHTML() {
		$html = '';
		$i = 0;
		$html .= '<ul style="margin:0;padding:0;list-style-type:none">';
		foreach($this->arrayFieldElements AS $fieldElement) {
			if(!empty($fieldElement)) {
				//$html .= '<li style="width:' . floor(100 / $this->columns) . '%;float:left;background-image:none;padding:0">';
				$html .= '<li style="background-image:none;padding:0">';
				$html .= '<input type="radio" name="' . $this->getInputFieldName(1) . '" value="'.$fieldElement.'" id="' . $this->getInputFieldName(1) . '_' . $i . '" ';
				if( $fieldElement == $this->getValue() ) {
					$html .= 'checked ';
				}
				$html .= '/><label for="' . $this->getInputFieldName(1) . '_' . $i . '">'.$fieldElement.'</label>';
				$html .= '</li>';
				$i++;
			}
		}
		$html .= '</ul>';
		return $html;
	}
	function getSearchHTML() {
		$html = '';
		$i = 0;
		$html .= '<ul style="margin:0;padding:0;list-style-type:none">';
		foreach($this->arrayFieldElements AS $fieldElement) {
			if(!empty($fieldElement)) {
				$html .= '<li style="width:100%;float:left;">';
				$html .= '<input type="radio" name="' . $this->getName() . '" value="'.$fieldElement.'" id="' . $this->getName() . '_' . $i . '" ';
				//$html .= '/><label for="' . $this->getName() . '_' . $i . '">'.$fieldElement.'</label><br />';
				$html .= '/><label for="' . $this->getName() . '_' . $i . '">'.$fieldElement.'</label>';
				$html .= '</li>';
				$i++;
			}
		}
		$html .= '</ul>';
		return $html;
	}
}

class mFieldType_checkbox extends mFieldType {
	
	function getInputHTML() {
		$i = 0;
		$html = '';
		if( $this->getValue() <> '' ) {
			$checkbox_values = explode("|",$this->getValue());
		} else {
			$checkbox_values = array();
		}
		foreach($this->arrayFieldElements AS $fieldElement) {
			// $html .= '<div style="width:' . floor(100 / $this->columns) . '%;float:left;">';
			$html .= '<input type="checkbox" name="' . $this->getInputFieldName(1) . '[]" value="'.$fieldElement.'" id="' . $this->getInputFieldName(1) . '_' . $i . '" ';
			if( in_array($fieldElement,$checkbox_values) ) {
				$html .= 'checked ';
			}
			$html .= '/><label for="' . $this->getInputFieldName(1) . '_' . $i . '">'.$fieldElement.'</label><br>';
			// $html .= '</div>';
			$i++;
		}
		return $html;
	}
	
	function getSearchHTML() {
		$i = 0;
		$html = '';
		foreach($this->arrayFieldElements AS $fieldElement) {
			$html .= '<input type="checkbox" name="' . $this->getName() . '[]" value="'.$fieldElement.'" id="' . $this->getName() . '_' . $i . '" ';
			$html .= '/><label for="' . $this->getName() . '_' . $i . '">'.$fieldElement.'</label><br>';
			$i++;
		}
		return $html;
	}

	function getWhereCondition() {
		$args = func_get_arg(0);
		$return = '(';
		
		if( is_array($args) ) {
			foreach( $args AS $arg ) {
				$where[] = 'cfv#.value LIKE \'%' . $arg . '%\'';
			}
		}
		if( count($where) > 1 ) {
			$return .= '((' . implode(') AND (',$where) . '))';
			$return .= ')';
			return $return;
		} else {
			$return .= $where[0] . ')';
			return $return;
		}
	}
	
	function getOutput($view=1) {
		$arrayValue = explode('|',$this->value);
		$html = '';
		
		if( $this->tagSearch && $this->hasValue() )
		{
			$countTags = count($arrayValue);
			for($i=0;$i<$countTags;$i++)
			{
				$arrTags[$i] = trim($arrayValue[$i]);

				$outputTags[$i] = '';
				$outputTags[$i] .= '<a class="tag" href="'.JRoute::_('index.php?option=com_mtree&task=searchby&cf_id='.$this->getId().'&value='.urlencode($arrTags[$i])).'">';
				$outputTags[$i] .= $arrTags[$i];
				$outputTags[$i] .= '</a>';
			}
			$arrayValue = $outputTags;
		}
			
		switch($view) {
			# Details view
			case '1':
				$html .= '<ul>';
				foreach( $arrayValue AS $value ) {
					if( $value != '' ) {
						$html .= '<li>' . $value . '</li>';
					}
				}
				$html .= '</ul>';
				break;
			# Summary view
			case '2':
				$html .= implode(',',$arrayValue);
				break;
		}
		return $html;
	}
	
}

class mFieldType_file extends mFieldType {
	var $isFile = true;
	
	function parseValue( $value ) { 
		return $value;
	}
	/*
	function getJSValidation() {
		$js = '';
		$js .= '} else if (!hasExt(form.' .$this->getInputFieldName(1) . '.value,\'png|jpe?g|gif\')) {'; 
		$js .= 'alert("' . $this->getCaption() . ': Please select png, jpg or gif file.");';
		return $js;
	}
	*/	
	function getOutput() {
		$html = '';
		$showCounter 	= $this->getParam('showCounter',1);

		if(!empty($this->value)) {
			$html .= '<a href="' . $this->getDataAttachmentURL() . '" target="_blank">';
			$html .= $this->getValue();
			$html .= '</a>';
		}
		
		$append_html = array();
		if( $showCounter ) {
			$append_html[] = JText::sprintf('{{n}} views', $this->counter);
		}
		
		if( !empty($append_html) ) {
			$html .= ' (' . implode(', ',$append_html) . ')';
		}
		return $html;
	}
	function getInputHTML() {
		$html = '';
		if( $this->attachment > 0 ) {
			$html .= $this->getKeepFileCheckboxHTML($this->attachment);
			$html .= '&nbsp;';
			$html .= '<a href="' . $this->getDataAttachmentURL() . '" target="_blank">';
			$html .= $this->getValue();
			$html .= '</a>';
			
			$showCounter = $this->getParam('showCounter',1);
			if( $showCounter ) {
				$html .= ' (' . JText::sprintf('{{n}} views', $this->counter) . ')';
			}
			
			$html .= '</br >';
		}
		$html .= '<input class="inputbox text_area" type="file" name="' . $this->getInputFieldName(1) . '" id="' . $this->getSearchFieldName(1) . '" />';
		return $html;
	}

	function getSearchHTML() {
		return '<input class="inputbox text_area" type="checkbox" name="' . $this->getSearchFieldName(1) . '" id="' . $this->getSearchFieldName(1) . '" />&nbsp;<label for="' . $this->getName() . '">' . JText::_( 'Contains file' ) . '</label>';
	}
	
	function getWhereCondition() {
		if( func_num_args() == 0 ) {
			return null;
		} else {
			return '(cfv#.attachment = \'1\')';
		}
	}

}

class mFieldType_number extends mFieldType {
	var $numOfSearchFields = 2;
	function getJSValidation() {
		$js = '';
		if( in_array($this->getName(),array('link_hits','link_votes','link_visited','link_rating')) ) {
			$js .= '} else if (form.elements["publishing[' .$this->getInputFieldName(1) . ']"].value != "" && /^[-]?([1-9]{1}[0-9]{0,}(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|\.[0-9]{1,2})$/i.test(form.elements["publishing[' .$this->getInputFieldName(1) . ']"].value)==false) {'; 
		} else {
			$js .= '} else if (form.'.$this->getInputFieldName(1).' && form.' .$this->getInputFieldName(1) . '.value != "" && /^[-]?([1-9]{1}[0-9]{0,}(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|\.[0-9]{1,2})$/i.test(form.' .$this->getName() . '.value)==false) {'; 
		}
		$js .= 'alert("' . $this->getCaption() . ': '. JText::_( 'Please enter a valid number' ) . '");';
		return $js;
	}
	function parseValue($value) {
		if(is_numeric($value)) {
			return trim($value);
		} else {
			return '';
		}
	}
	function getSearchHTML() {
		$html = '<select name="' . $this->getSearchFieldName(2) . '" class="inputbox text_area" size="1">';
		$html .= '<option value="1" selected="selected">' . JText::_( 'Exactly' ) . '</option>';
		$html .= '<option value="2">' . JText::_( 'More than' ) . '</option>';
		$html .= '<option value="3">' . JText::_( 'Less than' ) . '</option>';
		$html .= '</select>';
		$html .= '&nbsp;';
		$html .= '<input name="' . $this->getSearchFieldName(1) . '" type="text" class="inputbox text_area" size="'.$this->getSize().'" />';
		return $html;
	}
	
	function getWhereCondition() {
		$args = func_get_args();

		if( $this->isCore() ) {
			$fieldname = $this->getName();
		} else {
			$fieldname = 'cfv#.value';
		}
		
		if( ($args[1] >= 1 || $args[1] <= 3) && is_numeric($args[0]) ) {
			switch($args[1]) {
				case 1:
					return $fieldname . ' = ' . $args[0];
					break;
				case 2:
					return $fieldname . ' > ' . $args[0];
					break;
				case 3:
					return $fieldname . ' < ' . $args[0];
					break;
			}
		} else {
			return null;
		}
	}
}

class mFieldType_date extends mFieldType {
	var $numOfInputFields = 3;
	var $numOfSearchFields = 10;
	
	function parseValue( $value ) { 
		if ( is_array($value) && is_numeric($value[0]) && is_numeric($value[1]) && is_numeric($value[2]) ) {
			return $value[2] . '-' . str_pad($value[1],2,'0',STR_PAD_LEFT) . '-' . str_pad($value[0],2,'0',STR_PAD_LEFT);
		} else {
			return '';
		}
	}
	
	function getOutput() {
		$dateFormat = $this->getParam('dateFormat','%Y-%m-%d');
		$value = $this->getValue();
		return strftime($dateFormat,mktime(0,0,0,intval(substr($value,5,2)),intval(substr($value,8,2)),intval(substr($value,0,4))));
	}
	
	function getSearchHTML() {
		$startYear = $this->getParam('startYear',(date('Y')-70));
		$endYear = $this->getParam('endYear',date('Y'));
		
		$html = '';
		$html .= '<input id="' . $this->getSearchFieldName(1) . 'a" name=' . $this->getSearchFieldName(1) . ' type="radio" value="1" checked />';
		$html .= '<label for="' . $this->getSearchFieldName(1) . 'a">' . JText::_( 'Exactly on' ) . '</label>&nbsp;';
		
		$html .= '<select name="' . $this->getSearchFieldName(2) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($day=1;$day<=31;$day++) { $html .= '<option value="' . $day . '">' . $day . '</option>'; }
		$html .= '</select>';

		$html .= '<select name="' . $this->getSearchFieldName(3) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($month=1;$month<=12;$month++) { $html .= '<option value="' . $month . '">' . strftime('%b', mktime(0, 0, 0, $month)) . '</option>'; }
		$html .= '</select>';

		$html .= '<select name="' . $this->getSearchFieldName(4) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($year=$endYear;$year>=$startYear;$year--) { $html .= '<option value="' . $year . '">' . $year . '</option>'; }
		$html .= '</select>';
		
		$html .= '<br />';
		
		$html .= '<input id="' . $this->getSearchFieldName(1) . 'b" name=' . $this->getSearchFieldName(1) . ' type="radio" value="2" />';
		$html .= '<label for="' . $this->getSearchFieldName(1) . 'b">' . JText::_( 'Between' ) . '</label>&nbsp;';
		
		$html .= '<select name="' . $this->getSearchFieldName(5) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($day=1;$day<=31;$day++) { $html .= '<option value="' . $day . '">' . $day . '</option>'; }
		$html .= '</select>';

		$html .= '<select name="' . $this->getSearchFieldName(6) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($month=1;$month<=12;$month++) { $html .= '<option value="' . $month . '">' . strftime('%b', mktime(0, 0, 0, $month, 1)) . '</option>'; }
		$html .= '</select>';
		
		$html .= '<select name="' . $this->getSearchFieldName(7) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($year=$endYear;$year>=$startYear;$year--) { $html .= '<option value="' . $year . '">' . $year . '</option>'; }
		$html .= '</select>';
		
		$html .= '&nbsp;';
		$html .= JText::_( 'and' );
		$html .= '&nbsp;';
		
		$html .= '<select name="' . $this->getSearchFieldName(8) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($day=1;$day<=31;$day++) { $html .= '<option value="' . $day . '">' . $day . '</option>'; }
		$html .= '</select>';

		$html .= '<select name="' . $this->getSearchFieldName(9) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($month=1;$month<=12;$month++) { $html .= '<option value="' . $month . '">' . date("M", mktime(0, 0, 0, $month, 1)) . '</option>'; }
		$html .= '</select>';

		$html .= '<select name="' . $this->getSearchFieldName(10) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($year=$endYear;$year>=$startYear;$year--) { $html .= '<option value="' . $year . '">' . $year . '</option>'; }
		$html .= '</select>';
		

		
		return $html;
		
	}
	
	function getInputHTML() {
		$startYear = $this->getParam('startYear',(date('Y')-70));
		$endYear = $this->getParam('endYear',date('Y'));
		$value = $this->getValue();
		
		if(empty($value)) {
			$dayValue = 0;
			$monthValue = 0;
			$yearValue = 0;
		} else {
			$dayValue = intval(substr($value,-2));
			$monthValue = intval(substr($value,5,2));
			$yearValue = intval(substr($value,0,4));
		}
		
		$html = '';
		$html .= '<select name="' . $this->getInputFieldName(1) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($day=1;$day<=31;$day++) {
			$html .= '<option value="' . $day . '"';
			if( $day == $dayValue ) {
				$html .= ' selected';
			}
			$html .= '>' . $day . '</option>';
		}
		$html .= '</select>';

		$html .= '<select name="' . $this->getInputFieldName(2) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($month=1;$month<=12;$month++) {
			$html .= '<option value="' . $month . '"';
			if( $month == $monthValue ) {
				$html .= ' selected';
			}
			$html .= '>' . strftime('%b', mktime(0, 0, 0, $month)) . '</option>';
		}
		$html .= '</select>';
		
		$html .= '<select name="' . $this->getInputFieldName(3) . '" class="inputbox">';
		$html .= '<option value="">&nbsp;</option>';
		for($year=$endYear;$year>=$startYear;$year--) {
			$html .= '<option value="' . $year . '"';
			if( $year == $yearValue ) {
				$html .= ' selected';
			}
			$html .= '>' . $year . '</option>';
		}
		$html .= '</select>';		
		return $html;
	}
	
	function getWhereCondition() {
		$args = func_get_args();
		$date0 = $args[3] . '-' . $args[2] . '-' . $args[1];
		$date1 = $args[6] . '-' . $args[5] . '-' . $args[4];
		$date2 = $args[9] . '-' . $args[8] . '-' . $args[7];
		
		if( $this->isCore() ) {
			$fieldname = $this->getName();
		} else {
			$fieldname = 'cfv#.value';
		}
		
		if($args[0] == 1) {
			if( is_numeric($args[1]) && is_numeric($args[2]) && is_numeric($args[3]) ) {
				return 'STR_TO_DATE('.$fieldname.',\'%Y-%m-%d\') = STR_TO_DATE(\'' . $date0 .'\',\'%Y-%m-%d\')';
			}
		} else {
			if( is_numeric($args[4]) && is_numeric($args[5]) && is_numeric($args[6]) && empty($args[7]) && empty($args[8]) && empty($args[9]) ) {
				return 'STR_TO_DATE('.$fieldname.',\'%Y-%m-%d\') >= STR_TO_DATE(\'' . $date1 .'\',\'%Y-%m-%d\')';
			} elseif( is_numeric($args[7]) && is_numeric($args[8]) && is_numeric($args[9]) && empty($args[4]) && empty($args[5]) && empty($args[6]) ) {
				return 'STR_TO_DATE('.$fieldname.',\'%Y-%m-%d\') <= STR_TO_DATE(\'' . $date2 .'\',\'%Y-%m-%d\')';
			} elseif( is_numeric($args[4]) && is_numeric($args[5]) && is_numeric($args[6]) && is_numeric($args[7]) && is_numeric($args[8]) && is_numeric($args[9]) ) {
				$timestamp1 = mktime(0, 0, 0, $args[5], $args[4], $args[6]);
				$timestamp2 = mktime(0, 0, 0, $args[8], $args[7], $args[9]);
				if($timestamp1>$timestamp2) {
					$maxDate = $date1;
					$minDate = $date2;
				} else {
					$maxDate = $date2;
					$minDate = $date1;
				}
				if($maxDate == $minDate) {
					return 'STR_TO_DATE('.$fieldname.',\'%Y-%m-%d\') = STR_TO_DATE(\'' . $date1 . '\',\'%Y-%m-%d\')';
				} else {
					return '(STR_TO_DATE('.$fieldname.',\'%Y-%m-%d\') >= STR_TO_DATE(\'' . $minDate .'\',\'%Y-%m-%d\') AND STR_TO_DATE('.$fieldname.',\'%Y-%m-%d\') <= STR_TO_DATE(\'' . $maxDate .'\',\'%Y-%m-%d\'))';
				}
			} else {
				return null;
			}
		}
		return null;
	}
}

class mFieldType_email extends mFieldType {
	function getJSValidation() {
		$js = '';
		$js .= '} else if (form.' .$this->getName() . '.value != "" && /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,6}$/i.test(form.' .$this->getName() . '.value)==false) {'; 
		$js .= 'alert("' . JText::_( 'Please enter a valid email' ) . '");';
		return $js;
	}
	function getOutput() {
		$email = $this->getValue();
		$html = '';
		if(!empty($email)) {
			$html .= '<script type="text/javascript"><!--' . "\n" . 'document.write(\'<a hr\'+\'ef="mai\'+\'lto\'+\':\'+\'';
			for($i=0;$i<strlen($email);$i++) {
				$html .= '%'.dechex(ord(substr($email,$i,1)));
			}
			$html .= '">';
			for($j=0;$j<strlen($email);$j++){
			    $check = htmlentities($email[$j],ENT_QUOTES);
			   $html .= ($email[$j] == $check) ? "&#".ord($email[$j]).";" : $check;
			}
			$html .= '<\/a>\');' . "\n" . '//--></script>';
		}
		return $html;
	}
}

class mFieldType_tags extends mFieldType {

	function getInputHTML() {
		$params['maxChars'] = intval($this->getParam('maxChars',80));
		
		return '<input class="inputbox text_area" type="text" name="' . $this->getInputFieldName(1) . '" id="' . $this->getInputFieldName(1) . '" size="' . $this->getSize() . '" value="' . htmlspecialchars($this->getValue()) . '" maxlength="'.$params['maxChars'].'" />';
	}

	function getOutput() {
		$arrTags = explode(',',$this->getValue());
		$countTags = count($arrTags);
		
		for($i=0;$i<$countTags;$i++)
		{
			$arrTags[$i] = trim($arrTags[$i]);
			
			$outputTags[$i] = '';
			$outputTags[$i] .= '<a class="tag" href="'.JRoute::_('index.php?option=com_mtree&task=searchby&cf_id='.$this->getId().'&value='.urlencode($arrTags[$i])).'">';
			$outputTags[$i] .= $arrTags[$i];
			$outputTags[$i] .= '</a>';
		}

		$html = '';
		$html .= implode(', ',$outputTags);

		return $html;
	}
	
	function parseValue($value) {
		$params['maxChars'] = intval($this->getParam('maxChars',80));
		$value = JString::substr($value,0,$params['maxChars']);
		
		// Allow alphanumeric with dashes, and spaces
		$pattern = "/^[ŞşİıÇçĞğÜüÖöАаӐӑӒӓӘәӚӛӔӕБбВвГгҐґЃѓҒғӶӷҔҕДдЂђЕеЀѐЁёӖӗҼҽҾҿЄєЖжӁӂҖҗӜӝЗзҘҙӞӟӠӡЅѕИиЍѝӤӥӢӣІіЇїӀӀЙйҊҋЈјКкҚқҞҟҠҡӃӄҜҝЛлӅӆЉљМмӍӎНнҤҥҢңӉӊӇӈЊњОоӦӧӨөӪӫҨҩПпҦҧРрҎҏСсҪҫТтҬҭЋћЌќУуЎўӲӳӰӱӮӯҮүҰұФфХхҲҳҺһЦцҴҵЧчӴӵҶҷӋӌҸҹЏџШшЩщЪъЫыӸӹЬьҌҍЭэӬӭЮюЯяA-Za-z0-9- ]+$/";

		$arrTags = explode(',',$value);
		$countTags = count($arrTags);
		
		for($i=0;$i<$countTags;$i++)
		{
			$arrTags[$i] = trim($arrTags[$i]);
			if( !preg_match( $pattern, $arrTags[$i] ) ) {
			    unset($arrTags[$i]);
			}
		}
		return implode(', ',$arrTags);
	}
}
?>