<?php

namespace App\Services;

class UrlService
{
    public function checkVideoUrl($figure): bool
    {
        foreach ($figure->getValues() as $video) {

            if (!filter_var($video->getLink(), FILTER_VALIDATE_URL)) {
                return false;
            }

            $parsed_url = parse_url($video->getLink());

            if ($parsed_url['host'] != "www.youtube.com" || $parsed_url['path'] != "/watch") {
                return false;
            }
        }
        return true;
    }

    public function checkImageUrl($value): bool
    {
        foreach ($value->getValues() as $picture) {

            if (!filter_var($picture->getLink(), FILTER_VALIDATE_URL)) {
                return false;
            }
            $headers = get_headers($picture->getLink(), 1);
            if (!str_contains($headers['Content-Type'], 'image/')) {
                return false;
            }
        }
        return true;
    }
}
