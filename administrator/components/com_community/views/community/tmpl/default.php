<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<table width="100%" border="0">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
				<?php echo $this->addIcon('configuration.gif','index.php?option=com_community&view=configuration', JText::_('CC CONFIGURATION'));?>
				<?php echo $this->addIcon('edit-user.gif','index.php?option=com_community&view=users', JText::_('CC USERS'));?>
				<?php echo $this->addIcon('profiles.gif','index.php?option=com_community&view=profiles', JText::_('CC CUSTOM PROFILES'));?>
				<?php echo $this->addIcon('groups.gif','index.php?option=com_community&view=groups', JText::_('CC GROUPS'));?>
				<?php echo $this->addIcon('groupcategories.gif','index.php?option=com_community&view=groupcategories', JText::_('CC GROUP CATEGORIES'));?>
				<?php echo $this->addIcon('videos.gif','index.php?option=com_community&view=videoscategories', JText::_('CC VIDEO CATEGORIES'));?>
				<?php echo $this->addIcon('templates.gif','index.php?option=com_community&view=templates', JText::_('CC TEMPLATES'));?>
				<?php echo $this->addIcon('applications.gif','index.php?option=com_community&view=applications', JText::_('CC APPLICATIONS'));?>
				<?php echo $this->addIcon('event.gif','index.php?option=com_community&view=events', JText::_('CC EVENTS'));?>
				<?php echo $this->addIcon('eventcategories.gif','index.php?option=com_community&view=eventcategories', JText::_('CC EVENT CATEGORIES'));?>
				<?php echo $this->addIcon('mailq.gif','index.php?option=com_community&view=mailqueue', JText::_('CC MAIL QUEUE'));?>
				<?php echo $this->addIcon('reports.gif','index.php?option=com_community&view=reports', JText::_('CC REPORTINGS')); ?>
				<?php echo $this->addIcon('userpoints.gif','index.php?option=com_community&view=userpoints', JText::_('CC USERPOINTS')); ?>
				<?php echo $this->addIcon('message.gif','index.php?option=com_community&view=messaging', JText::_('CC MASSMESSAGING')); ?>
				<?php echo $this->addIcon('activities.gif','index.php?option=com_community&view=activities', JText::_('CC ACTIVITIES')); ?>
				<?php echo $this->addIcon('about.gif','index.php?option=com_community&view=about', JText::_('CC ABOUT')); ?>
				<?php echo $this->addIcon('help.gif','http://www.jomsocial.com/docs.html', JText::_('CC HELP'), true ); ?>
			</div>
		</td>
		<td width="45%" valign="top">
			<?php
				echo $this->pane->startPane( 'stat-pane' );
				echo $this->pane->startPanel( JText::_('CC WELCOME TO JOMSOCIAL') , 'welcome' );
			?>
			<table class="adminlist">
				<tr>
					<td>
						<div style="font-weight:700;">
							<?php echo JText::_('CC ANOTHER GREAT COMPONENT BROUGHT TO YOU BY AZRULCOM');?>
						</div>
						<p>
							If you require professional support just head on to the forums at 
							<a href="http://www.jomsocial.com/forum/" target="_blank">
							http://www.jomsocial.com/forum
							</a>
							For developers, you can browse through the documentations at 
							<a href="http://www.jomsocial.com/docs.html" target="_blank">http://www.jomsocial.com/docs.html</a>
						</p>
						<p>
							If you found any bugs, just drop us an email at bugs@azrul.com
						</p>
					</td>
				</tr>
			</table>
			<?php
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( JText::_('CC COMMUNITY STATISTICS') , 'community' );
			?>
				<table class="adminlist">
					<tr>
						<td>
							<?php echo JText::_( 'CC TOTAL USERS' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->community->total; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC TOTAL BLOCKED USERS' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->community->blocked; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC TOTAL APPLICATIONS INSTALLED' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->community->applications; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC TOTAL ACTIVITY UPDATES' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->community->updates; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC TOTAL PHOTOS' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->community->photos; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC TOTAL VIDEOS' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->community->videos; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC TOTAL GROUP DISCUSSIONS' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->community->groupDiscussion; ?></strong>
						</td>
					</tr>
				</table>

			<?php
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( JText::_('CC GROUP STATISTICS'), 'groups' );
			?>
				<table class="adminlist">
					<tr>
						<td>
							<?php echo JText::_( 'CC PUBLISHED GROUPS' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->groups->published; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC UNPUBLISHED GROUPS' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->groups->unpublished; ?></strong>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'CC GROUP CATEGORIES' ).': '; ?>
						</td>
						<td align="center">
							<strong><?php echo $this->groups->categories; ?></strong>
						</td>
					</tr>
				</table>
			<?php
				echo $this->pane->endPanel();
				echo $this->pane->endPane();
			?>
		</td>
	</tr>
</table>
