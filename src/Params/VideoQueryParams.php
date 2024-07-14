<?php

namespace Bjthecod3r\CloudflareStream\Params;

/**
 * During usage, all null properties will be ignored.
 *
 * Class VideoQueryParams
 *
 * @package Bjthecod3r\CloudflareStream\QueryParams
 */
class VideoQueryParams extends QueryParams
{
    /**
     * List videos in ascending order of creation. Default is false.
     * Values: true, false
     *
     * @var string|null
     */
    public ?string $asc = null;

    /**
     * A user defined identifier for the media creator.
     * @var string|null
     */
    public ?string $creator = null;

    /**
     * List videos created before the specified date.
     * Example: 2014-01-02T02:20:00Z
     *
     * @var string|null
     */
    public ?string $end = null;

    /**
     * Searches over the name key in the meta field. This field can be set with or after the upload request. Example: puppy.mp4
     *
     * @var string|null
     */
    public ?string $search = null;

    /**
     * Lists videos created after the specified date.
     * Example: 2014-01-02T02:20:00Z
     *
     * @var string|null
     */
    public ?string $start = null;

    /**
     * @var string|null
     */
    public ?string $includeCounts = null;

    /**
     * Specifies the processing status for all quality levels for a video.
     * Allowed values: pendingupload, downloading, queued, inprogress, ready, error
     *
     * @var string|null
     */
    public ?string $status = null;

    /**
     * Specifies whether the video is vod or live.
     * Example: vod
     *
     * @var string|null
     */
    public ?string $type = null;
}
