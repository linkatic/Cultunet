<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

global $mainframe;

?>

<script type="text/javascript" src="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/include/admin_menu/sdmenu/sdmenu.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/include/admin_menu/sdmenu/sdmenu.css" />

	<script type="text/javascript">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.oneSmOnly = true;  // One expanded submenu at a time
		myMenu.init();
	};
	// ]]>
	</script>

    <!-- <div>
		<img src="components/com_jsjobs/include/images/jsjobs_logo.png" width="175">
	</div> -->
	<div style="float: left" id="my_menu" class="sdmenu">
      <div class="collapsed">
        <span><?php echo JText::_('JS_ADMIN'); ?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=controlpanel"><?php echo JText::_('JS_CONTROL_PANEL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobtypes"><?php echo JText::_('JS_JOB_TYPES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobstatus"><?php echo JText::_('JS_JOB_STATUS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=shifts"><?php echo JText::_('JS_SHIFTS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=highesteducations"><?php echo JText::_('JS_HIGHEST_EDUCATIONS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=conf"><?php echo JText::_('JS_CONFIGURATIONS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=info"><?php echo JText::_('JS_INFORMATION'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=updates"><?php echo JText::_('JS_ACTIVATE_UPDATES'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_COMPANIES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=companies"><?php echo JText::_('JS_COMPANIES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=companiesqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=userfields&ff=1"><?php echo JText::_('JS_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=1"><?php echo JText::_('JS_FIELDS'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_JOBS');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobs"><?php echo JText::_('JS_JOBS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=appliedresumes"><?php echo JText::_('JS_APPLIED_RESUME'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=userfields&ff=2"><?php echo JText::_('JS_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=2"><?php echo JText::_('JS_FIELDS'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_RESUME');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=empapps"><?php echo JText::_('JS_RESUME'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=appqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=3"><?php echo JText::_('JS_FIELDS'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_CATEGORIES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=categories"><?php echo JText::_('JS_CATEGORIES'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_SALARYRANGE');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=salaryrange"><?php echo JText::_('JS_SALARYRANGE'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_USER_ROLES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=roles"><?php echo JText::_('JS_ROLES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=users"><?php echo JText::_('JS_USERS'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_EMAIL_TEMPLATES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=cm-ap"><?php echo JText::_('JS_COMPANY_APPROVAL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=cm-rj"><?php echo JText::_('JS_COMPANY_REJECTING'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ob-ap"><?php echo JText::_('JS_JOB_APPROVAL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ob-rj"><?php echo JText::_('JS_JOB_REJECTING'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=rm-ap"><?php echo JText::_('JS_RESUME_APPROVAL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=rm-rj"><?php echo JText::_('JS_RESUME_REJECTING'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ba-ja"><?php echo JText::_('JS_JOB_APPLY'); ?></a>
      </div>

	  
      <div class="collapsed">
        <span><?php echo JText::_('JS_COUNTRIES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=countries"><?php echo JText::_('JS_COUNTRIES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=loadaddressdata"><?php echo JText::_('JS_LOAD_ADDRESS_DATA'); ?></a>
      </div>
    </div>


