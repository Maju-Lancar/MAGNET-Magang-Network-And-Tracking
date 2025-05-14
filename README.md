# MAGNET 🧲

# How to clone the project 
1. Clone the project using Git

    ```bash
    git clone https://github.com/Maju-Lancar/MAGNET-Magang-Network-And-Tracking.git
    ```

2. Navigate to the project directory

    ```bash
    cd MAGNET-Magang-Network-And-Tracking
    ```

3. Install require dependencies

    Make sure you have [`Composer`](https://getcomposer.org/) and [`Node.js`](https://nodejs.org/en) installed on your computer. Then, run the following command:

    ```bash
    composer install && npm install
    ```

4. Create `.env` file

    Duplicate the `.env.example` file and rename the copy to `.env`. If your database has a password, make sure to update the `DB_PASSWORD` field in the `.env` file.

5. Generate the Laravel application key

    ```bash
    php artisan key:generate
    ```
    This will generate an `APP_KEY` and save it to the `.env` file.

6. Run database migration

    ```bash
    php artisan migrate
    ```
    This will set up your database schema.