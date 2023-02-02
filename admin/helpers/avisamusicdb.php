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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Helper\ContentHelper;

class AvisamusicdbHelper extends ContentHelper
{
    /**
     * add sidemenu for joomla3
     *
     * @param string $vName view name
     * @return void
     */
    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
            Text::_('COM_AVISAMUSICDB_TITLE_CELEBRITIES'),
            'index.php?option=com_avisamusicdb&view=celebrities',
            $vName == 'celebrities'
        );
		
        JHtmlSidebar::addEntry(
            Text::_('COM_AVISAMUSICDB_TITLE_ALBUMS'),
            'index.php?option=com_avisamusicdb&view=albums',
            $vName == 'albums'
        );
        JHtmlSidebar::addEntry(
            Text::_('COM_AVISAMUSICDB_TITLE_GENRES'),
            'index.php?option=com_avisamusicdb&view=genres',
            $vName == 'genres'
        );

        JHtmlSidebar::addEntry(
            Text::_('COM_AVISAMUSICDB_TITLE_MUSICS'),
            'index.php?option=com_avisamusicdb&view=musics',
            $vName == 'musics'
        );

        JHtmlSidebar::addEntry(
            Text::_('COM_AVISAMUSICDB_TITLE_REVIEWS'),
            'index.php?option=com_avisamusicdb&view=reviews',
            $vName == 'reviews'
        );
    }


    /**
     * show star rating
     *
     * @param integer $rating
     * @param integer $max
     * @return string
     */
    public static function displayRating($rating, $max = 5)
    {
        $html = array();
        $html[] = "<div class='music-rating' style='margin-bottom:10px'>";
        for ($i = 1; $i <= $max; $i++) {
            if ($i <= $rating) {
                $html[] = "<i class='avisamusicdb-icon-star' style='color: #ffc000;'></i>";
            } else if ($i > $rating && $rating > $i - 1) {
                $html[] = "<i class='avisamusicdb-icon-star-blank></i>";
            } else {
                $html[] = "<i class='avisamusicdb-icon-star-blank' style='color: #ffc000;'></i>";
            }
        }
        $html[] = "<span>(" . $rating . ")</span></div>";
        return implode("\n", $html);
    }


    /**
     * remove image meta data from image URL
     *
     * @param string $url
     * @return string
     */
    public static function formatImageUrl($url)
    {
        $imageUrl = '';
        if (strpos($url, '#') !== false) {
            $arr = explode('#', $url);
            $imageUrl = $arr[0];
        } else {
            $imageUrl = $url;
        }
        return $imageUrl;
    }


    /**
     * convert repeatable field data to form-repeatable data
     *
     * @param string $data
     * @param string $fieldName
     * @return string return a JSON string
     */
    public static function repeatableToFormRepeatable($data, $fieldName)
    {
        if ($data == null || $data == '') return $data;

        $rows = json_decode($data, true);
        $firstRow = current($rows);

        // check data is already in form repeatable format
        // form-repeatable data has params key
        if (isset($firstRow['params'])) {
            return $data;
        }

        $totalRow = 0;
        $convertedData = [];
        $fields = [];

        foreach ($rows as $key => $val) {
            array_push($fields, $key);
        }

        if (is_array($rows)) {
            $totalRow = count($firstRow);
        }

        for ($i = 0; $i < $totalRow; $i++) {
            $fieldsData = [];
            foreach ($fields as $f) {
                $fieldsData[$f] = $rows[$f][$i];
            }
            $convertedData[$fieldName . $i] = [
                'params' => $fieldsData
            ];
        }

        return json_encode($convertedData);
    }


    /**
     * get anchor links for celebrities by celebrity type
     *
     * @param string $str
     * @param string $type
     * @return string
     * @since 2.0.0
     */
    public static function getLinks($str, $type = 'celebrity')
    {

        if (empty($type) || $str == '') return '...';

        try {
            $arr = json_decode($str);
        } catch (\Exception $e) {
            return '...';
        }

        $idString = implode(", ", $arr);

        $db   = Factory::getDbo();
        $result = [];
        if ($type == 'genres') {
            $sql = "select a.id,a.title from #__avisamusicdb_genres AS a where a.id IN ($idString)";
        } else {
            $sql = "select a.id,a.title from #__avisamusicdb_celebrities AS a where a.id IN ($idString)";
        }

        $db->setQuery($sql);
        $result = $db->loadObjectList();

        $links = '';
        foreach ($result as $row) {
            $links .= '<a href="' . Route::_('/administrator/index.php?option=com_avisamusicdb&view=celebrity&layout=edit&id=' . $row->id) . '">' . $row->title . '</a> ';
        }
        return $links;
    }
}
