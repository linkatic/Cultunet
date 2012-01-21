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

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="55%" valign="top">
				<div id="cpanel">
					<?php echo $this->addIcon('entry','entry', 				JText::_('NEW ENTRY'));?>
					<?php echo $this->addIcon('comments','comments', 		JText::_('MANAGE COMMENTS'));?>
					<?php echo $this->addIcon('tags','tags', 				JText::_('TAGS'));?>
					<?php echo $this->addIcon('bookmarks','bookmarks', 		JText::_('BOOKMARKS'));?>
					<?php echo $this->addIcon('profiles','profiles', 		JText::_('PROFILES'));?>
					<?php echo $this->addIcon('groups','groups', 			JText::_('GROUPS'));?>
					<?php echo $this->addIcon('plugins','plugins', 			JText::_('PLUGINS'));?>
					<?php echo $this->addIcon('config','settings', 			JText::_('SETTINGS'));?>
				</div>
			</td>
			<td width="45%" valign="top">
				<?php
				echo $this->pane->startPane( 'genstat-pane' );
				echo $this->pane->startPanel( JText::_( 'GENERAL STATISTICS' ), 'unapproved' );
				?>
				<div id="dash_generalstats" class="postbox " >
					<div class="inside">
						<div class="table">
							<table>
							<tr class="first">
								<td class="first b"><a href="index.php?option=com_lyftenbloggie&view=entries"><?php echo $this->genstats['total']; ?></a></td>
								<td class="t"><?php echo JText::_('ENTRIES');?></td>
								<td class="b"><a href="index.php?option=com_lyftenbloggie&view=entries&filter_state=1"><?php echo $this->genstats['approved']; ?></a></td>
								<td class="last t approved"><?php echo JText::_('APPROVED ENTRIES');?></td>
							</tr>
							<tr>
								<td class="first b"><a href="index.php?option=com_lyftenbloggie&view=comments"><?php echo $this->genstats['comments']; ?></a></td>
								<td class="t"><?php echo JText::_('COMMENTS');?></td>
								<td class="b"><a href="index.php?option=com_lyftenbloggie&view=entries&filter_state=2"><?php echo $this->genstats['pending']; ?></a></td>
								<td class="last t waiting"><?php echo JText::_('PENDING ENTRIES');?></td>
							</tr>
							<tr>
								<td class="first b"><a href="index.php?option=com_lyftenbloggie&view=categories"><?php echo $this->genstats['categories']; ?></a></td>
								<td class="t"><?php echo JText::_('CATEGORIES');?></td>
								<td class="b"><a href="index.php?option=com_lyftenbloggie&view=comments&filter_state=2"><?php echo $this->genstats['compending']; ?></a></td>
								<td class="last t pending"><?php echo JText::_('PENDNING COMMENTS');?></td>
							</tr>
							<tr>
								<td class="first b"><a href="index.php?option=com_lyftenbloggie&view=tags"><?php echo $this->genstats['tags']; ?></a></td>
								<td class="t"><?php echo JText::_('TAGS');?></td>
								<td class="b"><a href="index.php?option=com_lyftenbloggie&view=comments&filter_state=2"><?php echo $this->genstats['reports']; ?></a></td>
								<td class="last t reports"><?php echo JText::_('REPORTED COMMENTS');?></td>
							</tr>
							<tr>
								<td class="b"><a href="index.php?option=com_lyftenbloggie&view=comments&filter_type=2"><?php echo $this->genstats['trackback']; ?></a></td>
								<td class="last t"><?php echo JText::_('TRACKBACKS');?></td>
								<?php if(isset($this->genstats['comspam'])) { ?>
								<td class="b"><a href="index.php?option=com_lyftenbloggie&view=comments&filter_type=2"><?php echo $this->genstats['comspam']; ?></a></td>
								<td class="last t spam"><?php echo JText::_('COMMENT SPAM');?></td>
								<?php }else{ ?>
								<td colspan="2"></td>
								<?php } ?>
							</tr>
							</table>
						</div>
					</div>
				</div>
				<?php
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( JText::_( 'MOST POPULAR' ), 'mostpop-pane' );
				?>
				<table class="adminlist">
					<thead>
						<tr>
							<td class="title"><strong><?php echo JText::_( 'TITLE' ); ?></strong></td>
							<td class="title"><strong><?php echo JText::_( 'HITS' ); ?></strong></td>
						</tr>
					</thead>
					<tbody>
						<?php
						$k = 0;
						for ($i=0, $n=count($this->popular); $i < $n; $i++) {
						$row = $this->popular[$i];
						$link 		= 'index.php?option=com_lyftenbloggie&amp;controller=entries&amp;task=edit&amp;cid[]='. $row->id;
						?>
						<tr class="row<?php echo $k; ?>">
							<td width="65%">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT ENTRY' ); ?>::<?php echo $row->title; ?>">
									<a href="<?php echo $link; ?>">
										<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
									</a>
								</span>
							</td>
							<td width="5%" align="center">
								<strong><?php echo $row->hits; ?></strong>
							</td>
						</tr>
						<?php $k = 1 - $k; } ?>
					</tbody>
				</table>
				<?php
				echo $this->pane->endPanel();
				if(isset($this->inlinks)) {
				echo $this->pane->startPanel( JText::_( 'INCOMING LINKS' ), 'incominglinks-pane' );
				?>
				<table class="adminlist">
					<thead>
						<tr>
							<td class="title" style="float:right;"><small><a href="http://blogsearch.google.com/blogsearch?hl=en&scoring=d&partner=wordpress&q=link:<?php echo $mainframe->getSiteURL(); ?>" target="_blank">See&nbsp;All</a>&nbsp;|&nbsp;<img class="rss-icon" src="<?php echo BLOGGIE_ASSETS_URL; ?>/images/rss.png" alt="rss icon"> <a href="http://blogsearch.google.com/blogsearch_feeds?hl=en&scoring=d&ie=utf-8&num=10&output=rss&partner=wordpress&q=link:<?php echo $mainframe->getSiteURL(); ?>" target="_blank">RSS</a></small></td>
						</tr>
					</thead>	
					<tbody>
						<?php
						$k = 0;
						foreach($this->inlinks as $row) { ?>
						<tr class="row<?php echo $k; ?>">
							<td>
							<?php
								if(strlen($row['description']) > 60)
								{
									$regex = "/(.{1,60})\b/";
									preg_match($regex, $row['description'], $matches);
									$row['description'] = (isset($matches[1])) ? $matches[1] : $row['description'];
								}
								echo '<strong>'.$row['dc:publisher'].'</strong> linked here <a href="'.$row['link'].'" target="_blank">saying</a>, "'.$row['description'].' ..."';	
							?>
							</td>
						</tr>
						<?php $k = 1 - $k; } ?>
					</tbody>
				</table>
				<?php
				echo $this->pane->endPanel();
				}
				echo $this->pane->endPane();
	
				if ($this->update == 1) {
				?>
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'UPDATE CHECK' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$currentVersion = (isset($this->current['update']['version']))?$this->current['update']['version']:'';
		$currentBuild = (isset($this->current['update']['build']))?$this->current['update']['build']:'';
		if( $this->version < $currentVersion || ( ($this->version <= $currentVersion) && ( $this->build < $currentBuild ) ) )
		{
		?>
			<tr>
				<td colspan="2" style="background:#008000" class="update">
					<strong><?php echo JText::_('NEW VERSION AVAILABLE');?></strong><br><?php echo JText::_('You are currently running on an older version of Lyften Bloggie.');?>
				</td>
			</tr>
			<tr>
				<td width="40%">
					<?php echo JText::_( 'VERSION INSTALLED' ).':'; ?>
				</td>
				<td>
					<?php echo JText::sprintf('<span style="font-weight:700; color: red">%1$s</span>' , $this->fversion );?>
				</td>
			</tr>
			<tr>
				<td width="40%">
					<?php echo JText::_( 'LATEST VERSION AVAILABLE' ).':'; ?>
				</td>
				<td>
					<?php echo JText::sprintf('<span style="font-weight:700;">%1$s</span>', $this->current['update']['version'] ); ?>
				</td>
			</tr>
			<?php if($this->current['update']['auto_upgrade']) { ?>
			<tr>
				<td width="40%">
					<?php echo JText::_( 'AUTO UPDATE' ).':'; ?>
				</td>
				<td>
					<a href="index.php?option=com_lyftenbloggie&view=update"><?php echo JText::_('UPDATE NOW'); ?></a>
				</td>
			</tr>
			<?php }else{ ?>
			<tr>
				<td width="40%">
					<?php echo JText::_( 'HOMEPAGE' ).':'; ?>
				</td>
				<td>
					<a href="<?php echo base64_decode($this->current['update']['upgrade']); ?>" target="_blank"><?php echo JText::_('VISIT'); ?></a>
				</td>
			</tr>
			<?php } ?>
		<?php
		}
		else
		{
		?>
			<tr>
				<td colspan="2" style="background:#656565" class="update">
					<strong><?php echo JText::_('NO UPDATES AVAILABLE');?></strong><br><?php echo JText::_('NO UPDATES DESC');?>
				</td>
			</tr>
			<tr>
				<td width="40%">
					<?php echo JText::_( 'VERSION INSTALLED' ).':'; ?>
				</td>
				<td>
					<?php echo JText::sprintf('<span style="font-weight:700; color: red">%1$s</span>' , $this->fversion );?>
				</td>
			</tr>
		<?php
		}
		if($this->current['patch']['total']){
		?>
			<tr>
				<td colspan="2" style="color:#000000;" class="update">
					<a href="index.php?option=com_lyftenbloggie&view=update"><strong><?php echo $this->current['patch']['total'].' '.JText::_('PATCHES AVAILABLE FOR VERSION').' '.$this->fversion; ?></strong></a>
					<?php echo ($this->current['patch']['critical']) ? '<br /><span style="font-weight:700; color: red">'.JText::sprintf( 'PATCHES ARE CRITICAL', $this->current['patch']['critical'] ).'</span>' : ''; ?>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
		</table>
		<?php } ?>
			</td>
		</tr>
	</table>