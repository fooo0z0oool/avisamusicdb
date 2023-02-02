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
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');
class JFormFieldAvisareview extends ListField
{
	protected $type = 'Avisareview';

	public function getRepeatable()
	{
		return $this->getInput();
	}

	public function getInput()
	{
		// $keyfield = $this->item->getKeyName();
		// $keyfield = $this->name;
		// $id  = $this->item->$keyfield;
		$id  = $this->id;

		$doc = Factory::getDocument();
		$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');

		$ratings = $this->getRating();
		$output = '<div class="music-rating" style="margin-bottom: 10px;">';

		for ($i = 0; $i < $ratings; $i++) {
			$output .= '<i class="avisamusicdb-icon-star" style="color: #ffc000;"></i>';
		}

		for ($i = 0; $i < 10 - $ratings; $i++) {
			$output .= '<i class="avisamusicdb-icon-star-blank" style="color: #ffc000;"></i>';
		}

		$output .= ' (' . $ratings . ')';

		$output .= '</div>';

		$output .= '<div class="music-review" style="margin-bottom: 10px;">';
		$output .= $this->value;
		$output .= '</div>';

		$output .= '<a href="index.php?option=com_avisamusicdb&view=review&id=' . $id . '">' . Text::_('COM_AVISAMUSICDB_EDIT_REVIEW') . '</a>';

		return $output;
	}

	protected function getRating()
	{
		// $keyfield = $this->item->getKeyName();
		// $keyfield = $this->name;
		// $id  = $this->item->$keyfield;
		$id  = $this->id;

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.rating'));
		$query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
		$query->where($db->quoteName('a.id') . ' = ' . $db->quote($id));
		$db->setQuery($query);
		$rating = $db->loadResult();

		return $rating;
	}
}
