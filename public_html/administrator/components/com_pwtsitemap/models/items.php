<?php
/**
 * @package    Pwtsitemap
 *
 * @author     Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2016 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com
 */

use Joomla\CMS\Version;
use Joomla\Registry\Registry;

if (Version::MAJOR_VERSION === 4)
{
	require_once __DIR__ . '/compatibility/items-40.php';
}
else
{
	require_once __DIR__ . '/compatibility/items-39.php';
}

/**
 * PWT Sitemap items model
 *
 * @since   1.0.0
 */
class PwtSitemapModelItems extends PwtSitemapModelItemsCompatibility
{
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

		foreach ($items as $i => $item)
		{
			$item->params = new Registry(json_decode($item->params, true));
		}

		return $items;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   Ordering
	 * @param   string  $direction  Direction
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = 'a.lft', $direction = 'asc')
	{
		parent::populateState($ordering, $direction);
	}
}
