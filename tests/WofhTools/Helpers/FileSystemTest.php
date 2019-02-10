<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace WofhTools\Helpers;


use PHPUnit\Framework\TestCase;


class FileSystemTest extends TestCase
{

    /** @var FileSystem */
    private $fs;

    /** @var string */
    private $root;

    /** @var string */
    private $source;

    /** @var string */
    private $dest;


    public function setUp()
    {
        $this->root = realpath(__DIR__.'/../../');
        $this->source = $this->root.'/assets/example.com.txt';
        $this->dest = $this->root.'/assets/tmp/save.tmp.txt';

        $this->fs = new FileSystem($this->root);

        if (!file_exists($this->root.'/assets/tmp')) {
            mkdir($this->root.'/assets/tmp');
        }
    }


    public function tearDown()
    {
        $this->fs = null;
        if (file_exists($this->dest)) {
            unlink($this->dest);
        }
        if (file_exists($this->root.'/assets/tmp')) {
            rmdir($this->root.'/assets/tmp');
        }
    }


    public function testRoot()
    {
        $this->assertEquals($this->root, $this->fs->root(), 'Return root directory without slash');
        $this->assertEquals($this->root, $this->fs->path(''),
            'Return root directory without slash');
    }


    public function testPath()
    {
        $ds = DIRECTORY_SEPARATOR;
        $tests = [
            ['/assets/example.com.txt', 'File with start slash'],
            ['assets/example.com.txt', 'File without start slash'],
            ['/assets/', 'Directory with both slashes'],
            ['assets/', 'Directory with end slash'],
            ['/assets', 'Directory with start slash'],
            ['assets', 'Directory without slashes'],
            ['assets/deep', 'Directory deep without slashes'],
        ];

        foreach ($tests as $test) {
            $expected = $ds.str_replace('/', $ds, trim($test[0], '/'));
            $this->assertEquals($expected, $this->fs->path($test[0], false), 'Relative: '.$test[1]);
        }

        foreach ($tests as $test) {
            $expected = $this->root.$ds.str_replace('/', $ds, trim($test[0], '/'));
            $this->assertEquals($expected, $this->fs->path($test[0]), 'Absolute: '.$test[1]);
        }
    }


    public function testJoin()
    {
        $this->assertEquals('', $this->fs->join());
        $this->assertEquals(
            DIRECTORY_SEPARATOR.join(DIRECTORY_SEPARATOR, ['assets']),
            $this->fs->join('assets')
        );
    }


    public function testResolve()
    {
        $this->assertEquals(
            $this->root.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'example.com.txt',
            $this->fs->resolve('assets/example.com.txt')
        );
    }


    public function testNotResolve()
    {
        $this->expectException(FileSystemException::class);
        $this->fs->resolve('assets/not-exists.file');
    }


    public function testReadFile()
    {
        $filename = $this->root.'/assets/example.com.txt';
        $this->assertStringEqualsFile($filename, $this->fs->readFile($filename));
    }


    public function testReadFileFail()
    {
        $this->expectException(FileSystemException::class);
        $this->fs->readFile($this->root.'/assets/not-exists.file');
    }


    public function testSaveFile()
    {
        $content = $this->fs->readFile($this->source);
        $this->fs->saveFile($this->dest, $content);

        $this->assertFileEquals($this->source, $this->dest);
    }


    public function testSaveFileFail()
    {
        $dest = $this->root.'/assets/tmp/nn/save.tmp.txt';
        $content = $this->fs->readFile($this->source);
        $this->expectException(FileSystemException::class);
        $this->fs->saveFile($dest, $content);
    }
}
