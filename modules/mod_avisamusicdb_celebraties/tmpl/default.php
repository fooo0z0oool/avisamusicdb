<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_celebraties
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Uri\Uri;

?>

<div id="mod-avisamusicdb-celebraties<?php echo $module->id; ?>" class="mod-avisamusicdb-celebrities <?php echo $params->get('moduleclass_sfx') ?>">
	<?php foreach ($items as $item) { ?>

		<?php
		$profile_image = dirname($item->profile_image) . '/_avisamedia_thumbs/' . basename($item->profile_image);
		if (file_exists(JPATH_ROOT . '/' . $profile_image)) {
			$item->profile_image = $profile_image;
		}
		?>

		<div class="avisamusicdb-celebrity clearfix">
			<a href="<?php echo $item->url; ?>">
				<div class="pull-left avisamusicdb-celebrity-thumb" style="background-image: url(<?php echo Uri::base(true) . '/' . $item->profile_image; ?>); "></div>
			</a>
			<div class="musicdb-celebrity-info">
				<div class="musicdb-celebrity-name">
					<a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a>
					<small class="musicdb-celebrity-designation"><?php echo $item->designation; ?></small>
				</div>
			</div> <!-- /.musicdb-top-celebrities-info -->
		</div><!-- /.musicdb-top-celebrity -->
	<?php } ?>
</div><!-- /.mod-avisamusicdb-celebrities -->