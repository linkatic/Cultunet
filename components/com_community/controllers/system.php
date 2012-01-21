<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CommunitySystemController extends CommunityBaseController
{
	public function ajaxReport( $reportFunc , $pageLink )
	{
		$objResponse    = new JAXResponse();
		$config			= CFactory::getConfig();
		
		$reports		= JString::trim( $config->get( 'predefinedreports' ) );
		
		$reports		= empty( $reports ) ? false : explode( "\n" , $reports );

		$html = '';

		$argsCount		= func_num_args();

		$argsData		= '';
		
		if( $argsCount > 1 )
		{
			
			for( $i = 2; $i < $argsCount; $i++ )
			{
				$argsData	.= "\'" . func_get_arg( $i ) . "\'";
				$argsData	.= ( $i != ( $argsCount - 1) ) ? ',' : '';
			}
		}

		ob_start();
?>
		<form id="report-form" name="report-form" action="" method="post">
			<table class="cWindowForm" cellspacing="1" cellpadding="0">
				<tr>
					<td class="cWindowFormKey"><?php echo JText::_('CC PREDEFINED REPORTS');?></td>
					<td class="cWindowFormVal">
						<select id="report-predefined" onchange="if(this.value!=0) joms.jQuery('#report-message').val( this.value ); else joms.jQuery('#report-message').val('');">
							<option selected="selected" value="0"><?php echo JText::_('CC SELECT PREDEFINED REPORTS'); ?></option>
							<?php
							if( $reports )
							{
								foreach( $reports as $report )
								{
							?>
								<option value="<?php echo $report;?>"><?php echo $report; ?></option>
							<?php
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="cWindowFormKey"><?php echo JText::_('CC REPORT MESSAGE');?><span id="report-message-error"></span></td>
					<td class="cWindowFormVal"><textarea id="report-message" name="report-message" rows="3"></textarea></td>
				</tr>
				<tr class="hidden">
					<td class="cWindowFormKey"></td>
					<td class="cWindowFormVal"><input type="hidden" name="reportFunc" value="<?php echo $reportFunc; ?>" /></td>
				</tr>
			</div>
		</form>
<?php
		$html	.= ob_get_contents();
		ob_end_clean();
		
		ob_start();
?>
		<button class="button" onclick="joms.report.submit('<?php echo $reportFunc;?>','<?php echo $pageLink;?>','<?php echo $argsData;?>');" name="submit">
		<?php echo JText::_('CC BUTTON SEND');?>
		</button>
		<button class="button" onclick="javascript:cWindowHide();" name="cancel">
		<?php echo JText::_('CC BUTTON CANCEL');?>
		</button>
<?php
		$action	= ob_get_contents();
		ob_end_clean();

		
		if( !COMMUNITY_FREE_VERSION ) {
			// Change cWindow title
			$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC REPORT THIS'));
			$objResponse->addAssign('cWindowContent', 'innerHTML', $html );
			$objResponse->addScriptCall('cWindowActions', $action);
			$objResponse->addScriptCall('cWindowResize', 200);
		}else{
			$tmpl		= new CTemplate();
			$html		= $tmpl->fetch( 'freeversion.ajax' );
			$height		= 250;
			$buttons    = '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('CC BUTTON CANCEL') . '"/>';
	
			$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC REPORT THIS'));
			$objResponse->addAssign('cWindowContent', 'innerHTML', $html );
			$objResponse->addScriptCall('cWindowActions', $buttons);
			$objResponse->addScriptCall('cWindowResize', $height + 220);
		}
		
		return $objResponse->sendResponse();
	}
	
	public function ajaxSendReport()
	{
		$reportFunc		= func_get_arg( 0 );
		$pageLink		= func_get_arg( 1 );
		$message		= func_get_arg( 2 );

		$argsCount		= func_num_args();
		$method			= explode( ',' , $reportFunc );

		$args			= array();
		$args[]			= $pageLink;
		$args[]			= $message;
		
		for($i = 3; $i < $argsCount; $i++ )
		{
			$args[]		= func_get_arg( $i );
		}

		// Reporting should be session sensitive
		// Construct $output
		$uniqueString	= md5($reportFunc.$pageLink);
		$session = JFactory::getSession();

		
		if( $session->has('action-report-'. $uniqueString))
		{
			$output	= JText::_('CC REPORT ALREADY SENT');
		}
		else
		{
			if( is_array( $method ) && $method[0] != 'plugins' )
			{
				$controller	= JString::strtolower( $method[0] );
				
	 			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . 'controller.php' );
	 			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . $controller . '.php' );
	
				$controller	= JString::ucfirst( $controller );
	 			$controller	= 'Community' . $controller . 'Controller';
	 			$controller	= new $controller();
	 			
	 			
	 			$output		= call_user_func_array( array( &$controller , $method[1] ) , $args );
			}
			else if( is_array( $method ) && $method[0] == 'plugins' )
			{
				// Application method calls
				$element	= JString::strtolower( $method[1] );
				require_once( JPATH_PLUGINS . DS . 'community' . DS . $element . '.php' );
				$className	= 'plgCommunity' . JString::ucfirst( $element );
				$output		= call_user_func_array( array( $className , $method[2] ) , $args );
			}
		}
		$session->set('action-report-'. $uniqueString, true);
		
		// Construct the action buttons $action
		ob_start();
?>
		<button class="button" onclick="javascript:cWindowHide();" name="cancel">
		<?php echo JText::_('CC BUTTON CLOSE');?>
		</button>
<?php
		$action	= ob_get_contents();
		ob_end_clean();
		
		// Construct the ajax response
		$objResponse	= new JAXResponse();
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC REPORT SENT'));
		$objResponse->addAssign('cWindowContent', 'innerHTML', $output);
		$objResponse->addScriptCall('cWindowActions', $action);
		$objResponse->addScriptCall('cWindowResize', 100);
		
		return $objResponse->sendResponse();
	}
	
	public function ajaxEditWall( $wallId , $editableFunc )
	{
		$objResponse	= new JAXResponse();
		$wall			=& JTable::getInstance( 'Wall' , 'CTable' );
		$wall->load( $wallId );
		
		CFactory::load( 'libraries' , 'wall' );
		$isEditable		= CWall::isEditable( $editableFunc , $wall->id );
		
		if( !$isEditable )
		{
			$objResponse->addAlert(JText::_('CC NOT ALLOWED TO EDIT') );
			return $objResponse->sendResponse();
		}

		CFactory::load( 'libraries' , 'comment' );
		$tmpl			= new CTemplate();
		$message		= CComment::stripCommentData( $wall->comment );
		$tmpl->set( 'message' , $message );
		$tmpl->set( 'editableFunc' , $editableFunc );
		$tmpl->set( 'id'	, $wall->id );
		
		$content		= $tmpl->fetch( 'wall.edit' );
		
		$objResponse->addScriptCall( 'joms.jQuery("#wall_' . $wallId . ' div.loading").hide();');
		$objResponse->addAssign( 'wall-edit-container-' . $wallId , 'innerHTML' , $content );
		
		return $objResponse->sendResponse();
	}
	
	public function ajaxUpdateWall( $wallId , $message , $editableFunc )
	{
		$wall			=& JTable::getInstance( 'Wall' , 'CTable' );
		$wall->load( $wallId );
		$objResponse	= new JAXresponse();
		
		if( empty($message) )
		{
			$objResponse->addScriptCall( 'alert' , JText::_('CC EMPTY MESSAGE') );
			return $objResponse->sendResponse();
		}
		

		CFactory::load( 'libraries' , 'wall' );
		$isEditable		= CWall::isEditable( $editableFunc , $wall->id );
		
		if( !$isEditable )
		{
			$objResponse->addAssign( 'cWindowContent' , 'innerHTML' , JText::_('CC NOT ALLOWED TO EDIT') );
			return $objResponse->sendResponse();
		}
			
		CFactory::load( 'libraries' , 'comment' );
		
		// We don't want to touch the comments data.
		$comments		= CComment::getRawCommentsData( $wall->comment );
		$wall->comment	= $message;
		$wall->comment	.= $comments;
		$my				= CFactory::getUser();
		$data			= CWallLibrary::saveWall( $wall->contentid , $wall->comment , $wall->type , $my , false , $editableFunc , 'wall.content' , $wall->id );		
		
		$objResponse	= new JAXResponse();
		
		$objResponse->addScriptCall('joms.walls.update' , $wall->id , $data->content );

		return $objResponse->sendResponse();
	}
}
