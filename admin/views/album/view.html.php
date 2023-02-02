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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;

class AvisamusicdbViewAlbum extends HtmlView
{
	protected $item;
	protected $form;

	public function display($tpl = null)
	{
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode('<br>', $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	protected function addToolbar()
	{
		$input = Factory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user = Factory::getUser();
		$userId = $user->id;
		$isNew = $this->item->id == 0;
		$canDo = ContentHelper::getActions('com_avisamusicdb', 'component');

		ToolbarHelper::title(sprintf('%s: %s', Text::_('COM_AVISAMUSICDB'), Text::_('COM_AVISAMUSICDB_TITLE_ALBUMS_EDIT')), '');

		if ($canDo->get('core.edit')) {
			ToolbarHelper::apply('album.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save('album.save', 'JTOOLBAR_SAVE');
			ToolbarHelper::save2new('album.save2new');
			ToolbarHelper::save2copy('album.save2copy');
		}

		ToolbarHelper::cancel('album.cancel', 'JTOOLBAR_CLOSE');
	}
}
