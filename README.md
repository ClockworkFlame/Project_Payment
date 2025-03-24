Dockerized it cause why not.

Steps to run:
1- Have docker installed
2- Navigate to root project folder
3- Run 'docker-compose up'
4- Navigate to http://localhost:8080/
5- Optional. Project uses hardcoded values for exchange rates. Add Exchangeratesapi key to config/keys.ini to use live exchange - https://app.exchangerate-api.com/

Steps to test:
???

Notes:
- Removed friendsofphp/php-cs-fixer package as its not compatible with PHP ^8.0
- Changed used PHPUnit version to 9, same issue as ^^^
- Not too happy with generating a new FeeCalculator class for each transaction. Thought about converting them to statics but Im not sure if thats optimal.