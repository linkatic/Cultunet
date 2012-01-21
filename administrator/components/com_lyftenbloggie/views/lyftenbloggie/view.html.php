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

jimport( 'joomla.application.component.view');

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieViewLyftenBloggie extends JView
{
	/**
	 * Creates the Entrypage
	 *
	 * @since 1.0
	 */
	function display( $tpl = null )
	{
		global $mainframe;
		
		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$document	= & JFactory::getDocument();
		$pane   	= & JPane::getInstance('sliders');
		$template	= $mainframe->getTemplate();
		$settings	=& BloggieSettings::getInstance();
		$update 	= 0;
		
		// Get data from the model
		$openquest	= & $this->get( 'Openquestions' );
		$unapproved = & $this->get( 'Pending' );

		//build toolbar
		JToolBarHelper::title( 'Lyften Bloggie', 'lyftenbloggie' );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//updatecheck
		if($settings->get('checkUpdates', 0))
		{
			//Get update data from the model
			$current    = BloggieAdmin::getUpdateData('simple');

			$build		= & $this->get( 'LocalBuild' );
			$version	= & $this->get( 'LocalVersion' );
			$update 	= (empty($current)) ? 0 : 1;

			$this->assign( 'fversion', 	BLOGGIE_COM_VERSION );
			$this->assign( 'version', 	$version );
			$this->assign( 'build', 	$build );
			$this->assign( 'current', 	$current );
		}

		// Require the base helper
		require_once (JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helper.php');
		
		//Get data from the model
		$genstats 	= & $this->get( 'Generalstats' );
		$popular	= & $this->get( 'Popular' );
		if($settings->get('incomingLinks', 0))
		{
			$inlinks = & $this->get( 'IncomingLinks' );
			$this->assignRef('inlinks'	, $inlinks);
		}

		$this->assignRef('genstats'		, $genstats);		
		$this->assignRef('popular'		, $popular);
		$this->assignRef('pane'			, $pane);
		$this->assignRef('unapproved'	, $unapproved);
		$this->assignRef('openquest'	, $openquest);
		$this->assignRef('update'		, $update);
		$this->assignRef('template'		, $template);

		parent::display($tpl);

	}
	
	/**
	 * Creates the buttons view
	 **/
	function addIcon( $image , $view, $text )
	{
		$lang		=& JFactory::getLanguage();
		$link		= 'index.php?option=com_lyftenbloggie&view=' . $view;
?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image', 'administrator/components/com_lyftenbloggie/assets/images/icon-48-'.$image.'.png' , NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
<?php
	}	

	/**
	 * Creates the Maintenance buttons view
	 **/
	function addMaintIcon( $image , $view, $text )
	{
		$lang		=& JFactory::getLanguage();
		$link		= 'index.php?option=com_lyftenbloggie&controller=lyftenbloggie&task=' . $view;
?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image', 'administrator/components/com_lyftenbloggie/assets/images/icon-48-'.$image.'.png' , NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
<?php
	}	
}
?>