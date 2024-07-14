<?php

namespace Bjthecod3r\CloudflareStream\Tests;

use Bjthecod3r\CloudflareStream\CloudflareStreamServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    use WithWorkbench;

    /**
     * Get package providers.
     *
     * @param Application $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            CloudflareStreamServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        $envVariables = [];  //$this->grabVariablesFromEnvFile();
        $envVariables['account_id'] = 'account_id';
        $envVariables['api_token'] = 'api_token';
        $envVariables['api_base_url'] = 'https://api.cloudflare.com/client/v4/accounts';
        $envVariables['customer_domain'] = 'https://customer-xxxxxxxxxxxxx.cloudflarestream.com';
        $envVariables['key_id'] = 'xxxxxxxxxxxxxxx';
        $envVariables['jwk_key'] = 'xxxxxxxxxxxxxxx';
        $envVariables['pem'] = 'xxxxxxxxxxxxxxx';

        tap($app['config'], function (Repository $config) use ($envVariables) {
            $config->set('cloudflare-stream.api_token', $envVariables['api_token']);
            $config->set('cloudflare-stream.account_id', $envVariables['account_id']);
            $config->set('cloudflare-stream.base_url', $envVariables['api_base_url']);
            $config->set('cloudflare-stream.customer_domain', $envVariables['customer_domain']);
            $config->set('cloudflare-stream.key_id', $envVariables['key_id']);
            $config->set('cloudflare-stream.jwk_key', $envVariables['jwk_key']);
            $config->set('cloudflare-stream.pem', $envVariables['pem']);
            $config->set('cloudflare-stream.default_options', [
                'requireSignedURLs' => true,
            ]);
        });
    }

    /**
     * If you have an .env file in your project root directory, you can grab variables from it to set in the config.
     * This is useful if you want to test with actual credentials during development.
     *
     * For example, you can grab a variable like api_token from the .env via $envVariables['api_token'],
     * with that you can use it in the defineEnvironment method.
     *
     * @param string $envPrefix
     * @return array
     */
    protected function grabVariablesFromEnvFile(string $envPrefix = 'CLOUDFLARE_'): array
    {
        $envFilePath = '.env';
        $contents = file_get_contents($envFilePath);
        $envVariables = [];

        foreach (explode("\n", $contents) as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);

            $key = strtolower(str_replace($envPrefix, '', $key));

            $envVariables[$key] = $value;
        }

        return $envVariables;
    }
}
