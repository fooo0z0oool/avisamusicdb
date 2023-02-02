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
use Joomla\CMS\Form\FormField;


class JFormFieldSpgenries extends FormField
{
	protected $type = 'Spgenries';

	public function getRepeatable()
	{
		return $this->getInput();
	}

	public function getInput()
	{

		$genre_ids = $this->value;

		if (isset($genre_ids) && count(json_decode($genre_ids))) {
			$genre_ids = implode(',', json_decode($genre_ids));
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->select(array('a.*'));
			$query->from($db->quoteName('#__avisamusicdb_genres', 'a'));
			$query->where($db->quoteName('a.id') . " IN (" . $genre_ids . ")");
			$db->setQuery($query);
			$genries = $db->loadObjectList();

			$output = '';
			foreach ($genries as $key => $genre) {
				$output .= '<a href="index.php?option=com_avisamusicdb&view=genre&id=' . $genre->id . '">' . $genre->title . '</a>' . ', ';
			}

			return rtrim(trim($output), ',');
		}

		return '....';
	}
}
