<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VideoUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $originalvideoname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $videoName = $originalvideoname.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $videoName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $videoName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}