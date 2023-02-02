<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$input = Factory::getApplication()->input;
$Itemid = $input->get('Itemid', 0, 'INT');
$alphaindex = $input->get('alphaindex', 'all', 'WORD');

$allAlphaUrl = 'index.php?option=com_avisamusicdb&view=celebrities&Itemid=' . $Itemid;

if ($alphaindex) {
    $alphaUrl = 'index.php?option=com_avisamusicdb&view=celebrities&alphaindex=' . $alphaindex . '&Itemid=' . $Itemid;
} else {
    $alphaUrl = 'index.php?option=com_avisamusicdb&view=celebrities&Itemid=' . $Itemid;
}

?>

<div id="com-avisa-musicdb" class="avisamusicdb avisa-musicdb avisa-musicdb-view-celebrities">

    <div class="musicdb-filters">
        <div class="pull-left">
            <ul>
                <li class="<?php echo ($alphaindex == 'all') ? 'active' : ''; ?>">
                    <a href="<?php echo Route::_($allAlphaUrl); ?>"><?php echo Text::_('COM_AVISAMUSICDB_ALL'); ?></a>
                </li>
                <?php foreach ($this->alphabets as $alphabet) { ?>
                    <li class="<?php echo ($alphaindex == $alphabet) ? 'active' : ''; ?>">
                        <a href="<?php echo Route::_($alphaUrl . '&alphaindex=' . $alphabet); ?>">
                            <?php echo $alphabet; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="pull-right">
            <?php echo Text::_('COM_AVISAMUSICDB_TOTAL'); ?>:
            <strong><?php echo $this->total_celebrities; ?></strong>
        </div>
    </div>

    <?php if (!count($this->items)) { ?>
        <div class="alert alert-warning">
            <?php echo Text::_('COM_AVISAMUSICDB_NOTHING_FOUND'); ?>
        </div>
    <?php } ?>

    <?php foreach (array_chunk($this->items, $this->columns) as $this->items) { ?>
        <div class="avisamusicdb-row">
            <?php foreach ($this->items as $celebrity) { ?>
                <div class="item avisamusicdb-col-sm-<?php echo round(12 / $this->columns); ?>">
                    <div class="celebritie-poster">
                        <img src="<?php echo Uri::root() . $celebrity->profile_image; ?>" alt="<?php echo $celebrity->title; ?>">
                        <a href="<?php echo $celebrity->url; ?>" class="play-icon"><i class="avisamusicdb-icon-celebrities"></i></a>
                    </div>
                    <div class="celebritie-details">
                        <div class="celebritie-name">
                            <a href="<?php echo $celebrity->url; ?>">
                                <h4 class="celebritie-title"><?php echo $celebrity->title; ?></h4>
                            </a>
                            <span><?php echo $celebrity->designation; ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php 
        if ($this->pagination->pagesTotal > 1) { ?>
        <?php echo $this->pagination->getPagesLinks(); ?>
    <?php } ?>

</div>