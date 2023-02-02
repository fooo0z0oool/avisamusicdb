<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$input = Factory::getApplication()->input;
$Itemid = $input->get('Itemid', 0, 'INT');
$alphaindex = $input->get('alphaindex', NULL, 'WORD');
$yearindex = $input->get('yearindex', NULL, 'INT');

if ($yearindex) {
    $alphaUrl = 'index.php?option=com_avisamusicdb&view=musics&yearindex=' . $yearindex . '&Itemid=' . $Itemid;
} else {
    $alphaUrl = 'index.php?option=com_avisamusicdb&view=musics&Itemid=' . $Itemid;
}

if ($alphaindex) {
    $yearUrl = 'index.php?option=com_avisamusicdb&view=musics&alphaindex=' . $alphaindex . '&Itemid=' . $Itemid;
} else {
    $yearUrl = 'index.php?option=com_avisamusicdb&view=musics&Itemid=' . $Itemid;
}

HTMLHelper::_('jquery.framework');
$doc = Factory::getDocument();
$doc->addScript(Uri::base(true) . '/components/com_avisamusicdb/assets/js/avisamusicdb.js');
?>
<div id="com-avisa-musicdb" class="avisamusicdb avisa-musicdb avisa-musicdb-view-items">
    <div class="musicdb-filters">
        <div class="pull-left">
            <ul class="list-inline list-style-none">
                <li class="<?php echo ($alphaindex == NULL) ? 'active' : ''; ?>">
                    <a href="<?php echo Route::_($alphaUrl); ?>"><?php echo Text::_('COM_AVISAMUSICDB_ALL'); ?></a>
                </li>
                <?php foreach ($this->alphabets as $alphabet) { ?>
                    <li class="<?php echo ($alphaindex == $alphabet) ? 'active' : ''; ?>">
                        <a href="<?php echo Route::_($alphaUrl . '&alphaindex=' . $alphabet); ?>">
                            <?php echo $alphabet; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="pull-right musicdb-yearindex">
            <label><?php echo Text::_('COM_AVISAMUSICDB_YEAR'); ?>:
                <select name="sorting-by-years" id="sorting-by-years">
                    <option <?php echo ($yearindex == 'all') ? 'selected="selected"' : ''; ?> value="<?php echo Route::_($yearUrl); ?>">
                        <?php echo Text::_('COM_AVISAMUSICDB_ALL'); ?>
                    </option>
                    <?php foreach ($this->musics_years as $year) { ?>
                        <option <?php echo ($yearindex == $year->year) ? 'selected="selected"' : ''; ?> value="<?php echo Route::_($yearUrl . '&yearindex=' . $year->year); ?>"><?php echo $year->year; ?></option>
                    <?php } ?>
                </select>
            </label>
        </div>
    </div>

    <?php if (!count($this->items)) { ?>
        <div class="alert alert-warning">
            <?php echo Text::_('COM_AVISAMUSICDB_NOTHING_FOUND'); ?>
        </div>
    <?php } ?>

    <div class="avisamusicdb-row">
        <?php foreach ($this->items as $item) { ?>
            <div class="item avisamusicdb-col-xs-12 avisamusicdb-col-sm-4 avisamusicdb-col-lg-<?php echo round(12 / $this->columns); ?>">
                <div class="music-poster">
                    <img src="<?php echo Uri::root() . $item->profile_image; ?>" alt="<?php echo $item->profile_image->title; ?>">
                    <?php if ((count($item->turls) > 0) && $item->turls) { ?>
                        <a class="play-icon play-video" href="<?php echo $item->turls[0]['src']; ?>" data-type="<?php echo $item->turls[0]['host']; ?>">
                            <i class="avisamusicdb-icon-play"></i>
                        </a>
                    <?php } else { ?>
                        <a class="play-icon" href="<?php echo $item->url; ?>">
                            <i class="avisamusicdb-icon-enter"></i>
                        </a>
                    <?php } ?>
                </div> <!-- ./music-poster -->
                <div class="music-details">
                    <?php
                    if (isset($item->ratings) && $item->ratings->count) {
                        $rating = round($item->ratings->total / $item->ratings->count);
                    } else {
                        $rating = 0;
                    } ?>
                    <div class="avisa-musicdb-rating-wrapper">
                        <div class="avisa-musicdb-rating">
                            <span class="star active"></span>
                        </div>
                        <span class="avisamusicdb-rating-summary"><span><?php echo $rating; ?></span>/<?php echo Text::_('COM_AVISAMUSICDB_RATING_MAX'); ?></span>
                    </div>
                    <div class="music-name">
                        <a href="<?php echo $item->url; ?>">
                            <h4 class="music-title"><?php echo $item->title; ?></h4>
                        </a>
                        <?php if (isset($item->genres) && $item->genres) { ?>
                            <span><?php echo LayoutHelper::render('music.genres', array('genres' => $item->genres)); ?></span>
                        <?php } ?>
                    </div>
                </div> <!-- /.music-details -->
            </div> <!-- /.item -->
        <?php } ?>
    </div> <!-- ./avisamusicdb-row -->
    <?php //} // END:: Array chunk 
    ?>
</div> <!-- ./avisamusicdb-row-fluid -->

<div class="video-container">
    <span class="video-close"><i class="avisamusicdb-icon-close"></i></span>
</div> <!-- /.video-container -->

<?php
if ($this->pagination->pagesTotal > 1) { ?>
    <?php echo $this->pagination->getPagesLinks(); ?>
<?php } ?>
</div> <!-- /.com-avisa-musicdb -->