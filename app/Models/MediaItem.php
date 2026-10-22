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
        'photo_urls',
        'cover_photo',
        'total_photos',
    ];

    protected $casts = [
        'photos' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $fillable = [
        'title',
        'type',
        'caption',
        'file_url',
        'photos',
        'video_url',
        'category',
        'is_active',
        'order',
    ];

    /**
     * Get list of all photo URLs for album.
     */
    public function getPhotoUrlsAttribute()
    {
        if (!empty($this->photos) && is_array($this->photos)) {
            return array_values(array_filter($this->photos));
        }

        if (!empty($this->file_url)) {
            $decoded = json_decode($this->file_url, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
            return [$this->file_url];
        }

        return [];
    }

    /**
     * Get cover photo URL.
     */
    public function getCoverPhotoAttribute()
    {
        $urls = $this->photo_urls;
        if (!empty($urls[0])) {
            return $urls[0];
        }
        return $this->file_url ?: 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb';
    }

    /**
     * Get count of photos in album.
     */
    public function getTotalPhotosAttribute()
    {
        return count($this->photo_urls);
    }

    /**
     * Helper to get YouTube Embed URL if applicable.
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = trim($this->video_url);

        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0';
        }

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
