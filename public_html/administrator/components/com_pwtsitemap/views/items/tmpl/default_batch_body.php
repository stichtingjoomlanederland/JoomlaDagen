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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var PwtSitemapViewItems $this */
?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="control-group span4">
			<div class="controls">
				<label id="batch-addtohtmlsitemap-lbl" for="batch-addtohtmlsitemap" class="modalTooltip" title="<?php echo HTMLHelper::_(
					'tooltipText', 'COM_PWTSITEMAP_FIELD_SHOW_IN_HTML', 'COM_PWTSITEMAP_FIELD_SHOW_IN_HTML_DESC'
				); ?>">
					<?php echo Text::_('COM_PWTSITEMAP_FIELD_SHOW_IN_HTML'); ?>
				</label>
				<select name="batch[addtohtmlsitemap]" class="inputbox advancedSelect custom-select" id="batch-addtohtmlsitemap">
					<option value=""><?php echo Text::_('COM_PWTSITEMAP_BATCH_NO_CHANGE'); ?></option>
					<?php echo HTMLHelper::_(
						'select.options', [['text' => Text::_('JYES'), 'value' => 'yes'], ['text' => Text::_('JNO'), 'value' => 'no']]
					); ?>
				</select>
			</div>
		</div>
		<div class="control-group span4">
			<div class="controls">
				<label id="batch-addtoxmlsitemap-lbl" for="batch-addtoxmlsitemap" class="modalTooltip" title="<?php echo HTMLHelper::_(
					'tooltipText', 'COM_PWTSITEMAP_FIELD_SHOW_IN_XML', 'COM_PWTSITEMAP_FIELD_SHOW_IN_XML_DESC'
				); ?>">
					<?php echo Text::_('COM_PWTSITEMAP_FIELD_SHOW_IN_XML'); ?>
				</label>
				<select name="batch[addtoxmlsitemap]" class="inputbox advancedSelect custom-select" id="batch-addtoxmlsitemap">
					<option value=""><?php echo Text::_('COM_PWTSITEMAP_BATCH_NO_CHANGE'); ?></option>
					<?php echo HTMLHelper::_(
						'select.options', [['text' => Text::_('JYES'), 'value' => 'yes'], ['text' => Text::_('JNO'), 'value' => 'no']]
					); ?>
				</select>
			</div>
		</div>
		<div class="control-group span4">
			<div class="controls">
				<label id="batch-changemenuitemrobots-lbl" for="batch-changemenuitemrobots" class="modalTooltip"
				       title="<?php echo HTMLHelper::_(
					       'tooltipText', 'COM_PWTSITEMAP_FIELD_ROBOT_SETTINGS_FOR_MENU', 'COM_PWTSITEMAP_FIELD_ROBOT_SETTINGS_FOR_MENU_DESC'
				       ); ?>">
					<?php echo Text::_('COM_PWTSITEMAP_FIELD_ROBOT_SETTINGS_FOR_MENU'); ?>
				</label>
				<select name="batch[changemenuitemrobots]" class="inputbox advancedSelect custom-select" id="batch-changemenuitemrobots">
					<option value=""><?php echo Text::_('COM_PWTSITEMAP_BATCH_NO_CHANGE'); ?></option>
					<?php echo HTMLHelper::_('select.options', [
							[
								'text'  => Text::sprintf('JGLOBAL_USE_GLOBAL_VALUE', Factory::getConfig()->get('robots', 'index, follow')),
								'value' => Factory::getConfig()->get('robots', 'index, follow')
							],
							['text' => 'index, follow', 'value' => 'index, follow'],
							['text' => 'noindex, follow', 'value' => 'noindex, follow'],
							['text' => 'index, nofollow', 'value' => 'index, nofollow'],
							['text' => 'noindex, nofollow', 'value' => 'noindex, nofollow']
						]
					); ?>
				</select>
			</div>
		</div>
	</div>
</div>
