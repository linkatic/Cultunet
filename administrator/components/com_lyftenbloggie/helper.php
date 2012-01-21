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

class EntryHelper
{
	function saveEntryPrep( &$row )
	{
		$ckEditor 	= false;
		$add		= 27;
		
		// Get submitted text from the request variables
		$text = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW );

		// Clean text for xhtml transitional compliance
		$text		= str_replace( '<br>', '<br />', $text );

		// Search for the {readmore} tag and split the text up accordingly.
		$tagPos	= JString::strpos( $text, '<hr id="system-readmore" />' );
		
		if(!$tagPos) {
			$ckEditor 	= true;
			$add 		= 0;
			$tagPos		= JString::strpos( $text, '<div style="page-break-after: always;">' );
		}

		if ( $tagPos === false )
		{
			$row->introtext	= $text;
		} else
		{
			$row->introtext	= JString::substr($text, 0, $tagPos);
			$row->fulltext	= JString::substr($text, $tagPos + $add );
		}

		if($ckEditor) {
			$ckEditor = true;
			$tagPos	= JString::strpos( $text, '<div style="page-break-after: always;">' );
		}

		// Filter settings
		jimport( 'joomla.application.component.helper' );
		$config	= JComponentHelper::getParams( 'com_content' );
		$user	= &JFactory::getUser();
		$gid	= $user->get( 'gid' );

		$filterGroups	= (array) $config->get( 'filter_groups' );
		if (in_array( $gid, $filterGroups ))
		{
			$filterType		= $config->get( 'filter_type' );
			$filterTags		= preg_split( '#[,\s]+#', trim( $config->get( 'filter_tags' ) ) );
			$filterAttrs	= preg_split( '#[,\s]+#', trim( $config->get( 'filter_attritbutes' ) ) );
			switch ($filterType)
			{
				case 'NH':
					$filter	= new JFilterInput();
					break;
				case 'WL':
					$filter	= new JFilterInput( $filterTags, $filterAttrs, 0, 0 );
					break;
				case 'BL':
				default:
					$filter	= new JFilterInput( $filterTags, $filterAttrs, 1, 1 );
					break;
			}
			$row->introtext	= $filter->clean( $row->introtext );
			$row->fulltext	= $filter->clean( $row->fulltext );
		}

		return true;
	}
}


class BlogAuthor
{
	/*
	* Create Avatar
	*/	
	function createAvatar($src_file, $avatar_name, $tag, $orig_name)
	{
		global $mainframe;
		
		// Get the page/component configuration
		$settings 	= & BloggieSettings::getInstance();
		
		ini_set('memory_limit', '20M');
				
		$src_file = urldecode($src_file);
	
		$orig_name = strtolower($orig_name);
		$findme  = '.jpg';
		$pos = strpos($orig_name, $findme);
		if ($pos === false)
		{
			$findme  = '.jpeg';
			$pos = strpos($orig_name, $findme);
			if ($pos === false)
			{
				$findme  = '.gif';
				$pos = strpos($orig_name, $findme);
				if ($pos === false)
				{
					$findme  = '.png';
					$pos = strpos($orig_name, $findme);
					if ($pos === false)
					{
						return;
					} else {
						$type = "png";
					}
				} else {
					$type = "gif";
				}
			} else {
				$type = "jpeg";
			}
		} else {
			$type = "jpeg";
		}

		$max_avatar_h = $settings->get('maxAvatarHeight');
		$max_avatar_w = $settings->get('maxAvatarWidth');
		$path = JPATH_COMPONENT_SITE.DS.'assets'.DS.'avatars'.DS;
		
		if ( file_exists( $path.$avatar_name )) {
			unlink( $path.$avatar_name );
		}
				
		$read = 'imagecreatefrom' . $type; 
		$write = 'image' . $type; 
				
		$src_img = $read($src_file);
		
		// height/width
		$imginfo = getimagesize($src_file);
		$src_w = $imginfo[0];
		$src_h = $imginfo[1];
				
		// set
		$dst_w = $src_h;
		$dst_h = $src_h;
		$t_src_x=0;
		$t_src_y=0; 
		if($src_w>$src_h){
			$t_src_x = ceil(($src_w-$src_h)/2);
			$t_src_w=$src_h;
			$t_src_h=$src_h;
		}else{
			$t_src_y = ceil(($src_h-$src_w)/2);
			$t_src_w=$src_w;
			$t_src_h=$src_w;
		} 

		$dst_avatar_img = imagecreatetruecolor($max_avatar_w,$max_avatar_h);
		$white = imagecolorallocate($dst_avatar_img,255,255,255);
		imagefill($dst_avatar_img,0,0,$white);
		imagecopyresampled($dst_avatar_img,$src_img, 0,0, $t_src_x, $t_src_y, $max_avatar_w,$max_avatar_h,$t_src_w,$t_src_h);
		$textcolor = imagecolorallocate($dst_avatar_img, 255, 255, 255);
		if (isset($tag))
			imagestring($dst_avatar_img, 2, 2, 2, "$tag", $textcolor);
		if($type == 'jpeg'){
			$desc_img = $write($dst_avatar_img,$path.$avatar_name, 100);
		}else{
			$desc_img = $write($dst_avatar_img,$path.$avatar_name, 2);
		}
		
		return $avatar_name;				
	}       
}

