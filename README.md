# Articles
Symfony Blog backend project

All you need to do is to run 'php bin/console server:run'

If there is a problem -> Fatal error: autoload.php ... it happens sometimes.
Just run: 
composer dump-autoload
composer update --no-scripts
php bin/console server:run
