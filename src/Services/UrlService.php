<?php

namespace App\Services;

use App\Entity\Figure;

class UrlService
{
    public function checkVideoUrl($figure): bool
    {
        foreach ($figure->getValues() as $video) {

            if (!filter_var($video->getVideo(), FILTER_VALIDATE_URL)) {
                return false;
            }

            $parsed_url = parse_url($video->getVideo());
            if ($parsed_url['host'] !== "www.youtube.com") {
                return false;
            }
        }
        return true;
    }

    public function checkImageUrl($value): bool
    {
        foreach ($value->getValues() as $picture) {

            if (!filter_var($picture->getPicture(), FILTER_VALIDATE_URL)) {
                return false;
            }
            $headers = get_headers($picture->getPicture(), 1);
            if (!str_contains($headers['Content-Type'], 'image/')) {
                return false;
            }
        }
        return true;
    }
}
