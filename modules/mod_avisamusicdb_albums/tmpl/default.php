<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_albums
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Uri\Uri;

?>

<div id="mod-avisamusicdb-albums<?php echo $module->id; ?>" class="mod-avisamusicdb-albums <?php echo $params->get('moduleclass_sfx') ?>">
	<?php foreach ($items as $item) { ?>

		<?php
		$profile_image = dirname($item->profile_image) . '/_avisamedia_thumbs/' . basename($item->profile_image);
		if (file_exists(JPATH_ROOT . '/' . $profile_image)) {
			$item->profile_image = $profile_image;
		}
		?>

		<div class="avisamusicdb-album clearfix">
			<a href="<?php echo $item->url; ?>">
				<div class="pull-left avisamusicdb-album-thumb" style="background-image: url(<?php echo Uri::base(true) . '/' . $item->profile_image; ?>); "></div>
			</a>
			<div class="musicdb-album-info">
				<div class="musicdb-album-name">
					<a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a>
					<small class="musicdb-album-designation"><?php echo $item->designation; ?></small>
				</div>
			</div> <!-- /.musicdb-top-albums-info -->
		</div><!-- /.musicdb-top-album -->
	<?php } ?>
</div><!-- /.mod-avisamusicdb-albums -->