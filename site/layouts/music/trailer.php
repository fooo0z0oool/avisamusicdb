<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */


defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

$trailers 	= $displayData['trailers'];
$music_info = $displayData['music_info'];

$model = BaseDatabaseModel::getInstance('Musics', 'AvisamusicdbModel');
$music_info->genres = $model->getGenries($music_info->genres);
$music_info->url = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $music_info->id . ':' . $music_info->alias . AvisamusicdbHelper::getItemid('musics'));

// $genres = new LayoutHelper('music.genres', $basePath = JPATH_ROOT . '/components/com_avisamusicdb/layouts');

?>
<!-- trailers-videos -->
<div class="trailers-videos avisamusicdb-row">
	<div class="avisamusicdb-trailer-item avisamusicdb-col-md-6 avisamusicdb-col-sm-12">
		<div class="avisamusicdb-trailer">
			<?php if ($trailers[0]['host'] == 'youtube' || $trailers[0]['host'] == 'vimeo') { ?>
				<iframe width="100%" height="315" src="<?php echo $trailers[0]['src']; ?>" frameborder="0" allowfullscreen></iframe>
			<?php } elseif ($trailers[0]['host'] == 'dailymotion') { ?>
				<iframe id="video-player" src="<?php echo $trailers[0]['src']; ?>?autoplay=1&color=ffffff&title=0&byline=0&portrait=0&badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			<?php } else { ?>
				<video id="video-player" controls autoplay>
					<source src="<?php echo $trailers[0]['src']; ?>">Your browser does not support the video tag.
				</video>
			<?php } ?>
		</div>
	</div> <!-- //avisamusicdb-trailer-item -->
	<div class="avisamusicdb-trailer-item-details avisamusicdb-col-md-6 avisamusicdb-col-sm-12">
		<h3 class="avisamusicdb-music-title">
			<a href="<?php echo $music_info->url; ?>"><?php echo $music_info->title . ' (' . HTMLHelper::date($music_info->release_date, 'Y') . ')'; ?></a>
		</h3>
		<p class="trailer-genres"><?php echo LayoutHelper::render('music.genres', ['genres' => $music_info->genres]) ?></p>

		<p>
			<?php echo HTMLHelper::_('string.truncate', strip_tags($music_info->music_story), 380); ?>
		</p>

		<a href="<?php echo $music_info->url; ?>" class="btn sppb-btn-primary buy-ticket">
			<i class="avisa-musicw-ticket"></i> <?php echo Text::_('COM_AVISAMUSICDB_MUSIC_MORE_DETAILS'); ?>
		</a>
	</div> <!-- //avisamusicdb-trailer-item-details -->
	<span class="video-close"></span>
</div> <!-- //trailers-videos -->
<!-- /.row -->