Movie Rental
===================

**Description**:
Movie rental is web application designed for video rental stores.
Application supports:
keeping movie inventory - adding, listing and editing movies.
renting movies - calculating price of movie rental and rent
returning movies - calculation surcharge and bonus points.

To be perform any action users have to be authenticated.
UI allows user sign up and login - logout actions. 
UI and API supports adding, editing and listing movies and rental availability on current date.
API supports:
- Calculating price of rental for one movie or more movies for the same date range.
- Renting movies
- Returning movies


Restrictions:
1. When movie is added application calculates movie type assignments (new, regular or old) based on movie release date. Movie is new up to 1 year starting from release date, Regular 1-5years and Old after 5 years from release date. These date ranges can be configured. If movie does not have release date, referent date for movie type assignments is today.
Movie type is assigned after movie is added to system and there is no support for re-calculating movie type assignment.
2. Only users that are added to the system can rent/return movie. Idea behind is to have different user types (staff, customer) and to support rent/return to be allowed only to certain users. In that sense all users can rent/return movies in name of other user.
3. UI and API access are allowed to all users.


**Installation:**

1.Install dependencies with composer.

2.Create new file under config/autoload called doctrine.local.php:

    <?php //config/autoload/doctrine.local.php
    return [ 
	    'doctrine' => [
		    'connection' => [
			    'orm_default' => [
				    'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				    'params' => [
					    'user' => '',
					    'password' => '',
					    'dbname' => ''
					]
				]
		    ]
	    ]
    ]

3.Create new database specified in doctrine.local.php configuration under dbname.

4.To get database tables created run 

    ./vendor/doctrine/doctrine-module/bin/doctrine-module orm:schema-tool:create

5.Set web app running on your local server.


If all steps are finished successfully:
Go to **[localhost]/sign-up** page and create a user, preferably with:
email: **test@test.com**
password: **testtest**