class BlogSystemFun
{

	function getSideMenu($type=1, $maintitle="Manage Product", $title="Manage", $pid=null)
	{
		$pid = ($pid)?'&pid='.$pid:'';
		
		$menus	= array(
					array(
						"type" => "1",
						"title" => "SETTINGS",
						"link" => ""
					),
					array(
						"type" => "1",
						"title" => "GENERAL SETTINGS",
						"link" => "index.php?option=com_lyftenbloggie&view=settings".$pid
					),
					array(
						"type" => "1",
						"title" => "ACCESS GROUPS",
						"link" => "index.php?option=com_lyftenbloggie&view=groups".$pid
					),
					array(
						"type" => "1",
						"title" => "PLUGINS",
						"link" => "index.php?option=com_lyftenbloggie&view=plugins".$pid
					),
					array(
						"type" => "1",
						"title" => "INSTALL ADDONS",
						"link" => "index.php?option=com_lyftenbloggie&view=addons".$pid
					),
					array(
						"type" => "1",
						"title" => "TEMPLATES",
						"link" => ""
					),
					array(
						"type" => "1",
						"title" => "SYSTEM THEMES",
						"link" => "index.php?option=com_lyftenbloggie&view=themes".$pid
					),
					array(
						"type" => "1",
						"title" => "EMAIL TEMPLATES",
						"link" => "index.php?option=com_lyftenbloggie&view=email".$pid
					),
					array(
						"type" => "1",
						"title" => "MAINTENANCE",
						"link" => ""
					),
					array(
						"type" => "1",
						"title" => "CHECK FOR UPDATES",
						"link" => "index.php?option=com_lyftenbloggie&view=update".$pid
					),
					array(
						"type" => "1",
						"title" => "OPTIMIZE DATABASE",
						"link" => "index.php?option=com_lyftenbloggie&controller=settings&task=databaseOptimise".$pid
					)
				 );
	?>
	<table width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr>
		<td style="vertical-align:top;width:165px;">
			<table width="165" class="sidemenu-box" width="100%" border="0" cellpadding="0" cellspacing="1">
				<tr>
					<td>
					<center><h2 class="title-box"><?php echo $maintitle; ?></h2></center>
						<div>
							<?php
							$i = 1;
							foreach($menus as $menu)
							{
								if($menu['type'] == $type)
								{
									if($menu['link'] == "")
									{
										if($i > 1) echo "</ul></div>";
										echo "<div class=\"title-menu\">".JText::_($menu['title'])."</div>\n";
										echo "<div style=\"width: 140px; display:block; \">\n";
										echo "<ul class=\"title-submenu\">";
									}else{
										echo "<li><a href=\"".$menu['link']."\">".JText::_($menu['title'])."</a></li>";
									}
									$i++;
								}
							}	 
							?>
							</ul>
						</div>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
	<?php
	}
}