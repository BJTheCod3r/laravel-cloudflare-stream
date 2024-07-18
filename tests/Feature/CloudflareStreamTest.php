<?php

namespace Bjthecod3r\CloudflareStream\Tests\Feature;

use Bjthecod3r\CloudflareStream\CloudflareStream;
use Bjthecod3r\CloudflareStream\Params\VideoQueryParams;
use Bjthecod3r\CloudflareStream\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CloudflareStreamTest extends TestCase
{
    protected string $apiBaseUrl;

    protected string $accountId;

    /**
     * @return void
     */
    public function test_can_list_videos_with_default_query(): void
    {
        $count = 2;
        $fakeResponse = $this->fakeVideoResponse($count);
        $url = "$this->apiBaseUrl/$this->accountId/stream";

        Http::fake([
            $url => Http::response($fakeResponse)
        ]);

        $stream = new CloudflareStream();

        $response = $stream->listVideos();

        Http::assertSent(function ($request) use ($url) {
            return $request->url() == $url;
        });

        $this->assertCount(2, $response['result']['videos']);
        $this->assertEquals($this->fakeVideoResponse(2)['result']['videos'], $response['result']['videos']);
    }

    /**
     * @param int $count
     * @return array< string, mixed >
     */
    private function fakeVideoResponse(int $count): array
    {
        $videoItem = [
            "uid" => "0e03729d6f2xx7b5ea536495b2d70890",
            "creator" => null,
            "thumbnail" => "https://customer-xxxg9xgxxxnfxxx8.cloudflarestream.com/xxxxx29d6fxxx5c7xxxx53xxxd70890/thumbnails/thumbnail.jpg",
            "thumbnailTimestampPct" => 0.02,
            "readyToStream" => true,
            "readyToStreamAt" => "2023-08-01T13:59:36.001972Z",
            "status" => [
                "state" => "ready",
                "pctComplete" => "100.000000",
                "errorReasonCode" => "",
                "errorReasonText" => ""
            ],
            "meta" => [
                "downloaded-from" => "https://example.s3.eu-north-1.amazonaws.com/9/some-videos.mp4",
                "name" => "some-videos"
            ],
            "created" => "2023-08-01T13:58:00.162096Z",
            "modified" => "2023-08-01T13:59:36.002825Z",
            "scheduledDeletion" => null,
            "size" => 426268726,
            "preview" => "https://customer-xxxxxxxxxxxxx.cloudflarestream.com/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx/watch",
            "allowedOrigins" => [
                "*.example.com",
            ],
            "requireSignedURLs" => true,
            "uploaded" => "2023-08-01T13:58:00.162046Z",
            "uploadExpiry" => null,
            "maxSizeBytes" => null,
            "maxDurationSeconds" => null,
            "duration" => 338,
            "input" => [
                "width" => 1920,
                "height" => 1080
            ],
            "playback" => [
                "hls" => "https://customer-xxxxxxxxxxxx.cloudflarestream.com/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx/manifest/video.m3u8",
                "dash" => "https://customer-xxxxxxxxxxxx.cloudflarestream.com/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx/manifest/video.mpd"
            ],
            "watermark" => null,
            "clippedFrom" => null,
            "publicDetails" => [
                "title" => null,
                "share_link" => null,
                "channel_link" => null,
                "logo" => null
            ]
        ];

        $videos = array_fill(0, $count, $videoItem);
        return [
            'result' => [
                'videos' => $videos,
            ],
            'success' => true,
            'errors' => [],
            'messages' => []
        ];
    }

    /**
     * @return void
     */
    public function test_can_list_videos_with_custom_query_array()
    {
        $count = 3;
        $query = ['include_counts' => 'true'];
        $param = http_build_query($query);
        $url = "$this->apiBaseUrl/$this->accountId/stream?$param";

        $fakeResponse = $this->fakeVideoResponse($count);

        $fakeResponse['result']['total'] = $count;
        $fakeResponse['result']['range'] = $count;

        Http::fake([
            '*/stream*' => Http::response($fakeResponse)
        ]);

        $stream = new CloudflareStream();
        $response = $stream->listVideos($query);

        Http::assertSent(function ($request) use ($query, $url) {
            return $request->url() == $url
                && $request->data() == $query;
        });

        $this->assertCount(3, $response['result']['videos']);
        $this->assertEquals($response['result']['total'], $count);
        $this->assertEquals($response['result']['range'], $count);
        $this->assertEquals($this->fakeVideoResponse(3)['result']['videos'], $response['result']['videos']);
    }

    /**
     * @return void
     */
    public function test_can_list_videos_with_custom_query_params_object(): void
    {
        $count = 3;
        $fakeResponse = $this->fakeVideoResponse($count);
        $date = '2024-01-01T00:00:00Z';

        $fakeResponse['result']['count'] = $count;
        $fakeResponse['result']['range'] = $count;

        $queryParams = new VideoQueryParams();
        $queryParams->includeCounts = 'true';
        $queryParams->end = $date;

        $query = http_build_query($queryParams->toArray());

        $url = "$this->apiBaseUrl/$this->accountId/stream?$query";

        Http::fake([
            $url => Http::response($fakeResponse)
        ]);

        $stream = new CloudflareStream();
        $response = $stream->listVideos($queryParams);

        Http::assertSent(function ($request) use ($queryParams, $url) {
            return $request->url() == $url
                && $request->data() == $queryParams->toArray();
        });

        $this->assertCount(3, $response['result']['videos']);
        $this->assertEquals($fakeResponse['result'], $response['result']);
    }

    /**
     * @return void
     */
    public function test_can_upload_video_via_link_successfully(): void
    {
        $uploadUrl = "https://example.upload.s3.amazonaws.com/example.mp4";
        $url = "$this->apiBaseUrl/$this->accountId/stream/copy";
        $fakeResponse = $this->fakeUploadResponse();

        Http::fake([
            $url => Http::response($fakeResponse)
        ]);

        $meta = [
            'name' => 'Test Video'
        ];

        $data = [
            "url" => $uploadUrl,
            "meta" => [
                "name" => "Test Video"
            ],
            "requireSignedURLs" => true
        ];

        $stream = new CloudflareStream();
        $response = $stream->uploadViaLink($uploadUrl, $meta);

        Http::assertSent(function ($request) use ($url, $data) {
            return $request->url() == $url && $request->data() == $data;
        });

        $this->assertTrue($response['result']['requireSignedURLs']);
        $this->assertEquals($response['result']['meta']['downloaded-from'], $data['url']);
        $this->assertEquals($response['result']['meta']['name'], $data['meta']['name']);
    }

    /**
     * @return array
     */
    private function fakeUploadResponse(): array
    {
        return [
            'result' => [
                'uid' => 'xxxxxx45110522xx931c92xxxxxxxxx',
                'creator' => null,
                'thumbnail' => 'https://customer-xxxxxxxxxx.cloudflarestream.com/xxxxxx44511052298931cxxxxxxxxxx/thumbnails/thumbnail.jpg',
                'thumbnailTimestampPct' => 0,
                'readyToStream' => false,
                'readyToStreamAt' => null,
                'status' => [
                    'state' => 'downloading',
                    'errorReasonCode' => '',
                    'errorReasonText' => ''
                ],
                'meta' => [
                    'downloaded-from' => "https://example.upload.s3.amazonaws.com/example.mp4",
                    'name' => 'Test Video'
                ],
                'created' => '2024-07-14T18:13:37.280983Z',
                'modified' => '2024-07-14T18:13:37.280983Z',
                'scheduledDeletion' => null,
                'size' => 234135885,
                'preview' => 'https://customer-xxxxxxxxxxxxx.cloudflarestream.com/xxxxxx44511052298931cxxxxxxxxxx/watch',
                'allowedOrigins' => [],
                'requireSignedURLs' => true,
                'uploaded' => '2024-07-14T18:13:37.280973Z',
                'uploadExpiry' => null,
                'maxSizeBytes' => null,
                'maxDurationSeconds' => null,
                'duration' => -1,
                'input' => [
                    'width' => -1,
                    'height' => -1
                ],
                'playback' => [
                    'hls' => 'https://customer-xxxxxxxxxxxxx.cloudflarestream.com/xxxxxx44511052298931cxxxxxxxxxx/manifest/video.m3u8',
                    'dash' => 'https://customer-xxxxxxxxxxxxx.cloudflarestream.com/xxxxxx44511052298931cxxxxxxxxxx/manifest/video.mpd'
                ],
                'watermark' => null,
                'clippedFrom' => null,
                'publicDetails' => null
            ],
            'success' => true,
            'errors' => [],
            'messages' => []
        ];
    }

    /**
     * @return void
     */
    public function test_can_get_stream_signed_token_successfully(): void
    {
        $fakeResponse = [
            'result' => [
                'token' => 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjEyMGU1YTNjZDg5NmZhMzY2ODMwNzhkMmNmNGNhMDM2In0.eyJzdWIiOiJlYTc4NTE3YjBjYWVhODFhZWI5NjBhZDBhNDUzNDM5YiIsImtpZCI6IjEyMGU1YTNjZDg5NmZhMzY2ODMwNzhkMmNmNGNhMDM2IiwiZXhwIjoiMTcyMDk4NTk2MCIsIm5iZiI6IjE3MjA5Nzg3NjEifQ.mLsK5LZwjncsRhiD-fqYolYLn3DQxLSCsoq-_uWnqg83XQsaPZYEmZRCK50w2H1UnoEneIm8TY-IuhEaE1WRHBpGwqtARoKN9QS7WX4L3Flhtvm2EkoZIAGdJrvY8a4VPUJmrJnJstJK0RuCnH7lG_nu3hCklrOA8gSqlobP40G-vufx0zCZIrP3WXu4A2X93B17XqPZQtPkoUQRgOhDOJRNZrgYVW30DqVozO-7WQCD5_Yz4K74YepHqFNCc9QyJwmcUcZM6qrMeFwb3nKGSlAMBO-LZq_eY8sWaeTsEKjzVy5yW02aPNwVMYzrEorC0I8aAMg7F_ijdT9jsLnB4w'
            ],
            'success' => true,
            'errors' => [],
            'messages' => []
        ];

        $id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

        $url = "$this->apiBaseUrl/$this->accountId/stream/$id/token";

        Http::fake([
            $url => Http::response($fakeResponse)
        ]);

        $stream = new CloudflareStream();
        $response = $stream->getStreamSignedToken($id);
        $this->assertEquals($fakeResponse['result']['token'], $response['result']['token']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiBaseUrl = config('cloudflare-stream.base_url');
        $this->accountId = config('cloudflare-stream.account_id');
    }
}
