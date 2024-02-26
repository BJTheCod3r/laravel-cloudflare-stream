<?php

namespace Bjthecod3r\CloudflareStream;

use Illuminate\Support\Facades\Http;

/**
 * Class CloudflareStream
 *
 * @package \Bjthecod3r\CloudflareStream
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 */
class CloudflareStream
{
    /**
     * List of error codes
     *
     * @var []
     */
    const ERROR_CODES = [
        'ERR_NON_VIDEO' => 'The upload is not a video',
        'ERR_DURATION_EXCEED_CONSTRAINT' => 'The video duration exceeds the constraints defined in the direct creator upload.',
        'ERR_FETCH_ORIGIN_ERROR' => 'The video failed to download from the URL',
        'ERR_MALFORMED_VIDEO' => 'The video is a valid file but contains corrupt data that cannot be recovered.',
        'ERR_DURATION_TOO_SHORT' => "The video's duration is shorter than 0.1 seconds.",
        'ERR_UNKNOWN' => 'Stream cannot determine the error'
    ];

    /**
     * @var string
     */
    private string $apiToken;

    /**
     * @var string
     */
    private string $accountId;

    /**
     * @var string
     */
    private string $webhookSecret;

    /**
     * @var string
     */
    private string $jwkKey;

    /**
     * @var string
     */
    private string $keyId;

    /**
     * @var string
     */
    private string $pem;

    /**
     * @var array
     */
    private array $defaultOptions;

    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * @var string
     */
    private string $baseDeliveryUrl;

    /**
     * @var object
     */
    private object $http;

    /**
     * CloudflareStream constructor
     */
    public function __construct()
    {
        $this->setApiToken();
        $this->setAccountId();
        $this->setBaseUrl();
        $this->setBaseDeliveryUrl();
        $this->setKeyId();
        $this->setPem();
        $this->setDefaultOptions();
        $this->setHttpOptions();
    }

    /**
     * Set the API token
     *
     * @return void
     */
    private function setApiToken(): void
    {
        $this->apiToken = config('cloudflare-stream.api_token');
    }

    /**
     * Set the Account ID
     *
     * @return void
     */
    private function setAccountId(): void
    {
        $this->accountId = config('cloudflare-stream.account_id');
    }

    /**
     * Set the Base URL
     *
     * @return void
     */
    private function setBaseUrl(): void
    {
        $this->baseUrl = config('cloudflare-stream.base_url');
    }

    /**
     * Set the default options
     *
     * @return void
     */
    private function setDefaultOptions(): void
    {
        $this->defaultOptions = config('cloudflare-stream.default_options');
    }

    /**
     * Set Http options
     *
     * @return void
     */
    private function setHttpOptions(): void
    {
        $this->http = Http::withHeaders([
            "Authorization" => "Bearer {$this->apiToken}"
        ]);
    }

    /**
     * upload to cloudflare by copying from a link
     *
     * @param string $url
     * @param array $meta
     * @param array $options
     * @return array
     */
    public function uploadViaLink(string $url, array $meta = [], array $options = []): array
    {
        $payload = [
            'url' => $url
        ];

        if (!empty($meta = $this->prepareData($meta))) {
            $payload['meta'] = $meta;
        }

        if (!empty($options = array_merge($this->prepareData($options), $this->prepareData($this->defaultOptions)))) {
            $payload = array_merge($payload, $options);
        }

        return $this->http->post("{$this->baseUrl}/{$this->accountId}/stream/copy", array_merge($payload, $this->prepareData($this->defaultOptions)))->json();
    }

    /**
     * Get rid of empty data
     *
     * @param array $data
     * @return array
     */
    private function prepareData(array $data): array
    {
        return array_filter($data, function ($value) {
            return !empty($value) || is_numeric($value);
        });
    }

    /**
     * Secure your video with signedURL and or origin
     * Example of the POST request
     *  {
     *     "uid": "dd5d531a12de0c724bd1275a3b2bc9c6",
     *     "requireSignedURLs": true,
     *     "allowedOrigins: ['localhost']
     *   }
     * @param string $id
     * @param array $payload
     * @return array
     */
    public function secureVideo(string $id, array $payload): array
    {
        $payload['uid'] = $id;
        return $this->http->post("{$this->baseUrl}/{$this->accountId}/stream/{$id}", $payload)->json();
    }

    /**
     * Subscribe to webhook notifications
     * Only one webhook subscription is allowed per-account
     * When a video finishes processing, you will receive a POST request notification with information about the video
     * The status field indicates whether the video processing finished successfully
     *
     * Example of a POST request body sent in response to successful encoding sent
     * {
     *   "uid": "dd5d531a12de0c724bd1275a3b2bc9c6",
     *   "readyToStream": true,
     *   "status": {
     *   "state": "ready"
     * },
     * "meta": {},
     * "created": "2019-01-01T01:00:00.474936Z",
     * "modified": "2019-01-01T01:02:21.076571Z",
     * // ...
     * }
     *
     * @param string $notification_url
     * @return array
     */
    public function subscribeToWebhookNotifications(string $notification_url): array
    {
        return $this->http->post("{$this->baseUrl}/{$this->accountId}/stream/webhook", [
            'notificationUrl' => $notification_url
        ])->json();
    }

