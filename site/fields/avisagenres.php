<?php

/**
 * @author    AvisaPro http://www.avisapro.ir
 * @copyright Copyright (C) 2010 - 2013 AvisaPro
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2
 */



defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

class JFormFieldSpgenres extends FormField
{

    protected $type = 'Avisagenres';


    protected function getInput()
    {

        // Get Tournaments
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        // Select all records from the user profile table where key begins with "custom.".
        $query->select($db->quoteName(array('id', 'title', 'alias')));
        $query->from($db->quoteName('#__avisamusicdb_genres'));
        $query->where($db->quoteName('published') . " = 1");
        $query->order('ordering DESC');

        $db->setQuery($query);
        $results = $db->loadObjectList();
        $genres = $results;

        $options = array('' => Text::_('COM_AVISAMUSICDB_FIELD_ALL'));

        foreach ($genres as $genre) {
            $options[] = HTMLHelper::_('select.option', $genre->id, $genre->title);
        }

        return HTMLHelper::_('select.genericlist', $options, $this->name, ['class' => 'form-control form-select'], 'value', 'text', $this->value);
    }
}
