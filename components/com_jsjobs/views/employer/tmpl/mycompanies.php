<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/myjobs.php
 ^ 
 * Description: template view for my jobs
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 require_once( JPATH_BASE . '/includes/pageNavigation.php' );
 global $mainframe;
 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$this->Itemid;
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<?php if ($this->config['offline'] == '1'){ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	<?php echo $this->config['title']; ?></td></tr>
	<tr><td height="25"></td></tr>
	<tr><td class="jsjobsmsg">
		<?php echo $this->config['offline_text']; ?>
	</td></tr>
</table>	
<?php }else{ ?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_COMPANIES'); ?>
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_NEW_COMPANY');
			$count = 0;
			if (sizeof($this->employerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->employerlinks as $lnk)	{ 
					if ($count == 1) { ?>
						<span>
							<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
						</span>
					<?php }	?>
					<span <?php if($lnk[2] == 1)echo 'class="first"'; elseif($lnk[2] == -1)echo 'class="last"';  ?>>
						<a href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
					</span>
				<?php $count++;	
				}
				echo '<ul></div>';			
			} 
		?>
	</td></tr>	
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_MY_COMPANIES'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>


<?php
if ($this->userrole->rolefor == 1) { // employer

if ($this->totalresults != 0) {

?>
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr  class="<?php echo $this->theme['sortlinks']; ?>" valign="middle">
			<td valign="center" class="<?php echo $this->theme['sortlinks']; ?>" align="left" > 
				<strong>&nbsp;<?php echo JText::_('JS_NAME'); ?></strong>
			</td>
			<td valign="center" class="<?php echo $this->theme['sortlinks']; ?>" align="left" > 
				<strong><?php echo JText::_('JS_CATEGORY'); ?></strong>
			</td>
			<td width="30"><strong><?php echo JText::_('JS_STATUS'); ?></strong></td>
			<td width="30"></td>
			<td width="20"></td>
			<td width="30"></td>
			<td width="30"></td>
		</tr>
		
		<?php 
		$isnew = date("Y-m-d H:i:s", strtotime("-$this->config['newdays'] days"));
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->companies) ){
		foreach($this->companies as $company)	{ 
			$isodd = 1 - $isodd; 
			?>
			<tr class="<?php echo $tdclass[$isodd]; ?>"> 
						<td class="maintext">&nbsp;<?php echo $company->name;?></td>
						<td class="maintext"><?php echo $company->cat_title; ?></td>
						<td class="maintext"><strong><?php if ($company->status == 1) echo JText::_('JS_APPROVED'); elseif ($company->status == 0) {echo '<span class="jobstatusmsg"> '. JText::_('JS_PENDDING'). '</span>';} elseif ($company->status == -1) echo '<span class="jobstatusmsg"> '. JText::_('JS_REJECTED'). '</span>'; ?></strong></td>
						<td></td>
					<td class="maintext" align="center"><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&md=<?php echo $company->id; ?>"><img width="15" height="15" src="components/com_jsjobs/images/edit.png" /></td>
					</td>
					<td class="maintext" align="center" valign="middle">
							<a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md=<?php echo $company->id; ?>"" >
								<img width="15" height="15" src="components/com_jsjobs/images/view.png" />
							</a>
					</td>
					<td class="maintext" align="center">
							<a href="index.php?option=com_jsjobs&c=jsjobs&task=deletecompany&md=<?php echo $company->id; ?>" >
								<img width="15" height="15" src="components/com_jsjobs/images/delete.png" />
							</a>
							
					</td>
						
					</tr>
					
					<tr><td height="1"></td></tr>
		<?php 
		}
		}?>		
	</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="deletecompany" />
			<input type="hidden" id="id" name="id" value="" />

	</form>

<?php

// paging		
$total=$this->totalresults;
$limit=$this->limit;
$limitstart=$this->limitstart;

/*$limit = $limit ? $limit : $mainframe->getCfg('list_limit');
if ( $total <= $limit ) { 
    $limitstart = 0;
}*/ 
 $pageNav = new mosPageNav( $total, $limitstart, $limit );

//$link = 'abc';

?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$this->Itemid); ?>" method="post">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
            <tr><td align="center" colspan="2">
                <?php
                    echo $pageNav->writePagesLinks( $link );
                ?>
            </td></tr>
            
            <tr><td align="left">
			<?php echo JText::_('JS_DISPLAY_#'); ?>
                    <?php
                        echo $pageNav->getLimitBox( $link );
                    ?>
            </td>
			<td align="right">
				<?php
					echo $pageNav->writePagesCounter(); 
				?>
			</td></tr>	
</table>
</form>	
<?php
}else{ // no result found in this category
	echo JText::_('JS_RESULT_NOT_FOUND');
}

} else{ // not allowed job posting
echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');
}	
}//ol
?>	
<div width="100%">
<?php 
if($this->config['fr_cr_txsh']) {
	echo 
	'<table width="100%" style="table-layout:fixed;">
		<tr><td height="15"></td></tr>
		<tr><td style="vertical-align:top;" align="center">'.$this->config['fr_cr_txa'].$this->config['fr_cr_txb'].'</td></tr>
	</table>';
}	
?>
</div>


