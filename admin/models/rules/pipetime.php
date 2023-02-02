<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

/**
 * Pipetime validation rule class
 * 
 * @since 2.0.0
 */
class JFormRulePipetime extends FormRule
{

    /**
     * pipe time validation regex.
     * valid 10:30 or 10:00|11:30|12:00
     * invalid format 10:30| or 10
     *
     * @var string
     */
    protected $regex = '/^([0-1][0-9]|[2][0-3]):([0-5][0-9])$|(([0-1][0-9]|[2][0-3]):([0-5][0-9])\|?).+(\d$)/';

    public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
    {
        if (preg_match($this->regex, $value) == false) {
            $element->attributes()->message = Text::_($element->attributes()->label) . ' field value is invalid. Valid example: 10:30|11:30|12:00';
            return false;
        }

        return true;
    }
}
