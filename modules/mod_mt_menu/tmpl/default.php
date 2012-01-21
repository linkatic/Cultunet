<?php /* $Id: default.php 897 2010-06-03 14:11:58Z cy $ */ defined('_JEXEC') or die('Restricted access'); ?>
<ul class="menu<?php echo $class_sfx; ?>">
	<?php
	
	if ($show['home']): ?>
	<li><a href="<?php echo JRoute::_(""); ?>"><?php echo JText::_( 'Home' ); ?></a></li>
	<?php endif;
	
	if ($show['browse']): ?>
	<li<?php echo ($active == 'browse') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree".$itemid); ?>"><?php echo JText::_( 'Browse' ); ?></a></li>
	<?php endif;

	if ($show['addlisting'] && $cat_allow_submission): ?>
	<li<?php echo ($active == 'addlisting') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=addlisting&cat_id=".$cat_id.$itemid); ?>"><?php echo JText::_( 'Add listing' ); ?></a></li>
	<?php endif;
	
	if ($show['addcategory']): ?>
	<li<?php echo ($active == 'addcategory') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=addcategory&cat_id=".$cat_id.$itemid); ?>"><?php echo JText::_( 'Add category' ); ?></a></li>
	<?php endif;

	if ($show['mypage'] && $my->id > 0): ?>
	<li<?php echo ($active == 'mypage') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=mypage".$itemid); ?>"><?php echo JText::_( 'My page' ); ?></a></li>
	<?php endif;

	if ($show['myfavourites'] && $mtconf->get('show_favourite') && $my->id > 0): ?>
	<li<?php echo ($active == 'viewusersfav' && $user_id == $my->id) ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=viewusersfav&user_id=".$my->id.$itemid); ?>"><?php echo JText::_( 'My favourites' ); ?></a></li>
	<?php endif;

	if ($show['newlisting']): ?>
	<li<?php echo ($active == 'listnew') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listnew&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'New listing' ); ?></a></li>
	<?php endif;

	if ($show['recentlyupdatedlisting']): ?>
	<li<?php echo ($active == 'listupdated') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listupdated&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'Recently updated listing' ); ?></a></li>
	<?php endif;

	if ($show['mostfavoured']): ?>
	<li<?php echo ($active == 'listfavourite') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listfavourite&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'Most favoured listings' ); ?></a></li>
	<?php endif;

	if ($show['featuredlisting']): ?>
  	<li<?php echo ($active == 'listfeatured') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listfeatured&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'Featured listing' ); ?></a></li>
	<?php endif;

	if ($show['popularlisting']): ?>
	<li<?php echo ($active == 'listpopular') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listpopular&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'Popular listing' ); ?></a></li>
	<?php endif;

	if ($show['mostratedlisting']): ?>
	<li<?php echo ($active == 'listmostrated') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listmostrated&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'Most rated listing' ); ?></a></li>
	<?php endif;

	if ($show['topratedlisting']): ?>
	<li<?php echo ($active == 'listtoprated') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listtoprated&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'Top rated listing' ); ?></a></li>
	<?php endif;

	if ($show['mostreviewedlisting']): ?>
	<li<?php echo ($active == 'listmostreview') ? ' class="parent active"' : ''; ?>><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=listmostreview&cat_id=".$toplist_cat_id.$itemid); ?>"><?php echo JText::_( 'Most reviewed listing' ); ?></a></li>
	<?php endif; ?>	
</ul>