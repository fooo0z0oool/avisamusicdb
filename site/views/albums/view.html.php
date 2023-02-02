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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;

class AvisamusicdbViewAlbums extends HtmlView
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
        
        $params = ComponentHelper::getParams('com_avisamusicdb');
        $this->columns = $params->get('albums_columns', 4);

        // Get model
        $this->total_albums = $model->getCountAlbums()->total_albums ?? 0;
        $this->alphabets = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        foreach ($this->items as &$this->item) {
            $this->item->url = Route::_('index.php?option=com_avisamusicdb&view=album&id=' . $this->item->id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('albums'));
        }

        return parent::display($tpl = null);
    }
}
