<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     	GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

/**
 * Review controller class.
 *
 * @since  1.0.0
 */
class AvisamusicdbControllerReview extends FormController
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	protected function allowAdd($data = array())
	{
		return parent::allowAdd($data);
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		$id = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = Factory::getUser();

		if (!$id) {
			return parent::allowEdit($data, $key);
		}

		if ($user->authorise('core.edit', 'com_avisamusicdb.review.' . $id)) {
			return true;
		}

		if ($user->authorise('core.edit.own', 'com_avisamusicdb.review.' . $id)) {
			$record = $this->getModel()->getItem($id);
			if (empty($record)) {
				return false;
			}
			return $user->id === $record->created_by;
		}
		return false;
	}
}
