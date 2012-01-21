<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	sortings	string	HTML code for the sorting
 * @params	groupsHTML	string HTML code for the group listings
 * @params	pagination	JPagination JPagination object 
 */
defined('_JEXEC') or die();
?>

<?php echo $sortings; ?>

<div class="cLayout clrfix">
	<?php echo $discussionsHTML;?>

	<!-- ALL MY GROUP LIST -->
	<div class="cMain clrfix">
		<?php echo $groupsHTML; ?>
		<div class="pagination-container">
			<?php echo $pagination->getPagesLinks(); ?>
		</div>
	</div>
	<!-- ALL MY GROUP LIST -->
	<div class="clr"></div>
</div>