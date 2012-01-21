<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.arrayhelper');

class CommunityViewInbox extends CommunityView
{

	function _addSubmenu()
	{
		$this->addSubmenuItem('index.php?option=com_community&view=inbox', JText::_('CC INBOX') );
		$this->addSubmenuItem('index.php?option=com_community&view=inbox&task=sent', JText::_('CC SENT'));
		$this->addSubmenuItem('index.php?option=com_community&view=inbox&task=write', JText::_('CC WRITE') );

		$task		= JRequest::getVar( 'task' , '' , 'REQUEST' );
		
		if(! empty($task) && $task == 'read')
		{
			$msgid		= JRequest::getVar('msgid' , '' , 'REQUEST');
			$this->addSubmenuItem('index.php?option=com_community&view=inbox&task=markUnread&msgid='.$msgid, JText::_('CC INBOX MARK UNREAD'), '', true );		
		}		
	}
	
	function showSubmenu(){
		$this->_addSubmenu();
		parent::showSubmenu();
	}
	
	function display($tpl = null)
	{
		$this->inbox();
	}		
	
	function inbox($data)
	{
		if(!$this->accessAllowed('registered'))	return;

	    $mainframe =& JFactory::getApplication();
		$my	=& JFactory::getUser();	
		$config		=& CFactory::getConfig();
		if( !$config->get('enablepm') )
		{
			echo JText::_('CC PRIVATE MESSAGING DISABLED');
			return;
		}

        //page title
		$this->addPathway( JText::_('CC INBOX TITLE') );
		
		$inboxModel 	=& CFactory::getModel( 'inbox' );
		
		$document =& JFactory::getDocument();
		$document->setTitle( JText::_('CC INBOX TITLE') );
		$this->showSubMenu();

		if(empty($data->msg))
		{
		?>
			<div class="column body">
				<div class="community-empty-list"><?php echo JText::_('CC MESSAGE EMPTY'); ?></div>
			</div>		   
		<?php
		}
		else
		{
			CFactory::load( 'libraries' , 'tooltip' );

			for( $i = 0; $i < count( $data->msg ) ; $i++ )
			{
				$row		=& $data->msg[$i];
				
				$user			= CFactory::getUser( $row->from );
				$row->avatar	= $user->getThumbAvatar();
				$row->isUnread     	= ( $row->unRead > 0 ) ? true : false;
				
				$row->from_name	= $user->getDisplayName();
			}
			$tmpl = new CTemplate();
			
			$tmpl->set('totalMessages'	, $inboxModel->getUserInboxCount() );
			$tmpl->set('messages'	, $data->msg );
			$tmpl->set('pagination'	, $data->pagination->getPagesLinks());
            echo $tmpl->fetch('inbox.list');
		}
	}
	
	
	function sent($data)
	{
	    if(!$this->accessAllowed('registered'))	return;
		
		$mainframe =& JFactory::getApplication();
		$my	=& JFactory::getUser();
		$config		=& CFactory::getConfig();
		if( !$config->get('enablepm') )
		{
			echo JText::_('CC PRIVATE MESSAGING DISABLED');
			return;
		}
		
		$this->showSubMenu();
        //page title
        $pathway 	=& $mainframe->getPathway();

		$pathway->addItem( JText::_('CC INBOX TITLE'), CRoute::_('index.php?option=com_community&view=inbox'));
		$pathway->addItem( JText::_('CC SENT MESSAGES TITLE') , '');
		
		$document =& JFactory::getDocument();
		$document->setTitle( JText::_('CC SENT MESSAGES TITLE') );		
		
		if(empty($data->msg)){
		?>		
			<div class="column body">
				<div class="community-empty-list"><?php echo JText::_('CC MESSAGE EMPTY'); ?></div>
			</div>		   
		<?php
		}
			else {	

			$appsLib	=& CAppPlugins::getInstance();
			$appsLib->loadApplications();
			
			for( $i = 0; $i < count( $data->msg ) ; $i++ )
			{
				$row		=& $data->msg[$i];
				
				// onMessageDisplay Event trigger
				$args = array();
				$args[]	=& $row;
				$appsLib->triggerEvent( 'onMessageDisplay' , $args );				
				
				$user			= CFactory::getUser( $row->from );
				$row->from_name	= $user->getDisplayName();
				$row->avatar	= $user->getThumbAvatar();
				$row->isUnread	= false; // for sent item, always set to false.
			}

			$tmpl = new CTemplate();
			$tmpl->set('messages', $data->msg );
			$tmpl->set('pagination', $data->pagination->getPagesLinks());
            echo $tmpl->fetch('inbox.list');
		}
	}
	

	
	function write($data)
	{
		if(!$this->accessAllowed('registered'))return;
		
        $mainframe 	=& JFactory::getApplication();
		$my			=& JFactory::getUser();
		$config		=& CFactory::getConfig();
		
		if( !$config->get('enablepm') )
		{
			echo JText::_('CC PRIVATE MESSAGING DISABLED');
			return;
		}
        //page title
        $pathway 	=& $mainframe->getPathway();

		$pathway->addItem(JText::_('CC INBOX TITLE'), CRoute::_('index.php?option=com_community&view=inbox'));
		$pathway->addItem(JText::_('CC TITLE COMPOSE'), '');
		
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('CC TITLE COMPOSE'));		
										
