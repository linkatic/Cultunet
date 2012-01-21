<?php
/**
 * @copyright	Copyright (C) 2009-2011 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

class plgAcymailingTagmosets extends JPlugin
{

	var $allFields = array();

	function plgAcymailingTagmosets(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('acymailing', 'tagmosets');
			$this->params = new JParameter( $plugin->params );
		}
    }

	 function acymailing_getPluginType() {

	 	$onePlugin = null;
	 	$onePlugin->name = 'Mosets Tree';
	 	$onePlugin->function = 'acymailingtagmosets_show';
	 	$onePlugin->help = 'plugin-tagmosets';

	 	return $onePlugin;
	 }

	 function acymailingtagmosets_show(){
		$app =& JFactory::getApplication();

		$pageInfo = null;

		$paramBase = ACYMAILING_COMPONENT.'.tagmosets';
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'a.link_id','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'desc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );
		$pageInfo->filter_cat = $app->getUserStateFromRequest( $paramBase.".filter_cat", 'filter_cat','','int' );
		$pageInfo->titlelink = $app->getUserStateFromRequest( $paramBase.".titlelink", 'titlelink','|link','string' );
		$pageInfo->contenttype = $app->getUserStateFromRequest( $paramBase.".contenttype", 'contenttype','|type:intro','string' );
		$pageInfo->images = $app->getUserStateFromRequest( $paramBase.".images", 'images','|images','string' );
		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );
		$pageInfo->autotitlelink = $app->getUserStateFromRequest( $paramBase.".autotitlelink", 'autotitlelink','|link','string' );
		$pageInfo->autoimages = $app->getUserStateFromRequest( $paramBase.".autoimages", 'autoimages','|images','string' );
		$pageInfo->automaxvalue = $app->getUserStateFromRequest( $paramBase.".max", 'max','','int' );
		$pageInfo->contentfilter = $app->getUserStateFromRequest( $paramBase.".contentfilter", 'contentfilter','|filter:created','string' );
		$pageInfo->contentorder = $app->getUserStateFromRequest( $paramBase.".contentorder", 'contentorder','|order:id','string' );
		$pageInfo->autocontenttype = $app->getUserStateFromRequest( $paramBase.".contenttypeauto", 'contenttypeauto','|type:intro','string' );

		$db =& JFactory::getDBO();

		$searchFields = array('a.link_id','a.link_name','c.name','a.user_id');

		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.$db->getEscaped($pageInfo->search,true).'%\'';
			$filters[] = implode(" LIKE $searchVal OR ",$searchFields)." LIKE $searchVal";
		}

		if($this->params->get('displaypub','all') == 'onlypub'){
			$filters[] = "a.link_published = 1";
		}

		$query = 'SELECT SQL_CALC_FOUND_ROWS c.name,c.username,a.user_id,a.link_id,a.link_name ';
		if(empty($pageInfo->filter_cat)){
			$query .= 'FROM `#__mt_links` as a';
		}else{
			$query .= 'FROM `#__mt_cl` as b LEFT JOIN `#__mt_links` as a ON a.link_id = b.link_id';
			$filters[] = "b.cat_id = ".$pageInfo->filter_cat;
		}
		$query .= ' LEFT JOIN `#__users` as c ON a.user_id = c.id';

		if(!empty($filters)){
			$query .= ' WHERE ('.implode(') AND (',$filters).')';
		}

		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}

		$db->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $db->loadObjectList();

		if(!empty($pageInfo->search)){
			$rows = acymailing::search($pageInfo->search,$rows);
		}

		$db->setQuery('SELECT FOUND_ROWS()');
		$pageInfo->elements->total = $db->loadResult();
		$pageInfo->elements->page = count($rows);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

		$type = JRequest::getString('type');

	?>

		<script language="javascript" type="text/javascript">
		<!--
			var selectedContents = new Array();
			function applyContent(contentid,rowClass){
				if(selectedContents[contentid]){
					window.document.getElementById('content'+contentid).className = rowClass;
					delete selectedContents[contentid];
				}else{
					window.document.getElementById('content'+contentid).className = 'selectedrow';
					selectedContents[contentid] = 'content';
				}

				updateTag();
			}

			function updateTag(){
				var tag = '';
				var otherinfo = '';
				for(var i=0; i < document.adminForm.contenttype.length; i++){
				   if (document.adminForm.contenttype[i].checked){ selectedtype = document.adminForm.contenttype[i].value; otherinfo += document.adminForm.contenttype[i].value; }
				}
				for(var i=0; i < document.adminForm.images.length; i++){
				   if (document.adminForm.images[i].checked){ otherinfo += document.adminForm.images[i].value; }
				}
				for(var i=0; i < document.adminForm.titlelink.length; i++){
				   if (document.adminForm.titlelink[i].checked){ otherinfo += document.adminForm.titlelink[i].value; }
				}
				for(var i in selectedContents){
					if(selectedContents[i] == 'content'){
						tag = tag + '{mosets:'+i+otherinfo+'}<br/>';
					}
				}
				setTag(tag);
			}

			var selectedCat = new Array();
			function applyAuto(catid,rowClass){
				if(selectedCat[catid]){
					window.document.getElementById('cat'+catid).className = rowClass;
					delete selectedCat[catid];
				}else{
					window.document.getElementById('cat'+catid).className = 'selectedrow';
					selectedCat[catid] = 'selectedone';
				}

				updateTagAuto();
			}

			function updateTagAuto(){
				tag = '{automosets:';

				for(var icat in selectedCat){
					if(selectedCat[icat] == 'selectedone'){
						tag += icat+'-';
					}
				}

				if(document.adminForm.min_article && document.adminForm.min_article.value && document.adminForm.min_article.value!=0){ tag += '|min:'+document.adminForm.min_article.value; }
				if(document.adminForm.max_article.value && document.adminForm.max_article.value!=0){ tag += '|max:'+document.adminForm.max_article.value; }
				for(var i=0; i < document.adminForm.contenttypeauto.length; i++){
				   if (document.adminForm.contenttypeauto[i].checked){ selectedtype = document.adminForm.contenttypeauto[i].value; tag += document.adminForm.contenttypeauto[i].value; }
				}
				for(var i=0; i < document.adminForm.autoimages.length; i++){
				   if (document.adminForm.autoimages[i].checked){ tag += document.adminForm.autoimages[i].value; }
				}
				for(var i=0; i < document.adminForm.autotitlelink.length; i++){
				   if (document.adminForm.autotitlelink[i].checked){ tag += document.adminForm.autotitlelink[i].value; }
				}

				if(document.adminForm.contentorder.value){ tag += document.adminForm.contentorder.value; }
				if(document.adminForm.contentfilter && document.adminForm.contentfilter.value){ tag += document.adminForm.contentfilter.value; }

				tag += '}';

				setTag(tag);
			}
		//-->
		</script>
		<?php
		$valImages = array();
		$valImages[] = JHTML::_('select.option', "|images",JText::_('JOOMEXT_YES'));
		$valImages[] = JHTML::_('select.option', "",JText::_('JOOMEXT_NO'));

		$ordering = array();
		$ordering[] = JHTML::_('select.option', "|order:link_id,DESC",JText::_('ID'));
		$ordering[] = JHTML::_('select.option', "|order:ordering,ASC",JText::_('ORDERING'));
		$ordering[] = JHTML::_('select.option', "|order:link_created,DESC",JText::_('CREATED_DATE'));
		$ordering[] = JHTML::_('select.option', "|order:link_modified,DESC",JText::_('MODIFIED_DATE'));
		$ordering[] = JHTML::_('select.option', "|order:link_name,ASC",JText::_('TITLE'));

		$contenttype = array();
		$contenttype[] = JHTML::_('select.option', "|type:title",JText::_('TITLE_ONLY'));
		$contenttype[] = JHTML::_('select.option', "|type:intro",JText::_('CATEGORY_VIEW'));
		$contenttype[] = JHTML::_('select.option', "|type:full",JText::_('DETAILS_VIEW'));

		jimport('joomla.html.pane');
		$tabs	=& JPane::getInstance('tabs');
		echo $tabs->startPane( 'mosets_tab');
		echo $tabs->startPanel( JText::_('TAG_ELEMENTS'), 'mosets_listings');
		?>
		<br style="font-size:1px"/>
		<table width="100%" class="adminform">
			<tr>
				<td><?php echo JText::_('DISPLAY');?></td>
				<td colspan="3"><?php echo JHTML::_('select.radiolist', $contenttype, 'contenttype' , 'size="1" onclick="updateTag();"', 'value', 'text', $pageInfo->contenttype); ?></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('DISPLAY_PICTURES');?>
				</td>
				<td>
				<?php echo JHTML::_('select.radiolist', $valImages, 'images' , 'size="1" onclick="updateTag();"', 'value', 'text', $pageInfo->images);?>
				</td>
				<td>
					<?php echo JText::_('CLICKABLE_TITLE');?>
				</td>
				<td>
					<?php $titlelinkType = acymailing::get('type.titlelink'); echo $titlelinkType->display('titlelink',$pageInfo->titlelink);?>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td width="100%">
					<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
					<input type="text" name="search" id="acymailingsearch" value="<?php echo $pageInfo->search;?>" class="text_area" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
					<button onclick="document.getElementById('acymailingsearch').value='';this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
				</td>
				<td nowrap="nowrap">
					<?php echo $this->_categories($pageInfo->filter_cat); ?>
				</td>
			</tr>
		</table>

		<table class="adminlist" cellpadding="1" width="100%">
			<thead>
				<tr>
					<th class="title">
						<?php echo JHTML::_('grid.sort', JText::_( 'TITLE'), 'a.link_name', $pageInfo->filter->order->dir,$pageInfo->filter->order->value ); ?>
					</th>
					<th class="title">
						<?php echo JHTML::_('grid.sort', JText::_( 'AUTHOR'), 'c.name', $pageInfo->filter->order->dir,$pageInfo->filter->order->value ); ?>
					</th>
					<th class="title titleid">
						<?php echo JHTML::_('grid.sort',   JText::_( 'ID' ), 'a.link_id', $pageInfo->filter->order->dir, $pageInfo->filter->order->value ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">
						<?php echo $pagination->getListFooter(); ?>
						<?php echo $pagination->getResultsCounter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
					$k = 0;
					for($i = 0,$a = count($rows);$i<$a;$i++){
						$row =& $rows[$i];
				?>
					<tr id="content<?php echo $row->link_id; ?>" class="<?php echo "row$k"; ?>" onclick="applyContent(<?php echo $row->link_id.",'row$k'"?>);" style="cursor:pointer;">
						<td>
						<?php
							echo $row->link_name;
						?>
						</td>
						<td>
						<?php
							if(!empty($row->name)){
								$text = '<b>'.JText::_('NAME',true).' : </b>'.$row->name;
								$text .= '<br/><b>'.JText::_('USERNAME',true).' : </b>'.$row->username;
								$text .= '<br/><b>'.JText::_('ID',true).' : </b>'.$row->user_id;
								echo acymailing::tooltip($text, $row->name, '', $row->name);
							}
						?>
						</td>
						<td align="center">
							<?php echo $row->link_id; ?>
						</td>
					</tr>
				<?php
						$k = 1-$k;
					}
				?>
			</tbody>
		</table>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $pageInfo->filter->order->value; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $pageInfo->filter->order->dir; ?>" />
	<?php

		echo $tabs->endPanel();
		echo $tabs->startPanel( JText::_('TAG_CATEGORIES'), 'mosets_auto');
?>
		<br style="font-size:1px"/>
		<table width="100%" class="adminform">
			<tr>
				<td><?php echo JText::_('DISPLAY');?></td>
				<td colspan="3"><?php echo JHTML::_('select.radiolist', $contenttype, 'contenttypeauto' , 'size="1" onclick="updateTagAuto();"', 'value', 'text', $pageInfo->autocontenttype); ?></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('DISPLAY_PICTURES');?>
				</td>
				<td>
				<?php echo JHTML::_('select.radiolist', $valImages, 'autoimages' , 'size="1" onclick="updateTagAuto();"', 'value', 'text', $pageInfo->autoimages);?>
				</td>
				<td>
					<?php echo JText::_('CLICKABLE_TITLE');?>
				</td>
				<td>
					<?php $titlelinkType = acymailing::get('type.titlelink'); $titlelinkType->onclick='updateTagAuto();'; echo $titlelinkType->display('autotitlelink',$pageInfo->autotitlelink);?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('MAX_ARTICLE'); ?>
				 </td>
				 <td>
				 	<input name="max_article" size="10" value="<?php echo $pageInfo->automaxvalue; ?>" onchange="updateTagAuto();"/>
				</td>
				<td>
				<?php echo JText::_('ORDER BY'); ?>
				 </td>
				 <td>
				 	<?php echo JHTML::_('select.genericlist', $ordering, 'contentorder' , 'size="1" onchange="updateTagAuto();"', 'value', 'text', $pageInfo->contentorder); ?>
				</td>
			</tr>
			<?php if($type == 'autonews') { ?>
			<tr>
				<td>
				<?php 	echo JText::_('MIN_ARTICLE'); ?>
				 </td>
				 <td>
				 <input name="min_article" size="10" value="1" onchange="updateTagAuto();"/>
				</td>
				<td>
				<?php echo JText::_('FILTER'); ?>
				 </td>
				 <td>
				 	<?php $filter = acymailing::get('type.contentfilter'); $filter->onclick='updateTagAuto();'; echo $filter->display('contentfilter',$pageInfo->contentfilter); ?>
				</td>
			</tr>
			<?php } ?>
		</table>
		<table class="adminlist" cellpadding="1" width="100%">
		<?php $k=0; foreach($this->catvalues as $oneCat){
			 if(empty($oneCat->value)) continue;
			?>
			<tr id="cat<?php echo $oneCat->value ?>" class="<?php echo "row$k"; ?>" onclick="applyAuto(<?php echo $oneCat->value ?>,'<?php echo "row$k" ?>');" style="cursor:pointer;">
				<td>
				<?php
					echo $oneCat->text;
				?>
				</td>
			</tr>
		<?php $k = 1 - $k; } ?>
		</table>
		<?php
		echo $tabs->endPanel();
		echo $tabs->endPane();

	//End of the function
	 }

	function _categories($filter_cat){
		//select all cats
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT * FROM `#__mt_cats` ORDER BY `ordering` DESC');
		$mosetCats = $db->loadObjectList();
		$this->cats = array();
		foreach($mosetCats as $oneCat){
			$this->cats[$oneCat->cat_parent][] = $oneCat;
		}

		$this->catvalues = array();
		$this->catvalues[] = JHTML::_('select.option', 0,JText::_('ALL'));
		$this->_handleChildrens();
		return JHTML::_('select.genericlist',   $this->catvalues, 'filter_cat', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', (int) $filter_cat);

	}

	function _handleChildrens($parentId=0,$level=0){
		if(!empty($this->cats[$parentId])){
			foreach($this->cats[$parentId] as $cat){
				//$cat->name = str_repeat($this->separator,$level).$cat->cat_name;
				$cat->name = $cat->cat_name;
				$this->catvalues[] = JHTML::_('select.option', $cat->cat_id,str_repeat(" - - ",$level).$cat->name);
				$this->_handleChildrens($cat->cat_id,$level+1);
			}
		}
	}

	 function _replaceOne(&$email){

		$match = '#{mosets:(.*)}#Ui';
		$variables = array('body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			//we unset the results so that we won't handle it later... it will save some memory and processing
			if(empty($results[$var][0])) unset($results[$var]);
		}

		//If we didn't find anything...
		if(!$found) return;


		//We will need the mailer class as well
		$this->mailerHelper = acymailing::get('helper.mailer');

		$htmlreplace = array();
		$textreplace = array();
		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				//Don't need to process twice a tag we already have!
				if(isset($htmlreplace[$oneTag])) continue;

				$content = $this->_replaceContent($allresults,$i);
				$htmlreplace[$oneTag] = $content;
				$textreplace[$oneTag] = $this->mailerHelper->textVersion($content,true);
			}
		}

		$email->body = str_replace(array_keys($htmlreplace),$htmlreplace,$email->body);
		$email->altbody = str_replace(array_keys($textreplace),$textreplace,$email->altbody);
	 }

	 function _replaceContent(&$results,$i){
		//1 : Transform the tag properly...
		$arguments = explode('|',$results[1][$i]);
		$tag = null;
		$tag->id = (int) $arguments[0];
		$tag->type = 'full';
		for($i=1,$a=count($arguments);$i<$a;$i++){
			$args = explode(':',$arguments[$i]);
			if(isset($args[1])){
				$tag->$args[0] = $args[1];
			}else{
				$tag->$args[0] = true;
			}
		}

		//2 : Load the content
		$query = 'SELECT b.name , b.email , b.username , d.cat_name, a.* FROM `#__mt_links` as a ';
		$query .= 'LEFT JOIN `#__users` as b ON a.user_id = b.id ';
		$query .= 'LEFT JOIN `#__mt_cl` as c ON a.link_id = c.link_id AND main = 1 ';
		$query .= 'LEFT JOIN `#__mt_cats` as d ON c.cat_id = d.cat_id ';
		$query .= 'WHERE a.link_id = '.$tag->id.' LIMIT 1';

		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$content = $db->loadObject();

		//In case of we could not load the article for any reason...
		if(empty($content)){
			$app =& JFactory::getApplication();
			if($app->isAdmin()){
				$app->enqueueMessage('The listing "'.$tag->id.'" could not be loaded','notice');
			}
			return '';
		}

		if($tag->type=='title'){
			$result = '';
			if(!empty($tag->link)) $result.= '<a style="text-decoration:none;" href="'.ACYMAILING_LIVE.'index.php?option=com_mtree&task=viewlink&link_id='.$tag->id.'">';
			$result .= $content->link_name;
			if(!empty($tag->link)) $result.= '</a>';
			return $result;
		}	
		if(empty($this->allFields)){
			//Load the rest of the content
			$query = 'SELECT a.* FROM `#__mt_customfields` as a WHERE a.`published` = 1 ORDER BY a.`ordering` ASC';
			$db->setQuery($query);
			$this->allFields = $db->loadObjectList();
		}
		//Load the rest of the content
		$db->setQuery('SELECT * FROM `#__mt_cfvalues` WHERE `link_id` = '.$tag->id);
		$contentValues = $db->loadObjectList('cf_id');


		if(!empty($tag->images)){
			$db->setQuery('SELECT filename FROM `#__mt_images` WHERE `link_id` = '.$tag->id.' ORDER BY `ordering` ASC');
			$images = $db->loadResultArray();
		}

		if(!empty($content->link_template) AND file_exists(ACYMAILING_MEDIA.'plugins'.DS.'tagmosets_'.$content->link_template.'.php')){
			ob_start();
			require(ACYMAILING_MEDIA.'plugins'.DS.'tagmosets_'.$content->link_template.'.php');
			return ob_get_clean();
		}
		if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'tagmosets_default.php')){
			ob_start();
			require(ACYMAILING_MEDIA.'plugins'.DS.'tagmosets_default.php');
			return ob_get_clean();
		}

		//No template so we will do it by ourself...
		$result = '<div class="acymailing_content">';
		if(!empty($tag->link)) $result.= '<a style="text-decoration:none;" href="'.ACYMAILING_LIVE.'index.php?option=com_mtree&task=viewlink&link_id='.$tag->id.'">';
		$result .= '<h2 class="acymailing_title">'.$content->link_name.'</h2>';
		if(!empty($tag->link)) $result.= '</a>';
		foreach($this->allFields as $oneField){
			if(($tag->type == 'intro' && empty($oneField->summary_view)) || ($tag->type == 'full' && empty($oneField->details_view))) continue;
			if($oneField->field_type == 'corename') continue;
			if($oneField->iscore){
				if($oneField->field_type == 'coredesc'){
					$currentValue = $content->link_desc;
				}else{
					$name = str_replace('core','',$oneField->field_type);
					$currentValue = @$content->$name;
				}
			}else{
				$currentValue = @$contentValues[$oneField->cf_id]->value;
			}
			if(empty($oneField->details_view) OR strlen($currentValue) < 1) continue;
			$result .=  '<div class="mosetfield">';
			if(empty($oneField->hide_caption)) $result .=  '<span class="mosetcaption">'.$oneField->caption.' : </span>';
			$result .= '<span class="mosetvalue">';
			if(!empty($oneField->prefix_text_display)) $result .=  $oneField->prefix_text_display;
			$result .=  $currentValue;
			if(!empty($oneField->suffix_text_display)) $result .=  $oneField->suffix_text_display;
			$result .=  '</span>';
			$result .=  '</div>';
		}
		if(!empty($images) AND !empty($tag->images)){
			$result .= '<div class="mosetimages">';
			foreach($images as $onePath){
				$result .= '<img style="padding:5px;float:left" hspace="2" vspace="2" src="'.ACYMAILING_LIVE.'components/com_mtree/img/listings/s/'.$onePath.'" />';
			}
			$result .= '</div>';
		}
		$result .= '</div>';

		return $result;

	}

	 function acymailing_replacetags(&$email){
	 	$this->_replaceAuto($email);
	 	$this->_replaceOne($email);
	 }

	 function _replaceAuto(&$email){
		$this->acymailing_generateautonews($email);

		if(!empty($this->tags)){
			$email->body = str_replace(array_keys($this->tags),$this->tags,$email->body);
			if(!empty($email->altbody)) $email->altbody = str_replace(array_keys($this->tags),$this->tags,$email->altbody);
		}
	}

	function acymailing_generateautonews(&$email){

		$return = null;
		$return->status = true;
		$return->message = '';

		$time = time();
		//Check if we should generate the autoNewsletter or not...
		$match = '#{automosets:(.*)}#Ui';
		$variables = array('body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			//we unset the results so that we won't handle it later... it will save some memory and processing
			if(empty($results[$var][0])) unset($results[$var]);
		}

		//If we didn't find anything... so we won't try to stop the generation
		if(!$found) return $return;

		$this->tags = array();
		$db =& JFactory::getDBO();

		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				if(isset($this->tags[$oneTag])) continue;

				$arguments = explode('|',$allresults[1][$i]);
				//The first argument is a list of sections and cats...
				$allcats = explode('-',$arguments[0]);
				$parameter = null;
				for($i=1;$i<count($arguments);$i++){
					$args = explode(':',$arguments[$i]);
					$arg0 = $args[0];
					if(isset($args[1])){
						$parameter->$arg0 = $args[1];
					}else{
						$parameter->$arg0 = true;
					}
				}
				//Load the articles based on all arguments...
				$selectedArea = array();
				foreach($allcats as $oneCat){
					if(empty($oneCat)) continue;
					$selectedArea[] = (int) $oneCat;
				}

				$query = 'SELECT DISTINCT a.`link_id` FROM `#__mt_links` as a LEFT JOIN `#__mt_cl` as b ON a.link_id = b.link_id';
				$where = array();
				if(!empty($selectedArea)){
					$where[] = 'b.`cat_id` IN ('.implode(',',$selectedArea).')';
				}

				if(!empty($parameter->filter) AND !empty($email->params['lastgenerateddate'])){
					$condition = 'a.`publish_up` >\''.date( 'Y-m-d H:i:s',$email->params['lastgenerateddate'] - date('Z')).'\'';
					$condition .= ' OR a.`link_created` >\''.date( 'Y-m-d H:i:s',$email->params['lastgenerateddate'] - date('Z')).'\'';
					if($parameter->filter == 'modify'){
						$condition .= ' OR a.`link_modified` > \''.date( 'Y-m-d H:i:s',$email->params['lastgenerateddate'] - date('Z')).'\'';
					}
					$where[] = $condition;
				}

				$where[] = 'a.`publish_up` < \'' .date( 'Y-m-d H:i:s',$time - date('Z')).'\'';
				$where[] = 'a.`publish_down` > \''.date( 'Y-m-d H:i:s',$time - date('Z')).'\' OR `publish_down` = 0';
				$where[] = 'a.`link_published` = 1';

				$query .= ' WHERE ('.implode(') AND (',$where).')';

				if(!empty($parameter->order)){
					$ordering = explode(',',$parameter->order);
					$query .= ' ORDER BY a.`'.acymailing::secureField($ordering[0]).'` '.acymailing::secureField($ordering[1]);
				}

				if(!empty($parameter->max)) $query .= ' LIMIT '.(int) $parameter->max;

				$db->setQuery($query);
				$allArticles = $db->loadResultArray();

				if(!empty($parameter->min) AND count($allArticles)< $parameter->min){
					//We won't generate the Newsletter
					$return->status = false;
					$return->message = 'Not enough mosets listings for the tag '.$oneTag.' : '.count($allArticles).' / '.$parameter->min;
				}

				$stringTag = '';
				if(!empty($allArticles)){
					if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'automosets.php')){
						ob_start();
						require(ACYMAILING_MEDIA.'plugins'.DS.'automosets.php');
						$stringTag = ob_get_clean();
					}else{
						//we insert the article tag one after the other in a table as they are already sorted
						$stringTag .= '<table>';
						foreach($allArticles as $oneArticleId){
							$stringTag .= '<tr><td>';
							$args = array();
							$args[] = 'mosets:'.$oneArticleId;
							if(!empty($parameter->link)) $args[] = 'link';
							if(!empty($parameter->type)) $args[] = 'type:'.$parameter->type;
							if(!empty($parameter->images)) $args[] = 'images';
							$stringTag .= '{'.implode('|',$args).'}';
							$stringTag .= '</td></tr>';
						}
						$stringTag .= '</table>';
					}
				}

				$this->tags[$oneTag] = $stringTag;
			}
		}

		return $return;
	}
}//endclass