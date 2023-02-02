<?php

/**
 * @package     Joomla.Plugins
 * @subpackage  System.avisamusicdbupdater
 * @author      AvisaPro http://www.avisapro.ir
 * @copyright   Copyright (c) 2020 - 2023 AvisaPro
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */


//no direct accees
defined('_JEXEC') or die('restricted aceess');

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Registry\Registry;

class plgSystemAvisamusicdbupdater extends CMSPlugin
{
    /**
     * fire this method on extension after save
     *
     * @param string $option
     * @param object $data
     * @return void
     */
    public function onExtensionAfterSave($option, $data)
    {

        if (($option == 'com_config.component') && ($data->element == 'com_avisamusicdb')) {

            $params = new Registry();
            $params->loadString($data->params);

            $email       = $params->get('avisapro_email');
            $license_key = $params->get('avisapro_license_key');

            if (!empty($email) and !empty($license_key)) {

                $extra_query = 'avisapro_email=' . urlencode($email);
                $extra_query .= '&amp;avisapro_license_key=' . urlencode($license_key);

                $db = Factory::getDbo();

                $fields = array(
                    $db->quoteName('extra_query') . '=' . $db->quote($extra_query),
                    $db->quoteName('last_check_timestamp') . '=0'
                );

                // Update site
                $query = $db->getQuery(true)
                    ->update($db->quoteName('#__update_sites'))
                    ->set($fields)
                    ->where($db->quoteName('name') . '=' . $db->quote('Avisa  Music Database'));
                $db->setQuery($query);
                $db->execute();
            }
        }
    }
}
