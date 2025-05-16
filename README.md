# Commission Fee CLI (Docker)

Commission Fee CLI is a command-line application that calculates the commission fee for transactions based on the provided rules.

---

# Environment Setup

1. Copy the example environment file:

   ```bash
   cp .env.example .env
   ```

2. Open the newly created `.env` file and set your API key for the exchange rates service:

   ```dotenv
   API_LAYER_EXCHANGE_RATES_API_KEY=your_api_key_here
   ```

   You can obtain a key from:

    * [https://exchangeratesapi.io/](https://exchangeratesapi.io/)
    * [https://apilayer.com/](https://apilayer.com/)

# Running locally

### 1. Prerequisites

* [PHP 8.2+](https://www.php.net/downloads)
* [Composer](https://getcomposer.org/download/)

### 2. Install dependencies

```bash
composer install
```

### 3. Run the app

```bash
php run calculate-commission-fee example-input.csv
```

### 4. Run the tests

```bash
./vendor/bin/pest
```

# Docker Setup

### 1. Prerequisites
* [Docker](https://docs.docker.com/get-docker/)
* [Docker Compose](https://docs.docker.com/compose/install/)

### 2. Build and run
```bash
# Copy environment file (only needed for running the app, not for tests)
cp .env.example .env

# Build the container
docker compose build

# Run the app (requires API key in .env)
docker compose run --rm app php run calculate-commission-fee example-input.csv

# Run the tests (no API key needed)
docker compose run --rm test
```
