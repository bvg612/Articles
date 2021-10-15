# Articles
Symfony Blog backend project

Installation process:

1.Clone the blog from github to your computer

2.Run composer install

3.Add database configuration .env file

4.Run php bin/console doctrine:database:create to create the database

5.Run php bin/console doctrine:migrations:diff to create migration

6.Run php bin/console doctrine:migrations:migrate to execute the migration

7.Run php bin/console doctrine:fixtures:load to insert the default user into database

8.php bin/console server:run

Building completed now open your browser and login with username admin and password admin.



If there is a problem -> Fatal error: autoload.php ... it happens sometimes.
Just run: 

1.composer dump-autoload

2.composer update --no-scripts

3.php bin/console server:run
