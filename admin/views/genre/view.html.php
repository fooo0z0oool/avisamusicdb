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

/**
 * View to edit a Genre.
 *
 * @since  1.0.0
 */
class AvisamusicdbViewGenre extends HtmlView
{
	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The JForm object
	 *
	 * @var  JForm
	 */
	protected $form;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
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

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$input = Factory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$canDo = ContentHelper::getActions('com_avisamusicdb', 'component');

		ToolbarHelper::title(sprintf('%s: %s', Text::_('COM_AVISAMUSICDB'), Text::_('COM_AVISAMUSICDB_TITLE_GENRES_EDIT')), '');

		if ($canDo->get('core.edit')) {
			ToolbarHelper::apply('genre.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save('genre.save', 'JTOOLBAR_SAVE');
			ToolbarHelper::save2new('genre.save2new');
			ToolbarHelper::save2copy('genre.save2copy');
		}

		ToolbarHelper::cancel('genre.cancel', 'JTOOLBAR_CLOSE');
	}
}
