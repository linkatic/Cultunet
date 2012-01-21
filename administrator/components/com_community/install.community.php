<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * This file and method will automatically get called by Joomla
 * during the installation process 
 **/
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
if(!class_exists('JURI'))
{
	jimport( 'joomla.environment.uri' );
}


function com_install()
{
	$lang =& JFactory::getLanguage();
	$lang->load( 'com_community', JPATH_ROOT . DS . 'administrator' );
	
	$destination = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS;
	$buffer = "installing";
	
	if(!JFile::write($destination.'installer.dummy.ini', $buffer))
	{
		ob_start();
		?>
		<table width="100%" border="0">
			<tr>
				<td>				
					There was an error while trying to create an installation file.
					Please ensure that the path <strong><?php echo $destination; ?></strong> has correct permissions and try again.
				</td>
			</tr>
		</table>
		<?php
		$html = ob_get_contents();
		@ob_end_clean();
	}
	else
	{
	
		$phpVersion = floatval(phpversion());
		if($phpVersion >= 5)
		{
			$link = rtrim( JURI::root() , '/' ) . '/administrator/index.php?option=com_community';
		
			ob_start();
			?>
			<style type="text/css">
			.button-next 
			{
				height: 34px;
				line-height: 34px;
				width: 200px;
				text-align: center;
				font-weight: 700;
				color: #333;
				background: #9c3;
				border: solid 1px #690;
				cursor: pointer;
			}
			</style>
			<table width="100%" border="0">
				<tr>
					<td>				
						Thank you for choosing JomSocial, please click on the following button to complete your installation.
					</td>
				</tr>
				<tr>
					<td>
						<input type="button" class="button-next" onclick="window.location = '<?php echo $link; ?>'" value="<?php echo JText::_('CC COMPLETE YOUR INSTALLATION');?>"/>
					</td>
				</tr>
			</table>
			<?php
			$html = ob_get_contents();
			@ob_end_clean();
		}
		else
		{
			ob_start();
			?>
			<table width="100%" border="0">
				<tr>
					<td style="color:red; font-weight:700">				
						Installation Error.
					</td>
				</tr>
				<tr>
					<td>
						Installation could not proceed any further because we detected that your site is using an unsupported version of PHP
					</td>
				</tr>
				<tr>
					<td>
						JomSocial only support <strong>PHP5</strong> and above. Please upgrade your PHP version and try again.
					</td>
				</tr>
			</table>
			<?php
			$html = ob_get_contents();
			@ob_end_clean();
		}
	}
	
	echo $html;
}
