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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Menu\MenuItem;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;

JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
JLoader::register('ContentHelperQuery', JPATH_SITE . '/components/com_content/helpers/query.php');
JLoader::register('ContentAssociationsHelper',
	JPATH_ADMINISTRATOR . '/components/com_content/helpers/associations.php'
);
BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

/**
 * PWT Sitemap Content Plugin
 *
 * @since  1.0.0
 */
class PlgPwtSitemapContent extends PwtSitemapPlugin
{
	/**
	 * Populate the PWT Sitemap Content plugin to use it a base class
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function populateSitemapPlugin()
	{
		$this->component = 'com_content';
		$this->views     = ['category', 'categories'];
	}

	/**
	 * Run for every menuitem passed
	 *
	 * @param   JMenuItem  $item         Menu items
	 * @param   string     $format       Sitemap format that is rendered
	 * @param   string     $sitemapType  Type of sitemap that is generated
	 *
	 * @return  array List of sitemap items
	 *
	 * @since   1.0.0
	 *
	 * @throws  Exception
	 */
	public function onPwtSitemapBuildSitemap($item, $format, $sitemapType = 'default')
	{
		if ($this->checkDisplayParameters($item, $format, ['article']))
		{
			// Prepare category menu-item
			if ($item->query['view'] === 'category' && (int) $item->params->get('addcontentto' . $format . 'sitemap', 1))
			{
				return $this->buildSitemapCategory($item, $format, $sitemapType);
			}

			// Prepare category menu-item
			if ($item->query['view'] === 'categories' && (int) $item->params->get('addcontentto' . $format . 'sitemap', 1))
			{
				return $this->buildSitemapCategories($item, $format, $sitemapType);
			}
		}

		return [];
	}

	/**
	 * Convert the given paramters to a PwtSitemapItem
	 *
	 * @param   stdClass  $article      The article
	 * @param   MenuItem  $item         The menu item belonging to the article
	 * @param   string    $link         An url to the article
	 * @param   string    $modified     Last modified date
	 * @param   string    $sitemapType  If the sitemap item is multilingual, an image or regular
	 *
	 * @return  BasePwtSitemapItem
	 *
	 * @since   1.0.0
	 */
	private function convertToSitemapItem($article, $item, $link, $modified, $sitemapType)
	{
		switch ($sitemapType)
		{
			case 'multilanguage':
				$sitemapItem               = new PwtMultilanguageSitemapItem($article->title, $link, $item->level + 1, $modified);
				$sitemapItem->associations = $this->getAssociatedArticles($article);

				return $sitemapItem;
				break;
			case 'image':
				$sitemapItem         = new PwtSitemapImageItem($article->title, $link, $item->level + 1, $modified);
				$sitemapItem->images = $this->getArticleImages($article);

				return $sitemapItem;
				break;
			default:
				return new PwtSitemapItem($article->title, $link, $item->level + 1, $modified);
		}
	}

	/**
	 * Get language associated articles
	 *
	 * @param   stdClass  $article  Article to find associations
	 *
	 * @return  array List of associated articles
	 *
	 * @since   1.0.0
	 */
	private function getAssociatedArticles($article)
	{
		$className = 'ContentAssociationsHelper';

		if (Version::MAJOR_VERSION === 4)
		{
			$className = '\Joomla\Component\Content\Administrator\Helper\AssociationsHelper';
		}

		$helper       = new $className;
		$associations = $helper->getAssociations('article', $article->id);

		// Map associations to Article objects
		$associations = array_map(
			static function ($value) use ($helper) {
				return $helper->getItem('article', explode(':', $value->id)[0]);
			}, $associations
		);

		// Append links
		foreach ($associations as $language => $association)
		{
			$association->link = ContentHelperRoute::getArticleRoute(
				$association->id . ':' . $association->alias, $association->catid, $association->language
			);
		}

		return $associations;
	}

