<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Uri\Uri;

$url 		= $displayData['url'];
$title 		= $displayData['title'];
$root       = Uri::base();
$root       = new Uri($root);
$itemUrl   	= $root->getScheme() . '://' . $root->getHost() . $url;

?>

<ul>
	<li>
		<a class="facebook" onClick="window.open('http://www.facebook.com/sharer.php?u=<?php echo $itemUrl; ?>','Facebook','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="http://www.facebook.com/sharer.php?u=<?php echo $itemUrl; ?>"><i class="avisamusicdb-icon-facebook"></i></a>
	</li>
	<li>
		<a class="twitter" onClick="window.open('http://twitter.com/share?url=<?php echo $itemUrl; ?>&amp;text=<?php echo str_replace(" ", "%20", $title); ?>','Twitter share','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="http://twitter.com/share?url=<?php echo $itemUrl; ?>&amp;text=<?php echo str_replace(" ", "%20", $title); ?>"><i class="avisamusicdb-icon-twitter"></i></a>
	</li>
	<li>
		<a class="googleplus" onClick="window.open('https://plus.google.com/share?url=<?php echo $itemUrl; ?>','Google plus','width=585,height=666,left='+(screen.availWidth/2-292)+',top='+(screen.availHeight/2-333)+''); return false;" href="https://plus.google.com/share?url=<?php echo $itemUrl; ?>"><i class="avisamusicdb-icon-google-plus"></i></a>
	</li>
	<li>
		<a class="pinterest" href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());'><i class="avisamusicdb-icon-pinterest"></i></a>
	</li>
</ul>