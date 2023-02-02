<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted Access');

$rating = $displayData['rating'];

$class = '';
if (isset($displayData['class']) && $displayData['class']) {
	$class = $displayData['class'];
}

?>
<div class="avisa-musicdb-rating <?php echo $class; ?>">
	<?php
	$max_rating = 10;
	$j = 0;
	for ($i = $rating; $i < $max_rating; $i++) {
		echo '<span class="star" data-rating_val="' . ($max_rating - $j) . '"></span>';
		$j = $j + 1;
	}
	for ($i = 0; $i < $rating; $i++) {
		echo '<span class="star active" data-rating_val="' . ($rating - $i) . '"></span>';
	}
	?>
</div>
<span class="avisamusicdb-rating-summary"><span><?php echo $rating; ?></span>/10</span>