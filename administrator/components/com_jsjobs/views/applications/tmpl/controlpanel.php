<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Mar 25, 2009
 ^
 + Project: 		Hotel Booking System
 * File Name:	views/applications/view.html.php
 ^ 
 * Description: HTML view of all applications 
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';
//echo 'ad '.$ADMINPATH;
?>
<script type="text/javascript" src="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/include/admin_menu/sdmenu/sdmenu.js"></script>

	<script type="text/javascript">
//	var myMenu;
	function mymenu(val) {
		myMenu = new SDMenu("my_menu");
myMenu.speed = 3;                     // Menu sliding speed (1 - 5 recomended)
myMenu.remember = true;               // Store menu states (expanded or collapsed) in cookie and restore later
myMenu.oneSmOnly = true;             // One expanded submenu at a time
myMenu.markCurrent = true;            // Mark current link / page (link.href == location.href)

myMenu.init();

// Additional methods...
var firstSubmenu = myMenu.submenus[val];
myMenu.expandMenu(firstSubmenu);      // Expand a submenu
	};
	</script>

<table width="100%">
	<tr>
		<td align="left" width="150">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
			<table width="100%" border="0">
				<tr><td height="0"></td></tr>
				<tr>
					<td align="center" class="header" colspan="2">
						
						<div class="header"><h2><u>JS Jobs</u></h2></div>

					</td>
				</tr>
				<tr>
					<td width="7%"></td>
					<td align="center" width="85%">
						<table class="adminlist" >
								<thead>
									<tr><th><?php echo JText::_('JS_CONTROL_PANEL'); ?></th></tr>
								</thead>
								<tbody>
									<tr>
										<td align="center">
											<div id="cpanel" >
												<table width="85%" border="0" cellpadding="0" cellspacing="1" >
													<tr align="center">
														<td width="15"></td>
														<td width="90">
														       
															   <div style="float:center;align:center;"><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=companies" onclick="mymenu(1)">
																	<img src="components/com_jsjobs/include/images/companies.png" height="56" width="56">
																	<br /><?php echo JText::_('JS_COMPANIES'); ?></a>
																</div></div>
														</td>
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=jobs" onclick="mymenu(2)">
																<img src="components/com_jsjobs/include/images/jobs.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_JOBS'); ?></a>
															</div></div>
														</td>	
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=empapps" onclick="mymenu(3)">
																<img src="components/com_jsjobs/include/images/resume.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_RESUME'); ?></a>
															</div></div>
														</td>	
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=categories" onclick="mymenu(4)">
																<img src="components/com_jsjobs/include/images/categories.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_CATEGORIES'); ?></a>
															</div></div>
														</td>	
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=salaryrange" onclick="mymenu(5)">
																<img src="components/com_jsjobs/include/images/salaryrange.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_SALARY_RANGE'); ?></a>
															</div></div>
														</td>	
													</tr>
													<tr><td colspan="4" height="25"></td></tr>	
													<tr align="center">
														<td></td>
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=roles" onclick="mymenu(6)">
																<img src="components/com_jsjobs/include/images/roles.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_ROLES'); ?></a>
															</div></div>
														</td>	
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=countries" onclick="mymenu(8)">
																<img src="components/com_jsjobs/include/images/countries.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_COUNTRIES'); ?></a>
															</div></div>
														</td>	
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=cm-ap" onclick="mymenu(7)">
																<img src="components/com_jsjobs/include/images/emailtemplates.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_EMAIL_TEMPLATES'); ?></a>
															</div></div>
														</td>	
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=conf" onclick="mymenu(0)">
																<img src="components/com_jsjobs/include/images/configurations.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_CONFIGURATIONS'); ?></a>
															</div></div>
														</td>	
														<td width="90">
															<div ><div class="icon">
																<a href="index.php?option=com_jsjobs&task=view&layout=info" onclick="mymenu(0)">
																<img src="components/com_jsjobs/include/images/information.png" height="56" width="56"> 
																<br/><?php echo JText::_('JS_INFORMATION'); ?></a>
															</div></div>
														</td>	
													</tr>
												</table>
											</div>
										</td>
									</tr>
								</tbody>
							</table>		
					</td>
					<td width="4%"></td>

				</tr>	
			</table>	
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
				include_once('components/com_jsjobs/views/jscr.php');
			?>
			</td>
			</tr></table>
		</td>
	</tr>
	
</table>	
