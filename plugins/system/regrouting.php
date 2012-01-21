<?php
/**
 * Registration Routing for JomSocial 1.6.x
 * 
 **/
defined('_JEXEC') or die('Restricted access to this plugin'); 

// Import library dependencies
jimport('joomla.event.plugin');

class plgSystemRegrouting extends JPlugin {

    /**
    * Constructor
    *
    * For php4 compatability we must not use the __constructor as a constructor for
    * plugins because func_get_args ( void ) returns a copy of all passed arguments
    * NOT references.  This causes problems with cross-referencing necessary for the
    * observer design pattern.
    */

    function plgSystemRegrouting(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
    * Plugin method with the same name as the event will be called automatically.
    */
    function onAfterRoute() {
            global $mainframe;
            $option = JRequest::getCmd('option');
            $task = JRequest::getCmd('task');
            $view = JRequest::getCmd('view');
            $userid= JRequest::getVar('userid');
            $Itemid= JRequest::getVar('Itemid');

            $displaymenu  = $this->params->get('displaymenu', 0);

            if ($option=='com_user' && $view=='register'){
               $mainframe->redirect(JRoute::_("index.php?option=com_community&view=register",false));
            }
            
            if($displaymenu==1 && $userid==0)
            {
                if ($option=='com_community' && $view=='profile')
                {
                    $user =& JFactory::getUser();
                    if($user->id > 0)
                        $mainframe->redirect(JRoute::_('index.php?option=com_community&view=profile&task='.$task.'&userid='.$user->id.'&Itemid='.$Itemid,false));
                }
            }
    }
}