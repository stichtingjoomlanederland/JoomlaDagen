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
use Joomla\CMS\Router\Route;

require_once JPATH_SITE . '/components/com_joomgallery/interface.php';
JLoader::register('PwtSitemapImageItem', JPATH_ROOT . '/components/com_pwtsitemap/models/sitemap/pwtsitemapimageitem.php');

/**
 * PWT Sitemap JoomGallery
 *
 * @since  1.3.0
 */
class PlgPwtSitemapJoomgallery extends PwtSitemapPlugin
{
	/**
	 * Populate the PWT sitemap plugin to use it a base class
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function populateSitemapPlugin()
	{
		$this->component = 'com_joomgallery';
		$this->views     = ['category', 'gallery'];
	}

	/**
	 * Run for every menuitem passed
	 *
	 * @param   StdClass  $item    Menu items
	 * @param   string    $format  Sitemap format that is rendered
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 *
	 * @throws Exception
	 */
    public function onPwtSitemapBuildSitemap($item, $format, $sitemapType = 'default')
    {
        $sitemapItems = [];

        if ($this->checkDisplayParameters($item, $format) && (int) $item->params->get('addjoomgalleryto' . $format . 'sitemap', 1))
        {
            $joomInterface = new JoomInterface();
            $extPath = $joomInterface->getAmbit()->get('img_url');

            if (isset($item->query['catid']) && $item->query['catid'])
            {
                $catIds = JoomHelper::getAllSubCategories($item->query['catid'], true, true);
            }
            else
            {
                $item->query['catid'] = 0;
                // If menu-item doesn't specify, use all
                $catIds = array_map(
                    static function ($e) {
                        return $e->cid;
                    },
                    JoomAmbit::getInstance()->getCategoryStructure(true)
                );
            }

            $categories = $this->getCategories($catIds);

            foreach ($categories as $category)
            {
                if ($category->cid <> $item->query['catid'])
                {
                    $link           = Route::_('index.php?option=com_joomgallery&view=category&Itemid=' . $item->id . '&catid=' . $category->cid);
                    $sitemapItems[] = new PwtSitemapItem($category->name, $link, $category->level);
                }

                // Display links to images
                if ((int) $item->params->get('addcontentto' . $format . 'sitemap', 1))
                {
                    $images = $joomInterface->getPicsByCategory($category->cid);

                    foreach ($images as $image)
                    {
                        $link           = Route::_('index.php?option=com_joomgallery&view=detail&id=' . $image->id . '&Itemid=' . $item->id);
                        $sitemapItem = new PwtSitemapImageItem($image->imgtitle, $link, $category->level + 1);
                        $sitemapItem->images = [(object) [
                            'url' => $extPath . $image->catpath . '/' . $image->imgfilename,
                            'caption' => $image->imgtitle
                        ]];

                        $sitemapItems[] = $sitemapItem;
                    }
                }
            }
        }

        return $sitemapItems;
    }

	/**
	 * Get the JoomGallery categories, simplified
	 *
	 * @return array|mixed
	 */
	private function getCategories($catIds)
	{
		// Creation of array
		$db   = Factory::getDBO();
		$user = Factory::getUser();

		// Read all categories from database
		$query = $db->getQuery(true)
			->select('c.cid, c.parent_id, c.name, c.level')
			->from(_JOOM_TABLE_CATEGORIES . ' AS c')
			->where('lft > 0')
			->where('c.published = 1')
			->where('cid IN (' . implode(',', $catIds) . ')')
			->order('c.lft');

		if (!$user->authorise('core.admin'))
		{
			$query->where('c.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');
		}

		return $db->setQuery($query)->loadObjectList('cid');
	}
}
