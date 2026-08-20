<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    use HasFactory;

    protected $appends = [
        'youtube_embed_url',
        'youtube_thumbnail_url',
    ];

    protected $fillable = [
        'title',
        'type',
        'caption',
        'file_url',
        'video_url',
        'category',
        'is_active',
        'order',
    ];

    /**
     * Helper to get YouTube Embed URL if applicable.
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = trim($this->video_url);

        // Convert YouTube Shorts (e.g. youtube.com/shorts/VIDEO_ID)
        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0';
        }

        // Convert standard YouTube URLs (watch?v=, embed, youtu.be)
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0';
        }

        return $url;
    }

    /**
     * Helper to get YouTube Thumbnail URL.
     */
    public function getYoutubeThumbnailUrlAttribute()
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = trim($this->video_url);

        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
        }

        return null;
    }
}
