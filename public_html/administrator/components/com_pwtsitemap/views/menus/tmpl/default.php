<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('jquery.framework');

$spinner = '../media/system/images/ajax-loader.gif';

if (Version::MAJOR_VERSION === 3)
{
	HTMLHelper::_('formbehavior.chosen');
	$spinner = '../media/system/images/modal/spinner.gif';
}

/** @var PwtSitemapViewMenus $this */

$uri           = Uri::getInstance();
$return        = base64_encode($uri);
$user          = Factory::getUser();
$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirn      = $this->escape($this->state->get('list.direction'));
$saveOrder     = ($listOrder === 'pwtsitemap_menu_types.ordering' && strtolower($listDirn) === 'asc');
$saveOrderingUrl = 'index.php?option=com_pwtsitemap&task=menus.saveOrderAjax&tmpl=component';

if ($saveOrder)
{
	if (Version::MAJOR_VERSION === 4)
	{
		HTMLHelper::_('draggablelist.draggable', 'menuList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
	}
	else
	{
		HTMLHelper::_('sortablelist.sortable', 'menuList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
	}
}
?>
<form action="<?php echo Route::_('index.php?option=com_pwtsitemap&view=menus'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this, 'options' => ['filterButton' => false]]); ?>
			<div class="clearfix"></div>
			<?php if (empty($this->items)) : ?>
				<div class="alert alert-no-items">
					<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php else : ?>
				<table class="table table-striped" id="menuList">
					<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort', '', 'pwtsitemap_menu_types.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
                        <th width="20%">
							<?php echo Text::_('COM_PWTSITEMAP_FIELD_SHOW_IN_HTML'); ?>
                        </th>
                        <th width="20%">
							<?php echo Text::_('COM_PWTSITEMAP_FIELD_SHOW_IN_XML'); ?>
                        </th>
					</tr>
					</thead>
					<tfoot>
					<tr>
						<td colspan="15">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
					</tfoot>
					<tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>"<?php endif; ?>>
					<?php foreach ($this->items as $i => $item) :
						$canEdit = $user->authorise('core.edit', 'com_menus.menu.' . (int) $item->id);
						$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out === $user->get('id') || (int) $item->checked_out === 0;
						$canChange = $user->authorise('core.edit.state', 'com_menus.menu.' . $item->id) && $canCheckin;
						?>
						<tr data-draggable-group="1"
						    data-item-id="<?php echo $item->id; ?>"
						    data-level="1"
						    data-order="<?php echo $item->ordering; ?>"

						>
							<td class="order nowrap center hidden-phone">
								<?php
								$iconClass = '';

								if (!$canChange)
								{
									$iconClass = ' inactive';
								}
								elseif (!$saveOrder)
								{
									$iconClass = ' inactive tip-top hasTooltip" title="' . HTMLHelper::_('tooltipText', 'JORDERINGDISABLED');
								}
								?>
								<span class="sortable-handler<?php echo $iconClass ?>">
                                        <span class="<?php echo Version::MAJOR_VERSION === 3 ? 'icon-menu' : 'icon-ellipsis-v'; ?>" aria-hidden="true"></span>
                                    </span>
								<?php if ($canChange && $saveOrder) : ?>
									<input type="text" style="display:none" name="order[]" size="5"
									       value="<?php echo $item->ordering; ?>"/>
									<input type="checkbox" style="display:none" id="cb<?php echo $i; ?>" name="cid[]"
									       size="5" value="<?php echo $item->id; ?>"/>
								<?php endif; ?>

							</td>
							<td>
								<a href="<?php echo Route::_('index.php?option=com_pwtsitemap&view=menu&layout=edit&id=' . $item->id); ?>">
									<?php echo ($item->custom_title === '' || $item->custom_title === null) ? '-' : $this->escape($item->custom_title); ?>
									(<?php echo $this->escape($item->title); ?>)</a>
								<div class="small">
									<?php echo Text::_('COM_PWTSITEMAP_MENU_MENUTYPE_LABEL'); ?>:
									<?php if ($canEdit) : ?>
										<a href="<?php echo Route::_('index.php?option=com_menus&task=menu.edit&id=' . $item->id); ?>"
										   title="<?php echo $this->escape($item->description); ?>">
											<?php echo $this->escape($item->menutype); ?></a>
									<?php else : ?>
										<?php echo $this->escape($item->menutype); ?>
									<?php endif; ?>
								</div>
							</td>
							<td>
                                <?php if ($item->params->get('addcontenttohtmlsitemap', 0) !== 'disabled') : ?>
                                    <?php echo PwtHtmlPwtSitemap::radio(
                                    'addcontenttohtmlsitemap', $item->id, 'pwtsitemapradio', $item->params->get('addcontenttohtmlsitemap', 1),
                                        Text::_('COM_PWTSITEMAP_ADD_ARTICLES_IN_HTML')
                                    ); ?>
                                <?php endif; ?>
                            </td>
							<td>
                                <?php if ($item->params->get('addcontenttoxmlsitemap', 0) !== 'disabled') : ?>
                                        <?php echo PwtHtmlPwtSitemap::radio(
                                    'addcontenttoxmlsitemap', $item->id, 'pwtsitemapradio', $item->params->get('addcontenttoxmlsitemap', 1),
                                        Text::_('COM_PWTSITEMAP_ADD_ARTICLES_IN_XML')
                                ); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</div>
</form>
<script type="text/javascript">
  	(function ($) {
		jQuery('#menuList input:radio').on('change', function () {
			var parameter = $(this).attr('name').split('_')[0];
			var itemId = $(this).attr('name').split('_')[1];
			var value = $(this).val();
			var saveIndicator = $(this).closest('td').find('.save-indication').addClass('icon-ok');

			var request = {
				'option': 'com_ajax',
				'plugin': 'pwtsitemap',
				'group': 'system',
				'itemId': itemId,
				'parameter': parameter,
				'value': value,
                'table': '#__pwtsitemap_menu_types',
				'format': 'json'
			};

			$(saveIndicator).removeClass('icon-ok').css({
				'background': 'url(<?php echo $spinner; ?>)',
				'display': 'inline-block',
				'width': '16px',
				'height': '16px'
			});

			$.ajax({
				type: 'POST',
				data: request,
				dataType: 'json',
				success: function (response) {
					$(saveIndicator).removeAttr('style').addClass('icon-ok');
				},
				error: function (xhr, status, err) {
					console.log(err)
				}
			});
		});
	}(jQuery));
</script>
