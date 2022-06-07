<?php
/**
 * @package    Pwtseo
 *
 * @author     Perfect Web Team <extensions@perfectwebteam.com>
 * @copyright  Copyright (C) 2016 - 2021 Perfect Web Team. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://extensions.perfectwebteam.com
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;

defined('_JEXEC') or die;

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'datalayer.ordering';

HTMLHelper::_('stylesheet', 'com_pwtseo/pwtseo.css', array('version' => 'auto', 'relative' => true));

HTMLHelper::_('behavior.multiselect');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_pwtseo&task=datalayers.saveOrderAjax&tmpl=component';
	HTMLHelper::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$workflow_state = false;

if (ComponentHelper::getParams('com_pwtseo')->get('workflow_enabled', false)) :

// @todo move the script to a file
$js = <<<JS
(function() {
	document.addEventListener('DOMContentLoaded', function() {
	  var elements = [].slice.call(document.querySelectorAll('.article-status'));

	  elements.forEach(function (element) {
		element.addEventListener('click', function(event) {
			event.stopPropagation();
		});
	  });
	});
})();
JS;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();

$wa->getRegistry()->addExtensionRegistryFile('com_workflow');
$wa ->useScript('com_workflow.admin-items-workflow-buttons')
    ->addInlineScript($js, [], ['type' => 'module']);

$workflow_state = Factory::getApplication()->bootComponent('com_pwtseo')->isFunctionalityUsed('core.state', 'com_pwtseo.datalayers');
endif;
?>

<form action="<?php echo Route::_('index.php?option=com_pwtseo&view=datalayers'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="<?php echo Version::MAJOR_VERSION < 4 ? 'row-fluid' : 'row' ?>">
		<div class="col-md-12 span12">
            <div id="j-sidebar-container" class="j-sidebar-container">
				<?php echo $this->sidebar ?>
            </div>
            <div id="j-main-container" class="j-main-container">
        		<?php
        		echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));?>
        		<?php if (empty($this->items)) : ?>
			        <?php if (Version::MAJOR_VERSION < 4): ?>
                        <div class="alert alert-no-items">
					        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </div>
			        <?php else: ?>
                        <div class="alert alert-info">
                            <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
					        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </div>
			        <?php endif; ?>
        		<?php else : ?>
                    <table class="table itemList" id="articleList">
                        <thead>
                            <tr>
    							<td class="w-1 text-center">
    								<?php echo HTMLHelper::_('grid.checkall'); ?>
    							</td>
                                <th scope="col" class="w-1 text-center d-none d-md-table-cell">
            		                <?php echo JHtml::_('searchtools.sort', '', 'datalayer.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
                                </th>
                                <th scope="col" class="w-1 text-center d-none d-sm-table-cell">
            		                <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" style="min-width:100px">
            						<?php echo HTMLHelper::_('searchtools.sort', 'COM_PWTSEO_HEADING_TITLE', 'datalayer.title', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-15 d-none d-md-table-cell">
            						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'datalayer.language', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-10 d-none d-md-table-cell">
            						<?php echo HTMLHelper::_('searchtools.sort', 'COM_PWTSEO_HEADING_TEMPLATE', 'datalayer.template', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-3 d-none d-lg-table-cell">
            						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'datalayer.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody<?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true"<?php endif; ?>>
						<?php foreach ($this->items as $i => $item) :
        					$ordering = ($listOrder == 'ordering');
        					$canEdit = $user->authorise('core.edit', 'com_pwtseo');
        					$canChange  = $user->authorise('core.edit.state', 'com_pwtseo.' . $item->id);
        					?>
                            <tr class="row<?php echo $i % 2; ?>" >
								<td class="text-center">
									<?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
								</td>
                                <td class="text-center d-none d-md-table-cell">
        		                    <?php
        		                    $iconClass = '';
        		                    if (!$canChange)
        		                    {
        			                    $iconClass = ' inactive';
        		                    }
                                    elseif (!$saveOrder)
        		                    {
        			                    $iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
        		                    }
        		                    ?>
                                    <span class="sortable-handler<?php echo $iconClass ?>">
        								<span class="icon-ellipsis-v" aria-hidden="true"></span>
        							</span>
        		                    <?php if ($canChange && $saveOrder) : ?>
                                        <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
        		                    <?php endif; ?>
                                </td>
                                <?php if (Version::MAJOR_VERSION < 4): ?>
                                    <td class="center">
                                        <div class="btn-group">
			                                <?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'datalayers.', $canChange, 'cb'); ?>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <td class="article-status text-center d-none d-sm-table-cell">
		                                <?php
		                                $options = [
			                                'task_prefix' => 'datalayers.',
			                                'disabled' => $workflow_state || !$canChange,
			                                'id' => 'state-' . $item->id
		                                ];

		                                echo (new Joomla\CMS\Button\PublishedButton)->render((int) $item->published, $i, $options);
		                                ?>
                                    </td>
                                <?php endif; ?>

                                <td scope="row" class="has-context">
                                    <div class="break-word">
        								<?php if ($canEdit) : ?>
                                            <a href="<?php echo Route::_('index.php?option=com_pwtseo&task=datalayer.edit&id=' . (int) $item->id); ?>">
        										<?php echo $this->escape($item->title); ?></a>
        								<?php else : ?>
        									<?php echo $this->escape($item->title); ?>
        								<?php endif; ?>
                                    </div>
                                </td>
                                <td class="small d-none d-md-table-cell">
        	                        <?php echo LayoutHelper::render('joomla.content.language', $item); ?>
                                </td>
                                <td class="small d-none d-md-table-cell">
        	                        <?php echo $item->template_title ? $this->escape(str_replace(',', ', ', $item->template_title)) : Text::_('JALL'); ?>
                                </td>
                                <td class="d-none d-lg-table-cell">
        							<?php echo (int) $item->id; ?>
                                </td>
                            </tr>
        				<?php endforeach; ?>
                        </tbody>
                    </table>

					<?php echo $this->pagination->getListFooter(); ?>

        		<?php endif; ?>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
            	<?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
