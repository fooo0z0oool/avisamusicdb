<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Layout\LayoutHelper;


$review = $displayData['review'];

if (isset($review) && $review) {
?>
	<div class="review-wrap review-item" id="review-id-<?php echo $review->id; ?>" data-review_id="<?php echo $review->id; ?>">
		<div class="profile-img">
			<img src="//www.gravatar.com/avatar/<?php echo md5($review->email); ?>?s=68" alt="">
		</div>
		<div class="review-box">
			<?php echo LayoutHelper::render('review.ratings', array('rating' => $review->rating)); ?>

			<div class="reviewers-review">
				<p class="pull-left reviewers-name">
					<?php echo $review->name; ?>
				</p>
				<div class="date-time">
					<i class="avisamusicdb-icon-clock"></i><span class="sppb-meta-date" itemprop="dateCreated"><?php echo AvisamusicdbHelper::timeago($review->created); ?></span>
				</div>
				<div class="clearfix"></div>
				<?php if (isset($review->review) && $review->review) { ?>
					<div class="review-message">
						<p>
							<?php echo nl2br($review->review); ?>
						</p>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php }
