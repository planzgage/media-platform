Media Platform for Laravel

A reusable Laravel 12 package providing a media domain with editorial workflow, streaming, search, and operational tooling.
Designed to integrate cleanly with Filament, Inertia, or API-only applications.

Requirements

PHP 8.3+

Laravel 12.x

Queue driver (Redis recommended)

FFmpeg available on the system (for audio processing)

Installation
1. Install the package
composer require planzgage/media-platform

2. Publish configuration (optional but recommended)
php artisan vendor:publish --tag=media-platform-config

3. Run migrations
php artisan migrate


This creates:

media_assets

search_index

stream_tokens

editorial support tables

Opt-in your models

The package does not ship Artist / Album / Song models.
Your models opt in via traits and contracts.

Example: Song model
use MediaPlatform\Traits\HasMedia;
use MediaPlatform\Traits\HasPublicationStatus;
use MediaPlatform\Contracts\Searchable;

class Song extends Model implements Searchable
{
    use HasMedia, HasPublicationStatus;

    public function searchablePayload(): array
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->album?->title,
        ];
    }
}

Media handling

Attach media using the HasMedia trait:

$song->addMedia($uploadedFile, collection: 'audio');


Media is processed asynchronously via queued jobs.

Editorial workflow

All opt-in models support publication states:

draft

review

scheduled

published

archived

Helper scope:

Song::published()->get();


Scheduled publishing is handled automatically by a queued job.

Public API

The package registers versioned API routes:

/api/v1/artists
/api/v1/albums/{slug}
/api/v1/search
/api/v1/stream/{song}


These endpoints:

expose published content only

support token-based streaming

are safe for web, mobile, and third-party clients

Search

Search indexing is automatic on publish/unpublish.

Manual rebuild:

php artisan media-platform:reindex

Streaming

Streaming URLs are:

signed

time-limited

range-request compatible

Direct file paths are never exposed.

Configuration

See config/media-platform.php for:

storage disk

audio bitrates

search driver

stream token TTL

Filament integration

Filament resources are not included in this package.

See the companion package:

planzgage/media-platform-filament

License

MIT
