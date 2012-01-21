<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once (BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'libraries'.DS.'pear'.DS.'Parser.php');

/**
 * LyftenBloggie Framework Template class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieTemplate extends JObject
{
	var $_template_path 	= null;
	var $_template_url 		= null;
	var $_template_name		= null;
	var $_template_file 	= null;
	var $_settings 			= null;
	var $_themeParams		= null;

	var $_string_template 	= null;
	var $_replacments 		= array();
	var $_nessted 			= array();

	var $output;
	var $_parent_template;
	var $_parent;

    /**
     * PHP4 Construct the object
     **/
    function BloggieTemplate($type)
    {
        BloggieTemplate::__construct($type);
    }

    /**
     * Construct the object
     **/
	function __construct($type=null)
	{
		if($type == 'default')
		{
			$this->setDefault();
			$this->_settings = & BloggieSettings::getInstance();
		}
	}

	/**
	 * Returns a reference to a global template object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$template = & BloggieTemplate::getInstance();
	 *
	 * @param 	signature
	 * @return	BloggieTemplate object.
	 **/
	function &getInstance($signature='default')
	{
		static $instances;

		if (!isset( $instances )) {
			$instances = array();
		}

		if (empty($instances[$signature]))
		{
			$instance[$signature] = new BloggieTemplate($signature);
		}

		return $instance[$signature];
	}

	/**
	 * Gets the parameter object for the component
	 *
	 * @access public
	 * @param string $name The component name
	 * @return object A JParameter object
	 */
	function &get( $name, $default=null )
	{
		$value = $this->_themeParams->get($name, $default);
		return $value;
	}

	/**
	 * Method to get the theme's name
	 *
	 * @access	public
	 * @return	string The name of the model
	 **/
	function setDefault()
	{
		if (!$this->_template_name)
		{
			$database = & JFactory::getDBO(); 
			$query = 'SELECT name, attribs'
					. ' FROM #__bloggies_themes'
					. ' WHERE is_default = 1'
					;
			$database->setQuery( $query );
			$theme = $database->loadObject();
	
			//Ensure there is a theme set
			if(!empty($theme)){
				$this->_themeParams 	= new JParameter($theme->attribs);
				$this->_template_name 	= strtolower($theme->name);
			}else{
				$this->_themeParams 	= new JParameter('');
				$this->_template_name 	= 'default';
			}
	
			//Set themes URL & Path
			$this->_template_url 	= BLOGGIE_SITE_URL.'/addons/themes/'.$this->_template_name;
			$this->_template_path 	= BLOGGIE_SITE_PATH.DS.'addons'.DS.'themes'.DS.$this->_template_name.DS;
			$this->_system_path 	= BLOGGIE_SITE_PATH.DS.'addons'.DS.'themes'.DS.'system'.DS;
		}

		return;
	}

	/**
	 * Method to get the template styles
	 **/
	function setStyles($dashboard=false)
	{
		static $headerAdded;

		if(!$headerAdded)
		{
			// Initialize variables
			$document 		=& JFactory::getDocument();
			$typeComments 	= $this->getSetting('typeComments', 'default');
			$view 			= JRequest::getVar('view');

			// Create style query
			$dashboard 	= ($dashboard) ? '&dash=1' : '';
			$comments 	= ($typeComments == 'default' && ($view == 'entry' || $view == 'comments')) ? '&comment=1' : '';
			$style 		= ($this->get('style')) ? '&style='.strtolower($this->get('style')) : '';

			//Add CSS file
			if(file_exists($this->_template_path.'css'.DS.'style.css')) {
				$document->addStyleSheet(BLOGGIE_SITE_URL.'/addons/themes/styles.php?theme='.$this->_template_name.$dashboard.$comments.$style);
			}else{
				$this->debug_push(JText::_('TEMPLATE ERROR'), '"style.css" '.JText::_('NOT FOUND'), 1);
			}

			$headerAdded = true;
		}
		return true;
	}

	/**
	 * Method to get System variable
	 *		 
	 * @param strng $path
	 **/
	function getSystemVar($variable)
	{
		switch ($variable)
		{
			case 'base_url':
				$value = JURI::base();
				break;
			case 'blog_assets':
				$value = JURI::base().'components/com_lyftenbloggie/addons/themes/system';
				break;
			case 'theme_url':
				$value = $this->_template_url;
				break;
			case 'view':
				$value = JRequest::getVar('view');
				break;
			case 'limitstart':
				$value = JRequest::getVar('limitstart');
				break;
			default:
				$value = '';
				break;
		}
		return $value;
	}

	/**
	 * Method to get Setting value
	 *		 
	 * @param strng $path
	 **/
	function getSetting($variable, $default=null)
	{
		if ($variable)
		{
			return $this->_settings->get($variable, $default);
		}
		return $default;
	}

	/**
	 * Method to set the location of template files
	 *		 
	 * @param strng $path
	 **/
	function setTemplatePath($path=null)
	{
		if ($path)
		{
			$this->_template_path = $path;
		}
	}

	/**
	 * Push sub_template, pattern assignment
	 *
	 * @param object $value
	 * @param strng $pattern
	 **/
	function assignTemplate($pattern, &$value)
	{
		if ($pattern != ''){
			if (is_object($value) && is_a($value, 'template')){
				$this->_nessted[$pattern] =& $value;
			}
		}
	}

	/**
	 * Push value, pattern assignment
	 *
	 * @param 	misc 	$value
	 * @param 	string 	$pattern
	 * @return 	void
	 **/
	function assign($pattern, $value)
	{
		if ($pattern != '')
		{
			if (is_array($value)){
				$this->_replacments[$pattern] =& $value;
			}elseif (is_object($value)) {
				$this->_replacments[$pattern] =& $value;
			}else{
				$this->_replacments[$pattern] = $value;
			}
		}
	}

	/**
	 * Get value
	 *
	 * @param 	string $pattern
	 * @return 	string $value
	 **/
	function &getValue($pattern)
	{
		$value = '';

		if (is_array($this->_replacments) && key_exists($pattern, $this->_replacments)){
			return $this->_replacments[$pattern];
		}elseif (is_array($this->_nessted) && key_exists($pattern, $this->_nessted)){
			if (is_object($this->_nessted[$pattern]) && is_a($this->_nessted[$pattern], 'template')){
				return $this->_nessted[$pattern]->getOutput();
			}
		}elseif (defined($pattern)){
			$value = constant($pattern);
		}
		return $value;
	}

	/**
	 * Template file name
	 *
	 * @param string $file
	 **/
	function setTemplateFile($file)
	{
		$file = str_replace('.tpl', '', $file);
		$this->_template_file = $file.'.tpl';
	}

	/**
	 * Get Template file name
	 *
	 * @return 	string
	 **/
	function getTempalteFile()
	{
		if (!empty($this->_template_file))
		{
			return $this->_template_file;
		}elseif ($this->_parent_template){
			return $this->_parent_template->getTempalteFile();
		}else{
			return JText::_('NO TEMPLATE FILE');
		}
	}

	/**
	 * Get template file content
	 *
	 **/
	function loadTemplate()
	{
		if(file_exists($this->_template_path.DS.$this->_template_file))
		{
			$this->_string_template = file_get_contents($this->_template_path.DS.$this->_template_file);
		}else{
			die("<b>Fatal error</b>:  <b>$this->_template_file</b> does not exist");
		}
	}

	/**
	 * Parse template file
	 *
	 **/
	function parse()
	{
		$parser = new BloggieTemplateParser(null, 'event');
		$parser->set__template($this);

		$this->_string_template =  str_replace ("{{", "&#123;", $this->_string_template);
		$this->_string_template =  str_replace ("}}", "&#125;", $this->_string_template);
		$this->_string_template =  str_replace ("&", "&amp;", $this->_string_template);
		$result = $parser->setInputString($this->_string_template);

		if (PEAR::isError($result)) {
			$this->debug_push(JText::_('PARSER ERROR'), $result->getMessage(), 1);
		}
		$result = $parser->parse();
		if (PEAR::isError($result))
		{
			$this->debug_push(JText::_('TEMPLATE'), $this->getTempalteFile(), 1);
			$this->debug_push(JText::_('PARSER ERROR'), $result->getMessage(), 1);
		}
		$this->_string_template =  str_replace ("&#123;", "{", $this->_string_template);
		$this->_string_template =  str_replace ("&#125;", "}", $this->_string_template);

		$this->output = $parser->getOutput();
		$parser->__destruct();
	}

	/**
	 * Set string template
	 *
	 * @param string $str_template
	 **/
	function setStringTemplate($str_template)
	{
		$this->_string_template = $str_template;
	}

	/**
	 * Get output
	 *
	 * @return string
	 **/
	function getOutput($return=true)
	{
		if ($this->_string_template == ''){
			$this->loadTemplate();
		}
		$this->parse();

		//Return Filled Template
		if($return)
		{
			echo $this->output;
		}else{
			return $this->output;
		}
	}

	function __destruct ()
	{
		foreach($this->_replacments as $k => $rep)
		{
			unset($this->_replacments[$k]);
		}
		foreach($this->_nessted as $k => $nes)
		{
			unset($this->_nessted[$k]);
		}
		unset($this->_parent_template);
		unset($this->output);
	}

	function debug_push($type, $message, $level = 0)
	{
		if(JDEBUG)
			echo '<b>'.$type.'</b>: '.$message.'<br />';
	}
}

