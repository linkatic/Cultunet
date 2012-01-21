<?php
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
<script type="text/javascript">
	window.addEvent('load', function()
	{
		FB_RequireFeatures(["XFBML"], function() {
			FB.Facebook.init( "<?php echo $config->get('fbconnectkey');?>" , "index.php?option=com_community&view=connect&task=receiver");
		});
	});
</script>
<?php
$content	= '<fb:name uid="' . $facebook->getUserId() . '" useyou="false" /> is a member of developers.jomsocial.com and would like to share that experience with you. To register, simply click on the "Register" button below.<fb:req-choice url="http://developers.jomsocial.com/index.php?option=com_community&view=register" label="Register" />'
?>
<fb:serverfbml>
	<script type="text/fbml">
		<fb:fbml>
			<fb:request-form action="<?php echo CRoute::_('index.php?option=com_community&view=connect&task=invite');?>" method="post" type="JomSocial Developers Site" content="<?php echo JText::_('Come check out this site!');?> <?php echo htmlentities($content,ENT_COMPAT,'UTF-8');?>">
				<fb:multi-friend-selector cols="4" showborder="true" actiontext="<?php echo JText::_('Invite your friends from Facebook');?>">
			</fb:request-form>
		</fb:fbml>
	</script>
</fb:serverfbml>