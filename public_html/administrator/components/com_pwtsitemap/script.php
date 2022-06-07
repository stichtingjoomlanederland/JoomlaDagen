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
use Joomla\CMS\Installer\Adapter\PackageAdapter;

/**
 * PWT Sitemap script file.
 *
 * @since    1.0.0
 */
class Com_PwtsitemapInstallerScript
{
	/**
	 * Called on uninstallation
	 *
	 * @param   PackageAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function uninstall($adapter)
	{
		$db = Factory::getDbo();

		try
		{
			$query = $db->getQuery(true)
				->update('#__extensions')
				->set($db->quoteName('enabled') . ' = ' . $db->quote(0))
				->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
				->where($db->quoteName('element') . ' = ' . $db->quote('pwtsitemap'))
				->where($db->quoteName('folder') . ' = ' . $db->quote('system'));

			$db->setQuery($query)
				->execute();
		}
		catch (Exception $e)
		{
		}

		$db->dropTable('#__pwtsitemap_menu_types');
	}
}