/**
 * BloggieTemplateNode class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
Class BloggieTemplateNode extends JObject
{
	var $attributes = array();
	var $_nodes 	= array();

	var $_type;
	var $_template;
	var $_value;
	var $parent;

	function BloggieTemplateNode(&$template, $type, $attributes = '', $value = '')
	{
		$this->attributes 	= $attributes;
		$this->_template 	=& $template;
		$this->_type 		= $type;
		$this->_value 		= $value;
	}

	function &_arrayValue($chain, $variable = null, $instruction_param = false)
	{
		$result = "";

		if (is_null($variable))
		{
			if (preg_match('/^(\w+)((\[\w+\])+)$/', $chain[0], $var))
			{
				$arr =& $this->_template->getValue($var[1]);
				$arr_keys = preg_split( "/\[([^\]]+)\]/", $var[2], -1, PREG_SPLIT_DELIM_CAPTURE );

				foreach ($arr_keys as $key)
				{
					if ($key !== '')
					{
						if (is_array($arr))
						{
							if (array_key_exists($key, $arr))
							{
								$arr =& $arr[$key];
							}else{
								return $result;
							}
						}else{
							return $result;
						}
					}
				}
				$variable =& $arr;
			}else{
				$variable =& $this->_template->getValue($chain[0]);
			}
			array_shift($chain);
	
			if (!empty($chain))
			{
				return $this->_arrayValue($chain, $variable, $instruction_param);
			}else{
				return $variable;
			}
		}else{
			if (is_object($variable))
			{
				$property = $chain[0];

				array_shift($chain);

				if (preg_match('/^(\w+)((\[\w+\])+)?$/', $property, $var)){
					$property = $var[1];
				}

				//method
				if (method_exists($variable, $property))
				{
					if (!empty ($instruction_param) && empty($chain))
					{
						$result =& call_user_func_array(array($variable, $property), $instruction_param);
					}else{
						$result =& call_user_func(array($variable, $property));
					}
				// property
				}else {
					if (isset($variable->$property))
					{
						$result =& $variable->$property;
					}elseif ($this->property_exists(get_class($variable), $property)){
						$result =& $variable->$property;
					}
				}

				if (isset($var[2]))
				{
					$arr_keys = preg_split( "/\[([^\]]+)\]/", $var[2], -1, PREG_SPLIT_DELIM_CAPTURE );

					foreach ($arr_keys as $key)
					{
						if ($key !== '')
						{
							if (is_array($result))
							{
								if (array_key_exists($key, $result))
								{
									$result =& $result[$key];
								}else{
									return $result;
								}
							}else{
								return $result;
							}
						}
					}
				}

				if (!empty($chain))
				{
					return $this->_arrayValue($chain, $result, $instruction_param);
				}else{
					return $result;
				}
			}else{
				$value = '';
				return $value;
			}
		}
	}

	function &paramValue($param)
	{
		if (substr($param,0,1) == '{')
		{
			$param = trim ($param, '{}');
			
			//Check System Variables
			if(strstr($param, 'SETTING.'))	//Settings
			{
				$value = str_replace('SETTING.' , '', $param);
				return $this->paramSetting($value);
			}elseif(strstr($param, 'PARAM.'))	//Template Parameter
			{
				$param = str_replace('PARAM.', '', $param);
				$default = '';
				if(strstr($param, '|'))
					@list($param, $default) = explode ('|',$param);
				$value = $this->_template->_themeParams->get($param, $default);
				return $value;
			}elseif(strstr($param, 'SYSTEM.'))	//Settings
			{
				$param = str_replace('SYSTEM.', '', $param);
				$value = $this->_template->getSystemVar($param);
				return $value;
			}elseif(strstr($param, 'JTEXT.'))	//Language
			{
				$value = JText::_(str_replace('JTEXT.' , '', $param));
				return $value;
			}
			
			//Get vaiable and function
			@list($variable_name, $instuction_str) = explode ('|',$param);

			//Instruction 
			if (!empty ($instuction_str))
			{
				$instruction_param = '';
				if (strpos($instuction_str,':') !== false && preg_match('/^\w+:.*$/', $instuction_str)){
					list($instruction_function, $instruction_param) = explode (':', $instuction_str, 2);
				}else{
					$instruction_function = $instuction_str;
				}

				if (!function_exists($instruction_function))
				{
					$instruction_param = $instruction_function;
					$instruction_function = null;
				}

				//Instruction params
				$instruction_params = array();
				$split_params = preg_split( "/[\s,]*('[^']+')[\s,]*|[\s,]+/", $instruction_param, -1, PREG_SPLIT_DELIM_CAPTURE );
				foreach ($split_params as $v)
				{
					if ($v == ''){
					}elseif (preg_match('/^\'[^\']+\'$/', $v)){
						$instruction_params[] = trim($v, '\'');
					}elseif (preg_match('/^\d+(\.\d+)?$/', $v)){
						$instruction_params[] = $v;
					}elseif ($v == 'true'){
						$instruction_params[] = true;
					}elseif ($v == 'false'){
						$instruction_params[] = false;
					}else{
						$instruction_params[] = $this->paramValue('{' . $v . '}');
					}
				}

			}else{
				$instruction_class 		= false;
				$instruction_function 	= null;
				$instruction_param 		= array();
			}

			//variable 
			if (!preg_match('/[\.\[\]]/', $variable_name))
			{
				$result =& $this->_template->getValue($variable_name);
			}elseif (preg_match('/[\w\.\[\]]+/', $variable_name))
			{
				$chain = explode('.', $variable_name);
				if (is_null($instruction_function) && !empty($instruction_params))
				{
					$result =& $this->_arrayValue($chain, null, $instruction_params);
				}else{
					$result =& $this->_arrayValue($chain);
				}

				if(is_object($result) || is_array($result))
				{
					$this->_template->debug_push(JText::_('PARSER ERROR'), (is_object($result) ? JText::sprintf('RETURNED AN OBJECT', $variable_name) : JText::sprintf('RETURNED AN ARRAY', $variable_name)), 1);
					$result = '';
				}
			}

			//Call function
			if (!empty ($instruction_function))
			{
				array_push($instruction_params, $result);
				$new_result = call_user_func_array($instruction_function, $instruction_params);
				return $new_result;
			}
			return $result;
		}else{
			if (substr($param,0,1) == '"')
			{
				$value = trim($param, '"');
			}elseif	(substr($param,0,1) == "'")
			{
				$value = trim($param, "'");
			}else {
				$value = $param;
			}
		}
		return $value;
	}

	//Get Setting
	function &paramSetting($param)
	{
		@list($variable_name, $default) = explode ('|',$param);
		$result =& $this->_template->getSetting($variable_name, $default);
		return $result;
	}

	function &get_child_by_tag ($tag, $index = 0)
	{
		$child = false;
		$i = 0;
		if (is_array($this->_nodes))
		{
			foreach ($this->_nodes as $k => $node)
			{
				if ($this->_nodes[$k]->_type == $tag)
				{
					if ($index == $i)
					{
						return $this->_nodes[$k];
					}
				}
			}
		}
		return $child;
	}

	function get_literal_output()
	{
		$output = '';
		if (is_array($this->_nodes) && !empty($this->_nodes))
		{
			$output = '<' . $this->_type . $this->get_attribute_literal_output() . '>';
			foreach ($this->_nodes as $k => $node)
			{
				$output .= $this->_nodes[$k]->get_literal_output();
			}
			$output .= '</' . $this->_type . '>';
		}elseif ($this->_type == "CDATA"){
			$output .= $this->_value;
		}elseif ($this->_type == "comment"){
			$output .= $this->_value;
		}else{
			$output = '<' . $this->_type . $this->get_attribute_literal_output() . ' />';
		}
		return $output;
	}

	function nodes_output()
	{
		$output = '';
		if (is_array($this->_nodes))
		{
			foreach ($this->_nodes as $k => $node)
			{
				$output .= $this->_nodes[$k]->getOutput();
			}
		}
		return $output;
	}

	function get_attribute_output($attribute_name)
	{
		$output = '';
		if (!empty($this->attributes))
		{
			if (is_array($this->attributes) && key_exists($attribute_name, $this->attributes))
			{
				if (preg_match('/\{/', $this->attributes[$attribute_name]))
				{
					$split_tpl = preg_split('/({.*?})/', $this->attributes[$attribute_name], -1, PREG_SPLIT_DELIM_CAPTURE);
					if (is_array ($split_tpl) && !empty($split_tpl))
					{
						foreach ($split_tpl as $v)
						{
							if (preg_match('/\{/', $v))
							{
								$output .= $this->paramValue($v);
							}else{
								$output .= $v;
							}
						}
					}
				}else{
					$output .= $this->attributes[$attribute_name];
				}
			}
		}
		return $output;
	}

	function get_attributes_output()
	{
		$output = '';
		if (!empty($this->attributes))
		{
			if (is_array($this->attributes))
			{
				foreach ($this->attributes as $key => $val)
				{
					$output .= ' ' . $key . '="' . $this->get_attribute_output($key) . '"';
				}
			}
		}
		return $output;
	}

	function get_attribute_literal_output()
	{
		$output = '';
		if (!empty($this->attributes))
		{
			if (is_array($this->attributes))
			{
				foreach ($this->attributes as $key => $val)
				{
				$output .= ' ' . $key . '="' . $val . '"';
				}
			}
		}
		return $output;
	}

	function getOutput()
	{
		$output = '';

		switch ($this->_type)
		{
			case 'blog:else':
				return $output;
			case 'blog:if':
				$countinue = false;

				if ($this->attributes['test'] && preg_match('/(\'[^\']*\'|\"[^"]*\"|-?\d+|true|false|\{[\w\.\[\]\|:,\-]+\})\s*(=|!=|&lt;|&gt;|in|and|or)\s*(\'[^\']*\'|\"[^"]*\"|-?\d+|true|false|\{[\w\.\[\]\|:,\-]+\})/i',$this->attributes['test'], $if_statment)){

				$variable =& $this->paramValue($if_statment[1]);
				$value =& $this->paramValue($if_statment[3]);
				switch ($if_statment[2])
				{
					case '=':
						if ($variable == $value) $countinue = true;
						break;
					case 'and':
						if ($variable && $value) $countinue = true;
						break;
					case 'or':
						if ($variable || $value) $countinue = true;
						break;
					case '!=':
						if ($variable != $value) $countinue = true;
						break;
					case '&lt;':
						if ($variable < $value) $countinue = true;
						break;
					case '&gt;':
						if ($variable > $value) $countinue = true;
						break;
					case 'in':
						if (((is_string($variable) or (is_numeric($variable)) or (is_bool($variable)))))
						{
							if(is_string($value) and $value != '')
							{
								$value = explode(',',$value);
								if (in_array($variable,$value)){
									$countinue = true;
								}
							}elseif(is_array($value) and !empty($value)){
								if (in_array($variable,$value))
								{
									$countinue = true;
								}
							}
						}
						break;
				}
				}elseif ($this->attributes['test'] && preg_match('/^(\{[\*\w\.\[\]\|:,\-\']+\})$/i',$this->attributes['test'], $if_statment)){
					$variable =& $this->paramValue($if_statment[1]);
					if (!empty($variable)){
						$countinue = true;
					}
				}elseif ($this->attributes['test'] && preg_match('/^(\{[\*\w\.\[\]\|:,\-\']+\})$/i',$this->attributes['test'], $if_statment)){
					$variable =& $this->paramValue($if_statment[1]);
					if (!empty($variable)){
						$countinue = true;
					}
				}

				if ($countinue){
					if (is_array($this->_nodes))
					{
						foreach ($this->_nodes as $k => $node)
						{
							$output .= $this->_nodes[$k]->getOutput();
						}
					}
				}else{
					$else_node =& $this->get_child_by_tag('blog:else');
	
					if ($else_node)
					{
						$output = '';
						if (is_array($else_node->_nodes))
						{
							foreach ($else_node->_nodes as $k => $node)
							{
								$output .= $else_node->_nodes[$k]->getOutput();
							}
						}
					}
				}
				return $output;

			case 'blog:foreach':

				$result = '';
				$array 	= (isset($this->attributes['from']))? $this->paramValue($this->attributes['from']) : false;
				$key 	= (isset($this->attributes['key'])) ? $this->attributes['key'] : false;

				if (is_array($array) && !empty($array))
				{
					$item_name 	= $this->attributes['item'];
					$count 		= count($array);
					$start 		= (isset ($this->attributes['start'])) ? intval($this->get_attribute_output('start')): 1;
					$loop 		= (isset ($this->attributes['loop'])) ? intval($this->get_attribute_output('loop')) + $start - 1 : $count;
					$step 		= (isset($this->attributes['step'])) ? intval($this->get_attribute_output('step')) : 1;

					if ($start > $count) return $result;

					$index 			= 0;
					$internal_index = $start;

					reset($array);
					do {
						$current_key = key($array);
						if (($index >= $start - 1) && ($index < $loop))
						{
							if ($key)
							{
								$position = ($index == 0) ? ' first' : ((($index + 1) == $count) ? ' last' : '');
								$this->_template->assign($key.'_pos', $position);
								$this->_template->assign($key, $internal_index);
								$this->_template->assign($key . '_count', $loop);
							}
							$this->_template->assign($item_name, $array[$current_key]);
							if (is_array($this->_nodes))
							{
								foreach ($this->_nodes as $k => $node)
								{
									$output .= $this->_nodes[$k]->getOutput();
								}
							}
							$internal_index += $step;
						}
	
						$index++;
	
						for ($i=1; $i< $step; $i++)
						{
							if (!next($array))
							{
								break;
							}
							$index++;
						}
					}while (next($array));
				}else{
					$else_node = $this->get_child_by_tag('blog:else');
					if ($else_node)
					{
						$output = '';
						if (is_array($else_node->_nodes))
						{
							foreach ($else_node->_nodes as $k => $node)
							{
								$output .= $else_node->_nodes[$k]->getOutput();
							}
						}
					}
				}

				return $output;
			case 'blog:literal':
				$output = '';
				if (is_array($this->_nodes) && !empty($this->_nodes)){
					foreach ($this->_nodes as $k => $node) {
						$output .= $this->_nodes[$k]->get_literal_output();
					}
				}
				return $output;
			case 'blog:jdate':
				$output = '';
				if (isset($this->attributes['date']))
				{
			//		$format = (isset($this->attributes['format'])) ? $this->attributes['format'] : $this->_template->getSetting->get('dateFormat', '%B %d, %Y');
				//	$output = JHTML::_('date', $this->attributes['date'], $format);
				}
				return $output;
			case 'blog:jroute':
				$output = '';
				if (isset($this->attributes['url']) && !empty($this->attributes['url']))
				{
					$url = $this->attributes['url'];

					//Replace All Variables
					preg_match_all('/\{[\w|\.|\[|\]]+\}/', $url, $variable_statment);
					if (is_array($variable_statment[0]))
					{
						foreach ($variable_statment[0] as $key => $value)
						{
							$variable =& $this->paramValue($value);
							$url = str_replace($value, $variable, $url);
						}
					}
				
					//Setup Attributes
					$tags = '';
					foreach ($this->attributes as $tag => $value)
					{
						$value =& $this->paramValue($value);
						$tags .= $tag.'="'.$value.'" ';
					}

					//Generate Link
					if (is_array($this->_nodes) && !empty($this->_nodes)){
						foreach ($this->_nodes as $k => $node) {
							$output .= $this->_nodes[$k]->getOutput();
						}
					}
					$output = ($output) ? '<a href="'.JRoute::_($url).'"'.trim($tags).'>'.$output.'</a>' : '';
				}
				
				return $output;

			case 'blog:commentbox':
				$output = '';

				// Set Comment Style
				if (isset($this->attributes['id']) && isset($this->attributes['allow']) && isset($this->attributes['total']))
				{
					if(!$this->get_attribute_output('allow')) return $output;

					$plugin = BloggieFactory::getPlugin('comment', 'default');
					$output = $plugin->displayForm($this->get_attribute_output('id'), $this->get_attribute_output('allow'), $this->get_attribute_output('total'));
				}

				return $output;
			case 'blog:comments':
				$output = '';

				//Get Comment Settings
				$id 	=& $this->paramValue($this->attributes['id']);
				$title 	=& $this->paramValue($this->attributes['title']);
				if(!$id && !$title) return $output;

				//Get Comment System Used
				$comment_system = $this->_template->getSetting('typeComments', 'default');
				if(!$comment_system) return $output;

				//Call Comment Plugins
				if($comment_system == 'default')
				{
					if (is_file($this->_template->_template_path . 'blog_comments.tpl'))
					{
						$output = file_get_contents($this->_template->_template_path . 'blog_comments.tpl');

						if ($output && (!isset($this->attributes['parse']) || $this->attributes['parse'] != 'off'))
						{
							$this->_template->debug_push(JText::_('TEMPLATE'), 'blog_comments.tpl');
							$this->_template->setStringTemplate($output);
							$output = $this->_template->getOutput(false);
							return $output;
						}
					}else if (is_file($this->_template->_system_path . 'blog_comments.tpl'))
					{
						$output = file_get_contents($this->_template->_system_path . 'blog_comments.tpl');

						if ($output && (!isset($this->attributes['parse']) || $this->attributes['parse'] != 'off'))
						{
							$this->_template->debug_push(JText::_('TEMPLATE'), 'blog_comments.tpl');
							$this->_template->setStringTemplate($output);
							$output = $this->_template->getOutput(false);
							return $output;
						}
					}
				}else{
					$plugin = BloggieFactory::getPlugin('comment', $comment_system);
					$output = $plugin->display($id, $title);
				}
				return $output;
			case 'blog:template':
				$output = '';
				if (is_array($this->_nodes))
				{
					foreach ($this->_nodes as $k => $node)
					{
						$output .= $this->_nodes[$k]->getOutput();
					}
				}
				return $output;
			case 'blog:value':
				return $this->paramValue($this->attributes['select']);
			case 'CDATA':	
				return $this->_value;
			default:
				if (!empty($this->_nodes))
				{
					$output = '';
					if (is_array($this->_nodes) && !is_object($this->_nodes))
					{
						$i = 0;
						foreach ($this->_nodes as $k => $node)
						{
							$value = $this->_nodes[$k]->getOutput();
							if(is_object($value) || is_array($value))
							{
								$this->_template->debug_push(JText::_('PARSER ERROR'), (is_object($value) ? JText::sprintf('RETURNED AN OBJECT', $k) : JText::sprintf('RETURNED AN ARRAY', $k)), 1);
								$value = '';
							}
							$output .= $value;
							unset($value);
							$i++;
						}
					}
					$output = '<' . $this->_type . $this->get_attributes_output() . '>' . $output;
					$output .= '</' . $this->_type . '>';
				}else{
					$output = '<' . $this->_type . $this->get_attributes_output() . ' />';
				}
				return $output;
		}
	}

	function property_exists($class, $property)
	{
		if (is_object($class)) $class = get_class($class);
		return array_key_exists($property, get_class_vars($class));
	}

	/**
     * Destroy Object
     */
	function __destruct()
	{
		if (!empty($this->nodes))
		{
			foreach($this->nodes as $k => $node)
			{
				$this->nodes[$k]->__destruct();
			}
			unset($this->nodes);
		}

		if (!empty($this->parent)) unset($this->parent);
		if (!empty($this->_template)) unset($this->_template);
		unset($this->attributes);
		unset($this->_value);
	}
}

