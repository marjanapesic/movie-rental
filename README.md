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




**API**
---


----------


**Get Movie**
Returns json data about a single movie.
URL  **/movie/id/:id**
Method: **GET**
URL Params
Required:
**id=[integer]**

Success Response:
Code: **200** 
Content: `{"movieId":12,"title":"Lepa sela lepo gore 2","releaseDate":"2016-08-10","numberOfCopies":7}`

Error Response:
Code: **404**
Content `{"error":{"message":"Resource not found."}}` or

    {"error":{"message":"Movie does not exist"}}

Code **401** Unauthorised
 
Sample Call:

    curl -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" 'http://casumotest/movie/id/12'

 


----------


 
**Get Movies**
Returns json data about all movies.
URL  **/movie[/availability/:availability]**
Method: **GET**
URL Params
Optional: **availability = [0-1]** whether number of currently available movies should be returned - 1 or not - 0

Success Response:
Code: **200** 

    Content: [{"movieId":7,"title":"Maka2","releaseDate":"2016-08-09","numberOfCopies":1},{"movieId":8,"title":"maka2","releaseDate":"2016-08-07”,”numberOfCopies”:5}]

or:

    [{"movieId":7,"title":"Maka2","releaseDate":"2016-08-09","numberOfCopies":1,"availabilityCount":"0"},{"movieId":8,"title":"maka2","releaseDate":"2016-08-07”,”numberOfCopies":5,"availabilityCount”:”4”}]


Error Response:
code **401** Unauthorised
 
Sample Call:

    curl  -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" 'http://casumotest/movie'

or

    curl -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" 'http://casumotest/movie/availability/1'

**Notes**:
Optional parameter availability when set to 1 passes additional field for each movie - current availability count.
 
 


----------


 
**Create Movie**
Allows movie creation.
 
URL **/movie**
Method: **POST**
Data Params
**Json representation of movie.**
example: { "title": "Lepa sela lepo gore", "releaseDate": "2000-01-02","numberOfCopies": 5}
Required:
**title**: Movie title
**numberOfCopies**: Number of movie copies available for rent.
Optional: releaseDate: Movie release date in format Y-m-d

Success Response:
Code: **200** 
Content: `{"status":"OK","movieId":17}`
 
Error Response:
Code **404** 

    {"error":{"message":"Movie title cannot be empty."}}
    {"error":{"message":"Number of copies cannot be empty."}}

code 401 Unauthorised
 
Sample Call:

    curl —XPOST -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" -d '{ "title": "Lepa sela lepo gore", "releaseDate": "2000-01-02","numberOfCopies": 5}' 'http://casumotest/movie'


----------


 
**Update Movies**
Allow existing movies to be updated
URL **/movie/id/:id**
Method: **PUT**
URL Params
**id=[integer]**
Data Params
**Json representation of movie.**
Required:
**title**: Movie title
**numberOfCopies**: Number of movie copies available for rent.
Optional:
releaseDate: Movie release date in format Y-m-d

Success Response:
Code: **200** 
Content: `{"movieId":"12","title":"Lepa sela lepo gore 2","releaseDate":"2016-08-10", "numberOfCopies":"7"}`
 
Error Response:
Code **404**

     {"error":{"message":"Movie title cannot be empty."}}
    {"error":{"message":"Number of copies cannot be empty."}}
    {"error":{"message":"Number of copies cannot be lower than number of movies rented at the moment (1).”}}
 
code **401** Unauthorised
 
Sample Call:

    curl  -XPUT -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" -d '{"title": "Lepa sela lepo gore 2", "releaseDate": "2016-08-10", "numberOfCopies": 7}' 'http://casumotest/movie/id/12'

**Notes**:
Number of copies cannot be lower than number of movies rented at the moment (1)
example value is 1 - represent number of currently rented copies - integer
 
 


----------


 
**Get Movie Rental price**
Returns price of one movie rental in json format.
 
URL **/movie-price/:id/end-date/:endDate**
Method: **GET**
URL Params
Required:
**id=[integer]**
**endDate=[date]** date should be in **Y-m-d** format

Success Response:
Code: **200** 
Content: `{"amount":90,"currency":"SEK"}`
Error Response:
code **404**

    {"error":{"message":"Missing end date."}}
    {"error":{"message":"Wrong date format."}}
    {"error":{"message":"End date cannot be before today."}}
    {"error":{"message":"Wrong movie id."}}

code **401** Unauthorised
 
 
Sample Call:

    curl —v XGET -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" 'http://casumotest/movie-price/12/end-date/2016-08-17'

**Notes**:
Price will be calculated regardless of movie availability.
 
 


----------


 
**Get Movie Rentals prices**
Returns prices for multiple movie rentals in json format.
 
URL **/movie-price/[ids/:ids]/end-date/:endDate**
Method: **GET**
URL Params
Required:
**endDate=[date]** date should be in **Y-m-d** format
Optional
ids= comma separated values of movie ids ex 1,2

Success Response:
Code: **200** 
Content: `{"11":{"amount":90,"currency":"SEK"},"12":{"amount":90,"currency":"SEK"}}`
Error Response:
code **401** Unauthorised
code **404**

    {“error":{"message":"Missing end date."}}
    {"error":{"message":"Wrong date format."}}
    {"error":{"message":"End date cannot be before today."}}

 
Sample Call:
**curl -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" 'http://casumotest/movie-price/ids/11,12/end-date/2016-08-17'**
Notes:
If received ids that do not exist status 200 without content for non-existing ids will be returned
 
 

----------

 
**Rent Movie**
Allows one movie to be rented.
 
URL **/movie-rent**
Method: **POST**
**Data Params**
{ :movieId: {
end-date: Y-m-d
amount: int
currency: string
}}
example: {“11":{"end-date": "2016-08-17","amount":90,"currency":"SEK"},"12":{"end-date": "2016-08-17", "amount":90,"currency":"SEK"}}

Success Response:
Code: **200** 
Content: `{"rentals":[{"movieId":11,"rentIdentifier":11,"amount":90,"currency":"SEK"},{"movieId":12,"rentIdentifier":12,"amount":90,"currency":"SEK"}],"totalPrice":{"amount":180,"currency":"SEK"}}`
Error Response:
code **401** Unauthorised
code **404**

    {"error":{"message":"Missing end date."}}
    {"error":{"message":"Wrong date format."}}
    {"error":{"message":"End date cannot be before today."}}
    {"error":{"message":"Missing price amount or currency."}}
    {"error":{"message":"Movie not available.”}}
    {"error":{"message":"Received and calculated prices do not match”}}

 
 
Sample Call:
curl -XPOST -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" 'http://casumotest/movie-price/ids/11,12/end-date/2016-08-17'
 
 


----------


 
**Return Movie**
Allows one movie to be rented.
 
URL **/movie-return**
Method: **POST**
Data Params
**{
“rentIdentifier” : [:identifiers]
}**
**:identifiers** – comma separated movie rental identifiers

Success Response:
Code: **200** 
Content: `{"10":{"subcharge":{"amount":210,"currency":"SEK"},"bonusPoints":2},"11":{"subcharge":{"amount":210,"currency":"SEK"},"bonusPoints":2}}`
Error Response:
code **401** Unauthorised
code **404**

    {"error":{"message":"Missing rental identifiers."}}
    {"error":{"message":"Invalid identifier."}}
    {"error":{"message":"Movie is already returned."}}

 
Sample Call:

    curl -XPOST -H 'Authorization: Basic dGVzdEB0ZXN0LmNvbTp0ZXN0dGVzdA==' -H "Content-type: application/json" -d '{"rentIdentifier": ["10","11"]}' 'http://casumotest/movie-return'
