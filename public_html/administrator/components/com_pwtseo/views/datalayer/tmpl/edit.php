<?php
/**
 * @package    Pwtseo
 *
 * @author     Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2016 - 2021 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;

defined('_JEXEC') or die;

HTMLHelper::_('behavior.formvalidator');
if (Version::MAJOR_VERSION < 4)
{
    HTMLHelper::_('formbehavior.chosen', 'select');
}
$cssPlatform = Version::MAJOR_VERSION < 4 ? 'bootstrap' : 'uitab';

Factory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "datalayer.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');
?>

<form action="<?php echo Route::_('index.php?option=com_pwtseo&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="item-form" class="form-validate">

    <div class="form-inline form-inline-header">
		<?php echo $this->form->renderField('title') ?>
		<?php echo $this->form->renderField('name') ?>
    </div>

    <div class="form-horizontal main-card">
        <?php echo HTMLHelper::_($cssPlatform . '.startTabSet', 'myTab', ['active' => 'attrib-datalayers', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php echo HTMLHelper::_($cssPlatform . '.addTab', 'myTab', 'attrib-datalayers', Text::_('COM_PWTSEO_DATALAYER_FORM_TAB_LABEL')); ?>
        <div class="row row-fluid">
            <div class="col-lg-9 span9">
                <fieldset class="adminform">
					<?php echo $this->form->renderFieldset('fields'); ?>
                </fieldset>
            </div>
            <div class="col-lg-3 span3">
                <fieldset class="form-vertical">
					<?php echo $this->form->renderField('published') ?>
					<?php echo $this->form->renderField('template') ?>
					<?php echo $this->form->renderField('language') ?>
                </fieldset>
            </div>
        </div>
		<?php echo HTMLHelper::_($cssPlatform . '.endTab'); ?>

	    <?php echo HTMLHelper::_($cssPlatform . '.addTab', 'myTab', 'attrib-info', Text::_('COM_PWTSEO_DATALAYER_INFO_TAB_LABEL')); ?>
        <div class="row row-fluid">
            <div class="col-12 col-lg-6 span12">
                <p><?php echo Text::_('COM_PWTSEO_DATALAYERS_INFO_PARAGRAPH_1') ?></p>
            </div>
        </div>
        <?php echo HTMLHelper::_($cssPlatform . '.endTab'); ?>
        <?php echo HTMLHelper::_($cssPlatform . '.endTabSet'); ?>

    </div>

	<?php echo $this->form->renderFieldset('default'); ?>

    <input type="hidden" name="task" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