/**
 * LyftenBloggie Template XML Parser class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieTemplateParser extends XML_Parser
{    
	var $_template;
	var $folding = false;
	var $nodes = array();
	var	$parent_stack = array(-1);
	var $parent_count = 0;

	function defaultHandler($xp, $cdata)
	{
		$current_index = count($this->nodes);
		$parent_index = $this->parent_stack[count($this->parent_stack) - 1];

		$this->nodes[$current_index] = new BloggieTemplateNode($this->_template, 'comment', array(), $cdata);

		if ($parent_index != -1){
			$this->nodes[$current_index]->parent =& $this->nodes[$parent_index];
			$this->nodes[$parent_index]->_nodes[] =& $this->nodes[$current_index];
		}
		unset($parent_index);
	}

	function startHandler($xp, $name, $attribs)
	{
		$current_index = count($this->nodes);
		$parent_index = $this->parent_stack[count($this->parent_stack) - 1];

		$this->nodes[$current_index] = new BloggieTemplateNode($this->_template, $name, $attribs, '');

		if ($parent_index != -1){
			$this->nodes[$current_index]->parent =& $this->nodes[$parent_index];
			$this->nodes[$parent_index]->_nodes[] =& $this->nodes[$current_index];
		}

		array_push($this->parent_stack, $current_index);
		unset($parent_index);
	}

	function endHandler($xp, $name)
	{
		array_pop($this->parent_stack);
	}

	function cdataHandler($xp, $cdata)
	{
		$current_index = count($this->nodes);
		$parent_index = $this->parent_stack[count($this->parent_stack) - 1];
	
		$this->nodes[$current_index] = new BloggieTemplateNode($this->_template, 'CDATA', array(), $cdata);
	
		if ($parent_index != -1)
		{
			$this->nodes[$current_index]->parent =& $this->nodes[$parent_index];
			$this->nodes[$parent_index]->_nodes[] =& $this->nodes[$current_index];
		}
	}

	function getOutput()
	{
		if (isset($this->nodes[0]))
		{
			return $this->nodes[0]->getOutput();
		}
	}

	function set__template(&$template) {
		$this->_template =& $template;
	}

	function __destruct()
	{
		if(!empty($this->nodes))
		{
			foreach($this->nodes as $k => $node)
			{
				if (!empty($this->nodes[$k]))
					$this->nodes[$k]->__destruct();

				$this->nodes[$k] = null;
			}
		}
		unset($this->nodes);
		unset($this->_template);
		unset($this->parent_stack);
	}
}

//Method to Format Karma
function formatKarma($karma)
{
	$rest = substr($karma, 0, 1);
	return ($rest == '-') ? $karma : '+'.$karma;
}
?>