<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;

/**
 * Avisamusicdb master display controller.
 *
 * @since  1.0.0
 */
class AvisamusicdbController extends BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  BannersController  This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view   = $this->input->get('view', 'musics');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		$this->input->set('view', $view);

		if ($view == 'music' && $layout == 'edit' && !$this->checkEditId('com_avisamusicdb.edit.music', $id)) {
			if (!\count($this->app->getMessageQueue())) {
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_avisamusicdb&view=musics', false));

			return false;
		}

		parent::display($cachable, $urlparams);

		return $this;
	}
}
