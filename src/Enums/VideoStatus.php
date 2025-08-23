<?php

namespace Bjthecod3r\CloudflareStream\Enums;

/**
 * Status values for the List Videos API.
 *
 * @see https://developers.cloudflare.com/api/operations/stream-videos-list-videos
 */
enum VideoStatus: string
{
    case PendingUpload = 'pendingupload';
    case Downloading   = 'downloading';
    case Queued        = 'queued';
    case InProgress    = 'inprogress';
    case Ready         = 'ready';
    case Error         = 'error';
}