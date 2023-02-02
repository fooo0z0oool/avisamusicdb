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
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

class AvisamusicdbViewMusic extends HtmlView
{

    protected $item;

    /**
     * Display the view
     *
     * @param   string The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $user  = Factory::getUser();
        $model = $this->getModel();
        $input = Factory::getApplication()->input;

        // get item
        $this->item = $model->getItem();

        // Get Values
        if ($model->validateDate($this->item->release_date)) {
            $this->item->title = $this->item->title . ' (' . HTMLHelper::date($this->item->release_date, 'Y') . ')';
        }

        if (!$model->validateDate($this->item->release_date)) {
            $this->item->release_date = '';
        }

        $this->item->genres    = (isset($this->item->genres) && $this->item->genres) ? $model->getGenries($this->item->genres) : '';
        $this->item->directors = (isset($this->item->directors) && $this->item->directors) ? $model->getCelebrities($this->item->directors) : '';
        $this->item->actors    = (isset($this->item->actors) && $this->item->actors) ? $model->getCelebrities($this->item->actors) : '';
		$this->item->albums    = (isset($this->item->albums) && $this->item->albums) ? $model->getAlbums($this->item->albums) : '';
        $this->item->turls     = $model->GenerateTrailers($this->item->id);
        $this->item->url       = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('musics'));


        //cover image
        $cover_baseurl = basename($this->item->cover_image);
        //generate thumb url
        if (isset($cover_baseurl) && $cover_baseurl) {
            $cover_image = dirname($this->item->cover_image) .  '/_avisamedia_thumbs' . '/' . File::stripExt($cover_baseurl) .  '.' . File::getExt($cover_baseurl);
        }

        if (file_exists($cover_image)) {
            $this->item->cover_image = $cover_image;
        }

        //profile image
        $profile_baseurl = basename($this->item->profile_image);
        //generate thumb url
        if (isset($profile_baseurl) && $profile_baseurl) {
            $profile_image = dirname($this->item->profile_image) .  '/_avisamedia_thumbs' . '/' . File::stripExt($profile_baseurl) .  '.' . File::getExt($profile_baseurl);
        }

        if (file_exists($profile_image)) {
            $this->item->profile_image = $profile_image;
        }

        // generate showtime
        $showtime_json = AvisamusicdbHelper::repeatableToFormRepeatable($this->item->show_time, 'show_time');
        $showtime_decode = json_decode($showtime_json);
        
        if (isset($showtime_decode) && $showtime_decode) {
            // Trailers URLS
            $this->item->show_times = array();
            $stkey = 0;
            
            foreach ($showtime_decode as $key => $val) {
                if ($val->params) {
                    $this->item->show_times[$stkey] = array(
                        'theatre_name'      => $val->params->theatre_name ?? '',
                        'theatre_location'  => $val->params->theatre_location ?? '',
                        'times'             => $val->params->time ? explode("|", $val->params->time) : [],
                        'ticket_url'        => $val->params->ticket_url ?? '',
                    );
                    $stkey++;
                }
            }
        }

        $this->myReview = $model->getMyReview($this->item->id);
        $this->reviews  = $model->getReviews($this->item->id);
        $this->ratings  = $model->getRatings($this->item->id);

        $this->showLoadMore = false;
        if ($model->getTotalReviews($this->item->id) > count($this->reviews)) {
            $this->showLoadMore = true;
        }

        if (isset($this->item->genres) && $this->item->genres) {
            //Related musics
            $this->related_musics = $model->getRelated($this->item->genres, $this->item->id);
            foreach ($this->related_musics as $this->related_music) {
                $this->related_music->url      = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->related_music->id . ':' . $this->related_music->alias . AvisamusicdbHelper::getItemid('musics'));
                $this->related_music->ratings  = $model->getRatings($this->related_music->id);
                $this->related_music->turls    = $model->GenerateTrailers($this->related_music->id);
            }
        }

        $model->hit();

        //Generate Item Meta
        $itemMeta                 = array();
        $itemMeta['title']         = $this->item->title;

        if (isset($this->item->genres) && $this->item->genres) {
            $genres = '';
            foreach ($this->item->genres as $this->item->genre) {
                $genres .= $this->item->genre->title . ', ';
            }
            $itemMeta['keywords'] = rtrim($genres, ', ');
        }

        $cleanText = $this->item->music_story;

        $itemMeta['metadesc'] = HTMLHelper::_('string.truncate', OutputFilter::cleanText($cleanText), 155);
        $itemMeta['image']    = Uri::base() . $this->item->profile_image;

        AvisamusicdbHelper::itemMeta($itemMeta);

        return parent::display($tpl = null);
    }
}
