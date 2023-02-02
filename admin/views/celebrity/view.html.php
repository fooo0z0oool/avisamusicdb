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

class AvisamusicdbViewCelebrity extends HtmlView
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

		ToolbarHelper::title(sprintf('%s: %s', Text::_('COM_AVISAMUSICDB'), Text::_('COM_AVISAMUSICDB_TITLE_CELEBRITIES_EDIT')), '');

		if ($canDo->get('core.edit')) {
			ToolbarHelper::apply('celebrity.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save('celebrity.save', 'JTOOLBAR_SAVE');
			ToolbarHelper::save2new('celebrity.save2new');
			ToolbarHelper::save2copy('celebrity.save2copy');
		}

		ToolbarHelper::cancel('celebrity.cancel', 'JTOOLBAR_CLOSE');
	}
}
