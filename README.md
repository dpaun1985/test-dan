# test-dan
## Overview

This is a PHP-based application designed to calculate fees for various financial operations such as deposits and withdrawals. The application processes input data from CSV files and outputs the calculated fees for each transaction.

## Features

- Supports deposit and withdrawal operations.
- Handles multiple currencies, including EUR, USD, and JPY.
- Processes input files with multiple rows of transactions.

## Requirements

- PHP 8.1 or higher
- Composer
- Symfony Console Component
- PHPUnit for testing

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/dpaun1985/test-dan.git
   cd test-dan

2. Install dependencies:
   ```bash
   cd my_project
   composer install
   ```
3. Define the environment variable `EXCHANGE_API_KEY` in a `.env.dev` file:
4. Start docker containers
   ```bash
   docker-compose build
   docker compose up -d
   ```


## Usage
1. Place your input CSV file in the `my_project` directory.
File example:
```
2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.00,EUR
2016-01-06,1,private,withdraw,30000,JPY
2016-01-07,1,private,withdraw,1000.00,EUR
2016-01-07,1,private,withdraw,100.00,USD
2016-01-10,1,private,withdraw,100.00,EUR
2016-01-10,2,business,deposit,10000.00,EUR
2016-01-10,3,private,withdraw,1000.00,EUR
2016-02-15,1,private,withdraw,300.00,EUR
2016-02-19,5,private,withdraw,3000000,JPY
```
   - The first column is the date of the transaction.
   - The second column is the user ID.
   - The third column is the user type (private or business).
   - The fourth column is the operation type (withdraw or deposit).
   - The fifth column is the amount.
   - The sixth column is the currency (EUR, USD, JPY ...).
2. Run the command to calculate fees:
   ```bash
   docker exec -it <docker_container_name> php bin/console app:calculate-fee <input>.csv
   ```