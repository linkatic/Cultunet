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

jimport( 'joomla.application.component.view' );

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0.0
 */
class LyftenBloggieViewSettings extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$document	= & JFactory::getDocument();
		$tabs 		= & JPane::getInstance('tabs');
		$acl		=& JFactory::getACL();
		$settings	=& BloggieSettings::getInstance();


		// Lets get some HELP!!!!
		require_once (JPATH_COMPONENT.DS.'helper.php');
		
		JHTML::_('behavior.tooltip');
		
		//add stuff to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');	
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');	
		
		//create the toolbar		
		JToolBarHelper::title( JText::_( 'SETTINGS' ), 'lbconfig' );
		JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::divider();
        JToolBarHelper::cancel();
		JToolBarHelper::divider();
		JToolBarHelper::help( 'settings.html', true );
        JToolBarHelper::spacer();

		//Create posting control HTML list
		$postingcontrol		= explode(",",$settings->get('PostingControl'));
		array_walk($postingcontrol,"trim");
		
		$gtree = $acl->get_group_children_tree( null, 'USERS', false );
		$lists['PostingControl']  		= '<select name="PostingControl[]" multiple="multiple" size="7">';
		$sel 							= (in_array('0',$postingcontrol))?' selected="selected"':'';
		$lists['PostingControl']  		.= '<option value="0"' . $sel . '>None</option>';		
		foreach($gtree as $row) {
			$sel 		= (in_array($row->value,$postingcontrol))?' selected="selected"':'';
			$lists['PostingControl']  	.= '	<option value="'.$row->value.'"' . $sel . '>'.$row->text.'</option>';
		}
		$lists['PostingControl']  		.= '</select>';

		//Create publish control HTML list
		$publishcontrol		= explode(",",$settings->get('PublishControl'));
		array_walk($publishcontrol,"trim");
		
		$gtree = $acl->get_group_children_tree( null, 'USERS', false );
		$lists['PublishControl']  		= '<select name="PublishControl[]" multiple="multiple" size="7">';
		$sel 							= (in_array('0',$publishcontrol))?' selected="selected"':'';
		$lists['PublishControl']  		.= '<option value="0"' . $sel . '>None</option>';
		foreach($gtree as $row) {
			$sel 		= (in_array($row->value,$publishcontrol))?' selected="selected"':'';
			$lists['PublishControl']  	.= '<option value="'.$row->value.'"' . $sel . '>'.$row->text.'</option>';
		}
		$lists['PublishControl']  		.= '</select>';

		// build the html for Avatar
		$avatarsys = $this->get('AvatarSystems');
		$lists['avatarsys'] = JHTML::_('select.genericlist', $avatarsys, 'settings[avatarUsed]', 'class="inputbox"', 'id', 'title', $settings->get('avatarUsed', '1') );
		unset($avatarsys);

		// build the html for Comment Systems
		$commentsys = $this->get('CommentSystems');
		$lists['commentsys'] = JHTML::_('select.genericlist', $commentsys, 'settings[typeComments]', 'class="inputbox"', 'id', 'title', $settings->get('typeComments', '1') );
		unset($commentsys);

		// build the html for Comment Systems
		$editors = $this->get('Editors');
		$lists['editors'] = JHTML::_('select.genericlist', $editors, 'settings[frontEditor]', 'class="inputbox"', 'id', 'title', $settings->get('frontEditor', 'joomla') );	
		unset($editors);

		//assign data to template
		$this->assignRef('lists'				, $lists);
		$this->assignRef('tabs'					, $tabs);
		$this->assignRef('settings'				, $settings);

		parent::display($tpl);
	}
	
	/**
	 * Creates the buttons view
	 **/
	function addIcon( $image , $view, $text)
	{
		$db    = & JFactory::getDBO();
		$query = 'SELECT value'
				. ' FROM #__jomprod_settings'
				. ' WHERE name = '.$db->Quote($view)
			;
		$db->setQuery($query);
		$last = $db->loadResult();
		$last = ($last)?JHTML::_('date', $last, '%b %d, %Y'):JText::_('NEVER');
	?>
		<div style="float:left'; ?>;">
			<div class="icon">
				<a href="#" onclick="javascript:doTask('<?php echo $view; ?>');return false;">
					<?php echo JHTML::_('image', 'administrator/components/com_brezza/assets/images/icon-48-'.$image.'.png' , NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
					<br><p style="font-size:10px;background:#ccc;float:left;margin:0 auto;text-align:center;width:100%;"><?php echo JText::_('LAST').': '.$last; ?></p>
			</div>
		</div>
	<?php
	}
}
