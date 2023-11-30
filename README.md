<img alt="Drupal Logo" src="https://www.drupal.org/files/Wordmark_blue_RGB.png" height="60px">

## Deployment steps

git clone https://gitlab.com/souhaibhmam/fidesio.git fidesio <br>
docker compose up -d <br>
docker exec -it fidesio_drupal /bin/sh <br>
composer install <br>
install drupal from existing config folder (drush si) <br>
drush migrate:import --group=feeds <br>
Check the sidebar on the home page for last articles block <br>
Search is on this url : /articles-search <br>

Note: no tags on the RSS flow, so to test tags facet you need to create tags manually and attatch them to articles
