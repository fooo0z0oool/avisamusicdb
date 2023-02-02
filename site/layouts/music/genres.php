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

$genres 	= $displayData['genres'];
$type 		= (isset($displayData['type']) && $displayData['type']) ? $displayData['type'] : '';
$genre_output = '';

if (isset($genres) && count($genres)) {
	foreach ($genres as $key => $genre) {
		$genre->url    = Route::_('index.php?option=com_avisamusicdb&view=searchresults&searchword=' . $genre->title . '&type=genres' . AvisamusicdbHelper::getItemid('musics'));
		if ($type != 'search') {
			$genre_output .= '<a class="avisamusicdb-genre-title" href="' . $genre->url . '">' . $genre->title . '</a>' . ', ';
		} else {
			$genre_output .= $genre->title . ', ';
		}
	}

	echo rtrim(trim($genre_output), ',');
}
