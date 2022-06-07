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

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Version;
use Joomla\Database\Exception\ExecutionFailureException;
use Joomla\Registry\Registry;

if (Version::MAJOR_VERSION === 4)
{
	require_once __DIR__ . '/compatibility/menus-40.php';
}
else
{
	require_once __DIR__ . '/compatibility/menus-39.php';
}

/**
 * PWT Sitemap menus model
 *
 * @since   1.0.0
 */
class PwtSitemapModelMenus extends PwtSitemapModelMenusCompatibility
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.0.0
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);

		$config['filter_fields'][] = 'ordering';
		$config['filter_fields'][] = 'pwtsitemap_menu_types.ordering';
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();

		/** @var JDatabaseQuery $query */
		return parent::getListQuery()
			->select(
				$db->quoteName(
					[
						'pwtsitemap_menu_types.ordering',
						'pwtsitemap_menu_types.custom_title',
						'pwtsitemap_menu_types.params',
					]
				)
			)
			->leftJoin(
				$db->quoteName('#__pwtsitemap_menu_types', 'pwtsitemap_menu_types')
				. ' ON ' . $db->quoteName('pwtsitemap_menu_types.menu_types_id') . ' = ' . $db->quoteName('a.id')
			);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   The column to order
	 * @param   string  $direction  The direction of the order
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = 'pwtsitemap_menu_types.ordering', $direction = 'asc')
	{
		parent::populateState($ordering, $direction);
	}

	/**
	 * Save the order of the menu items.
	 *
	 * @param   array  $pks    The list of IDs to order
	 * @param   array  $order  The list of orders
	 *
	 * @return  boolean  True on success | False on failure
	 *
	 * @since   1.0.0
	 *
	 * @throws  Exception
	 */
	public function saveorder($pks, $order)
	{
		if (Version::MAJOR_VERSION === 4)
		{
			$items    = $this->getItems();
			$position = 1;
			$order    = [];

			foreach ($items as $item)
			{
				$order[] = $position;
				$position++;

				if (in_array($item->id, $pks))
				{
					continue;
				}

				$pks[] = $item->id;
			}
		}

		foreach ($pks as $index => $pk)
		{
			/** @var PwtSitemapModelMenu $menuModel */
			$menuModel      = BaseDatabaseModel::getInstance('Menu', 'PwtSitemapModel');
			$data           = $menuModel->getItem((int) $pk);
			$data->ordering = (int) $order[$index];

			try
			{
				$menuModel->save((array) $data);
			}
			catch (ExecutionFailureException $e)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Get all available menu items
	 *
	 * @return  array List of menu items
	 *
	 * @since   1.0.0
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{
			$item->params = new Registry(json_decode($item->params, true));

			if (empty($item->params->get('addcontenttohtmlsitemap')))
			{
				$item->params->set('addcontenttohtmlsitemap', 0);
			}

			if (empty($item->params->get('addcontenttoxmlsitemap')))
			{
				$item->params->set('addcontenttoxmlsitemap', 0);
			}
		}

		return $items;
	}
}
