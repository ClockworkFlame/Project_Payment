Tech interview task that accepts a CSV of transactions (input.csv) and calculates their respective commission fees.
Further details about their calculation can be found in task-description.md.
Task follows specific constraints set by the interviewing company, such as 'one' unit test and no database solutions.
Ive also prioritized work over the meat of the project (calculating fees), rather than service modules like data importing and currency conversion API calls.

Steps to run:

- Run 'composer install' to generate vendor and dependencies
- Have docker installed
- Navigate to root project folder and run 'docker-compose up'
- Navigate to http://localhost:8080/ OR run though command line 'php index.php'
- ()Optional) Project uses hardcoded values for exchange rates. Add Exchangeratesapi key to config/keys.ini to use live exchange - https://app.exchangerate-api.com/

Steps to test:

- Just do 'composer run test'

Fee calculation is designed after a factory setup, as each user type requires a different set of conditions for calculating their deposit and withdrawal fees. Making it easy to extend in case a new user variety is introduced.
