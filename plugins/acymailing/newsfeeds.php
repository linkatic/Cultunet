<?php
/**
 * @copyright	Copyright (C) 2009-2011 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

class plgAcymailingnewsfeeds extends JPlugin
{

	function plgAcymailingnewsfeeds(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('acymailing', 'newsfeeds');
			$this->params = new JParameter( $plugin->params );
		}
    }

	 function acymailing_getPluginType() {

	 	$onePlugin = null;
	 	$onePlugin->name = 'News Feeds';
	 	$onePlugin->function = 'acymailingtagnewsfeeds_show';
	 	$onePlugin->help = 'plugin-newsfeeds';

	 	return $onePlugin;
	 }

	 function acymailingtagnewsfeeds_show(){
		$db =& JFactory::getDBO();
		$app =& JFactory::getApplication();

	    $pageInfo = null;

	    $paramBase = ACYMAILING_COMPONENT.'.newsfeeds';
	    $pageInfo->filter_cat = $app->getUserStateFromRequest( $paramBase.".filter_cat", 'filter_cat','','int' );
	    $pageInfo->filter_type = $app->getUserStateFromRequest( $paramBase.".filter_type", 'filter_type','','int' );
		$pageInfo->pict = $app->getUserStateFromRequest( $paramBase.".pict", 'pict','|pict:1','string' );
		$pageInfo->link = $app->getUserStateFromRequest( $paramBase.".link", 'link','|link','string' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );
		$pageInfo->automaxvalue = $app->getUserStateFromRequest( $paramBase.".max", 'max','20','int' );

		$searchFields = array('a.id','a.name','c.title');

		$query = 'SELECT a.*,c.title as catname FROM #__newsfeeds AS a LEFT JOIN #__categories AS c ON c.id = a.catid ';
		if(!empty($pageInfo->filter_cat)) $filters[] = " a.catid = ".$pageInfo->filter_cat;
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.$db->getEscaped($pageInfo->search,true).'%\'';
			$filters[] = implode(" LIKE $searchVal OR ",$searchFields)." LIKE $searchVal";
		}
		if(!empty($filters)){
			$query .= ' WHERE ('.implode(') AND (',$filters).')';
		}
		$query .= ' ORDER BY catname, a.ordering';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if(!empty($pageInfo->search)){
			$rows = acymailing::search($pageInfo->search,$rows);
		}
		$picts = array();
	    $picts[] = JHTML::_('select.option', "|pict:1",JText::_('JOOMEXT_YES'));
	    $picts[] = JHTML::_('select.option', "|pict:resized",JText::_('RESIZED'));
	    $picts[] = JHTML::_('select.option', "|pict:0",JText::_('JOOMEXT_NO'));
		$link = array();
		$link[] = JHTML::_('select.option', "|link",JText::_('JOOMEXT_YES'));
		$link[] = JHTML::_('select.option', "",JText::_('JOOMEXT_NO'));
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
				if(document.adminForm.max_article.value && document.adminForm.max_article.value!=0){ otherinfo += '|max:'+document.adminForm.max_article.value; }
				for(var i=0; i < document.adminForm.link.length; i++){
					if (document.adminForm.link[i].checked){ otherinfo += document.adminForm.link[i].value; }
				}
				for(var i=0; i < document.adminForm.pict.length; i++){
				   if (document.adminForm.pict[i].checked){ otherinfo += document.adminForm.pict[i].value; }
				}
				for(var i in selectedContents){
					if(selectedContents[i] == 'content'){
						tag = tag + '{newsfeeds:'+i+otherinfo+'}<br/>';
					}
				}
				setTag(tag);
			}
	//-->
	</script>
    <table width="100%" class="adminform">
      <tr>
        <td>
        <?php echo JText::_('CLICKABLE_TITLE'); ?>
         </td>
         <td><?php echo JHTML::_('select.radiolist', $link, 'link' , 'size="1" onclick="updateTag();"', 'value', 'text', $pageInfo->link); ?></td>
         <td>

         </td>
         <td>

        </td>
      </tr>
      <tr>
        <td><?php echo JText::_('DISPLAY_PICTURES'); ?></td>
        <td><?php echo JHTML::_('select.radiolist', $picts, 'pict' , 'size="1" onclick="updateTag();"', 'value', 'text', '|pict:resized'); ?></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
	  	 <td>
			<?php echo JText::_('MAX_ARTICLE'); ?>
		 </td>
		 <td>
		 	<input name="max_article" size="10" value="<?php echo $pageInfo->automaxvalue; ?>" onchange="updateTagAuto();"/>
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
						<?php echo JText::_( 'FIELD_TITLE'); ?>
					</th>
					<th class="title">
						<?php echo JText::_( 'TAG_CATEGORIES'); ?>
					</th>
					<th class="title titleid">
						<?php echo JText::_( 'ACY_ID' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$k = 0;
					for($i = 0,$a = count($rows);$i<$a;$i++){
						$row =& $rows[$i];
				?>
					<tr id="content<?php echo $row->id; ?>" class="<?php echo "row$k"; ?>" onclick="applyContent(<?php echo $row->id.",'row$k'"?>);" style="cursor:pointer;">
						<td>
						<?php
							$text = '<b>'.JText::_('JOOMEXT_ALIAS').' : </b>'.$row->alias;
							echo acymailing::tooltip($text, $row->name, '', $row->name);
						?>
						</td>
						<td align="center">
							<?php
								echo $row->catname;
							?>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
				<?php
						$k = 1-$k;
					}
				?>
			</tbody>
		</table>
	<?php
	 }
	function _categories($filter_cat){
		//select all cats
		$db =& JFactory::getDBO();
		if(version_compare(JVERSION,'1.6.0','<')){
			$db->setQuery('SELECT id,parent_id,title FROM #__categories WHERE section = "com_newsfeeds" ORDER BY `id` DESC');
		}else{
			$db->setQuery('SELECT id,parent_id,title FROM #__categories WHERE extension = "com_newsfeeds" ORDER BY `id` DESC');
		}
		$mosetCats = $db->loadObjectList();
		$this->cats = array();
		foreach($mosetCats as $oneCat){
			$this->cats[$oneCat->parent_id][] = $oneCat;
		}
		$this->catvalues = array();
		$this->catvalues[] = JHTML::_('select.option', 0,JText::_('ACY_ALL'));
		$this->_handleChildrens();
		return JHTML::_('select.genericlist',   $this->catvalues, 'filter_cat', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', (int) $filter_cat);

	}
	function _handleChildrens($parent_id=0,$level=0){
	  if(version_compare(JVERSION,'1.6.0','>') && $parent_id == 0){
		$parent_id = 1;
	  }
		if(!empty($this->cats[$parent_id])){
			foreach($this->cats[$parent_id] as $cat){
				//$cat->name = str_repeat($this->separator,$level).$cat->cat_name;
				$this->catvalues[] = JHTML::_('select.option', $cat->id,str_repeat(" - - ",$level).$cat->title);
				$this->_handleChildrens($cat->id,$level+1);
			}
		}
	}
	function acymailing_replacetags(&$email){
		$this->acymailing_generateautonews($email);

		if(!empty($this->tags)){
			$email->body = str_replace(array_keys($this->tags),$this->tags,$email->body);
			if(!empty($email->altbody)) $email->altbody = str_replace(array_keys($this->tags),$this->tags,$email->altbody);
			foreach($this->tags as $tag => $result){
				$email->subject = str_replace($tag,strip_tags(str_replace('</tr><tr>',' | ',$result)),$email->subject);
			}
		}
	 }


	 function _replaceContent(&$tag){

		//2 : Load the Joomla article... with the author, the section and the categories to create nice links
		$query = 'SELECT a.*,c.title as catname FROM #__newsfeeds AS a LEFT JOIN #__categories AS c ON c.id = a.catid WHERE a.id = '.$tag->id.' LIMIT 1';

		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$article = $db->loadObject();

		$result = '';

		//In case of we could not load the article for any reason...
		if(empty($article)){
			$app =& JFactory::getApplication();
			if($app->isAdmin()){
				$app->enqueueMessage('The Feed "'.$tag->id.'" could not be found','notice');
			}
			return $result;
		}


		//  get RSS parsed object
		$options = array();
		$options['rssUrl']		= $article->link;
		$options['cache_time']	= $article->cache_time;

		$rssDoc =& JFactory::getXMLparser('RSS', $options);
		if ( $rssDoc == false ) {
			$app =& JFactory::getApplication();
			if($app->isAdmin()){
				$app->enqueueMessage('The Feed "'.$tag->id.'" could not be loaded','notice');
			}
			return $result;
		}
		if(!empty($tag->max)){
			$maxArticle = $tag->max;
		}
		else{
			$maxArticle = $article->numarticles;
		}
		$feeds = array_slice($rssDoc->get_items(), 0, $maxArticle);
		$resultfeeds = array();

		foreach($feeds as $oneFeed){

			$styleTitle = '<h2 class="acymailing_title">';
			$styleTitleEnd = '</h2>';
			if(!empty($tag->link) AND !is_null($oneFeed->get_link())){
				$resultTitle = '<a href="'.$oneFeed->get_link().'" ';
				$resultTitle .= 'style="text-decoration:none" name="newsfeeds-'.$tag->id.'" ';
				$resultTitle .= 'target="_blank" >'.$styleTitle.$oneFeed->get_title().$styleTitleEnd.'</a>';
			}else{
				$resultTitle = $styleTitle.$oneFeed->get_title().$styleTitleEnd;
			}

			$resultContent = '';
			if(!empty($tag->notitle)) $resultTitle = '';

			if ( $oneFeed->get_description()){
				$resultContent = str_replace('&apos;', "'", $oneFeed->get_description());
			}

			$resultfeeds[] = '<div class="acymailing_content">'.$resultTitle.$resultContent.'</div>';

		}

		$result = implode('</td></tr><tr><td>',$resultfeeds);


		if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'newsfeeds.php')){
			ob_start();
			require(ACYMAILING_MEDIA.'plugins'.DS.'newsfeeds.php');
			$result = ob_get_clean();
		}

		if(isset($tag->pict)){
			$pictureHelper = acymailing::get('helper.acypict');
			$pictureHelper->maxHeight = empty($tag->maxheight) ? $this->params->get('maxheight',150) : $tag->maxheight;
			$pictureHelper->maxWidth = empty($tag->maxwidth) ? $this->params->get('maxwidth',150) : $tag->maxwidth;
			if($tag->pict == '0'){
				$result = $pictureHelper->removePictures($result);
			}elseif($tag->pict == 'resized'){
				if($pictureHelper->available()){
					$result = $pictureHelper->resizePictures($result);
				}elseif($app->isAdmin()){
					$app->enqueueMessage($pictureHelper->error,'notice');
				}
			}
		}

		return $result;

	}

	function acymailing_generateautonews(&$email){
		$return = null;
		$return->status = true;
		$return->message = '';
		$time = time();


		//Check if we should generate the SmartNewsletter or not...
		$match = '#{newsfeeds:(.*)}#Ui';
		$variables = array('subject','body','altbody');
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

				$arguments = explode('|',strip_tags($allresults[1][$i]));
				//The first argument is a list of sections and cats...
				$parameter = null;
				$parameter->id = (int)$arguments[0];
				for($a=1;$a<count($arguments);$a++){
					$args = explode(':',$arguments[$a]);
					$arg0 = trim($args[0]);
					if(isset($args[1])){
						$parameter->$arg0 = $args[1];
					}else{
						$parameter->$arg0 = true;
					}
				}
				$stringTag = '';

			    if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'autonewsfeeds.php')){
					ob_start();
					require(ACYMAILING_MEDIA.'plugins'.DS.'autonewsfeeds.php');
					$stringTag = ob_get_clean();
				}else{
					//we insert the article tag one after the other in a table as they are already sorted
					$stringTag .= '<table cellspacing="0" cellpadding="0" border="0">';
						$stringTag .= '<tr><td>';
						$stringTag .= $this->_replaceContent($parameter);
						$stringTag .= '</td></tr>';

					$stringTag .= '</table>';
			    }
			    $this->tags[$oneTag] = $stringTag;
			}
		}
	return $return;
	}
}//endclass