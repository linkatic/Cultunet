<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	groups		An array of events objects.
 */
defined('_JEXEC') or die();

if( $events )
{
	for( $i = 0; $i < count( $events ); $i++ )
	{
		$event				=& $events[$i];
		$creator			= CFactory::getUser($event->creator);
		$creatorUtcOffset	= $creator->getUtcOffset();
?>
	<div class="community-events-results-item">
		<div class="community-events-results-left">
			<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id );?>"><img class="avatar" src="<?php echo $event->getThumbAvatar();?>" border="0" alt="<?php echo $this->escape($event->title); ?>"/></a>
			<div class="eventDate"><?php echo CEventHelper::formatStartDate($event, $config->get('eventdateformat') ); ?></div>
		</div>
		<div class="community-events-results-right">
			<h3 class="eventName">
				<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id );?>"><?php echo $this->escape($event->title); ?></a>
			</h3>
			<div class="eventLocation"><?php echo $this->escape($event->location);?></div>
            <div class="eventTime"><?php echo JText::sprintf('CC EVENT TIME', JHTML::_('date', $event->startdate, JText::_('DATE_FORMAT_LC2') , $creatorUtcOffset ), JHTML::_('date', $event->enddate, JText::_('DATE_FORMAT_LC2') , $creatorUtcOffset )); ?></div>
            <?php if( $config->get('eventshowtimezone') ) { ?>
			<div class="eventTimezone small"><?php echo CTimeHelper::getTimezone($event->getCreator()->getUtcOffset()); ?></div>
			<?php } ?>
			<div class="eventActions">
				<span class="icon-group" style="margin-right: 5px;">
					<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=viewguest&eventid=' . $event->id . '&type='.COMMUNITY_EVENT_STATUS_ATTEND);?>"><?php echo JText::sprintf((cIsPlural($event->confirmedcount)) ? 'CC GUESTS COUNT MANY':'CC GUESTS COUNT', $event->confirmedcount);?></a>
				</span>
			</div>
		</div>
		<?php if($isExpired) { ?>
		    <span class="icon-offline-overlay">&nbsp;<?php echo JText::_('CC EVENTS PAST'); ?>&nbsp;</span>
		<?php } else if(CEventHelper::isToday($event)) { ?>
			<span class="icon-online-overlay">&nbsp;<?php echo JText::_('CC EVENTS ONGOING'); ?>&nbsp;</span>
		<?php }?>
		<div style="clear: both;"></div>
	</div>
<?php
	}
} else {
?>
	<div class="event-not-found"><?php echo JText::_('CC NO EVENTS FOUND'); ?></div>
<?php } ?>