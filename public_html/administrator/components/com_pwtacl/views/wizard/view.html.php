<?php
/**
 * @package    PwtAcl
 *
 * @author     Sander Potjer - Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2011 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com/pwt-acl
 */

defined('_JEXEC') or die;

use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Version;

/**
 * Wizard HTML view class.
 *
 * @since   3.0
 */
class PwtAclViewWizard extends HtmlView
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
	protected $form;

	/**
	 * @var     $sidebar
	 * @since   3.0
	 */
	protected $sidebar;

	/**
	 * @var     $components
	 * @since   3.0
	 */
	protected $components;

	/**
	 * @var     $group
	 * @since   3.0
	 */
	protected $group;

	/**
	 * @var     $step
	 * @since   3.0
	 */
	protected $step;

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
		$this->params     = ComponentHelper::getParams('com_pwtacl');
		$this->form       = $this->get('Form');
		$this->components = $this->get('Items');
		$this->group      = Factory::getApplication()->input->getInt('group');
		$this->step       = ($this->group) ? 2 : 1;

		// Access check.
		if (!Factory::getUser()->authorise('pwtacl.wizard', 'com_pwtacl'))
		{
			throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Load the toolbar
		$this->addToolbar();

		// Load the sidebar for Joomla! 3 and below only
		if (Version::MAJOR_VERSION < 4)
		{
			PwtaclHelper::addSubmenu('wizard');
			$this->sidebar = JHtmlSidebar::render();
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Include jQuery
		HTMLHelper::_('jquery.framework');

		// Load Javascript.
		HTMLHelper::_('script', 'media/com_pwtacl/js/permissions.js', ['version' => 'auto']);

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
		ToolbarHelper::title(Text::_('COM_PWTACL_SUBMENU_WIZARD'), 'pwtacl.png');

		// Buttons
		if (Factory::getUser()->authorise('core.admin', 'com_pwtacl'))
		{
			ToolbarHelper::preferences('com_pwtacl');
		}
	}
}
