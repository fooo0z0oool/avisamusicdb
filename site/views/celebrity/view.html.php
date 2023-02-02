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

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

class AvisamusicdbViewCelebrity extends HtmlView
{

    protected $item;

    /**
     * Display the view
     *
     * @param   string    The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        // Get model
        $model = $this->getModel();
        // get item
        $this->item = $model->getItem();

        //Load music Model
        $music_model =  BaseDatabaseModel::getInstance('Music', 'AvisamusicdbModel');

        //get celebrities latest musics
        $this->item->celebrity_musics = $model->getCelebrityMusicsbyId($this->item->id, 5);

        foreach ($this->item->celebrity_musics as $this->item->celebrity_music) {
            $this->item->celebrity_music->ratings     = $music_model->getRatings($this->item->celebrity_music->id);
            $this->item->celebrity_music->genres     = $music_model->getGenries($this->item->celebrity_music->genres);

            $this->item->celebrity_music->murl = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->celebrity_music->id . ':' . $this->item->celebrity_music->alias . AvisamusicdbHelper::getItemid('musics'));
        }

        // Get actor_trailers
        $this->item->actor_trailers = $model->getCelebrityMusicsbyId($this->item->id, 4);
        foreach ($this->item->actor_trailers as $this->item->actor_trailer) {
            $this->item->actor_trailer->title = $this->item->actor_trailer->title . ' (' . HTMLHelper::date($this->item->actor_trailer->release_date, 'Y') . ')';
            $this->item->actor_trailer->turls = $music_model->GenerateTrailers($this->item->actor_trailer->id)[0];

            $this->item->actor_trailer->murl = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->actor_trailer->id . ':' . $this->item->actor_trailer->alias . AvisamusicdbHelper::getItemid('musics'));
            $this->item->actor_trailer->genres  = $music_model->getGenries($this->item->actor_trailer->genres);
        }

        $this->item->url = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('celebrities'));

        $model->hit();

        //Generate Item Meta
        $itemMeta                 = array();
        $itemMeta['title']        = $this->item->title;

        if (isset($this->item->designation) && $this->item->designation) {
            $itemMeta['keywords']     = rtrim($this->item->designation, ', ');
        }

        $cleanText = $this->item->biography;

        $itemMeta['metadesc'] = HTMLHelper::_('string.truncate', OutputFilter::cleanText($cleanText), 155);
        $itemMeta['image']    = Uri::base() . $this->item->profile_image;
        AvisamusicdbHelper::itemMeta($itemMeta);

        return parent::display($tpl = null);
    }
}