		$this->showSubMenu();

 		$autoCLink  = CRoute::_(JURI::base().('index.php?option=com_community&view=inbox&task=ajaxAutoName&no_html=1&tmpl=component'));
 		
		$js = '/assets/validate-1.5';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		CAssets::attach($js, 'js');
 		
		$js = '/assets/autocomplete-1.0.js';
		CAssets::attach($js, 'js');
 		
		$js =<<<SHOWJS
		    var yPos;

			joms.jQuery().ready(function(){
				joms.jQuery("#to").autocomplete("$autoCLink", {
					minChars:1, 
					cacheLength:10, 
					selectOnly:1,
					matchSubset:true, 
					matchContains:true, 
					multiple:false,
					formatItem: function(data, i, n, value) {
	            		return data[0];
	        		},
	        		formatResult: function(data, value) {
	            		return data[0];
	 				}
	 			});
			});
SHOWJS;

		$document->addScriptDeclaration($js);
		
		if($data->sent)
		{
			return;
		}
				
		$inboxModel	=& CFactory::getModel( 'inbox' );
		$totalSent	= $inboxModel->getTotalMessageSent( $my->id );
		
		/**
		 * Get friend list
		 */
        $friends 	=& CFactory::getModel( 'friends' );
        
        /**
         * Get ban list
         */            
        $block          =& CFactory::getModel( 'block' );     

		$sorted			= JRequest::getVar( 'sort' , 'latest' , 'GET' );
        $rows			= $friends->getFriends( $my->id , $sorted , false );
        $friendlist     = array();
       
        foreach($rows as $row){
            // Exclude blocked user
            if( !$block->getBlockStatus($my->id,$row->id) ){
                $friendlist[]   = $row;
            }   
        }

		CFactory::load( 'libraries' , 'apps' );
		$app 		=& CAppPlugins::getInstance();
		$appFields	= $app->triggerEvent('onFormDisplay' , array('jsform-inbox-write'));
		$beforeFormDisplay	= CFormElement::renderElements( $appFields , 'before' );
		$afterFormDisplay	= CFormElement::renderElements( $appFields , 'after' );

		$tmpl	= new CTemplate();
		$tmpl->set( 'beforeFormDisplay', $beforeFormDisplay );
		$tmpl->set( 'afterFormDisplay'	, $afterFormDisplay );
        $tmpl->set( 'autoCLink'		, $autoCLink );
		$tmpl->set( 'data' 			, $data);
		$tmpl->set( 'rows'			, $friendlist );
		$tmpl->set( 'totalSent'		, $totalSent );
		$tmpl->set( 'maxSent'		, $config->get('pmperday') );
		$tmpl->set( 'useRealName'	, ($config->get('displayname') == 'name') ? '1' : '0' );
		$html = $tmpl->fetch('inbox.write');

