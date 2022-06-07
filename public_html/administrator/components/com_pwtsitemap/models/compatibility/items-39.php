<?php
/**
 * @package    Pwtsitemap
 *
 * @author     Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2016 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com
 */

JLoader::register('MenusModelItems', JPATH_ADMINISTRATOR . '/components/com_menus/models/items.php');

/**
 * PWT Sitemap items model
 *
 * @since   1.0.0
 */
class PwtSitemapModelItemsCompatibility extends MenusModelItems
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   2.0.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'language',
				'menutype',

				'menutype_title',
				'a.title'
			);
		}

		parent::__construct($config);
	}
}
