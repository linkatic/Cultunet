<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.arrayhelper');
jimport( 'joomla.html.html');

class CommunityViewRegister extends CommunityView
{

	function register($data = null)
	{
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'profile.php');
				
		$mainframe	=& JFactory::getApplication();
		$my 		= CFactory::getUser();
		
		$config		=& CFactory::getConfig();
		$document 	=& JFactory::getDocument();
		$document->setTitle(JText::_('CC REGISTER NEW'));		
		
		// Hide this form for logged in user
		if($my->id) {
			$mainframe->enqueueMessage(JText::_('CC ALREADY USER'), 'warning');
			return;	
		}
		
		// If user registration is not allowed, show 403 not authorized.
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration') == '0')		
		{
			//show warning message						
			$this->addWarning(JText::_( 'CC REGISTRATION DISABLED' ));
			return;
		}

		$fields 	= array();	
		$empty_html = array();
		$post 		= JRequest::get('post');

		$data								= array();
		$data['fields']						= $fields;
		$data['html_field']['jsname'] 		= (empty($post['jsname'])) ? '' : $post['jsname'];
		$data['html_field']['jsusername']	= (empty($post['jsusername'])) ? '' : $post['jsusername'];
		$data['html_field']['jsemail'] 		= (empty($post['jsemail'])) ? '' : $post['jsemail'];

		$js = JURI::root(). 'components/com_community/assets/validate-1.5';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		$document->addScript($js);
		
		// @rule: Load recaptcha if required.
		CFactory::load( 'helpers' , 'recaptcha' );
		$recaptchaHTML	= getRecaptchaHTMLData();
		
		$tmpl			= new CTemplate();
		$tmpl->set( 'data' 		, $data );
		$tmpl->set( 'recaptchaHTML' , $recaptchaHTML );
		$tmpl->set( 'config'	, $config );
		
		$content	= $tmpl->fetch( 'register.index' );
		
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();
				
		$args		= array(&$content);
		$appsLib->triggerEvent( 'onUserRegisterFormDisplay' , $args );				
		
		echo $content;
	}
	
	function registerProfile($data = null)
	{
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'profile.php');
		jimport( 'joomla.utilities.arrayhelper' );
		jimport( 'joomla.utilities.date' );
	
		$mainframe	=& JFactory::getApplication();	
		$document 	=& JFactory::getDocument();		
		$document->setTitle(JText::_('CC REGISTER NEW'));
		
		$model 		=& CFactory::getModel('profile');		
		
		
		//get all published custom field for profile
		//personalizando según el tipo de usuario
		
		$usertype = JRequest::getVar('usertype');
		$filter = array('published'=>'1', 'registration' => '1', 'usertype' => $usertype);

		$fields =& $model->getAllFields($filter);
		
		$empty_html = array();
		$post = JRequest::get('post');
						
		// Bind result from previous post into the field object
		if(! empty($post)){
		
			foreach($fields as $group)
			{
			    $field = $group->fields;
			    for($i = 0; $i <count($field); $i++)
				{
	 				$fieldid    = $field[$i]->id;
	 				$fieldType  = $field[$i]->type;
	 				
					if(!empty($post['field'.$fieldid]))
					{
						if(is_array($post['field'.$fieldid]))
						{
						   if($fieldType != 'date')
						   {
						        $values = $post['field'.$fieldid];
						        $value  = '';
								foreach($values as $listValue)
								{
									$value	.= $listValue . ',';
								}
						        $field[$i]->value = $value;
						   }
						   else 
						   {
						       $field[$i]->value = $post['field'.$fieldid];
						   }
						} 
						else 
						{
						    $field[$i]->value = $post['field'.$fieldid];						
						}
					}
                }
			}
			
		} 
		else 
		{
			foreach($fields as $key=>$val)
			{
				for($j =0; $j < count($fields[$key]->fields); $j++)
				{
					$fieldid = $fields[$key]->fields[$j]->id;
					if(!empty($post['field'.$fieldid]))
					{
						$empty_html['field'.$fieldid] = '';
					}
				}
			}
		}
		
		$data = array();
		$data['fields'] 	= $fields;
		$data['html_field'] = (empty($post)) ? $empty_html : $post;
		
		
		$config		=& CFactory::getConfig();		
		$js = JURI::root(). 'components/com_community/assets/validate-1.5';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		$document->addScript($js);

		$tmpl	= new CTemplate();
		$tmpl->set( 'fields' , $fields );
		$tmpl->set( 'usertype' , $post['usertype']);
		
		echo $tmpl->fetch( 'register.profile' );
	}
	
	/**
	 * Display Upload avatar form for user
	 **/	 	
	function registerAvatar()
	{
		$mainframe =& JFactory::getApplication();

        //retrive the current session.		
        $mySess =& JFactory::getSession();
        $user   = $mySess->get('tmpUser','');		
		
		$my			= CFactory::getUser($user->id);
		$firstLogin	= true;
		
		$uploadLimit = ini_get('upload_max_filesize');
		$uploadLimit = JString::str_ireplace('M', ' MB', $uploadLimit);		
		
		// Load the toolbar
		$this->showSubmenu();
		$document = & JFactory::getDocument ();
		$document->setTitle ( JText::_ ( 'CC EDIT AVATAR' ) );
		
		$tmpl	  = new CTemplate();
		$skipLink = CRoute::_('index.php?option=com_community&view=register&task=registerSucess');
		
		$tmpl->set( 'my' , $my );
		$tmpl->set( 'uploadLimit' , $uploadLimit );
		$tmpl->set( 'firstLogin' , $firstLogin );
		$tmpl->set( 'skipLink' , $skipLink );
		
		echo $tmpl->fetch( 'profile.uploadavatar' );
	}	
	
    function successPage($data = null){
	
        //page title
		$document =& JFactory::getDocument();				
		$document->setTitle(JText::_('CC USER REGISTERD'));
				
		$uri	= CRoute::_('index.php?option=com_community&view=frontpage');
		
        $usersConfig    = &JComponentHelper::getParams( 'com_users' );
        $useractivation = $usersConfig->get( 'useractivation' );
		
		// Everything went fine, set relevant message depending upon user activation state and display message
		if ( $useractivation == 1 ) {
			$msg  = JText::_( 'CC_REG_COMPLETE_ACTIVATE_REQUIRED' );
		} else {
			$msg = JText::_( 'CC_REG_COMPLETE' );
		}
												
		ob_start();
		?>
		
		<div class="column body">
			<div class="text"><?php echo $msg ?></div>
			<br />
			<div><a href="<?php echo $uri; ?>"><?php echo JText::_('CC BACK HOME'); ?></a></div>
		</div>		
		
	    <?php
		$content	= ob_get_contents();
		ob_end_clean();
		
		echo $content;	    
	}
	
	function activation()
	{
		$config		=& CFactory::getConfig();
		$document 	=& JFactory::getDocument ();
		$document->setTitle ( JText::_ ( 'CC RESEND ACTIVATION' ) );
						
		$js = JURI::root(). 'components/com_community/assets/validate-1.5';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';		
		$document->addScript($js);		
		
		$tmpl	  = new CTemplate();
		echo $tmpl->fetch( 'register.activation' );		
	}	

}
