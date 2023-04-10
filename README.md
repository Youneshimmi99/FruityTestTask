# Fruity Project Setup Instructions

## Prerequisites

Before you can set up this project, you will need to have the following installed on your system:

- [XAMPP](https://www.apachefriends.org/)
- [Git](https://git-scm.com/download/)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/en/download)

## Installation Steps

1. Open a terminal and change directory to `C:\xampp\htdocs`.
2. Clone the project by running the following command: `git clone https://github.com/Youneshimmi99/FruityTestTask.git`.
3. Change directory to `C:\xampp\htdocs\FruityTestTask`.
4. Run `composer update` to update the required PHP packages. Note: it's better to run `composer update` instead of `install` due to differences between PHP versions. This has been tested with different PHP versions and it's working.
5. Install the required Node.js packages by running the command `npm install`.
6. Start Apache and MySQL server from XAMPP Control Panel.
7. Go to `http://127.0.0.1/phpmyadmin/` on your browser and create a new database named `fruity_test_task`.
8. Migrate the database by running the command `php bin/console doctrine:migrations:migrate`.
9. If you want to receive notifications in your Gmail about the fetch command, go to line 94 in `C:\xampp\htdocs\FruityTestTask\src\Command\FruitsFetchCommand.php` and change the `->to()` parameter to your Gmail and name.
10. Run the fetch command to fill the database with retrieved data from the API and send a notification to the Gmail mentioned in step 9: `php bin/console fruits:fetch`.
11. Run `npm run dev` to build the front-end assets.
12. Start the server:
    - If you have Symfony CLI installed, run: `symfony server:start`.
    - If you don't have it installed, run: `php -S 127.0.0.1:8000 -t public`.
13. Visit `http://127.0.0.1:8000` on your browser and start testing the functionalities.

Thank you for your time.