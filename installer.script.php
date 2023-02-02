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
use Joomla\CMS\Installer\Installer;

/**
 * Installer script class for com_avisamusicdb.
 *
 * @since  1.0.0
 */
class com_avisamusicdbInstallerScript
{

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent)
    {

        $extensions = array(
            array('type' => 'module', 'name' => 'mod_avisa_musicdb_trailers'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_celebraties'),
			array('type' => 'module', 'name' => 'mod_avisamusicdb_albums'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_music'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_search'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_tab'),
            array('type' => 'plugin', 'name' => 'avisamusicdbupdater')
        );

        foreach ($extensions as $key => $extension) {
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('extension_id')));
            $query->from($db->quoteName('#__extensions'));
            $query->where($db->quoteName('type') . ' = ' . $db->quote($extension['type']));
            $query->where($db->quoteName('element') . ' = ' . $db->quote($extension['name']));
            $db->setQuery($query);
            $id = $db->loadResult();

            if (isset($id) && $id) {
                $installer = new Installer;
                $result = $installer->uninstall($extension['type'], $id);
            }
        }
    }

    /**
     * Runs right after any installation action is performed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  object $parent - Parent object calling object.
     *
     * @return void
     */
    function postflight($type, $parent)
    {
        $extensions = array(
            array('type' => 'module', 'name' => 'mod_avisa_musicdb_trailers'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_celebraties'),
			array('type' => 'module', 'name' => 'mod_avisamusicdb_albums'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_music'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_search'),
            array('type' => 'module', 'name' => 'mod_avisamusicdb_tab'),
            array('type' => 'plugin', 'name' => 'avisamusicdbupdater', 'group' => 'system')
        );

        foreach ($extensions as $key => $extension) {
            $ext = $parent->getParent()->getPath('source') . '/' . $extension['type'] . 's/' . $extension['name'];
            $installer = new Installer;
            $installer->install($ext);

            if ($extension['type'] == 'plugin') {
                $db = Factory::getDbo();
                $query = $db->getQuery(true);

                $fields = array($db->quoteName('enabled') . ' = 1');
                $conditions = array(
                    $db->quoteName('type') . ' = ' . $db->quote($extension['type']),
                    $db->quoteName('element') . ' = ' . $db->quote($extension['name']),
                    $db->quoteName('folder') . ' = ' . $db->quote($extension['group'])
                );

                $query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $db->execute();
            }
        }
    }
}
