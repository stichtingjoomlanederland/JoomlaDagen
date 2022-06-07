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

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Version;
use Joomla\Component\Contact\Site\Helper\RouteHelper;
use Joomla\Component\Contact\Site\Model\CategoryModel;
use Joomla\Component\Contact\Site\Model\FeaturedModel;

if (Version::MAJOR_VERSION === 3)
{
	JLoader::register('ContactHelperRoute', JPATH_SITE . '/components/com_contact/helpers/route.php');
	JLoader::register('ContactModelCategory', JPATH_SITE . '/components/com_contact/models/category.php');
	JLoader::register('ContactModelFeatured', JPATH_SITE . '/components/com_contact/models/featured.php');
	BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_contact/models', 'ContactModel');
}

/**
 * PWT Sitemap Contact
 *
 * @since  1.0.0
 */
class PlgPwtSitemapContact extends PwtSitemapPlugin
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
		$this->component = 'com_contact';
		$this->views     = ['category', 'featured'];
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
	 */
	public function onPwtSitemapBuildSitemap($item, $format)
	{
		$sitemapItems = [];

		if ($this->checkDisplayParameters($item, $format))
		{
			if ($item->query['view'] === 'featured')
			{
				$contacts = $this->getFeaturedContacts();
			}
			else
			{
				$contacts = $this->getContacts($item->query['id']);
			}

			if ($contacts !== false)
			{
				foreach ($contacts as $contact)
				{
					if (Version::MAJOR_VERSION === 3)
					{
						$link = ContactHelperroute::getContactRoute($contact->id . ':' . $contact->alias, $contact->catid, $contact->language);
					}

					if (Version::MAJOR_VERSION === 4)
					{
						$link = RouteHelper::getContactRoute($contact->id . ':' . $contact->alias, $contact->catid, $contact->language);
					}

					$sitemapItems[] = new PwtSitemapItem($contact->name, $link, $item->level + 1);
				}
			}
		}

		return $sitemapItems;
	}

	/**
	 * Get all contacts from a category
	 *
	 * @param   int  $id  Category id
	 *
	 * @return  mixed  stdClass on success, false otherwise
	 *
	 * @since   1.0.0
	 */
	private function getContacts($id)
	{
		if (Version::MAJOR_VERSION === 3)
		{
			/** @var ContactModelCategory $contactModel */
			$contactModel = new ContactModelCategory;
		}

		if (Version::MAJOR_VERSION === 4)
		{
			/** @var CategoryModel $contactModel */
			$contactModel = new CategoryModel;
		}

		// Calling getState before setState will prevent 'populateState' override the new state
		$contactModel->getState();
		$contactModel->setState('category.id', $id);

		return $contactModel->getItems();
	}

	/**
	 * Get all featured contacts
	 *
	 * @return  mixed  stdClass on success, false otherwise
	 *
	 * @since   1.0.0
	 */
	private function getFeaturedContacts()
	{
		if (Version::MAJOR_VERSION === 3)
		{
			/** @var ContactModelFeatured $model */
			$model = BaseDatabaseModel::getInstance('ContactModelFeatured');
		}

		if (Version::MAJOR_VERSION === 4)
		{
			/** @var FeaturedModel $model */
			$model = new FeaturedModel;
		}

		return $model->getItems();
	}
}
