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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Version;

/**
 * PWT HTML module helper class.
 *
 * @since  1.0.0
 */
abstract class PwtHtmlPwtSitemap
{
	/**
	 * Create a radio field
	 *
	 * @param   string  $name     Name
	 * @param   string  $id       ID
	 * @param   string  $class    Class
	 * @param   mixed   $active   Active
	 * @param   string  $tooltip  Tooltip
	 *
	 * @return  string The radio fields markup
	 *
	 * @since   1.0.0
	 */
	public static function radio($name, $id, $class, $active, $tooltip)
	{
		if (Version::MAJOR_VERSION === 4)
		{
			return LayoutHelper::render(
				'joomla.form.field.radio.switcher',
				[
					'name'          => $name . '_' . $id,
					'id'            => $name . '_' . $id,
					'class'         => $class,
					'value'         => $active,
					'options'       => [
						0 => HTMLHelper::_('select.option', 0, Text::_('JNO')),
						1 => HTMLHelper::_('select.option', 1, Text::_('JYES')),
					],
					'onchange'      => '',
					'dataAttribute' => '',
					'readonly'      => '',
					'disabled'      => '',
				]
			) . '<span class="save-indication save-indication-4"></span>';
		}

		return '<div class="btn-group btn-group-yesno radio hasTooltip" data-original-title="' . $tooltip . '">
					<input type="radio" id="' . $name . '_' . $id . '_1" class="' . $class . '" name="' . $name . '_' . $id . '" value="1" '
			. ((int) $active === 1 ? 'checked="checked"' : '') . '>
					<label for="' . $name . '_' . $id . '_1" class="btn">' . Text::_("JYES") . '</label>
	
					<input type="radio" id="' . $name . '_' . $id . '_0"  class="' . $class . '" name="' . $name . '_' . $id . '" value="0" '
			. ((int) $active === 0 ? 'checked="checked"' : '') . '>
					<label for="' . $name . '_' . $id . '_0" class="btn">' . Text::_("JNO") . '</label>
				</div><span class="save-indication"></span>';
	}

	/**
	 * Convert a language tag to a language flag
	 *
	 * @param   string  $languageTag  Language tag
	 *
	 * @return  string The language flag markup
	 *
	 * @since   1.0.0
	 */
	public static function languageFlag($languageTag)
	{
		$string = '';

		if ($languageTag !== '*')
		{
			$string = HTMLHelper::_(
				'image', 'mod_languages/' . substr($languageTag, 0, 2) . '.gif',
				$languageTag,
				['title' => $languageTag],
				true
			);
		}

		return $string;
	}
}
