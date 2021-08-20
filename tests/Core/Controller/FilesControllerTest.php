<?php

namespace Tests\Core\Controller;

use Core\Controller\FilesController;
use Directory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class FilesControllerTest extends TestCase
{

    private $folderName;
    private $folderNameForFile;

    protected function setUp(): void
    {
        $_FILES = [];
        $this->folderName = dirname(dirname(dirname(__DIR__))) . \DIRECTORY_SEPARATOR . "filesTest";
        $this->folderNameForFile = dirname(dirname(__DIR__)) . \DIRECTORY_SEPARATOR;
        if (file_exists($this->folderName)) {
            shell_exec('rm -R ' . $this->folderName);
        }
    }

    protected function tearDown(): void
    {
        shell_exec('rm -R ' . $this->folderName);
        shell_exec('cp ' . $this->folderNameForFile  . 'testcopy.png' . " " . $this->folderNameForFile  . 'test.png');
    }



    public function testCreateFolder()
    {
        new FilesController($this->folderName, true);
        $this->assertFileExists($this->folderName);
    }

    public function testCreateFolderExist()
    {
        new FilesController($this->folderName);
        new FilesController($this->folderName);
        $this->assertFileExists($this->folderName);
    }

    public function testCreateFolderFunction()
    {
        $fileController = new FilesController($this->folderName);
        $result = $fileController->createFolder("imgs");
        $this->assertFileExists($this->folderName . DIRECTORY_SEPARATOR . "imgs");
        $this->assertTrue($result);
    }

    public function testCreateRecursiveFolderFunction()
    {
        $fileController = new FilesController($this->folderName);
        $result = $fileController->createFolder("imgs/imgs2");
        $this->assertFileExists($this->folderName . DIRECTORY_SEPARATOR . "imgs" . DIRECTORY_SEPARATOR . "imgs2");
        $this->assertTrue($result);
    }

    public function testMoveFolder()
    {
        $fileController = new FilesController($this->folderName);
        $result1 = $fileController->createFolder("imgs");
        $result2 = $fileController->createFolder("imgs2");
        $this->assertFileNotExists($this->folderName . DIRECTORY_SEPARATOR . "imgs" .  DIRECTORY_SEPARATOR . "imgs2");
        $fileController->move("imgs2", "imgs");
        $this->assertFileExists($this->folderName . DIRECTORY_SEPARATOR . "imgs" .  DIRECTORY_SEPARATOR . "imgs2");
    }

    public function testMoveFolderFail()
    {
        $fileController = new FilesController($this->folderName);
        $result1 = $fileController->createFolder("imgs/imgs2");
        $result2 = $fileController->createFolder("imgs2");
        $this->assertFileExists($this->folderName . DIRECTORY_SEPARATOR . "imgs" .  DIRECTORY_SEPARATOR . "imgs2");
        $result = $fileController->move("imgs2", "imgs");
        $this->assertFalse($result);
    }
    public function testlist()
    {
        $fileController = new FilesController($this->folderName);
        $result1 = $fileController->createFolder("imgs/imgs2");
        $result2 = $fileController->createFolder("imgs2");
        file_put_contents(
            $this->folderName .  DIRECTORY_SEPARATOR .  "imgs2" .  DIRECTORY_SEPARATOR . "test.txt",
            "coucou"
        );
        $actual = $fileController->list();
        $this->assertIsArray($actual);
        $this->assertIsArray($actual["imgs"]);
        $this->assertIsArray($actual["imgs2"]);
        $this->assertIsArray($actual["imgs"]["imgs2"]);
        $this->assertContains("test.txt", $actual["imgs2"]);
    }

    public function testUpladFile()
    {
        $file = dirname(dirname(__DIR__)) .  DIRECTORY_SEPARATOR . 'test.png';
        $_FILES = [
            'filename' => [
                'name' => $file,
                'type' => 'image/png',
                'size' => \filesize($file),
                'tmp_name' => $file,
                'error' => 0
            ]
        ];
        $fileController = new FilesController($this->folderName, true);
        $nameTest = $fileController->uploadFile("img");
        $actual = $fileController->list();
        $this->assertTrue(in_array($nameTest[0]["Basename"], $actual["img"]));
    }
}
