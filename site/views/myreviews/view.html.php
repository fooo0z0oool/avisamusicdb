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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

class AvisamusicdbViewMyreviews extends HtmlView
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
        $this->items = $model->getItems();
        $user = Factory::getUser();

        if ($user->guest) {
            echo '<p class="alert alert-danger">' . Text::_('COM_AVISAMUSICDB_MY_REVIEWS_LOGIN') . '</p>';
            return false;
        }

        $this->myreviews = $model->getMyReviews($user->id);
        if (empty($this->myreviews)) {
            echo '<p class="alert alert-warning">' . Text::_('COM_AVISAMUSICDB_EMPTY_REVIEWS') . '</p>';
            return false;
        }

        return parent::display($tpl = null);
    }
}
