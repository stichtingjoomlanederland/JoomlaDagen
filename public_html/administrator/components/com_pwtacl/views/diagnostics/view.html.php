<?php
/**
 * @package    PwtAcl
 *
 * @author     Sander Potjer - Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2011 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com/pwt-acl
 */

use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die;

/**
 * Diagnostics HTML view class.
 *
 * @since   3.0
 */
class PwtAclViewDiagnostics extends HtmlView
{
	/**
	 * @var     $params
	 * @since   3.0
	 */
	protected $params;

	/**
	 * @var     $steps
	 * @since   3.0
	 */
	protected $steps;

	/**
	 * @var     $issues
	 * @since   3.0
	 */
	protected $issues;

	/**
	 * @var     $sidebar
	 * @since   3.0
	 */
	protected $sidebar;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template
	 *
	 * @return  mixed
	 * @since   3.0
	 * @throws  Exception on errors
	 */
	public function display($tpl = null)
	{
		$this->params = ComponentHelper::getParams('com_pwtacl');

		/** @var PwtAclModelDiagnostics $diagnostics */
		$diagnostics  = BaseDatabaseModel::getInstance('Diagnostics', 'PwtAclModel', ['ignore_request' => true]);
		$this->steps  = $diagnostics->getDiagnosticsSteps();
		$this->issues = $diagnostics->getQuickScan(true);

		// Access check.
		if (!Factory::getUser()->authorise('pwtacl.diagnostics', 'com_pwtacl'))
		{
			throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Include jQuery
		HTMLHelper::_('jquery.framework');

		// Load Javascript.
		HTMLHelper::_('script', 'media/com_pwtacl/js/diagnostics.js', ['version' => 'auto']);

		// Load the toolbar
		$this->addToolbar();

		// Load the sidebar
		PwtAclHelper::addSubmenu('diagnostics');
		$this->sidebar = JHtmlSidebar::render();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Display the view
		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 * @since   3.0
	 */
	protected function addToolbar()
	{
		// Title
		ToolbarHelper::title(Text::_('COM_PWTACL_SUBMENU_DIAGNOSTICS'), 'pwtacl.png');

		// Buttons
		if (Factory::getUser()->authorise('core.admin', 'com_pwtacl'))
		{
			ToolbarHelper::custom('diagnostics.rebuild', 'refresh.png', 'refresh_f2.png', 'COM_PWTACL_DIAGNOSTICS_STEP_REBUILD', false);
			ToolbarHelper::divider();
			ToolbarHelper::preferences('com_pwtacl');
		}
	}
}
