<?php
/**
 * @package    Pwtsitemap
 *
 * @author     Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2016 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * PWT Sitemap helper
 *
 * @since  1.0.0
 */
abstract class PwtSitemapHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			Text::_('COM_PWTSITEMAP_TITLE_DASHBOARD'),
			'index.php?option=com_pwtsitemap&view=dashboard',
			$vName === 'dashboard'
		);

		JHtmlSidebar::addEntry(
			Text::_('COM_PWTSITEMAP_TITLE_MENUS'),
			'index.php?option=com_pwtsitemap&view=menus',
			$vName === 'menus'
		);

		JHtmlSidebar::addEntry(
			Text::_('COM_PWTSITEMAP_TITLE_ITEMS'),
			'index.php?option=com_pwtsitemap&view=items',
			$vName === 'items'
		);
	}

	/**
	 * Save a menu item parameter
	 *
	 * @param   int     $itemId     Menu item id
	 * @param   string  $parameter  Parameter to change
	 * @param   mixed   $value      Value of parameter
	 * @param   string  $table      The table to update the params. Default: #__menu
	 *
	 * @return  boolean  True on success, false otherwise
	 *
	 * @since   1.0.0
	 */
	public static function saveMenuItemParameter($itemId, $parameter, $value, $table = '#__menu')
	{
		// Get current parameters and set new
		$params = self::getMenuItemParameters($itemId, $table);

		if ($params === null)
		{
			$insert = true;
			$params = new stdClass;
		}

		$params->{$parameter} = $value;

		// Save parameters
		$params = json_encode($params);

		if (isset($insert))
		{
			self::insertParams($itemId, $params, $table);
		}
		else
		{
			self::updateParams($itemId, $params, $table);
		}

		return true;
	}

	/**
	 * Get the parameter of a menu item
	 *
	 * @param   int     $itemId  Menu item id
	 * @param   string  $table
	 *
	 * @return  stdClass The menu item parameters
	 *
	 * @since   1.0.0
	 */
	public static function getMenuItemParameters($itemId, $table = '#__menu')
	{
		$idFieldName = ($table === '#__menu') ? "id" : "menu_types_id";

		$db    = Factory::getDbo();
		$query = $db
			->getQuery(true)
			->select($db->quoteName('params'))
			->from($db->quoteName($table))
			->where($db->quoteName($idFieldName) . '=' . (int) $itemId);

		if ($table === '#__pwtsitemap_menu_types')
		{
			$query->select($db->quoteName('menu_types_id'));
		}

		$db->setQuery($query);

		$entry  = $db->loadObject();
		$params = null;

		if ($table === '#__pwtsitemap_menu_types')
		{
			if (isset($entry->menu_types_id))
			{
				$params = json_decode($entry->params);

				if (is_null($params))
				{
					$params = new stdClass;
				}
			}
		}
		else
		{
			$params = json_decode($entry->params);
		}

		return $params;
	}

	/**
	 * Save a menu item parameter
	 *
	 * @param   int    $itemId  Menu item id
	 * @param   mixed  $value   Value of parameter
	 *
	 * @return  boolean  True on success, false otherwise
	 *
	 * @since   1.0.0
	 */
	public static function saveMenuItemRobots($itemId, $value)
	{
		// Get current parameters and set new
		$params         = self::getMenuItemParameters($itemId);
		$params->robots = $value;

		// Save parameters
		$params = json_encode($params);

		self::updateParams($itemId, $params);

		return true;
	}

	/**
	 * Save a menu item parameter
	 *
	 * @param   int     $itemId  Menu item id
	 * @param   string  $params  The params
	 * @param   string  $table
	 *
	 * @return  bool    True on success, false otherwise
	 *
	 * @since   1.0.0
	 */
	private static function insertParams($itemId, $params, $table = '#__menu')
	{
		$idFieldName = ($table === '#__menu') ? "id" : "menu_types_id";

		$db = Factory::getDbo();

		$data = (object) [
			$idFieldName => (int) $itemId,
			'params'     => $params
		];

		return $db->insertObject($table, $data);
	}

	/**
	 * Update a menu item parameter
	 *
	 * @param   int     $itemId  Menu item id
	 * @param   string  $params  The params
	 * @param   string  $table
	 *
	 * @return void True on success, false otherwise
	 *
	 * @since   1.0.0
	 */
	private static function updateParams($itemId, $params, $table = '#__menu')
	{
		$idFieldName = ($table === '#__menu') ? "id" : "menu_types_id";

		$db    = Factory::getDbo();
		$query = $db
			->getQuery(true)
			->clear()
			->update($db->quoteName($table))
			->set($db->quoteName('params') . '=' . $db->quote($params))
			->where($db->qn($idFieldName) . '=' . (int) $itemId);

		return $db->setQuery($query)->execute();
	}
}
