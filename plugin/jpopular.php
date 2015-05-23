<?php
/**
 * @copyright	Copyright (C) 2007 - 2014 Noxidsoft. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Joomla! System Audit Plugin - Content Hits
 *
 * @package		Joomla.Plugin
 * @subpackage	System.jpopular
 */
class plgSystemJpopular extends JPlugin
{
	// After page has loaded
	function onBeforeCompileHead()
	{
		// Get option and view for article particulars
		$option = JRequest::getVar('option');
		$view = JRequest::getVar('view');

		// Get JPopular module values
		//jimport('joomla.application.module.helper');
		//$jpopular_module 	= &JModuleHelper::getModule('jpopular');
		//$moduleParams 		= new JRegistry($jpopular_module->params);

		// other vars
		$config   			= JFactory::getConfig();
		$siteOffset 		= $config->get('config.offset');
		$dtnow 					= JFactory::getDate('now', $siteOffset);
		$ordering 			= 1;
		$state 					= 1;
		$hits 					= 1;
		$purge					= $this->params->get('jrelative_date_purge');

		// Maintain the database if Purge tool is in use
		if($purge>0){
			// Get a db connection.
			$db = JFactory::getDbo();
			$query_purge = $db->getQuery(true);

			// delete all records from user purge days entered.
			$conditions = array(
					'day_logged < DATE_SUB(CURDATE(), INTERVAL '.$purge.' DAY)');

			$query_purge->delete($db->quoteName('#__jpopular_content'));
			$query_purge->where($conditions);

			$db->setQuery($query_purge);

			try {
				$result_purge = $db->execute(); // $db->execute(); for Joomla 3.0. forward compatibility
			} catch (Exception $e) {
				// catch the error? No keep quiet. Put a JError notice here if you really want it.
				//echo 'Database deletion error - call your webteam or email them this: '.$e;
			}
		}

		// get user info, not really necessary but nice to see last visitor recorded
		$user = JFactory::getUser();
		if (!$user->guest) {
			$action_by = $user->name;
		} else {
			$action_by = 'Guest';
		}

		// content title particulars
		$id = JRequest::getInt('id'); // article id
		$article = JTable::getInstance('content');
		$article->load($id);
		$article_title = $article->get('title'); // article title

		$day_now = JHTML::_('date', substr($dtnow, 0, 10), 'Y-m-d'); // system day with offset

		$ip_address = $_SERVER['REMOTE_ADDR']; // get the IP address of visitor

		$db = JFactory::getDBO(); // connect to the database

		// get current entries
		$db->setQuery("SELECT id, hits FROM #__jpopular_content WHERE article_id='{$id}' AND day_logged='{$day_now}'");
		$result = $db->loadObject();
		$db->query();

		// Make sure it is a single article
		if ($option == 'com_content' && $view == 'article'):
			if (!$db->getNumRows()){
				// insert if no entries for current day and article id don't exist
				$db->setQuery("INSERT INTO #__jpopular_content VALUES ('',{$ordering},{$state},{$hits},'{$action_by}',{$id},'{$article_title}','{$day_now}','{$ip_address}')");
			} else {
				$hits = $result->hits + 1; // manage hits

				// update if current day and article id exist
				$db->setQuery("UPDATE #__jpopular_content SET hits={$hits}, action_by='{$action_by}', article_title='{$article_title}', ip_address='{$ip_address}' WHERE id={$result->id}");
			}

			$db->query();
		endif;

		return 0;
	}
}
