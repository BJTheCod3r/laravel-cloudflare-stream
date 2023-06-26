# Laravel Cloudflare Stream

A Laravel Package for working with Cloudflare Stream seamlessly

## Installation

Run
```bash
composer require bjthecod3r/laravel-cloudflare-stream
```

## Configuration

You may publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="Bjthecod3r\CloudflareStream\CloudflareStreamServiceProvider"
```

A configuration file named `cloudflare-stream` with some defaults and required properties will be placed in your `config` directory.


## Usage

Open your .env file and add the following variables, remember to substitute the actual values

```php
CLOUDFLARE_API_TOKEN=xxxxxxxxxxxx
CLOUDFLARE_ACCOUNT_ID=xxxxxxxxxxxxxxxxx
CLOUDFLARE_BASE_API_URL=https://api.cloudflare.com/client/v4/accounts
CLOUDFLARE_KEY_ID=xxxxxxxxxxxxxxx
```

Using the package is pretty straight forward, you can make use of the class like you would do any in Laravel. There is also support for facade.

```php
use Bjthecod3r\CloudflareStream\CloudflareStream;

use Bjthecod3r\CloudflareStream\Facades\CloudflareStream as CloudflareStreamFacade;

class StreamService
{
    public function fetchStream(string $id, CloudflareStream $cloudflareStream)
    {
        dd($cloudflareStream->fetchVideo($id));
    }
    
    public function fetchVideo(string $id): array
    {
       return CloudflareStreamFacade::fetchVideo(string $id)
    }
}
```

## Note
This package currently covers my current usage of cloudflare stream, although, in the next couple of weeks I plan to expand its features.
However, if there is a need for a particular feature, feel free to reach out to me via [fabulousbj@hotmail.com](mailto:fabulousbj@hotmail.com), and I'll 
gladly attend to it.

## Todo

* Add Tests
* Add the remaining useful features available on Cloudflare Stream

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
