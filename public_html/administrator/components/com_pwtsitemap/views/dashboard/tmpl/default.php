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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

if (Version::MAJOR_VERSION === 3)
{
	HTMLHelper::_('formbehavior.chosen');
}

/** @param   PwtSitemapViewDashboard  $this */

$isJoomla4 = Version::MAJOR_VERSION === 4;

?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<?php // The following form is necessary to handle the [Add to robots.txt] button ?>
<form action="<?php echo Route::_('index.php?option=com_pwtsitemap'); ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<div id="j-main-container" class="span10 cpanel-system cpanel-modules">
	<div class="<?php if ($isJoomla4) : ?>row<?php else: ?>row-fluid<?php endif; ?>">
		<div class="module-wrapper span8 col-lg-8">

			<!-- Standard Sitemaps -->
			<div class="well card mb-3">
				<legend class="card-header">
					<?php echo Text::_('COM_PWTSITEMAP_VIEW_SITEMAP_DEFAULT_HTML_TITLE'); ?>
				</legend>

				<form id="sitemap" name="sitemap" method="post" action="<?php echo Route::_('index.php?option=com_pwtsitemap&view=dashboard'); ?>"
				      class="sitemap-create">
					<?php if ($isJoomla4) : ?>
					<div class="row p-3 pt-0">
						<div class="col-4">
							<?php endif; ?>
							<?php echo HTMLHelper::_('select.genericlist', $this->menusList, 'menutype', 'class="menu form-select-sm advancedSelect custom-select"', 'value', 'text'); ?>
							<?php if ($isJoomla4) : ?>
						</div>
						<div class="col-sm">
							<?php endif; ?>
							<input class="btn btn-success btn-sm" type="submit" value="<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_CREATE_MENU_ITEM'); ?>">
							<?php if ($isJoomla4) : ?>
						</div>
					</div>
				<?php endif; ?>
					<input type="hidden" name="sitemap" value="Sitemap"/>
					<input type="hidden" name="alias" value="sitemap"/>
					<input type="hidden" name="type" value="sitemap"/>
					<input type="hidden" name="task" value="dashboard.saveSitemapMenuItem"/>
				</form>

				<?php if (!empty($this->menuItems['sitemap'])): ?>
					<table class="table table-striped mb-0" id="sitemap">
						<tbody>
						<?php foreach ($this->menuItems['sitemap'] as $menuItem):; ?>
							<tr>
								<td>
									<div class="<?php if ($isJoomla4) : ?>row<?php endif; ?>">
										<div class="col span7">
											<a href="<?php echo Uri::root() . $menuItem->path; ?>" target="_blank" class="small">
												<?php echo Uri::root() . $menuItem->path; ?>
											</a>
											<br>
											<small><b><i>(<?php echo $menuItem->menutype; ?>)</i></b></small>
										</div>
										<div class="col-5 span5">
											<div class="btn-group btn-group-sm btn-group-justified pull-right">
												<a class="btn btn-primary"
												   href="<?php echo Route::_('index.php?option=com_menus&view=item&layout=edit&id=' . $menuItem->id); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_EDIT_MENU_ITEM'); ?>
												</a>
												<a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_pwtsitemap&view=items'); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_ITEMS'); ?>
												</a>
											</div>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>

						</tbody>
					</table>
				<?php endif; ?>
			</div>

			<!-- XML Sitemaps -->
			<div class="well card mb-3">
				<legend class="card-header">
					<?php echo Text::_('COM_PWTSITEMAP_VIEW_SITEMAP_DEFAULT_XML_TITLE'); ?>
				</legend>

				<form id="xmlitemap" name="xmlitemap" method="post" action="<?php echo Route::_('index.php?option=com_pwtsitemap&view=dashboard'); ?>"
				      class="sitemap-create">
					<?php if ($isJoomla4) : ?>
					<div class="row p-3 pt-0">
						<div class="col-4">
							<?php endif; ?>
							<?php echo HTMLHelper::_('select.genericlist', $this->menusList, 'menutype', 'class="menu form-select-sm advancedSelect custom-select"', 'value', 'text'); ?>
							<?php if ($isJoomla4) : ?>
						</div>
						<div class="col-sm">
							<?php endif; ?>
							<input class="btn btn-sm btn-success" type="submit" value="<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_CREATE_MENU_ITEM'); ?>">
							<?php if ($isJoomla4) : ?>
						</div>
					</div>
				<?php endif; ?>
					<input type="hidden" name="sitemap" value="XML Sitemap"/>
					<input type="hidden" name="alias" value="xml-sitemap"/>
					<input type="hidden" name="type" value="xmlitemap"/>
					<input type="hidden" name="task" value="dashboard.saveSitemapMenuItem"/>
				</form>

				<?php if (!empty($this->menuItems['xmlsitemap'])): ?>
					<table class="table table-striped  mb-0" id="xmlsitemap">
						<tbody>
						<?php foreach ($this->menuItems['xmlsitemap'] as $menuItem):; ?>
							<tr>
								<td>
									<div class="<?php if ($isJoomla4) : ?>row<?php endif; ?>">
										<div class="col span7">
											<a href="<?php echo Uri::root() . $menuItem->path; ?>" target="_blank" class="small">
												<?php echo Uri::root() . $menuItem->path; ?>
											</a>
											<br>
											<small><b><i>(<?php echo $menuItem->menutype; ?>)</i></b></small>
										</div>
										<div class="col-5 span5">
											<div class="btn-group btn-group-sm  btn-group-justified pull-right">
												<a class="btn btn-primary"
												   href="<?php echo Route::_('index.php?option=com_menus&view=item&layout=edit&id=' . $menuItem->id); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_EDIT_MENU_ITEM'); ?>
												</a>
												<a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_pwtsitemap&view=items'); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_ITEMS'); ?>
												</a>
											</div>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>

			<!-- Multilingual Sitemaps -->
			<div class="well card mb-3">
				<legend class="card-header">
					<?php echo Text::_('COM_PWTSITEMAP_VIEW_MULTILANGUAGE_HTML_TITLE'); ?>
				</legend>

				<form id="multilingualsitemap" name="multilingualsitemap" method="post"
				      action="<?php echo Route::_('index.php?option=com_pwtsitemap&view=dashboard'); ?>" class="sitemap-create">
					<?php if ($isJoomla4) : ?>
					<div class="row p-3 pt-0">
						<div class="col-4">
							<?php endif; ?>
							<?php echo HTMLHelper::_('select.genericlist', $this->menusList, 'menutype', 'class="menu form-select-sm advancedSelect custom-select"', 'value', 'text'); ?>
							<?php if ($isJoomla4) : ?>
						</div>
						<div class="col-sm">
							<?php endif; ?>
							<input class="btn btn-sm btn-success" type="submit" value="<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_CREATE_MENU_ITEM'); ?>">
							<?php if ($isJoomla4) : ?>
						</div>
					</div>
				<?php endif; ?>
					<input type="hidden" name="sitemap" value="Multilingual sitemap"/>
					<input type="hidden" name="alias" value="mulitilingual-sitemap"/>
					<input type="hidden" name="type" value="multilingualsitemap"/>
					<input type="hidden" name="task" value="dashboard.saveSitemapMenuItem"/>
				</form>

				<?php if (!empty($this->menuItems['multilingualsitemap'])): ?>
					<table class="table table-striped mb-0" id="multilingualsitemap">
						<tbody>
						<?php foreach ($this->menuItems['multilingualsitemap'] as $menuItem):; ?>
							<tr>
								<td>
									<div class="<?php if ($isJoomla4) : ?>row<?php endif; ?>">
										<div class="col span7">
											<a href="<?php echo Uri::root() . $menuItem->path; ?>" target="_blank" class="small">
												<?php echo Uri::root() . $menuItem->path; ?>
											</a>
											<br>
											<small><b><i>(<?php echo $menuItem->menutype; ?>)</i></b></small>
										</div>
										<div class="col-5 span5">
											<div class="btn-group btn-group-sm btn-group-justified pull-right">
												<a class="btn btn-primary"
												   href="<?php echo Route::_('index.php?option=com_menus&view=item&layout=edit&id=' . $menuItem->id); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_EDIT_MENU_ITEM'); ?>
												</a>
												<a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_pwtsitemap&view=items'); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_ITEMS'); ?>
												</a>
											</div>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>

						</tbody>
					</table>
				<?php endif; ?>
			</div>

			<!-- Image Sitemaps -->
			<div class="well card mb-3">
				<legend class="card-header">
					<?php echo Text::_('COM_PWTSITEMAP_VIEW_IMAGE_HTML_TITLE'); ?>
				</legend>

				<form id="imagesitemap" name="imagesitemap" method="post"
				      action="<?php echo Route::_('index.php?option=com_pwtsitemap&view=dashboard'); ?>" class="sitemap-create">
					<?php if ($isJoomla4) : ?>
					<div class="row p-3 pt-0">
						<div class="col-4">
							<?php endif; ?>
							<?php echo HTMLHelper::_('select.genericlist', $this->menusList, 'menutype', 'class="menu form-select-sm advancedSelect custom-select"', 'value', 'text'); ?>
							<?php if ($isJoomla4) : ?>
						</div>
						<div class="col-sm">
							<?php endif; ?>
							<input class="btn btn-sm btn-success" type="submit" value="<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_CREATE_MENU_ITEM'); ?>">
							<?php if ($isJoomla4) : ?>
						</div>
					</div>
				<?php endif; ?>
					<input type="hidden" name="sitemap" value="Image sitemap"/>
					<input type="hidden" name="alias" value="image-sitemap"/>
					<input type="hidden" name="type" value="imagesitemap"/>
					<input type="hidden" name="task" value="dashboard.saveSitemapMenuItem"/>
				</form>

				<?php if (!empty($this->menuItems['imagesitemap'])): ?>
					<table class="table table-striped mb-0" id="imagesitemap">
						<tbody>
						<?php foreach ($this->menuItems['imagesitemap'] as $menuItem):; ?>
							<tr>
								<td>
									<div class="<?php if ($isJoomla4) : ?>row<?php endif; ?>">
										<div class="col span7">
											<a href="<?php echo Uri::root() . $menuItem->path; ?>" target="_blank" class="small">
												<?php echo Uri::root() . $menuItem->path; ?>
											</a>
											<br>
											<small><b><i>(<?php echo $menuItem->menutype; ?>)</i></b></small>
										</div>
										<div class="col-5 span5">
											<div class="btn-group btn-group-sm btn-group-justified pull-right">
												<a class="btn btn-primary"
												   href="<?php echo Route::_('index.php?option=com_menus&view=item&layout=edit&id=' . $menuItem->id); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_EDIT_MENU_ITEM'); ?>
												</a>
												<a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_pwtsitemap&view=items'); ?>">
													<?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_VIEW_ITEMS'); ?>
												</a>
											</div>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>

		<!-- Start Sidebar -->
		<div class="module-wrapper span4 card p-3 col-lg-4">
			<div class="well well-large pwt-extensions">

				<!-- PWT branding -->
				<div class="pwt-section">
					<?php echo HTMLHelper::_('image', 'com_pwtsitemap/pwt-sitemap.png', 'PWT Sitemap', ['class' => 'pwt-extension-logo'], true); ?>
					<p class="pwt-heading"><?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_ABOUT_HEADER'); ?></p>
					<p><?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_ABOUT_DESC'); ?></p>
					<p>
						<a href="https://extensions.perfectwebteam.com/pwt-sitemap">https://extensions.perfectwebteam.com/pwt-sitemap</a>
					</p>
					<p><?php echo Text::sprintf('COM_PWTSITEMAP_DASHBOARD_ABOUT_REVIEW', 'https://extensions.joomla.org/extension/pwt-sitemap'); ?></p>
				</div>

				<div class="pwt-section">

					<div class="btn-group btn-group-justified">
						<a class="btn btn-large btn-primary"
						   href="https://extensions.perfectwebteam.com/pwt-sitemap/documentation"><?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_ABOUT_DOCUMENTATION'); ?></a>
						<a class="btn btn-large btn-primary"
						   href="https://extensions.perfectwebteam.com/support"><?php echo Text::_('COM_PWTSITEMAP_DASHBOARD_ABOUT_SUPPORT'); ?></a>
					</div>

				</div>

				<div class="pwt-section pwt-section--border-top">
					<p>
						<strong><?php echo Text::sprintf('COM_PWTSITEMAP_DASHBOARD_ABOUT_VERSION', '</strong>2.2.0'); ?>
					</p>
				</div>
				<!-- End PWT branding -->

			</div>
		</div>
		<!-- End Sidebar -->
	</div>
</div>