    /**
     * Fetch video details
     *
     * @param string $id
     * @return array
     */
    public function fetchVideo(string $id): array
    {
        return $this->http->get("{$this->baseUrl}/{$this->accountId}/stream/{$id}")->json();
    }

    /**
     * Delete video
     *
     * @param string $id
     * @return bool
     */
    public function deleteVideo(string $id): bool
    {
        return is_null($this->http->delete("{$this->baseUrl}/{$this->accountId}/stream/{$id}")->json());
    }

    /**
     * Verify webhook authenticity
     *
     * @param string $webhook_signature
     * @param string $request_body
     * @return bool
     */
    public function verifyWebhookAuthenticity(string $webhook_signature, string $request_body): bool
    {
        $parts = explode(',', $webhook_signature);
        $timestamp = explode('=', $parts[0])[1];
        $sig1 = explode('=', $parts[1])[1];

        $this->setWebhookSecret();
        $source_string = $timestamp . '.' . $request_body;
        $signature = hash_hmac('sha256', $source_string, $this->webhookSecret, true);

        return bin2hex($signature) == $sig1;
    }

    /**
     * Set the Webhook Secret
     *
     * @return void
     */
    private function setWebhookSecret(): void
    {
        $this->webhookSecret = config('cloudflare-stream.webhook_secret');
    }

    /**
     * Get signed token to be used for video URL
     *
     * Access Rules structure
     * "accessRules": [
     * {
     * "type": "ip.geoip.country",
     * "country": ["US", "MX"],
     * "action": "allow",
     * },
     * {
     * "type": "ip.src",
     * "ip": ["93.184.216.0/24", "2400:cb00::/32"],
     * "action": "allow",
     * },
     * {
     * "type": "any",
     * "action": "block",
     * },
     * ]
     * Supported types for access rules are any, ip.src, ip.geoip.country
     * Supported action are allow and block
     *
     * @param string $id
     * @param int $expiresIn
     * @param bool $downloadable
     * @param array $accessRules
     * @return array
     */
    public function getStreamSignedToken(string $id, int $expiresIn = 3600, bool $downloadable = false, array $accessRules = []): array
    {
        return $this->http->post("{$this->baseUrl}/{$this->accountId}/stream/{$id}/token", [
            "exp" => time() + $expiresIn,
            "downloadable" => $downloadable,
            "accessRules" => $accessRules
        ])->json();
    }

    /**
     * Get the keys for generating Signed URLS
     * You should ideally call this once and store the keys
     *
     * @return array
     */
    public function getVerificationKeys(): array
    {
        return $this->http->post("{$this->baseUrl}/{$this->accountId}/stream/keys")->json();
    }

    /**
     * Set the jwk Key
     *
     * @return void
     */
    private function setJwkKey(): void
    {
        $this->jwkKey = config('cloudflare-stream.jwk_key');
    }

    /**
     * Set the jwk Key ID
     *
     * @return void
     */
    private function setKeyId(): void
    {
        $this->keyId = config('cloudflare-stream.key_id');
    }

    /**
     * Set pem
     *
     * @return void
     */
    private function setPem(): void
    {
        $this->pem = config('cloudflare-stream.pem');
    }

    /**
     * Set base delivery URL
     *
     * @return void
     */
    private function setBaseDeliveryUrl(): void
    {
        $this->baseDeliveryUrl = config('cloudflare-stream.base_delivery_url');
    }

    /**
     * Get the signed url for playback
     *
     * @param string $id
     * @param int $expiresIn
     * @return array
     */
    public function getSignedUrl(string $id, int $expiresIn = 3600)
    {
        $token =  $this->signToken($id, $expiresIn);
        return [
            'hls' => "$this->baseDeliveryUrl/$token/manifest/video.m3u8",
            'dash' => "$this->baseDeliveryUrl/$token/manifest/video.mpd"
        ];
    }

    private function signToken(string $uid, string $exp = null)
    {
        $privateKey = base64_decode($this->pem);

        $header = ['alg' => 'RS256', 'kid' => $this->keyId];
        $payload = ['sub' => $uid, 'kid' => $this->keyId];

        if ($exp) {
            $payload['exp'] = time() + $exp;
        }

        $encodedHeader = self::base64Url(json_encode($header));
        $encodedPayload = self::base64Url(json_encode($payload));

        openssl_sign("$encodedHeader.$encodedPayload", $signature, $privateKey, 'RSA-SHA256');

        $encodedSignature = self::base64Url($signature);

        return "$encodedHeader.$encodedPayload.$encodedSignature";
    }

    private function base64Url(string $data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
