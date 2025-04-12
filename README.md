
# Technical Requirements : 
Before cloning this project you must:

- Install PHP 8.2 
- Install Composer, which is used to install PHP packages.

You can check if your computer meets all requirements with : `symfony check:requirements`

# Installation

1. Clone the repository
2. Install Symfony and Vue dependencies
- Install PHP dependencies using Composer:
    * `composer install`
- Install JavaScript dependencies using Yarn (or npm):
    * `yarn install`
   
5. Create the database and run migrations
   * `php bin/console doctrine:database:create`
   * `php bin/console make:migration`
   * `php bin/console doctrine:migrations:migrate`
  

7. Start the Symfony server
   * `symfony server:start`

8. Build Frontend (Webpack Encore)
   * `yarn encore dev`         # for development
   * `yarn encore production`  # for production


# Setting Up a Cron Job for Symfony Command
1. Edit Crontab:
   `crontab -e`
2. Add a Cron Job:
   * `* * * * * cd /path/to/your/project && /usr/bin/php bin/console app:check-service-status >> /var/log/symfony-cron-spa.log 2>&1`
   * `0 * * * * cd /path/to/your/project && /usr/bin/php bin/console app:check-availability-rate-24 >> /var/log/symfony-cron-spa-24h.log 2>&1`
   * `0 0 * * * cd /path/to/your/project && /usr/bin/php bin/console app:check-availability-rate-7 >> /var/log/symfony-cron-spa-7d.log 2>&1`

- (* * * * * means this command runs every minute.)
- (0 * * * * means this command runs every hour.)
- (0 0 * * * means this command runs every day at midnight.)

3. Save and Exit:
- After adding the cron job, save the file and exit the editor.
