<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * to use CHTML::sort($array)  
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFilterBar
{
	function getHTML( $url , $sortItems = array() , $defaultSort = '' , $filterItems = array() , $defaultFilter = '' )
	{
		$cleanURL	= $url;
		$uri		= &JFactory::getURI();
		$queries	= JRequest::get('GET');

		// If there is Itemid in the querystring, we need to unset it so that CRoute
		// will generate it's correct Itemid
		if( isset( $queries['Itemid'] ) )
		{
			unset( $queries['Itemid'] );
		}
		
		ob_start();
?>

<div id="cFilterBar" class="cFilterBar">
<div class="cFilterBar_inner">
<?php
	if( $sortItems )
	{
		$selected = JRequest::getVar( 'sort', $defaultSort , 'GET' );
?>
	<div id="cFilterType_Sort" class="filterGroup">
        <span class="filterName"><?php echo JText::_('CC SORT BY'); ?>:</span>
        <ul class="filterOptions">
        <?php
        foreach( $sortItems as $key => $option )
        {
            $queries['sort'] = $key;
            $link = 'index.php?'. $uri->buildQuery($queries);
            $link = CRoute::_($link);
        ?>
        <?php if($key==JString::trim($selected)):?>
            <li class="filterOption active"><?php echo $option; ?></li>
		<?php else: ?>
        	<li class="filterOption"><a href="<?php echo $link; ?>"><?php echo $option; ?></a></li>
		<?php endif ?>
        <?php
        }
        ?>
        </ul>
    </div>
<?php
        $queries['sort'] = $selected;
    }
?>

<?php
    if( $filterItems )
    {
        $selected = JRequest::getVar( 'filter', $defaultFilter, 'GET' );
?>
	<div id="cFilterType_Filter" class="filterGroup">
        <span class="filterName"><?php echo JText::_('CC FILTER SHOW'); ?></span>
        <ul class="filterOptions">
        <?php
        foreach( $filterItems as $key => $option )
        {
            $queries['filter'] 		= $key;
            
            // We need to reset the pagination limitstart so the pagination will not affect the filter
            unset($queries['limitstart']);
            $link = 'index.php?'. $uri->buildQuery($queries);

            $link = CRoute::_($link);
        ?>
        <?php if($key==JString::trim($selected)):?>
            <li class="filterOption active"><?php echo $option; ?></li>
		<?php else: ?>
        	<li class="filterOption"><a href="<?php echo $link; ?>"><?php echo $option; ?></a></li>
		<?php endif ?>
        <?php
        }
        ?>
        </ul>
	</div>
<?php
    }
?>
</div>
</div>

<?php
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}
}
?>