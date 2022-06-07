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
use Joomla\CMS\Version;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('script', 'media/com_pwtacl/js/datatables.min.js', ['version' => 'auto']);
?>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10 cpanel-system cpanel-modules">
	<div id="pwtacl" class="dashboard row-fluid card-columns <?php echo (Version::MAJOR_VERSION === 4) ? 'j4' : 'j3' ?>">
		<!-- Start User Group Permissions -->
		<div class="span4 card">
			<div class="well groups">
				<h2 class="card-header">
					<span class="icon-users"></span> <?php echo Text::_('COM_PWTACL_DASHBOARD_PERMISSION_GROUP'); ?>
				</h2>

				<table class="table<?php if (Version::MAJOR_VERSION === 3): ?> table-striped<?php endif; ?>" id="groups">
					<thead>
					<tr>
						<th class="left"><?php echo Text::_('COM_USERS_GROUP_FIELD_TITLE_LABEL'); ?></th>
						<th width="15%"><?php echo Text::_('JGLOBAL_FIELD_ID_LABEL'); ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan="2" class="dataTables_empty"></td>
					</tr>
					</tbody>
				</table>

				<script type="text/javascript">
					jQuery(document).ready(function ($) {
						$('#groups').dataTable({
							serverSide: true,
							displayLength: 10,
							sort: false,
							dom: 'frtlip',
							info: false,
							pagingType: 'numbers',
							ajax: 'index.php?option=com_pwtacl&task=dashboard.tabledata&type=groups',
							columns: [
								{data: 'title'},
								{data: 'id'}
							],
							language: {
								search: '_INPUT_',
								searchPlaceholder: '<?php echo Text::_('JSEARCH_FILTER'); ?>',
								lengthMenu: '_MENU_'
							}
						});
					});
				</script>
			</div>
		</div>
		<!-- End User Group Permissions -->

		<!-- Start User Permissions -->
		<div class="span4 card">
			<div class="well users">
				<h2 class="card-header">
					<span class="icon-user"></span> <?php echo Text::_('COM_PWTACL_DASHBOARD_PERMISSION_USER'); ?>
				</h2>

				<table class="table<?php if (Version::MAJOR_VERSION === 3): ?> table-striped<?php endif; ?>" id="users">
					<thead>
					<tr>
						<th class="left"><?php echo Text::_('COM_USERS_HEADING_NAME'); ?></th>
						<th class="left" width="45%"><?php echo Text::_('JGLOBAL_USERNAME'); ?></th>
						<th width="15%"><?php echo Text::_('JGLOBAL_FIELD_ID_LABEL'); ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan="3" class="dataTables_empty"></td>
					</tr>
					</tbody>
				</table>

				<script type="text/javascript">
					jQuery(document).ready(function ($) {
						$('#users').dataTable({
							serverSide: true,
							displayLength: 10,
							sort: false,
							dom: 'frtlip',
							info: false,
							pagingType: 'numbers',
							ajax: 'index.php?option=com_pwtacl&task=dashboard.tabledata&type=users',
							columns: [
								{data: 'name'},
								{data: 'username'},
								{data: 'id'}
							],
							language: {
								search: '_INPUT_',
								searchPlaceholder: '<?php echo Text::_('JSEARCH_FILTER'); ?>',
								lengthMenu: '_MENU_'
							}
						});
					});
				</script>
			</div>
		</div>
		<!-- End User Permissions -->

		<!-- Start Sidebar -->
		<div class="span4 card">
			<div class="well well-large pwt-extensions p-3">

				<!-- PWT branding -->
				<div class="pwt-section">
					<?php echo HTMLHelper::_('image', 'com_pwtacl/pwt-acl.png', 'PWT ACL', ['class' => 'pwt-extension-logo'], true); ?>
					<p class="pwt-heading"><?php echo Text::_('COM_PWTACL_DASHBOARD_ABOUT_HEADER'); ?></p>
					<p><?php echo Text::_('COM_PWTACL_DASHBOARD_ABOUT_DESC'); ?></p>
					<p>
						<a href="https://extensions.perfectwebteam.com/pwt-acl">https://extensions.perfectwebteam.com/pwt-acl</a>
					</p>
					<p><?php echo Text::sprintf('COM_PWTACL_DASHBOARD_ABOUT_REVIEW', 'https://extensions.joomla.org/extension/acl-manager'); ?></p>
				</div>

				<div class="pwt-section">
					<div class="btn-group btn-group-justified">
						<a class="btn btn-large btn-primary" href="https://extensions.perfectwebteam.com/pwt-acl/documentation"><?php echo Text::_('COM_PWTACL_DASHBOARD_ABOUT_DOCUMENTATION'); ?></a>
						<a class="btn btn-large btn-primary" href="https://extensions.perfectwebteam.com/support"><?php echo Text::_('COM_PWTACL_DASHBOARD_ABOUT_SUPPORT'); ?></a>
					</div>
				</div>

				<div class="pwt-section pwt-section--border-top">
					<p>
						<strong><?php echo Text::sprintf('COM_PWTACL_DASHBOARD_ABOUT_VERSION', '</strong>4.1.0'); ?>
					</p>
				</div>
				<!-- End PWT branding -->

			</div>
		</div>
		<!-- End Sidebar -->
	</div>
</div>
