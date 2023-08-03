# Lynus Hub

## Installation

1. Install [Node.js](https://nodejs.org/en/download/) (v12.18.3 or higher)
2. Run `composer install`.
3. Run `npm install`.
4. Run `npm run build`.
5. Set permissions for `storage` and `bootstrap/cache` to `www-data` like so:
- `sudo chown -R www-data:www-data storage`
- `sudo chown -R www-data:www-data bootstrap/cache`
6. Copy `.env.example` to `.env` and fill in the required information.

## Development

1. Run `npm run dev` to watch for changes in the frontend.
2. Run `php artisan serve` to start the development server.
3. Open `http://localhost:8000` in your browser.
