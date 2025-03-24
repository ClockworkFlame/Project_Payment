Dockerized it cause why not.

Steps to run:
1- Have docker installed
2- Navigate to root project folder
3- Run 'docker-compose up'
4- Add Exchangeratesapi key to config/keys  -  https://app.exchangerate-api.com/

Steps to test:
???

Notes:
- Removed friendsofphp/php-cs-fixer package as its not compatible with PHP ^8.0
- Changed used PHPUnit version to 9, same issue as ^^^
- Would be nice to ORM the CSV. Might look bad that I havent, but I feel like the core of this task is showing how I deal with calculating the fees above all.