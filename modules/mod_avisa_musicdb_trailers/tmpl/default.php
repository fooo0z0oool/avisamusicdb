<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisa_musicdb_trailers
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


//no direct access
defined('_JEXEC') or die('No direct access');

use Joomla\CMS\Layout\FileLayout;

$genres = new FileLayout('music.genres', $basePath = JPATH_ROOT . '/components/com_avisamusicdb/layouts');

?>

<div id="avisa-musicwdb-trailers<?php echo $module->id; ?>" class="mod-avisamusicdb-trailers avisamusicdb-trailers avisamusicdb <?php echo $moduleclass_sfx; ?>">

	<!-- trailers-videos -->
	<div class="trailers-videos">
		<div class="avisamusicdb-row">
			<?php foreach ($items as $key => $item) {
				if ($key == 0) {
					$item_type = 'leading avisamusicdb-col-sm-12';
					$turl['thumb'] = $item->turls[0]['tmb_large'];
				} else {
					$item_type = 'subleading avisamusicdb-col-sm-3';
					$turl['thumb'] = $item->turls[0]['tmb_small'];
				} ?>

				<div class="avisamusicdb-trailer-item <?php echo $item_type; ?>">
					<div class="avisamusicdb-trailer">
						<div class="trailer-image-wrap">
							<img src="<?php echo $turl['thumb']; ?>" alt="">
							<a class="play-video" href="<?php echo $item->turls[0]['src']; ?>" data-type="<?php echo $item->turls[0]['host']; ?>">
								<i class="play-icon avisamusicdb-icon-play"></i>
							</a>
						</div> <!-- trailer-image-wrap -->
						<div class="avisamusicdb-trailer-info avisa-avisamusicdb-trailers-info">
							<div class="avisamusicdb-trailer-info-block">
								<?php if ($key == 0) { ?>
									<img src="<?php echo $item->turls[0]['tmb_large']; ?>" class="thumb-img" alt="">
								<?php } ?>

								<h3 class="music-title"><a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a></h3>
								<p class="avisamusicdb-genry">
									<?php echo $genres->render(array('genres' => $item->genres)); ?>
								</p>
							</div>

							<?php if ($key == 0) { ?>
								<div class="count-rating pull-right">
									<span>
										<?php if (isset($item->ratings) && $item->ratings->count) {
											$rating = round($item->ratings->total / $item->ratings->count);
										} else {
											$rating = 0;
										}
										echo $rating ?>
									</span>
								</div>
							<?php } ?>

						</div>
					</div>
				</div>

			<?php } ?>
		</div> <!-- /.row -->
	</div> <!-- //trailers-videos -->

	<div class="video-container">
		<span class="video-close"><i class="avisamusicdb-icon-close"></i></span>
	</div> <!-- /.video-container -->

	<div class="clearfix"></div>

</div>