<?php
/**
 * @package		Noxidsoft.Site
 * @subpackage	mod_jpopular
 * @copyright	Copyright (C) 2005 - 2014 Noxidsoft. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$list = modJpopularHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_jpopular', $params->get('layout', 'default'));
