<?php
/**
 * @package    PwtAcl
 *
 * @author     Sander Potjer - Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2011 - 2022 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com/pwt-acl
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;

defined('_JEXEC') or die;
?>

<thead>
<tr>
	<th rowspan="2" width="15%"><?php echo Text::_('COM_PWTACL_TABLE_ASSET_TITLE'); ?></th>
	<th colspan="<?php echo (Version::MAJOR_VERSION === 4) ? '4' : '3'; ?>" width="24%" class="pwtacl-border-left header-larger"><?php echo Text::_('COM_PWTACL_TABLE_ACTION_LOGIN'); ?></th>
	<th colspan="3" width="18%" class="brlft pwtacl-border-left header-larger"><?php echo Text::_('COM_PWTACL_TABLE_ACTION_EXTENSION'); ?></th>
	<th colspan="6" width="36%" class="pwtacl-border-left header-larger"><?php echo Text::_('COM_PWTACL_TABLE_ACTION_OBJECT'); ?></th>
	<th rowspan="2" width="4%" class="nowrap brlft pwtacl-border-left">
		<button type="button" class="btn btn-outline-primary btn-small btn-sm js-closed" data-toggle="additional" data-target="#pwtaclall">
			<span class="icon-arrow-right large-icon"></span>
		</button>
	</th>
	<th rowspan="2" width="3%" class="nowrap brlft pwtacl-border-left"><?php echo Text::_('JGLOBAL_FIELD_ID_LABEL'); ?></th>
</tr>
<tr>
	<th width="6%" class="pwtacl-border-left">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_LOGIN_SITE_DESC'); ?>">
			<?php echo Text::_('JSITE'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_LOGIN_ADMIN_DESC'); ?>">
			<?php echo Text::_('COM_PWTACL_TABLE_ACTION_ADMIN'); ?>
		</span>
	</th>
	<?php if (Version::MAJOR_VERSION === 4): ?>
		<th width="6%">
			<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_LOGIN_OFFLINE_DESC'); ?>">
				<?php echo Text::_('JAPI'); ?>
			</span>
		</th>
	<?php endif; ?>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_LOGIN_OFFLINE_DESC'); ?>">
			<?php echo Text::_('COM_PWTACL_TABLE_ACTION_OFFLINE'); ?>
		</span>
	</th>
	<th width="6%" class="brlft pwtacl-border-left">
		<span class="hasTooltip" title="<?php echo Text::_('JACTION_ADMIN_COMPONENT_DESC'); ?>">
			<?php echo Text::_('JACTION_ADMIN'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('JACTION_OPTIONS_COMPONENT_DESC'); ?>">
			<?php echo Text::_('JACTION_OPTIONS'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('JACTION_MANAGE_COMPONENT_DESC'); ?>">
			<?php echo Text::_('JFIELD_ACCESS_LABEL'); ?>
		</span>
	</th>
	<th width="6%" class="pwtacl-border-left">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_CREATE_DESC'); ?>">
			<?php echo Text::_('JACTION_CREATE'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_DELETE_DESC'); ?>">
			<?php echo Text::_('JACTION_DELETE'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_EDIT_DESC'); ?>">
			<?php echo Text::_('JACTION_EDIT'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_EDITSTATE_DESC'); ?>">
			<?php echo Text::_('JACTION_EDITSTATE'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_EDITOWN_DESC'); ?>">
			<?php echo Text::_('JACTION_EDITOWN'); ?>
		</span>
	</th>
	<th width="6%">
		<span class="hasTooltip" title="<?php echo Text::_('COM_CONFIG_ACTION_EDITVALUE_DESC'); ?>">
			<?php echo Text::_('JACTION_EDITVALUE'); ?>
		</span>
	</th>
</tr>
</thead>
