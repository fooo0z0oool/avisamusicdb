<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die('Restricted Access');

$trailers 	= $displayData['trailers'];

?>
<!-- trailers-videos -->
<div class="trailers-videos">
	<div class="header-title">
		<span><i class="avisamusicdb-icon-trailer"></i></span>
		<h3 class="title"><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_TRAILER_AND_VIDOES'); ?></h3>
	</div>

	<div class="avisamusicdb-row">
		<?php foreach ($trailers as $key => $trailer) {
			if ($key == 0) {
				$item_type = 'leading avisamusicdb-col-sm-12';
				$trailer['thumb'] = $trailer['tmb_large'];
			} else {
				$item_type = 'subleading avisamusicdb-col-sm-4';
				$trailer['thumb'] = $trailer['tmb_small'];
			} ?>

			<div class="avisamusicdb-trailer-item <?php echo $item_type; ?>">
				<div class="avisamusicdb-trailer">
					<div class="trailer-image-wrap">
						<img src="<?php echo $trailer['thumb']; ?>" alt="">
						<a class="play-video" href="<?php echo $trailer['src']; ?>" data-type="<?php echo $trailer['host']; ?>">
							<i class="play-icon avisamusicdb-icon-play"></i>
						</a>
					</div> <!-- trailer-image-wrap -->
					<div class="avisamusicdb-trailer-info avisa-avisamusicdb-trailers-info">
						<div class="avisamusicdb-trailer-info-block">
							<?php if ($key == 0) { ?>
								<img src="<?php echo $trailer['tmb_small']; ?>" class="thumb-img" alt="">
							<?php } ?>
							<h4 class="music-title"><?php echo $trailer['title']; ?></h4>
							<?php if ($key == 0) { ?>
								<p class="avisamusicdb-genry">
									<?php echo LayoutHelper::render('music.genres', array('genres' => $trailer['genres'])); ?>
								</p>
							<?php } ?>
						</div>
					</div>

				</div>
			</div>
		<?php } ?>
	</div> <!-- /.row -->


</div> <!-- //trailers-videos -->