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
<script type="text/javascript">
	function addImageUrl(fileUrl)
	{
		window.top.opener.CKEDITOR.tools.callFunction( 2, fileUrl );
		parent.close();			
	};
</script>
</head>
<body class="contentpane">
<?php if (count($this->data) > 0) { ?>
	<?php for ($i=0,$n=count($this->data); $i<$n; $i++) { ?>
	<div class="SelectThumb" _ckffileid="<?php echo $i; ?>">
		<table width="100" height="100" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td valign="middle" align="center">
						<a style="cursor:pointer" onclick="addImageUrl('<?php echo $this->data[$i]->path_relative; ?>');">
							<img src="<?php echo (isset($this->data[$i]->icon)) ? $this->data[$i]->icon : $this->data[$i]->path_relative; ?>"<?php echo $this->data[$i]->dimensions_100; ?> />
						</a>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="SelectFileName" nowrap=""><?php echo $this->data[$i]->name; ?></div>
		<div class="SelectFileSize" nowrap=""><?php echo MediaHelper::parseSize($this->data[$i]->size); ?></div>
	</div>
	<?php } ?>
<?php } else { ?>
	<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">
				<?php echo JText::_( 'No files Found' ); ?>
			</div>
		</td>
	</tr>
	</table>
<?php } ?>
</body>
</html>