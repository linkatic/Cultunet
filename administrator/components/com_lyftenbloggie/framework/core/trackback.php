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

/**
 * LyftenBloggie Framework Trackback class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieTrackback extends JObject
{
	var $_settings;
	var $_db;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		//initialize variables
		$this->_db 			= & JFactory::getDBO(); 
		$this->_settings 	= & BloggieSettings::getInstance();
	}

	/**
	 * Method to process the trackback
	 **/
	function process()
	{
		//Are we accepting?
		if ( !$this->_settings->get('allowTrackbacks') )
			$this->_response(1, 'Sorry, trackbacks are closed for this item.');

		//Get entry ID
		if ( !isset( $_GET['tb_id'] )) {
			$tb_id	= explode('/', $_SERVER['REQUEST_URI']);
			$tb_id	= intval( $tb_id[ count($tb_id) - 1 ] );
		}
		if (!$tb_id) {
			$a = explode('&', $_SERVER['REQUEST_URI']);
			$i = 0;
			while ($i < count($a)) {
			    $b = split('=', $a[$i]);
					if (strtolower($b[0]) == 'id')
						$tb_id = $b[1];
			    $i++;
			}
		}

		if ( !intval( $tb_id ) )
			$this->_response(1, 'I really need an ID for this to work.');
		
		$tb_url  = JArrayHelper::getValue( $_REQUEST, 'url', '' );
		$charset = JArrayHelper::getValue( $_REQUEST, 'charset', '' );

		// These three are stripslashed here so that they can be properly escaped after mb_convert_encoding()
		$title     = stripslashes(JArrayHelper::getValue( $_REQUEST, 'title', '' ));
		$excerpt   = stripslashes(JArrayHelper::getValue( $_POST, 'excerpt', '' ));
		$blog_name = stripslashes(JArrayHelper::getValue( $_REQUEST, 'blog_name', '' ));

		if ($charset)
			$charset = strtoupper( trim($charset) );
		else
			$charset = 'ASCII, UTF-8, ISO-8859-1, JIS, EUC-JP, SJIS';

		// No valid uses for UTF-7
		if ( false !== strpos($charset, 'UTF-7') )
			die;

		// For international trackbacks
		if ( function_exists('mb_convert_encoding') ) {
			$title     	= mb_convert_encoding($title, 'UTF-8', $charset);
			$excerpt   	= mb_convert_encoding($excerpt, 'UTF-8', $charset);
			$blog_name 	= mb_convert_encoding($blog_name, 'UTF-8', $charset);
			$tb_url 	= mb_convert_encoding($tb_url, 'UTF-8', $charset);
		}

		//Spam Filter
		//TODO: Workout bugs 
		if($this->_settings->get('filterTrackbacks'))
		{
			//Get Package
			$objFetchSite 	= & BloggieFactory::load('http');
			$response		= $objFetchSite->get($tb_url, array('timeout' => 60));

			$id = JArrayHelper::getValue( $_REQUEST, 'id' );
			$Itemid = ($Itemid = JArrayHelper::getValue( $_REQUEST, 'Itemid' )) ? '&Itemid='.$Itemid : '';
			if($year = JArrayHelper::getValue( $_REQUEST, 'year' ))
			{
				$archive		= JURI::base().'index.php?option=com_lyftenbloggie&view=entry&&year='.$year.'&month='.JArrayHelper::getValue( $_REQUEST, 'month' ).'&day='.JArrayHelper::getValue( $_REQUEST, 'day' ).'&id='.$id.$Itemid;
			}else if($category = JArrayHelper::getValue( $_REQUEST, 'category' )) {
				$archive		= JURI::base().'index.php?option=com_lyftenbloggie&view=entry&&category='.$category.'&id='.$id.$Itemid;
			}
			$permalink = JRoute::_($archive);

			if ( !$response['error'] && $response['response']['code'] == '200' && $response['body'] )
			{
				if (!preg_match("/<\s*a.*href\s*=[\"'\s]*".$permalink."[\"'\s]*.*>.*<\s*\/\s*a\s*>/i",$response['body']) && !preg_match("/<\s*a.*href\s*=[\"'\s]*".$archive."[\"'\s]*.*>.*<\s*\/\s*a\s*>/i",$response['body'])) {
					$this->_response(1, JText::_('You are Spam!'));
				}
			}
		}

		// Now that mb_convert_encoding() has been given a swing, we need to escape these three
		$title     	= $this->_db->getEscaped($title, true );
		$excerpt   	= $this->_db->getEscaped($excerpt, true );
		$blog_name 	= $this->_db->getEscaped($blog_name, true );
		$tb_url 	= $this->_db->getEscaped($tb_url, true );

		if (empty($title) && empty($tb_url) && empty($blog_name)) {
			$this->setRedirect(JRoute::_('index.php?view=entry&id='. $tb_id, false), $msg );
			return;
		}

		if ( !empty($tb_url) && !empty($title) )
		{
			header('Content-Type: text/xml; charset=utf-8' );

			$title =  $this->_makeExcerpt( $title, 250 ).'...';
			$excerpt = $this->_makeExcerpt( $excerpt, 252 ).'...';
			$datenow =& JFactory::getDate();

			$entry_id 		= (int) $tb_id;
			$author 		= $blog_name;
			$author_email 	= '';
			$author_url 	= $tb_url;
			$content 		= "<strong>$title</strong>\n\n$excerpt";
			$type 			= '2';
			$date			= $datenow->toMySQL();

			//Get make use this isn't a duplicate
			$query = 'SELECT COUNT(id)' .
				' FROM #__bloggies_comments' .
				' WHERE author='.$this->_db->Quote($author, false ) .
				' AND author_url='.$this->_db->Quote($author_url, false )
				;
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				$this->_response(1, $this->_db->getErrorMsg());
			}
			$total = $this->_db->loadResult();

			if ( $total )
				$this->_response(1, 'We already have a ping from that URL for this post.');

			$query = "INSERT INTO `#__bloggies_comments` SET `entry_id`='$entry_id',`author`='$author',`author_email`='$author_email',`author_url`='$author_url',`content`='$content',`type`='$type',`date`='$date'";
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				$this->_response(1, $this->_db->getErrorMsg());
			}
			$this->_response(0);
		}
	}

	/**
	 * Method to respond with error or success XML message
	 **/
	function _response($error = 0, $error_message = '')
	{
		global $mainframe;
		header('Content-Type: text/xml; charset=utf-8' );
		if ($error) {
			echo '<?xml version="1.0" encoding="utf-8"?'.">\n";
			echo "<response>\n";
			echo "<error>1</error>\n";
			echo "<message>$error_message</message>\n";
			echo "</response>";
			die();
		} else {
			echo '<?xml version="1.0" encoding="utf-8"?'.">\n";
			echo "<response>\n";
			echo "<error>0</error>\n";
			echo "</response>";
		}
		$mainframe->close();
	}
}
?>