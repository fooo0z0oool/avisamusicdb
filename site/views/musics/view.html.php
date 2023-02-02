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
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class AvisamusicdbViewMusics extends HtmlView
{

    protected $items;
    protected $pagination;

    /**
     * Display the view
     *
     * @param   string    The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {

        $model = $this->getModel();
        $this->pagination  = $model->getPagination();
        $this->items = $model->getItems();

        $params  = ComponentHelper::getParams('com_avisamusicdb');
        $this->columns = $params->get('music_columns', 4);
        $this->musics_years = $model->getMusicsYear();

        $this->alphabets = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

        $singular_model =  BaseDatabaseModel::getInstance('Music', 'AvisamusicdbModel');

        foreach ($this->items as $this->item) {
            $this->item->url        = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('musics'));
            $this->item->genres     = (isset($this->item->genres) && $this->item->genres) ? $singular_model->getGenries($this->item->genres) : '';
            $this->item->ratings    = $singular_model->getRatings($this->item->id);
            $this->item->turls      = $singular_model->GenerateTrailers($this->item->id);
        }

        return parent::display($tpl = null);
    }
}
