<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */


defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('jquery.framework');
$doc = Factory::getDocument();
$doc->addScript(Uri::base(true) . '/components/com_avisamusicdb/assets/js/avisamusicdb.js');
$doc->addScriptdeclaration('var avisamusicdb_url="' . Uri::base() . 'index.php?option=com_avisamusicdb";');


$music 	= $displayData['music'];

?>

<div class="music-poster">
	<img src="<?php echo $music->profile_image; ?>" alt="<?php echo $music->title; ?>">

	<?php if ((count($music->turls) > 0) && $music->turls) { ?>
		<a class="play-icon play-video" href="<?php echo $music->turls[0]['src']; ?>" data-type="<?php echo $music->turls[0]['host']; ?>">
			<i class="avisamusicdb-icon-play"></i>
		</a>
	<?php } else { ?>
		<a class="play-icon" href="<?php echo $music->url; ?>">
			<i class="avisamusicdb-icon-enter"></i>
		</a>
	<?php } ?>
</div>

<div class="music-details">
	<?php
	if (isset($music->ratings) && $music->ratings->count) {
		$rating = round($music->ratings->total / $music->ratings->count);
	} else {
		$rating = 0;
	}
	?>
	<div class="avisa-musicdb-rating-wrapper">
		<div class="avisa-musicdb-rating">
			<span class="star active"></span>
		</div>
		<span class="avisamusicdb-rating-summary"><span><?php echo $rating; ?></span>/<?php echo Text::_('COM_AVISAMUSICDB_RATING_MAX'); ?></span>
	</div>
	<div class="music-name">
		<a href="<?php echo $music->url; ?>">
			<h4 class="music-title"><?php echo $music->title; ?></h4>
		</a>
	</div>
</div>