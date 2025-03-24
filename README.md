Dockerized it cause why not.

Steps to run:
- Run 'composer install' to generate vendor and dependencies
- Have docker installed
- Navigate to root project folder and run 'docker-compose up'
- Navigate to http://localhost:8080/ OR run though command line 'php index.php'
- Optional. Project uses hardcoded values for exchange rates. Add Exchangeratesapi key to config/keys.ini to use live exchange - https://app.exchangerate-api.com/

Steps to test:
- Run './vendor/phpunit/phpunit/phpunit' from project root
- Actually just do 'composer run test'

Notes:
- Removed friendsofphp/php-cs-fixer package as its not compatible with PHP ^8.0
- Changed used PHPUnit version to 9, same issue as ^^^
- Not too happy with generating a new FeeCalculator class for each transaction. Thought about converting them to statics but Im not sure if thats optimal.