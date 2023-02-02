<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_search
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Helper\ModuleHelper;

// load component layout
$genres = new FileLayout('music.genres', $basePath = JPATH_ROOT . '/components/com_avisamusicdb/layouts');

$results = $displayData['results'];
$type 	 = $displayData['type'];

// get params
jimport('joomla.application.module.helper');
$module = ModuleHelper::getModule('mod_avisamusicdb_search');
$registry = new Registry();
$params = $registry->loadString($module->params);

// get show thumb
$show_thumb = $params->get('show_thumb');

$thumb_class = ($show_thumb) ? 'show-thumb' : '';

?>

<ul class="avisamusicdb-music-search results-list <?php echo $thumb_class; ?>">
	<?php
	if (!empty($results)) {
		foreach ($results as $result) {

			// if type genre
			if ($type == 'genres') {
				$genres_info = modAvisamusicdbSearchHelper::getGenries($result->genres);
			}

			// if type not celebrities
			if ($type != 'celebrities') {

				$result->ratings 	= modAvisamusicdbSearchHelper::getRatings((int) $result->avisamusicdb_music_id);

				if (isset($result->ratings) && $result->ratings->count) {
					$rating = round($result->ratings->total / $result->ratings->count);
				} else {
					$rating = 0;
				}
			}

	?>

			<li>
				<a href="<?php echo $result->url; ?>">

					<?php if ($result->profile_image && $show_thumb) { ?>
						<img class="avisamusicdb-search-music-img" src="<?php echo Uri::root() . $result->profile_image; ?>" style="width: 40px;" />
					<?php } else { ?>
						<i class="avisamusicdb-icon-search"></i>
					<?php } ?>


					<span class="avisamusicdb-search-music-genres">
						<?php
						if ($type == 'genres') {
							echo $result->title . ' (' . $genres->render(array('genres' => $genres_info, 'type' => 'search')) . ')';
						} else {
							echo $result->title;
						} ?>
					</span>

					<?php if ($type != 'celebrities') { ?>
						<div class="avisa-musicdb-rating-wrapper">
							<div class="avisa-musicdb-rating">
								<span class="star active"></span>
							</div>
							<span class="avisamusicdb-rating-summary"><span><?php echo $rating; ?></span>/<?php echo Text::_('COM_AVISAMUSICDB_RATING_MAX'); ?></span>
						</div>
					<?php } ?>

				</a>
			</li>
		<?php  } // end:: foreach
	} else { ?>
		<li class="avisamusicdb-empty">
			<?php echo Text::_('MOD_AVISAMUSICDBESEARCH_NO_ITEM_FOUND'); ?>
		</li>
	<?php } ?>
</ul>