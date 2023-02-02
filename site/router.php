<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\RouterViewConfiguration;

class AvisamusicdbRouter extends RouterView
{
	protected $noIDs = false;

	/**
	 * The DB Object
	 *
	 * @var		DatabaseDriver
	 * @sine	4.0.0
	 */
	private $db = null;
	/**
	 * The query string generator.
	 *
	 * @var		object
	 * @since	4.0.0
	 */
	private $queryBuilder = null;

	public function __construct($app = null, $menu = null)
	{
		$this->db = Factory::getDbo();
		$this->queryBuilder = $this->db->getQuery(true);

		$params = ComponentHelper::getParams('com_avisamusicdb');
		$this->noIDs = (bool) $params->get('sef_ids', 1);

		//single viw without parent
		$this->registerView(new RouterViewConfiguration('trailers'));
		$this->registerView(new RouterViewConfiguration('myreviews'));

		//views with parents
		$musics = new RouterViewConfiguration('musics');
		$this->registerView($musics);
		$music = new RouterViewConfiguration('music');
		$music->setKey('id')->setParent($musics);
		$this->registerView($music);

		$celebrities = new RouterViewConfiguration('celebrities');
		$this->registerView($celebrities);
		$celebrity = new RouterViewConfiguration('celebrity');
		$celebrity->setKey('id')->setParent($celebrities);
		$this->registerView($celebrity);

		$albums = new RouterViewConfiguration('albums');
		$this->registerView($albums);
		$album = new RouterViewConfiguration('album');
		$album->setKey('id')->setParent($albums);
		$this->registerView($album);
		
		parent::__construct($app, $menu);
		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 1)) {
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
	}


	/**
	 * Get missing alias from the provided ID.
	 *
	 * @param	string		$id		The ID with or without the alias.
	 * @param	string		$table	The table name.
	 *
	 * @return	string		The alias string.
	 * @since	2.0.0
	 */
	private function getAlias(string $id, string $table): string
	{
		try {
			$this->queryBuilder->clear();
			$this->queryBuilder->select('alias')
				->from($this->db->quoteName($table))
				->where($this->db->quoteName('id') . ' = ' . (int) $id);
			$this->db->setQuery($this->queryBuilder);
			return (string) $this->db->loadResult();
		} catch (Exception $e) {
			echo $e->getMessage();
			return '';
		}
	}

	/**
	 * Get id from the alias.
	 *
	 * @param	string		$alias		The alias string.
	 * @param	string		$table		The table name.
	 *
	 * @return	int			The id.
	 * @since	2.0.0
	 */
	private function getId(string $alias, string $table): int
	{
		try {
			$this->queryBuilder->clear();
			$this->queryBuilder->select('id')
				->from($this->db->quoteName($table))
				->where($this->db->quoteName('alias') . ' = ' . $this->db->quote($alias));
			$this->db->setQuery($this->queryBuilder);
			return (int) $this->db->loadResult();
		} catch (Exception $e) {
			echo $e->getMessage();
			return 0;
		}
	}

	/**
	 * Get the view segment for the common views.
	 *
	 * @param	string	$id		The ID with or without alias.
	 * @param	string	$table	The table name.
	 *
	 * @return	array	The segment array.
	 * @since	2.0.0
	 */
	private function getViewSegment(string $id, string $table): array
	{
		if (strpos($id, ':') === false) {
			$id .= ':' . $this->getAlias($id, $table);
		}

		if ($this->noIDs) {
			list($key, $alias) = explode(':', $id, 2);

			return [$key => $alias];
		}

		return [(int) $id => $id];
	}

	/**
	 * get the view ID for the common pattern view.
	 *
	 * @param	string	$segment	The segment string.
	 * @param	string	$table		The table name.
	 *
	 * @return	int		The id.
	 * @since	2.0.0
	 */
	private function getViewId(string $segment, string $table): int
	{
		return $this->noIDs
			? $this->getId($segment, $table)
			: (int) $segment;
	}

	/**
	 * Method to get the segment(s) for a course
	 *
	 * @param   string  $id     ID of the article to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getMusicSegment($id, $query)
	{
		return $this->getViewSegment($id, '#__avisamusicdb_musics');
	}

	/**
	 * Method to get the segment(s) for a teacher
	 *
	 * @param   string  $segment  Segment of the article to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getMusicId($segment, $query)
	{
		return $this->getViewId($segment, '#__avisamusicdb_musics');
	}

	/**
	 * Method to get the segment(s) for a course
	 *
	 * @param   string  $id     ID of the article to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getCelebritySegment($id, $query)
	{
		return $this->getViewSegment($id, '#__avisamusicdb_celebrities');
	}
	
	/**
	 * Method to get the segment(s) for a teacher
	 *
	 * @param   string  $segment  Segment of the article to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getCelebrityId($segment, $query)
	{
		return $this->getViewId($segment, '#__avisamusicdb_celebrities');
	}
	
		/**
	 * Method to get the segment(s) for a course
	 *
	 * @param   string  $id     ID of the article to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getAlbumSegment($id, $query)
	{
		return $this->getViewSegment($id, '#__avisamusicdb_albums');
	}

	/**
	 * Method to get the segment(s) for a teacher
	 *
	 * @param   string  $segment  Segment of the article to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getAlbumId($segment, $query)
	{
		return $this->getViewId($segment, '#__avisamusicdb_albums');
	}
}

/**
 * Content router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function avisamusicdbBuildRoute(&$query)
{
	$app = Factory::getApplication();
	$router = new AvisamusicdbRouter($app, $app->getMenu());

	return $router->build($query);
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function avisamusicdbParseRoute($segments)
{
	$app = Factory::getApplication();
	$router = new AvisamusicdbRouter($app, $app->getMenu());

	return $router->parse($segments);
}
