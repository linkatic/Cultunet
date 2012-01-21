<?php
/**
* @package Admin-User-Access (com_pi_admin_user_access)
* @version 2.3.1
* @copyright Copyright (C) 2007-2011 Carsten Engel. All rights reserved.
* @license GPL available versions: free, trial and pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

//no direct access
if(!defined('_VALID_MOS') && !defined('_JEXEC')){
	die('Restricted access');
}

if(!$class_ua->ua_config['display_actions'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//get usergroups from db
$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
$usergroups = $class_ua->db-> loadObjectList();

//get actions access from db
$class_ua->db->setQuery("SELECT action_usergroupid FROM #__pi_aua_actions");
$action_permissions = $class_ua->db-> loadResultArray();

$actions = array(
array(_pi_ua_lang_jfrontend,'header','joomla_front_end'),
array(_pi_ua_lang_new_item_frontend,23,_pi_ua_lang_new_item_frontend_tip),
array(_pi_ua_lang_new_article_right,198,_pi_ua_lang_new_article_right_tip),
array(_pi_ua_lang_edit_item_frontend,24,_pi_ua_lang_edit_item_frontend_tip),
array(_pi_ua_lang_edit_item_other_author,174,_pi_ua_lang_other_author_frontend_tip),
array(_pi_ua_lang_new_weblink_frontend,25,_pi_ua_lang_new_weblink_frontend_tip),
array(_pi_ua_lang_hide_sec_uncategorized_option,26,_pi_ua_lang_hide_sec_uncategorized_option_tip),
array(_pi_ua_lang_can_publish,42,_pi_ua_lang_can_publish_tip),
array(_pi_ua_lang_no_publish,39,_pi_ua_lang_no_publish_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_section,50,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_category,51,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_enable_access_levels_selection,43,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_enable_author_alias_selection,44,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_enable_ordering_selection,45,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_enable_start_publishing_selection,46,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_enable_stop_publishing_selection,47,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_description,52,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_keywords,53,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_enable_frontpage_selection,48,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_no_frontpage,85,_pi_ua_lang_no_frontpage_tip),
array(_pi_ua_lang_jbackend,'header','joomla_back_end'),
array(_pi_ua_lang_access_to_backend,30,_pi_ua_lang_access_to_backend_tip),
array(_pi_ua_lang_module_manager,'header3','joomla_modules'),
array(_pi_ua_lang_module_enable,184,_pi_ua_lang_module_enabled_tip),
array(_pi_ua_lang_module_copy,193,_pi_ua_lang_module_copy_tip),
array(_pi_ua_lang_module_copy_access,199,_pi_ua_lang_module_copy_access_tip),
array(_pi_ua_lang_module_delete,194,_pi_ua_lang_module_delete_tip),
array(_pi_ua_lang_module_new,195,_pi_ua_lang_module_new_tip),
array(_pi_ua_lang_module_new_rights,196,_pi_ua_lang_module_new_rights_tip),
array(_pi_ua_lang_module_reordering,179,_pi_ua_lang_module_reordering_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_module_title,188,_pi_ua_lang_module_title_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_module_showtitle,183,_pi_ua_lang_module_showtitle_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_module_position,185,_pi_ua_lang_module_position_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_module_access2,186,_pi_ua_lang_module_access2_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_module_menu,187,_pi_ua_lang_module_menu_tip),
array(_pi_ua_lang_display_help_button,201,_pi_ua_lang_display_help_button_tip),
array(_pi_ua_lang_plugin_manager,'header3','joomla_plugins'),
array(_pi_ua_lang_plugins_enable,190,_pi_ua_lang_plugins_enable_tip),
array(_pi_ua_lang_plugins_reordering,180,_pi_ua_lang_plugins_reordering_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_plugins_name,189,_pi_ua_lang_plugins_name_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_plugins_file,191,_pi_ua_lang_plugins_file_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_plugins_access,192,_pi_ua_lang_plugins_access_tip),
array(_pi_ua_lang_article_manager,'header3','joomla_articles'),
array(_pi_ua_lang_articles_reordering,181,_pi_ua_lang_articles_reordering_tip),
array(_pi_ua_lang_new_article,28,_pi_ua_lang_new_article_tip),
array(_pi_ua_lang_new_article_right,197,_pi_ua_lang_new_article_right_tip),
array(_pi_ua_lang_edit_item_backend,29,_pi_ua_lang_edit_item_backend_tip),
array(_pi_ua_lang_can_publish,31,_pi_ua_lang_can_publish_tip),
array(_pi_ua_lang_no_publish,49,_pi_ua_lang_no_publish_tip),
array(_pi_ua_lang_can_unpublish,32,_pi_ua_lang_can_unpublish_tip),
array(_pi_ua_lang_move_articles,33,_pi_ua_lang_move_articles_tip),
array(_pi_ua_lang_article_frontpage,34,_pi_ua_lang_article_frontpage_tip),
array(_pi_ua_lang_no_frontpage,54,_pi_ua_lang_no_frontpage_tip),
array(_pi_ua_lang_reorder_articles,35,_pi_ua_lang_reorder_articles_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_archive_articles,36,_pi_ua_lang_archive_articles_tip),
array(_pi_ua_lang_unarchive_articles,37,_pi_ua_lang_unarchive_articles_tip),
array(_pi_ua_lang_article_other_authors,38,_pi_ua_lang_article_other_authors_tip),
array(_pi_ua_lang_copy_article,40,_pi_ua_lang_copy_article_tip),
array(_pi_ua_lang_trash_article,41,_pi_ua_lang_trash_article_tip),
array(_pi_ua_lang_display_help_button,200,_pi_ua_lang_display_help_button_tip),
array(_pi_ua_lang_hide_sec_uncategorized_option_backend,27,_pi_ua_lang_hide_sec_uncategorized_option_backend_tip),
//array(_pi_ua_lang_item_properties,'header3',''),
array(_pi_ua_lang_enable_field._pi_ua_lang_alias_field,55,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_section_selector,56,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_category_selector,57,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_author,58,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_author_alias,59,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_access_level,60,_pi_ua_lang_enable_field_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_enable_field._pi_ua_lang_creation_date,61,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_publish_date,62,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_unpublish_date,63,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_show_title,64,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_linkable,65,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_intro_text,66,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_section_name,67,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_section_title_linkable,68,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_category_title,69,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_category_title_linkable,70,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_article_rating,71,_pi_ua_lang_enable_field_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_enable_field._pi_ua_lang_author_name,72,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_created_date_time,73,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_modified_date_time,74,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_pdf_icon,75,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_print_icon,76,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_email_icon,77,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_content_language,78,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_key_reference,79,_pi_ua_lang_key_reference_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_alt_read_more,80,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_description,81,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_keywords,82,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_robots,83,_pi_ua_lang_enable_field_tip),
array(_pi_ua_lang_enable_field._pi_ua_lang_author_meta,84,_pi_ua_lang_enable_field_tip),
array('Pages-and-Items','header','pages_and_items'),
array(_pi_ua_lang_new_page,1,_pi_ua_lang_new_page_tip),
array(_pi_ua_lang_new_link_page,2,_pi_ua_lang_new_link_page_tip),
array(_pi_ua_lang_edit_page,3,_pi_ua_lang_edit_page_tip),
array(_pi_ua_lang_edit_pagelink,4,_pi_ua_lang_edit_pagelink_tip),
array(_pi_ua_lang_publish_page,5,_pi_ua_lang_publish_page_tip),
array(_pi_ua_lang_page_trash,6,_pi_ua_lang_page_trash_tip),
array(_pi_ua_lang_page_move,7,_pi_ua_lang_page_move_tip),
array(_pi_ua_lang_notify_new_page,8,_pi_ua_lang_notify_new_page_tip),
array(_pi_ua_lang_notify_edit_page,9,_pi_ua_lang_notify_edit_page_tip),
array(_pi_ua_lang_new_item,10,_pi_ua_lang_new_item_tip),
array(_pi_ua_lang_edit_item,11,_pi_ua_lang_edit_item_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_publish_item,12,_pi_ua_lang_publish_item_tip),
array(_pi_ua_lang_no_publish,132,_pi_ua_lang_no_publish_tip),
array(_pi_ua_lang_article_other_authors,175,_pi_ua_lang_article_other_authors_tip),
array(_pi_ua_lang_trash_item,13,_pi_ua_lang_trash_item_tip),
array(_pi_ua_lang_move_item,14,_pi_ua_lang_move_item_tip),
array(_pi_ua_lang_notify_new_item,15,_pi_ua_lang_notify_new_item_tip),
array(_pi_ua_lang_notify_edit_item,16,_pi_ua_lang_notify_edit_item_tip),
array(_pi_ua_lang_image_upload,17,_pi_ua_lang_image_upload_tip),
array(_pi_ua_lang_download_upload,18,_pi_ua_lang_download_upload_tip),
array(_pi_ua_lang_download_select,19,_pi_ua_lang_download_select_tip),
array(_pi_ua_lang_download_delete,20,_pi_ua_lang_download_delete_tip),
array(_pi_ua_lang_download_reset,21,_pi_ua_lang_download_reset_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_item_properties,'header3','pi_item_properties'),
array(_pi_ua_lang_article_frontpage,86,_pi_ua_lang_article_frontpage_tip),
array(_pi_ua_lang_no_frontpage,87,_pi_ua_lang_no_frontpage_tip),
array(_pi_ua_lang_hits,96,_pi_ua_lang_hits_tip),
array(_pi_ua_lang_version,97,_pi_ua_lang_version_tip),
array(_pi_ua_lang_created_date_time,98,_pi_ua_lang_created_date_time_tip),
array(_pi_ua_lang_modified_date_time,99,_pi_ua_lang_modified_date_time_tip),
array(_pi_ua_lang_item_id,100,_pi_ua_lang_item_id_tip),
array(_pi_ua_lang_category_id,101,_pi_ua_lang_category_id_tip),
array(_pi_ua_lang_section_id,102,_pi_ua_lang_section_id_tip),
array(_pi_ua_lang_other_item,131,_pi_ua_lang_other_item_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_alias_field,88,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_show_title,89,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_access_level,90,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_author,91,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_author_alias,92,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_creation_date,93,_pi_ua_lang_show_field_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_show_field._pi_ua_lang_publish_date,94,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_unpublish_date,95,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_show_title,103,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_pdf_icon,104,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_print_icon,105,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_email_icon,106,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_linkable,107,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_intro_text,108,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_section_name,109,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_section_title_linkable,110,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_category_title,111,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_category_title_linkable,112,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_article_rating,113,_pi_ua_lang_show_field_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_show_field._pi_ua_lang_author_name,114,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_created_date_time,115,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_modified_date_time,116,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_content_language,117,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_key_reference,118,_pi_ua_lang_key_reference_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_alt_read_more,119,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_description,120,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_keywords,121,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_robots,122,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_author_meta,123,_pi_ua_lang_show_field_tip),
array('groupsrow','groupsrow','groupsrow'),
array(_pi_ua_lang_show_field._pi_ua_lang_key_reference,124,_pi_ua_lang_key_reference_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_content_language,125,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_alt_read_more,126,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_description,127,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_keywords,128,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_robots,129,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_author_meta,130,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_page_properties,'header3','pi_page_properties'),
array(_pi_ua_lang_show_field._pi_ua_lang_publish,133,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_section2,134,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_page_title,135,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_page_title_visible,136,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_show_in_menu,137,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_access,138,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_default_homepage,139,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_leading_items,140,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field.' #'._pi_ua_lang_intro_text,141,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_columns,142,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_links,143,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_category_order,144,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_primary_order,145,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_pagination,146,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_pagination_results,147,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_feed,148,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_unautorized,149,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_category_description,150,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_description_image,151,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_category_name,152,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_name_link,153,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_item_titles,154,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_show_introtext,155,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_section_title,156,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_section_title_link,157,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_linked_titles,158,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_read_more,159,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_rating,160,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_author_names,161,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_created_date,162,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_modified_date,163,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_show_nav,164,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_icons,165,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_pdf,166,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_print,167,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_email,168,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_hits,169,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_feed2,170,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_class_suffix,171,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_ssl,172,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field.ucfirst(_pi_ua_lang_alias),173,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_category_id,176,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_author_meta,177,_pi_ua_lang_show_field_tip),
array(_pi_ua_lang_show_field._pi_ua_lang_author_meta,178,_pi_ua_lang_show_field_tip)


//page_props_alias',173
);

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();

//make javascript array from actions
$javascript_array_actions = 'var actions = new Array(';
for($n = 0; $n < count($actions); $n++){	
	if($actions[$n][1]!='header' && $actions[$n][1]!='groupsrow' && $actions[$n][1]!='header3'){	
		if($n!=0 && $n!=1){
			$javascript_array_actions .= ',';	
		}	
		$javascript_array_actions .= "'".$actions[$n][1]."'";		
	}	
}	
$javascript_array_actions .= ');';
//echo $javascript_array_actions;

?>
<script language="javascript" type="text/javascript">

<?php echo $javascript_array_actions."\n"; ?>

function select_all(usergroup_id, select_all_id){
	action = document.getElementById(select_all_id).checked;	
	for (i = 0; i < actions.length; i++){
		box_id = actions[i]+'__'+usergroup_id;
		if(action==true){
			document.getElementById(box_id).checked = true;
		}else{
			document.getElementById(box_id).checked = false;
		}
	}	
}

</script>

<form name="adminForm" method="post" action="">
		<input type="hidden" name="option" value="com_pi_admin_user_access" />
		<input type="hidden" name="task" value="actions_save" />
		<?php $class_ua->not_in_free_version(); ?>
<p><?php echo _pi_ua_lang_actions_info; ?>
</p>		
<p>
	<ul>
		<li>
			<a href="#joomla_front_end"><?php echo _pi_ua_lang_jfrontend; ?></a>
		</li>
		<li>
			<a href="#joomla_back_end"><?php echo _pi_ua_lang_jbackend; ?></a>
			<ul>
				<li>
					<a href="#joomla_modules"><?php echo _pi_ua_lang_module_manager; ?></a>
				</li>
				<li>
					<a href="#joomla_plugins"><?php echo _pi_ua_lang_plugin_manager; ?></a>
				</li>
				<li>
					<a href="#joomla_articles"><?php echo _pi_ua_lang_article_manager; ?></a>
				</li>
			</ul>
		</li>
		<li>
			<a href="#pages_and_items">Pages-and-Items</a>
			<ul>
				<li>
					<a href="#pi_item_properties"><?php echo _pi_ua_lang_item_properties; ?></a>
				</li>
				<li>
					<a href="#pi_page_properties"><?php echo _pi_ua_lang_page_properties; ?></a>
				</li>				
			</ul>
		</li>
	</ul>
</p>
<table class="adminlist">	
		
	<?php
		
		//row with select_all checkboxes
		echo '<tr>';
		echo '<td>'._pi_ua_lang_selectall.'</td>';
		foreach($usergroups as $usergroup){
			echo '<td style="text-align:center;"><input type="checkbox" name="checkall[]" value="" id="checkall_'.$usergroup->id.'" onclick="select_all('.$usergroup->id.',this.id);" /></td>';
		}
		echo '</tr>';
		
		$k = 1;			
		for($n = 0; $n < count($actions); $n++){
			$action_array = $actions[$n];			
			for($m = 0; $m < count($action_array); $m++){				
				$action_text = $action_array[0];
				$action_name = $action_array[1];
				$action_tooltip = $action_array[2];				
			}
			if($action_name=='header'){	
				
				echo '<tr>';
				echo '<td colspan="'.(count($usergroups)+1).'">';
				echo '&nbsp;<a name="'.$action_tooltip.'"></a>';
				echo '</td>';
				echo '</tr>';
				if($k==1){
					$k = 0;
				}else{
					$k = 1;
				}	
				echo '<tr><th>&nbsp;</th>';	
				$class_ua->loop_usergroups($usergroups);
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="'.(count($usergroups)+1).'" style="font-size: 3em; font-weight: bold;">';
				echo $action_text;
				echo '</td>';
				echo '</tr>';
			}elseif($action_name=='groupsrow'){
				if($k==1){
					$k = 0;
				}else{
					$k = 1;
				}
				echo '<tr><th>&nbsp;</th>';	
				$class_ua->loop_usergroups($usergroups);
				echo '</tr>';	
			}elseif($action_name=='header3'){
				if($k==1){
					$k = 0;
				}else{
					$k = 1;
				}
				echo '<tr>';
				echo '<td colspan="'.(count($usergroups)+1).'">';
				echo '&nbsp;<a name="'.$action_tooltip.'"></a>';
				echo '<span style="font-size: 1.5em; font-weight: bold; color: #000;">'.$action_text.'</span>&nbsp;';				
				echo '</td>';
				echo '</tr>';		
			}else{					
				echo '<tr class="row'.$k.'"><td>';
				echo '<span class="editlinktip"><span onmouseover="return overlib(\''.$action_tooltip.'\', CAPTION, \''.$action_text.'\', BELOW, RIGHT, WIDTH, 450);" onmouseout="return nd();" >'.$action_text.'</span></span>';
				echo '</td>';		
				foreach($usergroups as $usergroup){
					$checked = '';
					if (in_array($action_name.'__'.$usergroup->id, $action_permissions)) {
						$checked = 'checked="checked"';
					}
					echo '<td align="center"><input type="checkbox" name="actions_permissions[]" id="'.$action_name.'__'.$usergroup->id.'" value="'.$action_name.'__'.$usergroup->id.'" '.$checked.' /></td>';
				}
				echo '</tr>';
			}
			if($k==1){
				$k = 0;
			}else{
				$k = 1;
			}
		}
	
	?>
			
</table>
</form>
<?php
$class_ua->display_footer();
?>