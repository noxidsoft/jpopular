<?php
/**
 * @package		Noxidsoft.Site
 * @subpackage	mod_jpopular
 * @copyright	Copyright (C) 2007 - 2014 Noxidsoft. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<ul class="mostread<?php echo $moduleclass_sfx; ?>">
<?php foreach ($list as $item) : ?>
	<?php //print_r($item); ?>
	<li>
		<?php if ($item->link) :?>
			<a href="<?php echo $item->link; ?>">
				<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); if ($params->get('show_hits')){ echo '<span style="float:right;">'.$item->hits.'</span>';} ?></a>
		<?php else :
			echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
		endif; ?>
	</li>
<?php endforeach; ?>
