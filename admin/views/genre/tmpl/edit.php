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
use Joomla\CMS\Version;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

$doc = Factory::getDocument();
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
if (Version::MAJOR_VERSION < 4) {
	HTMLHelper::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0));
}
$rowClass = Version::MAJOR_VERSION < 4 ? 'row-fluid' : 'row';
$colClass = Version::MAJOR_VERSION < 4 ? 'span' : 'col-lg-';
$tab = Version::MAJOR_VERSION < 4 ? 'bootstrap' : 'uitab';
?>

<form action="<?php echo Route::_('index.php?option=com_avisamusicdb&view=genre&layout=edit&id=' . (int) $this->item->id); ?>" name="adminForm" id="adminForm" method="post" class="form-validate">
	<?php if (Version::MAJOR_VERSION < 4 && !empty($this->sidebar)) { ?>
		<div id="j-sidebar-container" class="<?php echo $colClass; ?>2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="<?php echo $colClass; ?>10">
		<?php } else { ?>
			<div id="j-main-container"></div>
		<?php } ?>
		<div class="form-horizontal">
			<div class="<?php echo $rowClass; ?>">
				<div class="<?php echo $colClass; ?>12">

					<?php echo HTMLHelper::_("$tab.startTabSet", 'myTab', array('active' => 'basic')); ?>

					<?php echo HTMLHelper::_("$tab.addTab", 'myTab', 'basic', Text::_('COM_AVISAMUSICDB_FIELDSET_GENRE')); ?>
					<?php echo $this->form->renderFieldset('basic'); ?>
					<?php echo HTMLHelper::_("$tab.endTab"); ?>

					<?php echo HTMLHelper::_("$tab.endTabSet"); ?>

				</div>
			</div>
		</div>

		<input type="hidden" name="task" value="genre.edit" />
		<?php echo HTMLHelper::_('form.token'); ?>
		</div>
</form>