	/**
	 * Get the images of an article
	 *
	 * @param   stdClass  $article  Article
	 *
	 * @return  array List of images for the given article
	 *
	 * @since   1.0.0
	 */
	private function getArticleImages($article)
	{
		$images        = [];
		$articleImages = json_decode($article->images, false);

		
		if (!empty($articleImages->image_intro))
		{
			$image          = new stdClass;
			$image->url     = PwtSitemapUrlHelper::getURL('/' . $articleImages->image_intro);
			$image->caption = !empty($articleImages->image_intro_caption) ? $articleImages->image_intro_caption
				: $articleImages->image_intro_alt;

			$images[] = $image;
		}

		if (!empty($articleImages->image_fulltext))
		{
			$image          = new stdClass;
			$image->url     = PwtSitemapUrlHelper::getURL('/' . $articleImages->image_fulltext);
			$image->caption = !empty($articleImages->image_fulltext_caption) ? $articleImages->image_fulltext_caption
				: $articleImages->image_fulltext_alt;

			$images[] = $image;
		}

		
		return $images;
	}

	/**
	 * Build sitemap for com_content category view
	 *
	 * @param   MenuItem  $item         Menu items
	 * @param   string    $format       Sitemap format that is rendered
	 * @param   string    $sitemapType  Type of sitemap that is generated
	 *
	 * @return  array List of sitemap category items
	 *
	 * @since   1.1.0
	 *
	 * @throws  Exception
	 */
	public function buildSitemapCategory($item, $format, $sitemapType)
	{
		// Save new items
		$sitemapItems = [];

		// Do we have tags set?
		$tag = isset($item->query['filter_tag']) ? $item->query['filter_tag'] : null;

		// Get articles for category
		$articles = $this->getArticles($item->query['id'], $item->language, $item->params, 0, 0, $tag);

		foreach ($articles as $article)
		{
			$oParam = new Registry;
			$oParam->loadString($article->metadata);

			// Only add article if no-index is not set
			if (strpos($oParam->get('robots'), 'noindex') !== false)
			{
				continue;
			}

			$link = ContentHelperRoute::getArticleRoute($article->id . ':' . $article->alias, $article->catid,
				$article->language
			);

			if ($article->modified == '0000-00-00 00:00:00')
			{
				$lastmod = HTMLHelper::_('date', $article->created, 'Y-m-d');
			}
			else
			{
				$lastmod = HTMLHelper::_('date', $article->modified, 'Y-m-d');
			}

			$sitemapItems[] = $this->convertToSitemapItem($article, $item, $link, $lastmod, $sitemapType);
		}

		return $sitemapItems;
	}

	/**
	 * Get articles from the #__content table
	 *
	 * @param   mixed    $categories  Category id array or string
	 * @param   string   $language    Language prefix
	 * @param   array    $params      Additional params to pass on to the modal
	 * @param   integer  $start       Starting index
	 * @param   integer  $limit       A limit to the amount of returning articles
	 * @param   array    $tag         Tag set for menu
	 *
	 * @return  array A list of articles
	 *
	 * @since   1.0.0
	 */
	private function getArticles($categories, $language, $params, $start = 0, $limit = 0, $tag = [])
	{
		$articles = $this->getArticlesModel($categories, $language, $params, $tag);

		$articles->setState('list.start', $start);
		$articles->setState('list.limit', $limit);

		// Minimize the amount of resources required for the items
		$articles->setState('list.select', 'a.id, a.title, a.metadata, a.alias, a.catid, a.modified, a.created, a.attribs, a.language, a.images');

		return $articles->getItems();
	}

