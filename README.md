# POC Turbo with Symfony

## Requirements

- PHP 8.1 or higher
- Composer
- Symfony CLI
- Docker

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/Faez-B/poc-turbo.git
    cd poc-turbo
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install asset mapper dependencies:

    ```bash
    bin/console importmap:install
    ```

4. Start containers:

    ```bash
    docker compose up -d
    ```

5. Create the database:

    ```bash
    symfony console doctrine:database:create --if-not-exists
    ```

6. Run migrations:

    ```bash
    symfony console doctrine:migrations:migrate --dry-run
    ```

## Running the Application

Start the Symfony server:

```bash
symfony serve -d
```

Open your browser and navigate to `http://127.0.0.1:8000` or `symfony open:local`.

## What I've Done

- Integrated Turbo Drive for fast, seamless navigation.
- Implemented Turbo Streams for real-time updates, enhancing user experience during form submissions and chat interactions.
- Configured Docker for containerized development.
- Automated asset management with Symfony's asset mapper.
