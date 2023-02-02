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
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class AvisamusicdbViewMusics extends HtmlView
{
	protected $items;
	protected $state;
	protected $pagination;
	protected $model;
	public $filterForm, $activeFilters;

	public function display($tpl = null)
	{
		$this->items			= $this->get('Items');
		$this->state			= $this->get('State');
		$this->pagination		= $this->get('Pagination');
		$this->model			= $this->getModel('musics');
		$this->filterForm		= $this->get('FilterForm');
		$this->activeFilters	= $this->get('ActiveFilters');


		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode('<br>', $errors), 500);
		}

		AvisamusicdbHelper::addSubmenu('musics');
		$this->sidebar = JHtmlSidebar::render();

		$this->addToolbar();
		return parent::display($tpl);
	}

	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= ContentHelper::getActions('com_avisamusicdb', 'component');
		$user	= Factory::getUser();
		$bar	= Toolbar::getInstance('toolbar');

		if ($canDo->get('core.create')) {
			ToolbarHelper::addNew('music.add');
		}

		if ($canDo->get('core.edit')) {
			ToolbarHelper::editList('music.edit');
		}

		if ($canDo->get('core.edit.state')) {
			ToolbarHelper::publish('musics.publish', 'JTOOLBAR_PUBLISH', true);
			ToolbarHelper::unpublish('musics.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			ToolbarHelper::archiveList('musics.archive');
			ToolbarHelper::checkin('musics.checkin');
		}

		if ($state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			ToolbarHelper::deleteList('', 'musics.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')) {
			ToolbarHelper::trash('musics.trash');
		}

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_avisamusicdb');
		}

		JHtmlSidebar::setAction('index.php?option=com_avisamusicdb&view=musics');
		ToolbarHelper::title(sprintf('%s: %s', Text::_('COM_AVISAMUSICDB'), Text::_('COM_AVISAMUSICDB_TITLE_MUSICS')), '');
	}
}
