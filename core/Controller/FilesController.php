<?php

namespace Core\Controller;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FilesController
{

    private $path;
    private $test;

    public function __construct(string $path, bool $test = false)
    {
        $this->path = $path;
        $this->test = $test;
        if (!file_exists($path)) {
            mkdir($path);
        }
    }

    public function createFolder(string $name): bool
    {
        $folder = $this->path . DIRECTORY_SEPARATOR . $name;
        if (!file_exists($folder)) {
            return mkdir($folder, 0777, true);
        }
        return false;
    }

    public function move(string $oldFile, string $newpath): bool
    {
        if (\strpos("/", $oldFile)) {
            $oldFileName = end(explode("/", $oldFile));
        } else {
            $oldFileName = $oldFile;
        }
        $newFile = $this->path . \DIRECTORY_SEPARATOR . $newpath . \DIRECTORY_SEPARATOR . $oldFileName;

        if (!file_exists($newFile)) {
            return rename($this->path . \DIRECTORY_SEPARATOR . $oldFile, $newFile);
        }
        return false;
    }

    public function list(string $path = null): array
    {
        if (is_null($path)) {
            $path = $this->path . \DIRECTORY_SEPARATOR;
        }
        $folderName = array_filter(explode("/", str_replace($this->path . \DIRECTORY_SEPARATOR, "", $path)));
        $folderName = empty($folderName) ? "" : end($folderName);
        $return = scandir($path) ?: [];
        foreach ($return as $key => $value) {
            if ($value != "." && $value != "..") {
                if (is_dir($path . $value)) {
                    $return[$value] = $this->list($path . $value . \DIRECTORY_SEPARATOR);
                } else {
                    $return[] = $value;
                }
            }
            unset($return[$key]);
        }
        return $return;
    }
    public function uploadFile(string $folder = "")
    {
        $request = Request::createFromGlobals();
        $this->createFolder($folder);

        foreach ($request->files as $file) {
            /** @var UploadedFile $file */
            //dump(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $fileName = uniqid() . '.' . $file->guessExtension();
            //dd($this->path . \DIRECTORY_SEPARATOR . $folder . \DIRECTORY_SEPARATOR);
            if ($this->test) {
                // Symfony\Component\HttpFoundation\File\UploadedFile.php
                // public function trunTest()
                // {
                //     $this->test = true;
                // }
                $file->trunTest();
            }
            try {
                /** @var File $newFile */
                $newFile = $file->move($this->path .  DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR, $fileName);
            } catch (FileException $e) {
                dd($e . "oups");
            }
            $arrafile[] = ["Basename" => $newFile->getBasename(), "name" => $fileName, "folder"  => $folder];
        }
        return $arrafile;
    }
}
