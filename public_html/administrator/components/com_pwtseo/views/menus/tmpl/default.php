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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;

defined('_JEXEC') or die;

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

HTMLHelper::_('stylesheet', 'com_pwtseo/pwtseo.css', array('version' => 'auto', 'relative' => true));

HTMLHelper::_('behavior.multiselect');

if (Version::MAJOR_VERSION < 4)
{
    HTMLHelper::_('formbehavior.chosen', 'select');
}

?>

<form action="<?php echo Route::_('index.php?option=com_pwtseo&view=menus'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="<?php echo Version::MAJOR_VERSION < 4 ? 'row-fluid' : 'row' ?>">
		<div class="col-md-12 span12">
            <div id="j-sidebar-container" class="j-sidebar-container">
				<?php echo $this->sidebar ?>
            </div>
			<div id="j-main-container" class="j-main-container ">
        		<?php
        		echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
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
                    <table class="table table-striped itemList" id="articleList">
                        <thead>
                        <tr>
                            <td class="w-1 text-center">
        						<?php echo HTMLHelper::_('grid.checkall'); ?>
                            </th>
                            <th scope="col" class="w-1 text-center d-none d-sm-table-cell">
        						<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'menu.published', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="min-width:100px">
        						<?php echo HTMLHelper::_('searchtools.sort', 'COM_PWTSEO_HEADING_TITLE', 'menu.title', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" class="w-15 d-none d-lg-table-cell">
        						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'menu.language', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" class="w-15 d-none d-md-table-cell">
        						<?php echo HTMLHelper::_('searchtools.sort', 'COM_PWTSEO_HEADING_FOCUSWORD', 'seo.focus_word', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" class="w-5 d-none d-lg-table-cell">
        						<?php echo HTMLHelper::_('searchtools.sort', 'COM_PWTSEO_HEADING_SCORE', 'seo.pwtseo_score', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" class="w-3 d-none d-lg-table-cell">
        						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'article.id', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
        				<?php foreach ($this->items as $i => $item) :
        					$ordering = ($listOrder == 'ordering');
        					$canEdit = $user->authorise('core.edit', 'com_content');
        					$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
        					$return = base64_encode('index.php?option=com_pwtseo&view=menus');
        					$scoreClass = $item->pwtseo_score < 40 ? 0 : ($item->pwtseo_score < 75 ? 1 : 2);
        					?>
                            <tr class="row<?php echo $i % 2; ?>">
								<td class="text-center">
        							<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="text-center d-none d-sm-table-cell">
        							<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'menus.', false, 'cb'); ?>
                                </td>
                                <td scope="row" class="has-context">
                                    <div class="break-word">
        								<?php if ($item->checked_out) : ?>
        									<?php echo HTMLHelper::_('jgrid.checkedout', $i, '', $item->checked_out_time); ?>
        								<?php endif; ?>
        								<?php if ($canEdit) : ?>
                                            <a href="<?php echo Route::_('index.php?option=com_menus&task=item.edit&id=' . (int) $item->id . '&return=' . $return); ?>">
        										<?php echo $this->escape($item->title); ?></a>
        								<?php else : ?>
        									<?php echo $this->escape($item->title); ?>
        								<?php endif; ?>
                                        <div class="small break-word">
        									<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
        								</div>
                                    </div>
                                </td>
                                <td class="small d-none d-lg-table-cell">
        							<?php echo LayoutHelper::render('joomla.content.language', $item); ?>
                                </td>
                                <td class="small d-none d-md-table-cell">
        							<?php echo $this->escape($item->focus_word); ?>
                                </td>
                                <td class="small d-none d-lg-table-cell">
        							<?php if ($item->pwtseo_score): ?>
                                        <span class="seoscore seoscore-<?php echo $scoreClass ?>"
        									<?php if ($item->flag_outdated): ?> title="<?php echo Text::_('COM_PWTSEO_FLAGS_OUTDATED_LABEL') ?>" <?php endif; ?>>
        		                            <?php echo $item->pwtseo_score ?>
                                        </span>
        								<?php if ($item->flag_outdated): ?>
                                            *
        								<?php endif; ?>
        							<?php endif; ?>
                                </td>
                                <td class="d-none d-lg-table-cell">
        							<?php echo $item->id; ?>
                                </td>
                            </tr>
        				<?php endforeach; ?>
                        </tbody>
                    </table>

					<?php echo $this->pagination->getListFooter(); ?>

        			<?php if ($user->authorise('core.edit', 'com_content')) : ?>
        				<?php echo HTMLHelper::_(
        					'bootstrap.renderModal',
        					'collapseModal',
        					array(
        						'title'  => Text::_('COM_PWTSEO_BATCH_OPTIONS'),
        						'footer' => $this->loadTemplate('batch_footer'),
        					),
        					$this->loadTemplate('batch_body')
        				); ?>
        			<?php endif; ?>
        		<?php endif; ?>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
            	<?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
