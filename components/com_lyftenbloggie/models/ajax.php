<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.1.0
 */
class LyftenBloggieModelAjax extends JModel
{
	var $_error = null;
	var $_ajax 	= null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		$this->_ajax = &BloggieFactory::load('ajax');
	}

	/**
	* Direct task to function
	**/
	function execute($function)
	{
		// Initialize variables
		$params = isset($_REQUEST["param"]) ? $_REQUEST["param"] : array();

		switch ($function)
		{
			case 'vote':
				$this->_vote($params);
				break;

			case 'report':
				$this->_report($params);
				break;

			case 'submitreport':
				$this->_submitreport($params);
				break;

			default:
				$this->setError("Unknown Function $function.");
				break;
		}

		//Function not found
		if($this->_error)
		{
			$this->_ajax->alert($this->_error);
		}

		//Send Output
		print $this->_ajax->getOutput();
		return;
	}

	/**
	 * Static. Add an error message
	 *
	 * @return	void
	 **/
	function setError($e)
	{
		$this->_error = $e;
	}

	/**
	 *  Method of the voting
	 **/
	function _submitreport($params)
	{
		if(!isset($params['reportid']))
		{
			$this->setError(JText::_('AN ERROR HAS OCCURRED'));
			return;
		}

		$error 	= false;

		//Validate Results
		if(!$params['reportReason']){
			$this->_ajax->assign('report-reason', 'className', 'error');
			$error 	= true;
		}else{
			$this->_ajax->assign('report-reason', 'className', '');
		}
		if(!$params['reportDescription']){
			$this->_ajax->assign('report-desc', 'className', 'error');
			$error 	= true;
		}else{
			$this->_ajax->assign('report-desc', 'className', '');
		}

		//Stop!!! Error!
		if($error) return;

		//initialize variables
		$user 		= &JFactory::getUser();
		$datenow 	= & JFactory::getDate();
		$date		= $datenow->toMySQL();

		$query = "INSERT INTO `#__bloggies_reports` SET `user_id`='".$user->id."',`comment_id`='".$params['reportid']."',`reason`='".$params['reportReason']."',`details`='".$params['reportDescription']."',`date`='".$date."'";
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError(JText::_('AN ERROR OCCURED'));
			return;
		}else{
			$msg = JText::_('REPORT SENT');
		}

		//Get entry ID for Notification
		$query = 'SELECT c.entry_id'
			. ' FROM #__bloggies_comments AS c'
			. ' WHERE c.id = \''.$params['reportid'].'\'';
		$this->_db->setQuery( $query );
		$entry_id = $this->_db->loadResult();

		//Send Notification
		$mailer = BloggieFactory::getMailer();
		$mailer->setTemplate('comment_report');
		if($mailer->emailWho($entry_id))
		{
			$name = $user->get('name', JText::_('GUEST'));
			$mailer->assign('report', array('author'=>$name, 'type'=>$params['reportReason'], 'url'=>JRoute::_(JURI::base().'index.php?option=com_lyftenbloggie&view=comments&id='.$entry_id)));
			$mailer->sendMail(JText::_('COMMENT REPORTED'));
		}

		//Clean Up the front
		$this->_ajax->assign('resultmsg', 'innerHTML', $msg);
		$this->_ajax->assign('box-details', 'style.display', 'none');
		$this->_ajax->assign('details-footer', 'style.display', 'none');
		$this->_ajax->assign('sent-details', 'style.display', 'block');
		$this->_ajax->assign('sent-footer', 'style.display', 'block');
		return;
	}

	/**
	 *  Method of the voting
	 **/
	function _report($params)
	{
		if(!isset($params['id']))
		{
			$this->setError('Missing Parameters');
			return;
		}

		// Initialize variables
		$id 		= $params['id'];
		$user 		= &JFactory::getUser();	
		$settings 	= & BloggieSettings::getInstance();
		?>
		<div id="popbox" class="popbox">
		<form id="form">
			<fieldset id="box-details">
				<p>
					<label for="report-reason" class="required"><?php echo JText::_('REASON'); ?>:</label><br />
					<select id="report-reason" name="param[reportReason]" size="1">
						<option value="" selected="selected"><?php echo JText::_('CHOOSE REASON'); ?></option>
						<option value="offensive"><?php echo JText::_('COMMENT IS OFFENSIVE'); ?></option>
						<option value="off-topic"><?php echo JText::_('COMMENT IS OFF-TOPIC'); ?></option>
						<option value="spam"><?php echo JText::_('COMMENT IS SPAM'); ?></option>
						<option value="not-listed"><?php echo JText::_('MY REASON IS NOT LISTED HERE'); ?></option>
					</select>
				</p>
				<p>
					<label for="report-desc" class="required"><?php echo JText::_('DETAILS'); ?>:</label><br />
					<textarea id="report-desc" name="param[reportDescription]" cols="30" rows="4"></textarea>
				</p>
			</fieldset>
			<fieldset id="sent-details" style="display:none;">
				<h2 id="resultmsg"><?php echo JText::_('REPORT SENT'); ?></h2>
			</fieldset>
			<div id="details-footer">
				<div class="submit-result" style="display:none;"></div>
				<div class="boxctrls">
					<a href="#" onclick="blogajax.form2query('form');return false;" class="rbutton" id="reportsubmit"><?php echo JText::_('SUBMIT'); ?></a> <a href="#" class="rbutton" onclick="closeMessage();return false;"><?php echo JText::_('CANCEL'); ?></a>
				</div>
			</div>
			<div id="sent-footer" style="display:none;">
				<a href="javascript:void(null);" class="rbutton" onclick="closeMessage();return false;"><?php echo JText::_('OKAY'); ?></a>
			</div>
			<input type="hidden" name="param[reportid]" value="<?php echo $id; ?>"/>
			<input type="hidden" name="act" value="submitreport"/>
		</form>
		</div>
		<?php
	}

	/**
	 *  Method of the voting
	 **/
	function _vote($params)
	{
		global $mainframe;

		if(!isset($params['id']) || !isset($params['vote']))
		{
			$this->setError('Missing Parameters');
			return;
		}

		// Initialize variables
		$id 		= $params['id'];
		$rating		= $params['vote'];
		$session 	= &JFactory::getSession();
		$user 		= &JFactory::getUser();
		$msg		= '';

		if(!$user->guest)
		{
			$cookieName	= JUtility::getHash( $mainframe->getName() . 'lyftenbloggierating' . $id );
			$rated = JRequest::getVar( $cookieName, '0', 'COOKIE', 'INT');

			$ratingcheck = false;
			if ($session->has('rating', 'lyftenbloggie')) {
				$ratingcheck = $session->get('rating', 0,'lyftenbloggie');
				$ratingcheck = in_array($id, $ratingcheck);
			}

			if ( $rated || $ratingcheck )	{
				$msg = JText::_('ALREADY RATED');
			} else {
				setcookie( $cookieName, '1', time()+1*24*60*60*60 );

				$stamp = array();
				$stamp[] = $id;
				$session->set('rating', $stamp, 'lyftenbloggie');

				//Decide Up/Down
				if ($rating == 1) {
					$rate = '+ 1';
				} elseif ($rating == 0) {
					$rate = '- 1';
				} else {
					$msg = JText::_( 'RATING FAILED' );
				}

				//Store Vote
				if(!$msg) {
					$query = 'UPDATE #__bloggies_comments'
						.' SET karma = ( karma '.$rate.' )'
						.' WHERE id = '.(int)$id
						;
					$this->_db->setQuery($query);
					$this->_db->query();
					$msg = JText::_( 'RATED' );
				}
			}
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		} else {
			$msg = JText::_('YOU MUST LOGIN');
		}

		//Send Result thru ajax
		$this->_ajax->assign('rate-up-'.$id, 'style.display', 'none');
		$this->_ajax->assign('rate-dwn-'.$id, 'style.display', 'none');
		$this->_ajax->assign('rate-result-'.$id, 'innerHTML', $msg);
		$this->_ajax->assign('rate-result-'.$id, 'style.display', 'block');
		return;
	}
}