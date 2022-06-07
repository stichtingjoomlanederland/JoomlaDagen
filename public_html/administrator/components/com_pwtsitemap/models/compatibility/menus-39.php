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

JLoader::register('MenusModelMenus', JPATH_ADMINISTRATOR . '/components/com_menus/models/menus.php');

/**
 * PWT Sitemap menus model
 *
 * @since   1.0.0
 */
class PwtSitemapModelMenusCompatibility extends MenusModelMenus
{
}
