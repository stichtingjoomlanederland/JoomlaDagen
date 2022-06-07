ALTER TABLE `#__pwtsitemap_menu_types`
    ADD `params` TEXT null;

UPDATE `#__pwtsitemap_menu_types`
SET `params` = '{"addcontenttohtmlsitemap":1,"addcontenttoxmlsitemap":1}'
