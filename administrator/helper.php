<?php
/**
 * @package		Noxidsoft.Site
 * @subpackage	mod_jpopular
 * @copyright	Copyright (C) 2007 - 2014 Noxidsoft. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

abstract class modJpopularHelper
{
	public static function getList(&$params)
	{
		// user
		$user = JFactory::getUser();

		// Get a db connection.
		$db = JFactory::getDbo();

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));

		$relative_date 	= $params->get('jrelative_date_pop');
		$order_field 		= $params->get('order_field');
		$count					= $params->get('count');

		// Excluded article filter
		$excluded_articles = $params->get('excluded_articles', '-1');
		if ($excluded_articles) {
			$excluded_articles = explode("\r\n", $excluded_articles);
		}
		$excluded_articles = join(',',$excluded_articles);

		// Include only user selected categories
		$category	= $params->get('catid', array());
		$category = join(',',$category);
		if (!$category) { // all categories
			// Create a new query object.
			$query_cat = $db->getQuery(true);

			$query_cat
			->select(array('c.id'))
			->from('#__categories AS c');

			// Reset the query using our newly populated query object.
			$db->setQuery($query_cat);

			// Load the results as a list of stdClass objects.
			$category = $db->loadObjectList();

			// get $category values into searchable list
			$category = array_reduce($category, function($v, $w) {
			    if ($v) $v .= ',';
			    return $v . $w->id;
			});
		}

		// remove leading comma if present
		$category = ltrim ($category,',');

		// Create a new query object.
		$query_content = $db->getQuery(true);

		$query_content = ('
				SELECT SUM(j.hits) AS hits, j.article_id, j.day_logged, a.id, a.title, a.alias, a.catid, a.access, c.alias AS category_alias
				FROM #__jpopular_content AS j
				INNER JOIN #__content AS a ON (j.article_id = a.id)
				LEFT JOIN #__categories AS c ON (a.id = c.id)
				WHERE j.day_logged BETWEEN CURDATE() - INTERVAL '.$relative_date.' DAY AND CURDATE() AND a.id NOT IN ('.$excluded_articles.') AND a.catid IN ('.$category.')
				GROUP BY j.article_id
				ORDER BY hits '.$order_field.' LIMIT '.$count);

		// Reset the query using our newly populated query object.
		$db->setQuery($query_content);

		// Load the results as a list of stdClass objects.
		$results = $db->loadObjectList();

		// setup links before we return the database results
		foreach ($results as $item) :
			if ($user->authorise('core.edit', 'com_content.article.'.$item->id)){
				$item->link = JRoute::_('index.php?option=com_content&task=article.edit&id='.$item->id);
			} else {
				$item->link = '';
			}
		endforeach;

		return $results;
	}


}
