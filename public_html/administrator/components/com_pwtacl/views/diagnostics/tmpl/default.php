<?php
/**
 * @package    PwtAcl
 *
 * @author     Sander Potjer - Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2011 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com/pwt-acl
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;

defined('_JEXEC') or die;

// Get timeout setting, minimum of 50 milliseconds
$timeout = (int) $this->params->get('diagnostics_timeout', 50);
$timeout = ($timeout > 50) ? $timeout : 50;
?>

<?php if (Version::MAJOR_VERSION < 4) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
<?php endif; ?>

<div id="j-main-container" class="span10 main-card p-4">
	<div id="pwtacl" class="diagnostics bootstrap <?php echo (Version::MAJOR_VERSION === 4) ? 'j4' : 'j3' ?>">
		<?php if ($this->issues): ?>
			<div class="well well-large alert alert-warning">
				<h2 class="alert-heading"><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_ISSUES_DETECTED'); ?></h2>
				<p><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_ISSUES_DETECTED_DESC'); ?></p>
				<p class="d-grid">
					<button class="btn btn-large btn-block btn-success js--start" type="button" data-timeout="<?php echo $timeout; ?>" data-steps="<?php echo (Version::MAJOR_VERSION === 4) ? 17 : 14 ?>">
						<?php echo Text::_('COM_PWTACL_DIAGNOSTICS_FIX'); ?>
					</button>
				</p>
				<div class="progress progress-striped active hidden">
					<div class="bar bar-success bg-success" style="width:0%"></div>
				</div>
			</div>
		<?php endif ?>

		<div class="well well-large alert alert-success <?php if ($this->issues): ?> hidden<?php endif; ?>">
			<h2 class="alert-heading"><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_NO_ISSUES_DETECTED'); ?></h2>
			<p><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_NO_ISSUES_DETECTED_DESC'); ?></p>
			<p class="d-grid">
				<button class="btn btn-large btn-success btn-block js--rebuild" type="button" onclick="Joomla.submitbutton('diagnostics.rebuild');">
					<?php echo Text::_('COM_PWTACL_DIAGNOSTICS_STEP_REBUILD'); ?>
				</button>
			</p>
		</div>

		<?php echo HTMLHelper::_('bootstrap.startAccordion', 'pwtacl-diagnostics', ['class' => 'sd']); ?>

		<?php $stepNumber = 1; ?>
		<?php foreach ($this->steps as $key => $step): ?>
			<?php
			$steptitle  = Text::_('COM_PWTACL_DIAGNOSTICS_STEP_' . $step);
			$slideTitle = '<span class="js-step-heading ' . (($this->issues) ? 'muted' : 'text-success') . '">';
			$slideTitle .= '<span class="js-step-done badge badge-success bg-success p-2 mr-2' . (($this->issues) ? ' hidden' : '') . '"><i class="icon-ok icon-white"></i></span>';
			$slideTitle .= '<span class="js-assets-fixed-number badge badge-warning bg-warning p-2 pull-right float-end"></span>';

			$slideTitle .= '<span class="step-title">';
			$slideTitle .= Text::sprintf('COM_PWTACL_DIAGNOSTICS_STEP', $stepNumber) . ' ';

			if (strpos($step, 'GENERAL') === false)
			{
				$slideTitle .= $steptitle;
				$slideTitle .= '&nbsp;<small>' . Text::_('COM_PWTACL_DIAGNOSTICS_STEP_' . $step . '_DESC') . '</small>';
			}
			else
			{
				$slideTitle .= Text::sprintf('COM_PWTACL_DIAGNOSTICS_STEP_GENERAL_TITLE', $steptitle);
				$slideTitle .= '&nbsp;<small>' . Text::sprintf('COM_PWTACL_DIAGNOSTICS_STEP_GENERAL_DESC', $steptitle, $steptitle) . '</small>';
			}

			$slideTitle .= '</span></span>';
			?>
			<?php echo HTMLHelper::_('bootstrap.addSlide', 'pwtacl-diagnostics', $slideTitle, 'step' . $key, 'step' . $key); ?>

			<div class="js-results-alert <?php if ($this->issues): ?> hidden<?php endif; ?> alert alert-success">
				<?php if (strpos($step, 'GENERAL') === false) : ?>
					<?php echo Text::_('COM_PWTACL_DIAGNOSTICS_STEP_' . $step . '_SUCCESS'); ?>
				<?php else: ?>
					<?php echo Text::sprintf('COM_PWTACL_DIAGNOSTICS_STEP_GENERAL_SUCCESS', $steptitle); ?>
				<?php endif; ?>
				<span class="js-assets-fixed hidden">
					<strong>
						<span class="js-assets-fixed-number"></span> <?php echo Text::sprintf('COM_PWTACL_DIAGNOSTICS_RESULTS_ITEMS_FIXED', null); ?>.
					</strong>
				</span>
			</div>
			<table class="table table-striped table-bordered js-results-table hidden">
				<thead>
				<tr>
					<th width="6%"></th>
					<th width="10%"><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_RESULTS_TYPE'); ?></th>
					<th><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_RESULTS_TITLE'); ?></th>
					<th width="35%"><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_RESULTS_CHANGES'); ?></th>
					<th width="30px"><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_RESULTS_ID'); ?></th>
				</tr>
				</thead>
				<tbody></tbody>
			</table>
			<?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
			<?php $stepNumber++; ?>
		<?php endforeach; ?>

		<div class="accordion-group completed hidden">
			<div class="accordion-heading">
				<span class="accordion-toggle nopointer" data-parent="#diagnosticsteps">
					<h3 class="text-success text-center p-3"><?php echo Text::_('COM_PWTACL_DIAGNOSTICS_COMPLETED'); ?></h3>
				</span>
			</div>
		</div>

		<?php echo HTMLHelper::_('bootstrap.endAccordion'); ?>

		<!-- Begin rebuild -->
		<form action="index.php" method="post" id="adminForm">
			<input type="hidden" name="option" value="com_pwtacl"/>
			<input type="hidden" name="task" value="diagnostics.rebuild"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
		<!-- End rebuild -->
	</div>
</div>