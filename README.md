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

# Using Docker

## 1. Prerequisites

* [Docker](https://www.docker.com/get-started) installed

## 2. Build the image

```bash
docker-compose build
```

## 3. Run the app

```bash
docker-compose run --rm commission-fee calculate-commission-fee example-input.csv
```

## 4. Run the tests

```bash
docker-compose run --rm commission-fee ./vendor/bin/pest
```

---

That's it! After filling out your `.env`, you can build and run the CLI seamlessly using Docker.


# Running locally

## 1. Prerequisites

* [PHP 8.2+](https://www.php.net/downloads)
* [Composer](https://getcomposer.org/download/)

## 2. Install dependencies

```bash
composer install
```

## 3. Run the app

```bash
php run calculate-commission-fee example-input.csv
```

## 4. Run the tests

```bash
./vendor/bin/pest
```
