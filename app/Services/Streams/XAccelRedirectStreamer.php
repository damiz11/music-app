<?php

namespace App\Services\Streams;

use App\Model\Setting;

class XAccelRedirectStreamer extends Streamer implements StreamInterface
{
    /**
     * Stream the current song using nginx's X-Accel-Redirect.
     */
    public function stream()
    {
        $relativePath = str_replace(Setting::get('media_path'), '', $this->_song->path);

        // We send our media_path value as a 'X-Media-Root' header to downstream (nginx)
        // It will then be use as `alias` in X-Accel config location block.
        // See nginx.conf.example.
        header('X-Media-Root: '.Setting::get('media_path'));
        header("X-Accel-Redirect: /media/$relativePath");
        header("Content-Type: {$this->_contentType}");
        header('Content-Disposition: inline; filename="'.basename($this->_song->path).'"');

        exit;
    }
}
