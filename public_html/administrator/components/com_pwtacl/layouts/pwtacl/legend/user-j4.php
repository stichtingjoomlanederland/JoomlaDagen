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

defined('_JEXEC') or die;
?>

<div class="legend j4">
	<span class="legend-item">
		<span class="legend-icon action">
			<span class="icon-not-ok"></span>
		</span>
		<?php echo Text::_('COM_PWTACL_SIDEBAR_DENIED'); ?>
	</span>

	<span class="legend-item">
		<span class="legend-icon action">
			<span class="icon-ok"></span>
		</span>
		<?php echo Text::_('COM_PWTACL_SIDEBAR_ALLOWED'); ?>
	</span>
</div>