	/**
	 * Get articles from the #__content table
	 *
	 * @param   mixed   $categories  Category id array or string
	 * @param   string  $language    Language prefix
	 * @param   array   $params      Additional params to pass on to the modal
	 * @param   array   $tag         Tag set for menu
	 *
	 * @return  ContentModelArticles
	 *
	 * @since   1.0.0
	 */
	private function getArticlesModel($categories, $language, $params, $tag)
	{
		$globalParams = ComponentHelper::getParams('com_content');

		// Get ordering from menu
		$articleOrderby   = $params->get('orderby_sec', $globalParams->get('orderby_sec', 'rdate'));
		$articleOrderDate = $params->get('order_date', $globalParams->get('order_date', 'published'));

		$className = 'ContentHelperQuery';

		if (Version::MAJOR_VERSION === 4)
		{
			$className = '\Joomla\Component\Content\Site\Helper\QueryHelper';
		}

		$secondary = $className::orderbySecondary($articleOrderby, $articleOrderDate);

		/** @var ContentModelArticles $articles */
		$articles = BaseDatabaseModel::getInstance('Articles', 'ContentModel', ['ignore_request' => true]);

		$articles->setState('params', $params);
		$articles->setState('filter.published', 1);
		$articles->setState('filter.access', 1);
		$articles->setState('filter.language', $language);
		$articles->setState('filter.category_id', $categories);
		$articles->setState('filter.tag', $tag);
		$articles->setState('list.start', 0);
		$articles->setState('list.limit', 0);
		$articles->setState('list.ordering', $secondary . ', a.created DESC');
		$articles->setState('list.direction', '');

		// Include subcategories
		$showSubcategories = $params->get('show_subcategory_content', $params->get('maxLevel', '0'));

		if ($showSubcategories)
		{
			// -1 actually means all, but we have to define an actual number
			$articles->setState('filter.max_category_levels', $showSubcategories === '-1' ? 9999 : $showSubcategories);
			$articles->setState('filter.subcategories', true);
		}

		return $articles;
	}

	/**
	 * Build sitemap for com_content categories view
	 *
	 * @param   MenuItem  $item         Menu items
	 * @param   string    $format       Sitemap format that is rendered
	 * @param   string    $sitemapType  Type of sitemap that is generated
	 *
	 * @return  array List of sitemap category items
	 *
	 * @since   1.1.0
	 *
	 * @throws  Exception
	 */
	public function buildSitemapCategories($item, $format, $sitemapType)
	{
		// Save new items
		$sitemapItems = [];

		// Do we have tags set?
		$tag = isset($item->query['filter_tag']) ? $item->query['filter_tag'] : null;

		$categoryIds = [];
		$this->getChildCategoriesByCategoryId($categoryIds, $item->query['id']);

		// Get articles for category
		$articles = $this->getArticles($categoryIds, $item->language, $item->params, 0, 0, $tag);

		foreach ($articles as $article)
		{
			$oParam = new Registry;
			$oParam->loadString($article->metadata);

			if (strpos($oParam->get('robots'), 'noindex') !== false)
			{
				continue;
			}

			$link = ContentHelperRoute::getArticleRoute($article->id . ':' . $article->alias, $article->catid,
				$article->language
			);

			if ($article->modified == '0000-00-00 00:00:00')
			{
				$lastmod = HTMLHelper::_('date', $article->created, 'Y-m-d');
			}
			else
			{
				$lastmod = HTMLHelper::_('date', $article->modified, 'Y-m-d');
			}

			$sitemapItems[] = $this->convertToSitemapItem($article, $item, $link, $lastmod, $sitemapType);
		}

		return $sitemapItems;
	}

	/**
	 * Method to get all child categories of a given category id (and their respective children)
	 *
	 * @param   array  $ids  The array to fill with the id's
	 * @param   int    $pk   The id of the parent category
	 *
	 * @since   1.0.0
	 */
	private function getChildCategoriesByCategoryId(&$ids, $pk)
	{
		/** @var ContentModelCategory $categoryModel */
		$categoryModel = BaseDatabaseModel::getInstance('Category', 'ContentModel', ['ignore_request' => true]);

		$categoryModel->setState('category.id', $pk);
		$categoryModel->setState('filter.published', 1);

		$category = $categoryModel->getCategory();

		foreach ($category->getChildren() as $category)
		{
			$this->getChildCategoriesByCategoryId($ids, $category->id);
		}

		$ids[] = $pk;
	}
}
