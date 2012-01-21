<?php
defined('_JEXEC') or die();
?>
<a href="<?php echo $userInfo['profile_url'];?>" target="_blank"><img src="<?php echo $userInfo['pic_square'];?>" border="0" style="border: 1px solid #000; float: left; margin: 5px;line-height:0;" /></a>
<p><?php echo JText::sprintf('CC FACEBOOK CONNECT DESCRIPTION', $userInfo['name'] );?></p>
<div style="margin-top: 30px; padding: 10px 0px">
	<div style="font-weight:700;font-size: 14px;"><?php echo JText::_('CC I AM CURRENTLY');?></div>
	<label class="lblradio-block" style="font-weight: 700;"><input type="radio" value="1" name="membertype" checked /><?php echo JText::_('CC A NEW USER');?></label>
	<div style="margin-left: 20px;"><?php echo JText::_('CC NEW MEMBER DESCRIPTION');?></div>
	<label class="lblradio-block" style="font-weight: 700;"><input type="radio" value="2" name="membertype" /><?php echo JText::_('CC MEMBER OF SITE');?></label>
	<div style="margin-left: 20px;"><?php echo JText::_('CC EXISTING SITE MEMBER DESCRIPTION');?></div>
	<div style="color: red;margin-top:20px;"><?php echo JText::_('CC LINKING NOTICE');?></div>
</div>