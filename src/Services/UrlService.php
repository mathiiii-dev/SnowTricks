<?php

namespace App\Services;

use App\Entity\Figure;

class UrlService
{
    public function checkVideoUrl(Figure $figure): bool
    {
        $videos = $figure->getVideos();

        foreach ($videos as $video) {

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

    public function checkImageUrl(Figure $figure): bool
    {
        foreach ($figure->getPictures() as $picture) {

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
