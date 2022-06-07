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

if (Version::MAJOR_VERSION < 4)
{
	HTMLHelper::_('formbehavior.chosen', 'select');
}
HTMLHelper::_('behavior.formvalidator');
?>

<form
	action="<?php echo Route::_('index.php?option=com_pwtacl&view=assets&type=' . $this->type); ?>"
	method="post"
	name="adminForm"
	id="adminForm"
	enctype="multipart/form-data"
>
	<?php if (Version::MAJOR_VERSION < 4) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php
			echo $this->sidebar;
			echo LayoutHelper::render('pwtacl.legend.' . $this->type);
			?>
		</div>
	<?php endif; ?>

	<div id="j-main-container" class="span10">
		<?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

		<?php if ($this->group || $this->user): ?>
			<table
				id="pwtacl"
				class="table table-fixed-header <?php echo $this->type; ?> <?php echo (Version::MAJOR_VERSION === 4) ? 'j4' : 'table-bordered j3'; ?>"
				data-offset="<?php echo (Version::MAJOR_VERSION === 4) ? 66 : 82; ?>"
			>
				<?php echo LayoutHelper::render('pwtacl.table.assets.header'); ?>
				<?php echo LayoutHelper::render('pwtacl.table.assets.body', [
					'assets' => $this->assets,
					'group'  => $this->group
				]); ?>
			</table>
			<?php echo $this->pagination->getListFooter(); ?>
			<?php if (Version::MAJOR_VERSION === 4) : ?>
				<?php echo LayoutHelper::render('pwtacl.legend.' . $this->type . '-j4'); ?>
			<?php endif; ?>
		<?php else: ?>
			<div class="alert alert-info">
				<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
				<?php if ($this->type === 'group'): ?>
					<?php echo Text::_('COM_PWTACL_ASSETS_SELECT_GROUP'); ?>
				<?php elseif ($this->type === 'user'): ?>
					<?php echo Text::_('COM_PWTACL_ASSETS_SELECT_USER'); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php echo HTMLHelper::_(
		'bootstrap.renderModal',
		'importModal',
		[
			'title'  => Text::_('COM_PWTACL_TOOLBAR_IMPORT'),
			'footer' => $this->loadTemplate('import_footer'),
		],
		$this->loadTemplate('import_body')
	); ?>

	<?php echo HTMLHelper::_(
		'bootstrap.renderModal',
		'copyModal',
		[
			'title'  => Text::_('COM_PWTACL_TOOLBAR_COPY'),
			'footer' => $this->loadTemplate('copy_footer'),
		],
		$this->loadTemplate('copy_body')
	); ?>

	<input type="hidden" name="option" value="com_pwtacl"/>
	<input type="hidden" name="task" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

