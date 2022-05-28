<?php

namespace App\Services;

use App\Models\Seminar;

class SeminarService
{
    
    public function handler(Seminar $seminar, Int $userLevel) : Array
    {
        $availableVideos = $unAvailableVideos = $availableFiles = $unAvailableFiles = array();

        if (!empty($seminar->videos)) {
            foreach ($seminar->videos as $video) {
                if ($userLevel >= $video->level) {
                    $availableVideos[] = $video;
                } else {
                    $unAvailableVideos[] = $video;
                }
            }
        }

        if (!empty($seminar->files)) {
            foreach ($seminar->files as $file) {
                if ($userLevel >= $file->level) {
                    $availableFiles[] = $file;
                } else {
                    $unAvailableFiles[] = $file;
                }
            }
        }

        return [
            'availableVideos' => $availableVideos,
            'unAvailableVideos' => $unAvailableVideos,
            'availableFiles' => $availableFiles,
            'unAvailableFiles' => $unAvailableFiles
            
        ];

    }

}