<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Form\Field\ListField;

FormHelper::loadFieldClass('list');

class JFormFieldAvisacelebrities extends ListField
{

	public function getOptions()
	{
		$db		= Factory::getDbo();
		$query	= $db->getQuery(true)
			->select('DISTINCT a.id AS value, a.title AS text')
			->from('#__avisamusicdb_celebrities AS a');

		$query->order('a.id ASC');

		// Get the options.
		$db->setQuery($query);

		try {
			$options = $db->loadObjectList();
		} catch (RuntimeException $e) {
			return false;
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	public function getRepeatable()
	{
		$celebrity_ids = $this->value;

		if (isset($celebrity_ids) && count(json_decode($celebrity_ids))) {
			$celebrity_ids = implode(',', json_decode($celebrity_ids));
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->select(array('a.*'));
			$query->from($db->quoteName('#__avisamusicdb_celebrities', 'a'));
			$query->where($db->quoteName('a.id') . " IN (" . $celebrity_ids . ")");
			$db->setQuery($query);
			$clebrities = $db->loadObjectList();

			$output = '';
			foreach ($clebrities as $key => $celebrity) {
				$output .= '<a href="index.php?option=com_avisamusicdb&view=celebrity&id=' . $celebrity->id . '">' . $celebrity->title . '</a>' . ', ';
			}

			return rtrim(trim($output), ',');
		}

		return '....';
	}
}
