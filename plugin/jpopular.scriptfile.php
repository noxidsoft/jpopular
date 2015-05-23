<?php
/**
 * JPopular - Joomla 3.1.x
 *
 * Copyright (c) 2007-2014 Noxidsoft - Noel Dixon
 *
 * @license GNU / GPL
 *
 * For the latest version please go to http://www.noxidsoft.com
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of JPopular plugin
 */
class plgSystemJpopularInstallerScript
{
  function install($parent) {
    // Connect to database
	$db = JFactory::getDbo();

	// Create table(s)


	// I activate the plugin
    $tableExtensions = $db->nameQuote("#__extensions");
    $columnElement   = $db->nameQuote("element");
    $columnType      = $db->nameQuote("type");
    $columnEnabled   = $db->nameQuote("enabled");

    // Enable plugin
    $db->setQuery("UPDATE $tableExtensions SET $columnEnabled=1 WHERE $columnElement='jpopular' AND $columnType='plugin'");
    $db->query();

    echo '<h4 style="color:red;">'. JText::_('JPopular plugin enabled and published for you! JPopular is now collecting data.') .'</h4>';
  }
}
