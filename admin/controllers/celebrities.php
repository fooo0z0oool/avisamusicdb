<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license       GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Celebrity list controller class.
 *
 * @since  1.0.0
 */
class AvisamusicdbControllerCelebrities extends AdminController
{
      /**
       * Method to get a model object, loading it if required.
       *
       * @param string $name
       * @param string $prefix
       * @param array $config
       * @return void
       */
      public function getModel($name = 'Celebrity', $prefix = 'AvisamusicdbModel', $config = array('ignore_request' => true))
      {
            $model = parent::getModel($name, $prefix, $config);
            return $model;
      }
}
