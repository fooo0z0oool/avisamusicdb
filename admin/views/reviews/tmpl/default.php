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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Version;

$user 		= Factory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn 	= $this->escape($this->state->get('list.direction'));
$canOrder 	= $user->authorise('core.edit.state', 'com_avisamusicdb');
$saveOrder = ($listOrder == 'a.ordering');

if ($saveOrder && !empty($this->items)) {
	if (Version::MAJOR_VERSION < 4) {
		$saveOrderingUrl = 'index.php?option=com_avisamusicdb&task=reviews.saveOrderAjax&tmpl=component';
		$html = HTMLHelper::_('sortablelist.sortable', 'reviewList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
	} else {
		$saveOrderingUrl = 'index.php?option=com_avisamusicdb&task=reviews.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
		HTMLHelper::_('draggablelist.draggable');
	}
}

HTMLHelper::_('jquery.framework', false);
?>

<script type="text/javascript">
	window.addEventListener("DOMContentLoaded", e => {
		Joomla.orderTable = function() {
			table = document.getElementById('sortTable');
			direction = document.getElementById('directionTable');
			order = table.options[table.selectedIndex].value;
			if (order != '<?php echo $listOrder; ?>') {
				dirn = 'asc';
			} else {
				dirn = direction.options[direction.selectedIndex].value;
			}
			Joomla.tableOrdering(order, dirn, '');
		}
	})
</script>

<form action="<?php echo Route::_('index.php?option=com_avisamusicdb&view=reviews'); ?>" method="POST" name="adminForm" id="adminForm">
	<?php if (JVERSION < 4 && !empty($this->sidebar)) { ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>

		<div id="j-main-container" class="span10">
		<?php } else { ?>
			<div id="j-main-container"></div>
		<?php } ?>

		<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<div class="clearfix"></div>
		<?php if (!empty($this->items)) { ?>
			<table class="table table-striped" id="reviewList">
				<thead>
					<tr>
						<th class="nowrap center hidden-phone" width="1%">
							<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>

						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>

						<th width="1%" class="nowrap center">
							<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
						</th>

						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>

						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_AVISAMUSICDB_FIELD_MUSIC_REVIEW', 'a.review', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_AVISAMUSICDB_FIELD_REVIEW_MUSIC', 'a.musicid', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_AVISAMUSICDB_FIELD_USER', 'a.created_by', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_AVISAMUSICDB_FIELD_REVIEW_CREATED_ON', 'a.created', $listDirn, $listOrder); ?>
						</th>

					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<?php if (JVERSION < 4) : ?>
					<tbody>
					<?php else : ?>
					<tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false" <?php endif; ?>>
					<?php endif; ?>
					<?php foreach ($this->items as $i => $item) : ?>

						<?php
						$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
						$canChange		= $user->authorise('core.edit.state', 'com_avisamusicdb') && $canCheckin;
						$canEdit		= $user->authorise('core.edit', 'com_avisamusicdb');
						?>
						<?php if (JVERSION < 4) : ?>
							<tr>
							<?php else : ?>
							<tr class="row<?php echo $i % 2; ?>" data-draggable-group="1">
							<?php endif; ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel = '';
									if (!$saveOrder) :
										$disabledLabel = Text::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif;
								?>

									<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
										<i class="icon-menu"></i>
									</span>
									<input type="text" style="display: none;" name="order[]" size="5" class="width-20 text-area-order " value="<?php echo $item->ordering; ?>">
								<?php else : ?>
									<span class="sortable-handler inactive">
										<i class="icon-menu"></i>
									</span>
								<?php endif; ?>
							</td>

							<td class="center hidden-phone">
								<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
							</td>

							<td class="center">
								<?php if (Version::MAJOR_VERSION > 3) { ?>
									<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'reviews.', true, 'cb'); ?>
								<?php } else { ?>
									<div class="btn-group">
										<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'reviews.', true, 'cb'); ?>
										<?php
										if ($canChange) {
											HTMLHelper::_('actionsdropdown.' . ((int) $item->published === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'reviews');
											HTMLHelper::_('actionsdropdown.' . ((int) $item->published === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'reviews');
											echo HTMLHelper::_('actionsdropdown.render', $this->escape($item->musicid));
										}
										?>
									</div>
								<?php } ?>
							</td>
							<td>
								<?php if ($canEdit) : ?>
									<a class="title" href="<?php echo Route::_('index.php?option=com_avisamusicdb&task=review.edit&id=' . $item->id); ?>">
										<?php echo $this->escape($item->id); ?>
									</a>
								<?php else : ?>
									<?php echo $this->escape($item->id); ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if ($item->checked_out) : ?>
									<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'reviews.', $canCheckin); ?>
								<?php endif; ?>

								<?php echo AvisamusicdbHelper::displayRating($item->rating, 10); ?>

								<?php if ($canEdit) : ?>
									<a class="title" href="<?php echo Route::_('index.php?option=com_avisamusicdb&task=review.edit&id=' . $item->id); ?>">
										<?php echo $this->escape($item->review); ?>
									</a>
								<?php else : ?>
									<?php echo $this->escape($item->review); ?>
								<?php endif; ?>
							</td>

							<td>
								<?php echo $this->escape($item->music_name); ?>
							</td>

							<td>
								<?php echo Factory::getUser($item->created_by)->username; ?>
							</td>

							<td>
								<?php echo HTMLHelper::_('date', $item->created, 'd M, Y'); ?>
							</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
			</table>
		<?php } else { ?>
			<div class="alert alert-danger"><?php echo Text::_('COM_AVISAMUSICDB_NO_RECORD_FOUND'); ?></div>
		<?php } ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo HTMLHelper::_('form.token'); ?>
		</div>
</form>