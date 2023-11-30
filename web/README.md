<img alt="Drupal Logo" src="https://www.drupal.org/files/Wordmark_blue_RGB.png" height="60px">

## Deployment steps

git clone https://gitlab.com/souhaibhmam/fidesio.git fidesio
docker compose up -d
docker exec -it fidesio_drupal /bin/sh
composer install
install drupal from existing config folder (drush si)
drush migrate:import --group=feeds
Check the sidebar on the home page for last articles block
Search is on this url : /articles-search

Note: no tags on the RSS flow, so to test tags facet you need to create tags manually and attatch them to articles
