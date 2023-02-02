<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;

class JFormFieldAvisamusic extends FormField
{
	protected $type = 'Avisamusic';

	public function getRepeatable()
	{
		return $this->getInput();
	}

	public function getInput()
	{
		$music_id = $this->value;
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($db->quoteName('a.id') . ' = ' . $db->quote($music_id));
		$db->setQuery($query);
		$music = $db->loadObject();

		return '<a href="index.php?option=com_avisamusicdb&view=music&id=' . $music_id . '">' . $music->title . '</a>';
	}
}
