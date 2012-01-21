<?php
/**
 * @category Template
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();

if( $formElements )
{
?>
<table class="formtable" cellspacing="1" cellpadding="0" style="width: 98%;">
<?php

	foreach( $formElements as $element )
	{
		if( $element instanceof CFormElement && $element->position == $position )
		{
?>
			<tr>
				<td class="key"><label><?php echo $element->label;?></label></td>
				<td class="value">
					<div style="display: inline-block;"><?php echo $element->html;?></div>
				</td> 
			</tr>
<?php
		}
	}
?>
</table>
<?php
}