Custom Migration for Drupal 8

drush mi --migrate-debug creporter_categories,creporter_locations,creporter_tags 

Useful commands 

drush config-import --partial --source=/media-disk/Sites/creporter/web/modules/custom/migrate_creporter/config/install

drush mim creporter_content_stories --migrate-debug --limit=1

