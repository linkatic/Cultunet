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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Insert Image</title>
  <link rel="stylesheet" href="<?php echo JURI::base(); ?>components/com_lyftenbloggie/assets/media.css" type="text/css" />

</head>
<body class="contentpane">
<table style="height: 100%; width: 100%; table-layout: fixed;" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td class="borderBlock"><h2><?php echo JText::_('INSERT '.$this->type); ?></h2></td>
		</tr>
		<tr>
			<td style="height: 100%; width: 100%; vertical-align: top;">
				<iframe src="index.php?option=com_lyftenbloggie&task=viewImages&format=raw&layout=thumbs&type=<?php echo $this->type; ?>" style="height: 100%; width: 100%;" frameborder="0" id="folderframe" name="folderframe"></iframe>
			</td>
		</tr>
		<tr>
			<td class="borderBlock">
				<?php if(BloggieFactory::allowAccess('author.can_upload')) { ?>
				<form action="<?php echo $this->action ?>" method="post" name="adminForm" enctype="multipart/form-data">
				<label for="upload"><?php echo JText::_('UPLOAD '.$this->type); ?>:</label> <input id="upload" name="upload" type="file">
				<input type="submit" value="<?php echo JText::_('SUBMIT'); ?>">
				<input type="hidden" name="task" value="uploadfile" />
				<input type="hidden" name="type" value="<?php echo $this->type; ?>" />
				<input type="hidden" name="option" value="com_lyftenbloggie" />
				<?php echo JHTML::_( 'form.token' ); ?>
				</form>
				<span class="msgText"><?php echo $this->msg; ?></span>
				<?php } ?>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>