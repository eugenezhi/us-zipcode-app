## US zip codes

> A page with a form to search all zip codes by city+state combination.

Asks the user in a web form for a city & state in a dropdown (US-only);  
Look up all zip codes contained within that city + state combination using the [Smarty US ZIP Code API](https://www.smarty.com/docs/cloud/us-zipcode-api) and displays a list of all zip codes found;  
Stores the following in the database: city, state, timestamp, when the search was performed, and the search result;  
Checks the database to see if the last search was performed within the last 60 minutes; if > 60 minutes, performs the search again, saving the result; if <= 60 minutes, displays the saved result without calling the API again;  
When displaying the result, shows the user whether the result came from the API directly, or from the database (with a timestamp of when the data was originally saved).  

[Live demo](https://us-zipcode-app.42web.io)

### Installation
- Clone the project
- Run in the project root folder:
```bash 
#install dependencies
composer install
#create configuration file
php -r "file_exists('.env') || copy('.env.example', '.env');"
```
- Set [authentication keys](https://www.smarty.com/docs/cloud/authentication) as the configuration value ```ZIPCODE_API_AUTH_ID```, ```ZIPCODE_API_AUTH_TOKEN``` in the ```.env``` file
- Start Laravel's local development server:
```bash 
php artisan serve
```
and open the page in the browser (http://127.0.0.1:8000)

Running tests: 
```bash 
php artisan test
```