		echo $html;
	}
	
	function reply($data)
	{
	    $mainframe =& JFactory::getApplication();
				
        //page title
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('CC TITLE REPLY'));		
		
		?>
		<fieldset>
		<form name="writeMessageForm" id="writeMessageForm" action="" method="POST">
			<input type="hidden" name="subject" value="RE :">
			<p>
			Reply to: <?php echo $data['reply_to']; ?>
			</p>
			<div>
			<label style="text-align:top;"><?php echo JText::_('CC MESSAGE'); ?> :</label>
			<textarea name="body"></textarea>
			</div>
			
			<div>
			<?php if($data['allow_reply']){ ?>					
			  <input type="hidden" name="action" value="doSubmit"/>			  
			  <input type="submit" value="<?php echo JText::_('CC_BUTTON_SUBMIT');?>"/>
			<?php }//end if ?> 
			<button name="cancel" onclick="javascript: history.go(-1); return false;"><?php echo JText::_('CC_BUTTON_CANCEL'); ?></button>
			</div>
		</form>
		</fieldset>
		<?php
	}	
	
	/**
	 * Show the message reading window
	 */	 		
	function read($data)
	{
		$mainframe =& JFactory::getApplication();
		if(!$this->accessAllowed('registered'))
		{
			return;
		}

        //page title
		$document =& JFactory::getDocument();
		
		$this->showSubMenu();		
		
		$inboxModel = CFactory::getModel('inbox');
		$my			=& JFactory::getUser();
		$msgid		= JRequest::getVar('msgid', 0, 'REQUEST');
		
		if(!$inboxModel->canRead($my->id, $msgid))
		{
			$mainframe =& JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('CC PERMISSION DENIED'), 'error');
			return;
		}

        $pathway 	=& $mainframe->getPathway();
		$pathway->addItem( $this->escape( JText::_('CC INBOX TITLE') ), CRoute::_('index.php?option=com_community&view=inbox') );

		$parentData	= '';
		$html		= '';
		$messageHeading	= '';

		if(! empty($data->messages))
		{
			$document	=& JFactory::getDocument();
			$parentData	= $data->messages[0];
			$pathway->addItem( JText::sprintf('CC VIEW MESSAGE TITLE' , $this->escape( $parentData->subject ) ) );
			$document->setTitle(JText::sprintf('CC VIEW MESSAGE TITLE' , $this->escape( $parentData->subject ) ));
			
			require_once( COMMUNITY_COM_PATH.DS.'libraries' . DS . 'apps.php' );			
			$appsLib	=& CAppPlugins::getInstance();
			$appsLib->loadApplications();
				
			foreach ($data->messages as $row)
			{
				// onMessageDisplay Event trigger
				$args = array();
				$args[]	=& $row;
				$appsLib->triggerEvent( 'onMessageDisplay' , $args );
				$user	= CFactory::getUser($row->from);

				//construct the delete link
		        $deleteLink = CRoute::_('index.php?option=com_community&view=inbox&task=remove&msgid='.$row->id);
				$authorLink	= CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->id );
							
				$tmpl = new CTemplate();
				$tmpl->set( 'user',  $user );
				$tmpl->set( 'msg', $row );
				$tmpl->set( 'isMine' 	, COwnerHelper::isMine($my->id, $user->id));
				$tmpl->set( 'removeLink', $deleteLink);
				$tmpl->set( 'authorLink'	, $authorLink );
				$html .= $tmpl->fetch( 'inbox.message' );
			}
			
			$userId		= $my->id == $parentData->from ? $parentData->to : $parentData->from;
			$recipient	= CFactory::getUser( $userId );
			$myLink		= CRoute::_('index.php?option=com_community&view=profile&userid=' . $my->id );
			$userLink	= CRoute::_('index.php?option=com_community&view=profile&userid=' . $userId );
			
			$messageHeading	= JText::sprintf('CC MSG BETWEEN YOU AND USER' , $myLink , $userLink , $recipient->getDisplayName() );
		} 
		else 
		{
			$html	= '<div class="column body">'
					. '	<div class="text">' . JText::_('CC MESSAGE EMPTY') . '</div>'
					. '</div>';
					
		}//end if

		$tmplMain	= new CTemplate();
		$tmplMain->set( 'messageHeading'	, $messageHeading );
		$tmplMain->set( 'messages',  $data->messages );
		$tmplMain->set( 'parentData',  $parentData );
		$tmplMain->set( 'htmlContent',  $html );
		
		echo $tmplMain->fetch( 'inbox.read' );		
	
	}//end messages
	
	function successPage(){
	
        //page title
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('CC TITLE COMPOSE'));		
		
		
		$msg = JText::_('CC MESSAGE SENT');
		
		?>	
		<div class="column body">
			<div class="text"><?php echo $msg ?></div>
		</div>
		<form>
		    <input type="hidden" name="option" value="com_community">
		    <input type="hidden" name="view" value="inbox">
		    <input type="hidden" name="task" value="write">
			<div>
			    <input type="submit" value="<?php echo JText::_('CC_BUTTON_DONE');?>"/>
			</div>
		</form>	
	    <?php
	}
}
