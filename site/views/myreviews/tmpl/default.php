<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     	GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */


// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

?>
<div class="user-reviews">
    <div id="my-reviews" class="avisamusicdb avisa-musicdb-view-myreviews">

        <?php if (!count($this->myreviews)) { ?>
            <div class="alert alert-warning">
                <?php echo Text::_('COM_AVISAMUSICDB_NOTHING_FOUND'); ?>
            </div>
        <?php } ?>

        <?php foreach ($this->myreviews as $key => $myreview) { ?>
            <div class="review-wrap review-item" id="review-id-<?php echo $myreview->id; ?>">
                <div class="review-box">

                    <h4 class="music-title">
                        <a href="<?php echo $myreview->url; ?>">
                            <?php echo $myreview->title; ?>
                        </a>
                    </h4>

                    <?php echo LayoutHelper::render('review.ratings', array('rating' => $myreview->rating)); ?>

                    <div class="reviewers-review">
                        <div class="date-time">
                            <i class="avisamusicdb-icon-clock"></i><span class="sppb-meta-date" itemprop="dateCreated"><?php echo AvisamusicdbHelper::timeago($myreview->created); ?></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="review-message">
                            <p><?php echo nl2br($myreview->review); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>