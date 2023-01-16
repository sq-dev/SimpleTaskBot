## Installation

1. Clone the repository 
```bash
git clone https://github.com/sq-dev/SimpleTaskBot.git
```
2. Navigate to the project directory
```bash
cd chatbot-project
```
3. Install dependencies
```bash
composer install
```
4. Create a copy of the .env file and set your configuration
```bash
cp .env.example .env
```
5. Generate an application key
```bash
php artisan key:generate
```
6. Run the migrations
```bash
php artisan migrate
```
7. Obtain a Telegram token by talking to the [BotFather](https://telegram.me/botfather) and set it in your .env file as `TELEGRAM_TOKEN`

## Usage

1. To run the project in development mode:
   ```php artisan nutgram:run```

   This command will start a long-polling connection with Telegram servers and will handle incoming updates to your chatbot.

2. To set up a webhook for production:
   ```php artisan nutgram:webhook```

   This command will create a webhook endpoint in your application that Telegram can use to send updates to your chatbot. You will need to configure your web server to forward incoming requests to this endpoint.

3. You can also configure the chatbot to work with NutGram by following the instructions [here](https://github.com/nutgram/nutgram)

## Additional Resources

1. [Laravel documentation](https://laravel.com/docs)

2. [NutGram documentation](https://github.com/nutgram/nutgram)
