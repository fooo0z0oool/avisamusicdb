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

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class AvisamusicdbViewTrailers extends HtmlView
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
        $this->columns = $params->get('trailer_columns', 4);

        $model = $this->getModel();
        $this->pagination  = $model->getPagination();
        $this->items = $model->getItems();

        $singular_model =  BaseDatabaseModel::getInstance('Music', 'AvisamusicdbModel');

        foreach ($this->items as $this->item) {
            $this->item->title  = $this->item->title . ' (' . HTMLHelper::date($this->item->release_date, 'Y') . ')';
            $this->item->url    = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('musics'));
            $this->item->genres = $singular_model->getGenries($this->item->genres);
            $this->item->turls  = $singular_model->GenerateTrailers($this->item->id);
        }

        return parent::display($tpl = null);
    }
}
