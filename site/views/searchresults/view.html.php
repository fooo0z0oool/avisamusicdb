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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class AvisamusicdbViewSearchresults extends HtmlView
{

    protected $items;
    protected $pagination;

    /**
     * Display the view
     *
     * @param   string The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $params  = ComponentHelper::getParams('com_avisamusicdb');
        $this->columns = $params->get('search_columns', 4);

        $model = $this->getModel();
        $this->items = $model->getItems();
        $this->pagination = $model->getPagination();

        $input = Factory::getApplication()->input;
        $searchtype = $input->get('type', 'all', 'WORD');

        $model_music = BaseDatabaseModel::getInstance('Music', 'AvisamusicdbModel');

        if (!count($this->items) && !$this->items) {
            echo '<p class="alert alert-warning">' . Text::_('MOD_AVISAMUSICDB_NO_ITEM_FOUND') . '</p>';
            return false;
        }

        // avisamusicdb_celebrity_id, avisamusicdb_music_id aliased in model
        foreach ($this->items as $this->item) {
            if (isset($this->item->avisamusicdb_celebrity_id) && $this->item->avisamusicdb_celebrity_id) {
                $this->item->url = Route::_('index.php?option=com_avisamusicdb&view=celebrity&id=' . $this->item->avisamusicdb_celebrity_id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('celebrities'));
            } elseif (isset($this->item->avisamusicdb_music_id) && $this->item->avisamusicdb_music_id) {
                $this->item->title  = $this->item->title . ' (' . HTMLHelper::date($this->item->release_date, 'Y') . ')';
                $this->item->turls  = $model_music->GenerateTrailers($this->item->avisamusicdb_music_id);
                $this->item->genres = $model_music->getGenries($this->item->genres);
                $this->item->url    = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->avisamusicdb_music_id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('musics'));
            }
        }

        return parent::display($tpl = null);
    }
}
