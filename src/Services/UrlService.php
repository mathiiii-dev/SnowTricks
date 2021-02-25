<?php


namespace App\Services;


use App\Entity\Figure;
use Symfony\Component\Form\FormError;

class UrlService
{
    /**
     * @param $figure
     * @return array|false
     */
    public function checkVideoUrl(Figure $figure)
    {
        $videos = $figure->getVideos();
        $arrVideos = [];
        foreach ($videos as $video) {
            $parsed_url = parse_url($video);
            if ($parsed_url['host'] !== "www.youtube.com") {
                return false;
            }
            $cleanVideo = str_replace('/watch?v=', '/embed/', $video);
            array_push($arrVideos, $cleanVideo);
        }
        return $arrVideos;
    }

    /**
     * @param Figure $figure
     * @return bool
     */
    public function checkImageUrl(Figure $figure): bool
    {
        foreach ($figure->getPictures() as $picture) {

            $headers = get_headers($picture, 1);
            if (!str_contains($headers['Content-Type'], 'image/')) {
                return false;
            }
        }
        return true;
    }
}