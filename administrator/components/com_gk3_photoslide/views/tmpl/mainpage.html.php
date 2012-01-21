<?php

/**
 * 
 * @version		3.0.0
 * @package		Joomla
 * @subpackage	Photoslide GK3
 * @copyright	Copyright (C) 2008 - 2009 GavickPro. All rights reserved.
 * @license		GNU/GPL
 * 
 * ==========================================================================
 * 
 * Mainpage view html.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// loading Modal Box
JHTML::_( 'behavior.modal' );
// getting client variable
$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

?>

<div id="wrapper">
      <?php ViewNavigation::generate(array( JText::_("MAINPAGE") => 'option=com_gk3_photoslide')); ?>
      <table style="width: 924px;" class="adminform">
            <tbody>
                  <tr align="top">
                        <td width="60%"><div id="cpanel">
                                    <h3><?php echo JText::_('WHAT_DO_YOU_WANT_TO_DO'); ?></h3>
                                    <div style="float: left; margin-left: 5px;">
                                          <div class="icon">
                                                <a href="<?php echo $uri->root().'administrator/index.php?option=com_gk3_photoslide&c=group'; ?>">
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/icon-48-section.png'; ?>" alt="<?php echo JText::_('MANAGE_GROUPS'); ?>" /><br/>
                                                <span><?php echo JText::_('MANAGE_GROUPS'); ?></span>
                                                </a>
                                          </div>
                                    </div>
                                    <div style="float: left; margin-left: 5px;" id="quick_group_manage_button">
                                          <div class="icon">
                                                <a href="#">
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/icon-48-category.png'; ?>" alt="<?php echo JText::_('MANAGE_SLIDES'); ?>" /><br/>
                                                <span><?php echo JText::_('MANAGE_SLIDES'); ?></span>
                                                </a>
                                          </div>
                                    </div>
                                    <div style="float: left; margin-left: 5px;" id="quick_group_add_button">
                                          <div class="icon">
                                                <a href="<?php echo $uri->root().'administrator/index.php?option=com_gk3_photoslide&c=group&task=add'; ?>">
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/button_add_group.png'; ?>" alt="<?php echo JText::_('ADD_GROUP'); ?>" /><br/>
                                                <span><?php echo JText::_('ADD_GROUP'); ?></span></a>
                                          </div>
                                    </div>
                                    <div style="float: left; margin-left: 5px;margin-bottom:30px;" id="quick_slide_add_button">
                                          <div class="icon">
                                                <a href="#">
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/button_add_slide.png'; ?>" alt="<?php echo JText::_('ADD_SLIDE'); ?>" /><br/>
                                                <span><?php echo JText::_('ADD_SLIDE'); ?></span></a>
                                          </div>
                                    </div>
                                    <h3 style="clear: both;"><?php echo JText::_('COMPONENT_OPTIONS'); ?></h3>
                                    <div style="float: left; margin-left: 5px;">
                                          <div class="icon">
                                                <a href="<?php echo $uri->root().'administrator/index.php?option=com_gk3_photoslide&c=option'.(($modal_settings) ? '&tmpl=component' : ''); ?>" <?php if($modal_settings) echo ' class="modal"  rel="{handler: \'iframe\', size: {x: 400, y: 400}}"'; ?>>
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/icon-48-config.png'; ?>" alt="<?php echo JText::_('SETTINGS'); ?>" />
                                                <br/>
                                                <span><?php echo JText::_('SETTINGS'); ?></span>
                                                </a>
                                          </div>
                                    </div>
                                    <div style="float: left; margin-left: 5px;">
                                          <div class="icon">
                                                <a href="<?php echo $uri->root().'administrator/index.php?option=com_gk3_photoslide&c=check_system'.(($modal_settings) ? '&tmpl=component' : ''); ?>" <?php if($modal_settings) echo ' class="modal"  rel="{handler: \'iframe\', size: {x: 800, y: 300}}"'; ?>>
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/icon-48-checkin.png'; ?>" alt="<?php echo JText::_('CHECK_SYSTEM'); ?>" />
                                                <br/>
                                                <span><?php echo JText::_('CHECK_SYSTEM'); ?>
                                                </span>
                                                </a>
                                          </div>
                                    </div>
                                    <div style="float: left; margin-left: 5px;">
                                          <div class="icon">
                                                <a href="<?php echo $uri->root().'administrator/index.php?option=com_gk3_photoslide&c=news&task=view_news_all'.(($modal_settings) ? '&tmpl=component' : ''); ?>" <?php if($modal_settings) echo ' class="modal"  rel="{handler: \'iframe\', size: {x: 800, y: 400}}"'; ?>>
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/icon-48-help_header_news-48.png'; ?>" alt="<?php echo JText::_('GAVICK_NEWS'); ?>" />
                                                <br/>
                                                <span><?php echo JText::_('GAVICK_NEWS'); ?>
                                                </span>
                                                </a>
                                          </div>
                                    </div>
                                    <div style="float: left; margin-left: 5px;margin-bottom: 20px;">
                                          <div class="icon">
                                                <a href="<?php echo $uri->root().'administrator/index.php?option=com_gk3_photoslide&c=info&task=info'.(($modal_settings) ? '&tmpl=component' : ''); ?>" <?php if($modal_settings) echo ' class="modal"  rel="{handler: \'iframe\', size: {x: 400, y: 200}}"'; ?>>
                                                <img src="<?php echo $uri->root().'administrator/components/com_gk3_photoslide/interface/images/icon-48-help_header.png'; ?>" alt="<?php echo JText::_('INFO_AND_HELP'); ?>" />
                                                <br/>
                                                <span><?php echo JText::_('INFO_AND_HELP'); ?>
                                                </span>
                                                </a>
                                          </div>
                                    </div>
                              </div></td>
                        
                      		  <td width="40%">
								<div id="cpanel">
                                    <div class="gavick_news">
                                          <?php if($gavick_news) : ?>
										  <h3><?php echo JText::_('GAVICKPRO_LATEST_NEWS'); ?></h3>
                                          <ul>
                                                <?php echo ViewMainpage::loadGavickRSS(); ?>
                                          </ul>
                                          <?php else : ?>
                                          <img src="components/com_gk3_photoslide/interface/images/logo.png" style="display: block;margin: 0 auto;" alt="GavickPro logo" />
                                          <?php endif; ?>
                                    </div>
                              	</div>
							  </td>
                  </tr>
            </tbody>
      </table>
      <!-- Dynamic forms -->
      <script type="text/javascript">
		window.addEvent("domready", function(){
			if($('quick_group_manage_button')){
				$('quick_group_manage_button').addEvent("click", function(){
					$('quick_group_manage').setStyles({
						'display' : 'block',
						'opacity' : 0,
						'top' : ($('quick_group_manage_button').getPosition().y - $('wrapper').getPosition().y)  + 50,
						'left' : ($('quick_group_manage_button').getPosition().x - $('wrapper').getPosition().x) + 100
					});
					new Fx.Style('quick_group_manage', 'opacity').start(0,1);
				});
			}
			
			if($('quick_slide_add_button')){
				$('quick_slide_add_button').addEvent("click", function(){
					$('quick_slide_add').setStyles({
						'display' : 'block',
						'opacity' : 0,
						'top' : ($('quick_slide_add_button').getPosition().y - $('wrapper').getPosition().y)  + 50,
						'left' : ($('quick_slide_add_button').getPosition().x - $('wrapper').getPosition().x) + 100
					});
					new Fx.Style('quick_slide_add', 'opacity').start(0,1);
				});
			}
			
			if($("quick_group_manage_submit")){
				$("quick_group_manage_submit").addEvent("click", function(e){
					new Event(e).stop();
					$('quick_group_manage_form').submit();
				});
			}
			
			if($("quick_group_manage_cancel")){
				$("quick_group_manage_cancel").addEvent("click", function(e){
					new Event(e).stop();
					new Fx.Style('quick_group_manage', 'opacity', {onComplete: function(){
						$('quick_group_manage').setStyle('display','none');
					}}).start(0);
				});
			}
			
			if($("quick_slide_add_submit")){
				$("quick_slide_add_submit").addEvent("click", function(e){
					new Event(e).stop();
					$('quick_slide_add_form').submit();
				});
			}
			
			if($("quick_slide_add_cancel")){
				$("quick_slide_add_cancel").addEvent("click", function(e){
					new Event(e).stop();
					new Fx.Style('quick_slide_add', 'opacity', {onComplete: function(){
						$('quick_slide_add').setStyle('display','none');
					}}).start(0);
				});
			}
		});
	</script>
      
      <div id="quick_group_manage">
            <form action="index.php?option=com_gk3_photoslide&c=group&task=view" method="post" id="quick_group_manage_form">
                  <table class="adminlist">
                        <tbody>
                        <td><select name="cid[]">
                                          <?php echo $group_list; ?>
                                    </select></td>
                              <td><button id="quick_group_manage_submit"><?php echo JText::_('CHOOSE_GROUP'); ?></button>
                                    <button id="quick_group_manage_cancel"><?php echo JText::_('CANCEL'); ?></button></td>
                              </tbody>
                  </table>
                  <input type="hidden" name="option" value="com_gk3_photoslide" />
                  <input type="hidden" name="client" value="<?php echo $client->id;?>" />
                  <input type="hidden" name="boxchecked" value="0" />
            </form>
      </div>
      <div id="quick_slide_add">
            <form action="index.php?option=com_gk3_photoslide&c=slide&task=add" method="post" id="quick_slide_add_form">
                  <table class="adminlist">
                        <tbody>
                              <tr>
                                    <td>
										<select name="gid">
                                    	<?php echo $group_list; ?>
                                     	</select>
					 				</td>
                                    <td>
										<button id="quick_slide_add_submit"><?php echo JText::_('CHOOSE_GROUP'); ?></button>
          								<button id="quick_slide_add_cancel"><?php echo JText::_('CANCEL'); ?></button>
								  	</td>
                              </tr>
                        </tbody>
                  </table>
                  <input type="hidden" name="option" value="com_gk3_photoslide" />
                  <input type="hidden" name="client" value="<?php echo $client->id;?>" />
                  <input type="hidden" name="boxchecked" value="0" />
            </form>
      </div>
</div>
<form action="index.php" method="get" name="adminForm">
      <input type="hidden" name="option" value="com_gk3_photoslide" />
      <input type="hidden" name="client" value="<?php echo $client->id;?>" />
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="c" value="mainpage" />
      <input type="hidden" name="boxchecked" value="0" />
</form>
