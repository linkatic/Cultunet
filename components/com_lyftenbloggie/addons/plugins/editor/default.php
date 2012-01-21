<?php
/**
 * Default Editor Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class DefaultEditorPlugin extends BloggiePlugin
{
	var $skin;
	var $toolbar;
	var $uploads;
	
	/**
	 * Constructor for PHP4
	 **/
	function DefaultEditorPlugin($params)
	{
		// Initialize variables
		$this->skin 	= $params->get('skin','kama');
		$this->toolbar	= $params->get('toolbar','Basic');
		$this->uploads	= BloggieFactory::allowAccess('author.can_upload');
	}
	
	/**
	  * Returns the html needed by the editor
	 **/
	function display($name, $content, $width='580', $height, $col, $row=10, $buttons = true)
	{
		$BrowserComp = $this->isCompatible();
		
		//Add Javascript
		if ( $BrowserComp )
			$this->setJavascript();
		
		$editor = "<textarea id=\"{$name}\" name=\"{$name}\" class=\"ckeditor\" rows=\"{$row}\" cols=\"{$col}\" wrap=\"virtual\">{$content}</textarea>\n";
		$editor .= "<div id=\"editor-xtd-buttons\">
		<div class=\"button2-left\" style=\"margin-right:5px;\"><div class=\"readmore\"><a title=\"Read more\" href=\"#\" onclick=\"insertReadmore('{$name}');return false;\" rel=\"\">".JText::_('Read more')."</a></div></div>
		</div>";

		if ( $BrowserComp )
			$editor .= $this->addSettings($name, $width);

		return $editor;
	}
	
	/**
	 * Document Javascript
	 **/
	function javascript()
	{
	?>
	function jInsertEditorText( text,editor ) {
			text = text.replace( /<img src="/, '<img src="<?php echo JURI::base(); ?>' );
			CKEDITOR.instances.text.insertHtml( text );
	}
	function insertReadmore(editor) {
		var content =  CKEDITOR.instances.text.getData(); 
		if (content.match(/<hr\s+id=("|')system-readmore("|')\s*\/*>/i)) {
			alert('<?php echo JText::_('RM ALREADY EXISTS'); ?>');
			return false;
		} else {
			jInsertEditorText('<hr id="system-readmore" />', editor);
		}
	}
	<?php
	}

	/**
	 * Sets Javascript
	 **/
	function setJavascript()
	{
		//Initialize variables
		$document		=& JFactory::getDocument();

		//Add Javascript
		$document->addScript(JURI::base().'components/com_lyftenbloggie/addons/plugins/editor/ckeditor/ckeditor.js');
	}

	/**
	 * Add settings 
	 **/
	function addSettings($name, $width)
	{
		//Initialize variables
		$retun	= array();
		$upload	= '';

		//Get Language
		$lg 		= &JFactory::getLanguage();
		$tag 		= $lg->get('tag');
		$pieces 	= explode("-", $tag);

		//Ensure there is a translation
		if(isset($pieces[0]) && file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'addons'.DS.'plugins'.DS.'editor'.DS.'ckeditor'.DS.'lang'.DS.$pieces[0].'.js'))
		{
			$retun['lang'] = true;
			$lang = "language : '".$pieces[0]."',\n				";
		}else{
			$retun['lang'] = false;
			$lang = "language : 'en',\n				";
		}

		//Uploads
		if($this->uploads) {
			$upload =",
				filebrowserUploadUrl : '".JURI::base()."index.php?option=com_lyftenbloggie&task=ajaxuploadfile&".JUtility::getToken()."=1'";
		}
		
		//Javascript
		$js = "<script type=\"text/javascript\">
		//<![CDATA[
		CKEDITOR.replace( '{$name}',
			{
				{$lang}toolbar : '{$this->toolbar}',
				skin : '{$this->skin}',
				width : '{$width}',
				filebrowserBrowseUrl : '".JURI::base()."index.php?option=com_lyftenbloggie&task=viewImages&format=raw',
				filebrowserFlashBrowseUrl : '".JURI::base()."index.php?option=com_lyftenbloggie&task=viewImages&format=raw&type=flash'{$upload}
			}
		);
		//]]>
		</script>\n";
		
		return $js;
	}
	
	/**
	 * Browser Compatibility
	 **/
	function isCompatible()
	{
		$sAgent = $_SERVER['HTTP_USER_AGENT'] ;

		if ( strpos($sAgent, 'MSIE') !== false && strpos($sAgent, 'mac') === false && strpos($sAgent, 'Opera') === false )
		{
			$iVersion = (float)substr($sAgent, strpos($sAgent, 'MSIE') + 5, 3) ;
			return ($iVersion >= 5.5) ;
		}
		else if ( strpos($sAgent, 'Gecko/') !== false )
		{
			$iVersion = (int)substr($sAgent, strpos($sAgent, 'Gecko/') + 6, 8) ;
			return ($iVersion >= 20030210) ;
		}
		else
			return false ;
	}
	
	/**
	 * Just cause
	 **/
	function save( $data ){}
	
	/**
	 * Get the editor content
	 **/
	function getContent( $editor )
	{
		return "CKEDITOR.instances.".$editor.".getData();\n";	
	}
}