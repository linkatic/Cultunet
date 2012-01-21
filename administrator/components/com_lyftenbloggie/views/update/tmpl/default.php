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
global $mainframe;
?>
<?php echo BlogSystemFun::getSideMenu('1', 'Settings');
if($this->newVersion) { ?>
<form action="index.php" method="post" name="updateForm">
	<div id='update-nag'><?php echo JText::sprintf('UPDATE AVAILABLE NAG', $this->update['version']); ?>! ( <a href="#" onclick="javascript:document.updateForm.submit();"><?php echo JText::_('AUTO-UPDATE'); ?></a> | <a href="<?php echo $this->update['url']; ?>"><?php echo JText::sprintf('MANUAL UPDATE', $this->update['version']); ?></a> )</div>
	<input type="hidden" name="upgrade[]" value="<?php echo $this->update['upgrade']; ?>" />
	<input type="hidden" name="task" value="update" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="update" />
	<input type="hidden" name="view" value="update" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php } ?>
<h2 class="settingstitle"><?php echo JText::_('PATCHES'); ?></h2>
<form action="index.php" method="post" name="adminForm">
	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="1%"></th>
			<th width="15%" colspan="2"><?php echo Jtext::_('RELEASE DATE'); ?></th>
			<th class="title"><?php echo Jtext::_('ABOUT PATCH'); ?></th>
			<th width="15%"><?php echo Jtext::_('HOMEPAGE'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		$i = 0;
		if(isset($this->patches))
		{
			$k = 0;
			foreach($this->patches as $patch)
			{
				if(!in_array($patch['id'], $this->patched))
				{
					$install	= '<a href="#" onclick="javascript:document.adminForm.upgrade'.$patch['id'].'.checked = true;submitbutton(\'update\');">'.$patch['release'].'</a>';
					$url 		= ($patch['url']) ? '<a href="'.$patch['url'].'" target="_blank">'.JText::_('VISIT').'</a>' : '';
					$manual		= ($patch['homepage']) ? '<a href="'.base64_decode($patch['homepage']).'" target="_blank">'.JText::_('MANUAL DOWNLOAD').'</a>' : $url;
					?>
					<tr class="row<?php echo $k; ?>">
						<td width="7" align="center"><input id="upgrade<?php echo $patch['id']; ?>" name="upgrade[<?php echo $patch['id']; ?>]" value="<?php echo base64_encode($patch['install']); ?>" type="radio"></td>
						<td style="width:16px;padding:0;marin:0;" align="center"><img src="<?php echo ($patch['critical']) ? BLOGGIE_ASSETS_URL.'/images/critical.png' : BLOGGIE_ASSETS_URL.'/images/noncritical.png'; ?>" alt="" /></td>
						<td align="center"><?php echo $install; ?></td>
						<td align="center"><?php echo $patch['about']; ?></td>
						<td align="center"><?php echo $url; ?></td>
					</tr>
					<?php
					$i++;
					$k = 1 - $k;
				}
			}
		}
		if($i == 0){ ?>
		<tr>
			<td align="center" colspan="7"><?php echo JText::_( 'THERE ARE NO PATCHES AVAILABLE' ); ?></td>
		</tr>
		<?php } ?>
	</tbody>
	</table>
	<input type="hidden" name="boxchecked" value="0" />	
	<input type="hidden" name="patch" value="1" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="update" />
	<input type="hidden" name="view" value="update" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	</td>
</tr>
</table>