<?php
/**
 * @package    PwtAcl
 *
 * @author     Sander Potjer - Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2011 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com/pwt-acl
 */

use Joomla\CMS\Application\CliApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Version;
use Joomla\DI\Container;
use Joomla\Event\DispatcherInterface;

// Set flag that this is a parent file.
const _JEXEC = 1;

error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', 1);
ini_set('session.use_cookies', 0);

// Load system defines
if (file_exists(dirname(__DIR__) . '/defines.php'))
{
	require_once dirname(__DIR__) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(__DIR__));
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_BASE . '/includes/framework.php';

/**
 * This script will rebuild the #__assets table via the PWT ACL Diagnostics tool
 *
 * @since   3.0
 */
class PwtAclRebuild extends CliApplication
{
	/**
	 * Entry point for the script
	 *
	 * @return  void
	 * @since   3.0
	 * @throws  Exception
	 */
	public function doExecute()
	{
		// Load the model.
		BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_pwtacl/models', 'PwtAclModel');

		// Start the rebuild
		$this->out('============================');
		$this->out('Start rebuild Assets table');

		try
		{
			/** @var PwtAclModelDiagnostics $model */
			$model = BaseDatabaseModel::getInstance('Diagnostics', 'PwtAclModel', ['ignore_request' => true]);
			$model->rebuildAssetsTable();
		}
		catch (Exception $e)
		{
			$this->out('Failed to rebuild');
		}

		$this->out('Finished rebuild Assets table');
		$this->out('============================');
	}

	/**
	 * Gets the name of the current running application.
	 *
	 * @return  string  The name of the application.
	 *
	 * @since  4.0
	 */
	public function getName()
	{
		return 'PwtAclRebuild';
	}
}

if (Version::MAJOR_VERSION < 4)
{
	CliApplication::getInstance('PwtAclRebuild')->execute();
}
else
{
	// Set up the container
	Factory::getContainer()->share(
		'PwtAclRebuild',
		function (Container $container) {
			return new PwtAclRebuild(
				null,
				null,
				null,
				null,
				$container->get(DispatcherInterface::class),
				$container
			);
		},
		true
	);

	Factory::$application = Factory::getContainer()->get('PwtAclRebuild');
	Factory::$application->execute();
}
