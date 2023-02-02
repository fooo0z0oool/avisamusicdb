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

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View class for a list of Review.
 *
 * @since  1.0.0
 */
class AvisamusicdbViewReviews extends HtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The pagination object
	 *
	 * @var  JPagination
	 */
	protected $pagination;

	protected $model;
	public $filterForm, $activeFilters;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->items			= $this->get('Items');
		$this->state			= $this->get('State');
		$this->pagination		= $this->get('Pagination');
		$this->model			= $this->getModel('reviews');
		$this->filterForm		= $this->get('FilterForm');
		$this->activeFilters	= $this->get('ActiveFilters');

		AvisamusicdbHelper::addSubmenu('reviews');

		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode('<br>', $errors), 500);
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= ContentHelper::getActions('com_avisamusicdb', 'component');

		if ($canDo->get('core.create')) {
			ToolbarHelper::addNew('review.add');
		}

		if ($canDo->get('core.edit')) {
			ToolbarHelper::editList('review.edit');
		}

		if ($canDo->get('core.edit.state')) {
			ToolbarHelper::publish('reviews.publish', 'JTOOLBAR_PUBLISH', true);
			ToolbarHelper::unpublish('reviews.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			ToolbarHelper::archiveList('reviews.archive');
			ToolbarHelper::checkin('reviews.checkin');
		}

		if ($state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			ToolbarHelper::deleteList('', 'reviews.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')) {
			ToolbarHelper::trash('reviews.trash');
		}

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_avisamusicdb');
		}

		JHtmlSidebar::setAction('index.php?option=com_avisamusicdb&view=reviews');
		ToolbarHelper::title(sprintf('%s: %s', Text::_('COM_AVISAMUSICDB'), Text::_('COM_AVISAMUSICDB_TITLE_REVIEWS')), '');
	}
}
