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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;

HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('bootstrap.tooltip');

if (Version::MAJOR_VERSION < 4)
{
	$this->form->removeField('core.login.api');
}
?>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	<?php if ($this->step === 2 && Version::MAJOR_VERSION < 4): ?>
		<?php echo LayoutHelper::render('pwtacl.legend.group'); ?>
	<?php endif; ?>
</div>
<div id="j-main-container" class="span10 main-card p-4">
	<div id="pwtacl" class="row-fluid <?php echo (Version::MAJOR_VERSION === 4) ? 'j4' : '' ?>">
		<?php if ($this->step === 1): ?>
			<div class="alert alert-info">
				<h2 class="alert-heading"><?php echo Text::_('COM_PWTACL_WIZARD_STEP1'); ?></h2>
				<?php echo Text::_('COM_PWTACL_WIZARD_STEP1_DESC'); ?>
			</div>

			<form action="<?php echo Route::_('index.php?option=com_pwtacl&view=wizard'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
				<div class="form-horizontal">
					<?php echo $this->form->renderFieldset('default'); ?>
				</div>

				<input type="submit" class="btn btn-large btn-success btn-block" value="<?php echo Text::_('COM_PWTACL_WIZARD_STEP1_SUBMIT'); ?>"/>
				<input type="hidden" name="task" value="wizard.groupSetup"/>
				<?php echo HTMLHelper::_('form.token'); ?>
			</form>
		<?php endif; ?>

		<?php if ($this->step === 2): ?>
			<div class="alert alert-info">
				<h2 class="alert-heading"><?php echo Text::_('COM_PWTACL_WIZARD_STEP2'); ?></h2>
				<?php echo Text::_('COM_PWTACL_WIZARD_STEP2_DESC'); ?>
			</div>

			<?php foreach ($this->components as $component): ?>
				<h3><?php echo $component->title; ?></h3>
				<table id="pwtacl" class="table table-bordered table-fixed-header managepermissions">
					<?php echo LayoutHelper::render('pwtacl.table.wizard.header'); ?>
					<?php echo LayoutHelper::render('pwtacl.table.wizard.body', [
						'assets' => $component->assets,
						'group'  => $this->group
					]); ?>
				</table>
			<?php endforeach; ?>

			<form action="<?php echo Route::_('index.php?option=com_pwtacl&view=wizard'); ?>" method="post" name="adminForm" id="item-form">
				<input type="submit" class="btn btn-large btn-success btn-block" value="<?php echo Text::_('COM_PWTACL_WIZARD_STEP2_SUBMIT'); ?>"/>
				<input type="hidden" name="groupid" value="<?php echo $this->group; ?>"/>
				<input type="hidden" name="task" value="wizard.finalize"/>
				<?php echo HTMLHelper::_('form.token'); ?>
			</form>
		<?php endif; ?>

	</div>
</div>
