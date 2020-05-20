Guidance to Installation the software:

1. Git clone the repo
2. cd into the project folder
3. run below commands one by one:

composer install,
npm install

4. Copy .env.example file and paste as .env file
5. Open .env file and setup database connention string and other environment variables as needed
6. Now once database setup is done, run below command

php artisan migrate --seed

7. Make sure your domain/sub-domain points to the /public foler under laravel Installation