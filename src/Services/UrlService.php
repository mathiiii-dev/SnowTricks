<?php


namespace App\Services;


use App\Entity\Figure;

class UrlService
{
    private $error;

    public function __construct(ErrorService $error)
    {
        $this->error = $error;
    }

    /**
     * @param $figure
     * @return array|false
     */
    public function checkVideoUrl(Figure $figure)
    {
        $videos = $figure->getVideos();
        $arrVideos = [];
        foreach ($videos as $video) {
            $parsed_url = parse_url($video->getVideo());
            if ($parsed_url['host'] !== "www.youtube.com") {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Figure $figure
     * @return bool
     */
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