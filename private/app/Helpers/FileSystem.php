<?php

namespace WofhTools\Helpers;


/**
 * Класс для работы с файловой системой
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2014–2016 delphinpro
 * @license     Licensed under the MIT license
 *
 * @package     WofhTools\Helpers
 */
class FileSystem
{
    /** @var string */
    private $root;


    public function __construct(string $root)
    {
        $this->root = rtrim($root, '\\/');
    }


    /**
     * @return string
     */
    public function root(): string
    {
        return $this->root;
    }


    /**
     * Преобразует относительный путь (from root project) в абсолютный
     *
     * @param string $path     Относительный путь
     * @param bool   $absolute Преобразовать к абсолютному (по умолчанию)
     *
     * @return mixed|string Абсолютный путь в файловой системе
     */
    public function path($path, $absolute = true)
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, trim($path, '\\/'));

        if (!empty($path)) {
            $path = DIRECTORY_SEPARATOR.$path;
        }

        if ($absolute) {
            $path = $this->root.$path;
        }

        return $path;
    }


    /**
     * @param array $paths
     *
     * @return string
     */
    public function join(...$paths)
    {
        if (empty($paths)) {
            return '';
        }

        $paths = array_map(function ($path) { return trim($path, '\\/'); }, $paths);

        return DIRECTORY_SEPARATOR.join($paths, DIRECTORY_SEPARATOR);
    }


    /**
     * @param $path
     *
     * @return string
     * @throws FileSystemException
     */
    public function resolve($path)
    {
        $absolutePath = $this->path($path);
        $resolvedPath = realpath($absolutePath);

        if ($resolvedPath === false) {
            throw new FileSystemException('File or directory not exists: '.$absolutePath);
        }

        return $resolvedPath;
    }


    /**
     * Читает файл и возвращает его содержимое
     *
     * @param string $filename Абсолютный путь к файлу
     *
     * @return string Прочтенные данные
     * @throws FileSystemException
     */
    public function readFile($filename)
    {
        if (!file_exists($filename)) {
            throw new FileSystemException('File not exists: '.$filename);
        }

        $fileContent = file_get_contents($filename);

        if ($fileContent === false) {
            throw new FileSystemException('File not exists: '.$filename);
        }

        return $fileContent;
    }


    /**
     * Записывает данные в файл, с установкой прав доступа
     *
     * @param string $filename Абсолютный путь к файлу
     * @param string $content  Данные для записи в файл
     * @param int    $perms    Права доступа (0777 по умолчанию)
     *
     * @return int Количество записанных байт в файл
     * @throws FileSystemException
     */
    public function saveFile($filename, $content, $perms = 0777)
    {
        $directory = dirname($filename);

        if (!file_exists($directory)) {
            throw new FileSystemException('FileSystem::saveFile(): Directory not found: '.$directory);
        }

        $status = file_put_contents($filename, $content);

        if ($status === false) {
            throw new FileSystemException('FileSystem::saveFile(): Error save file');
        }

        if (!chmod($filename, $perms)) {
            throw new FileSystemException('FileSystem::saveFile(): Error change permissions');
        }

        return $status;
    }


    /**
     * Создает директорию
     *
     * @param string $dir       Абсолютный путь к директории
     * @param int    $perms     Права доступа, устанавливаемые на созданную директорию
     * @param bool   $recursive Позволяет рекурсивно создать все вложенные директории
     *
     * @throws FileSystemException
     */
    public function mkdir($dir, $perms = 0777, $recursive = true)
    {
        if ($recursive) {
            $chunks = explode(DIRECTORY_SEPARATOR, $dir);
            $path = array_shift($chunks);
            $chunks = array_filter($chunks);

            foreach ($chunks as $chunk) {
                $path .= DIRECTORY_SEPARATOR.$chunk;

                if (!is_dir($path)) {
                    $status = mkdir($path, $perms);

                    if ($status === false) {
                        throw new FileSystemException('FileSystem::mkdir(): Error make directory: '.$path);
                    }

                    chmod($dir, $perms);
                }
            }
        } else {
            $status = mkdir($dir, $perms, false);
            if ($status) {
                throw new FileSystemException('FileSystem::mkdir(): Error make directory: '.$dir);
            }
            chmod($dir, $perms);
        }
    }


    public function scanDir($directory, $dot = false)
    {
        $files = scandir($directory, SCANDIR_SORT_ASCENDING);
        if (!$dot) {
            $files = array_filter($files,
                function ($item) {
                    return $item !== '..' && $item !== '.';
                });
        }

        return $files;
    }


    /**
     * Читает и декодирует json-файл
     *
     * @param Json   $json
     * @param string $filename Путь к файлу
     * @param bool   $assoc    Возвращает данные в виде ассоциативного массива, если true, или в
     *                         виде объекта, если false
     *
     * @return mixed|null Декодированные данные из файла
     * @throws FileSystemException
     * @throws JsonCustomException
     */
    public function loadJson(Json $json, $filename, $assoc = true)
    {
        if (!is_file($filename)) {
            throw new FileSystemException('FileSystem::loadJson(): File is not a file: '.$filename);
        }

        $jsonString = $this->readFile($filename);
        $jsonData = $json->decode($jsonString, $assoc);

        return $jsonData;
    }
}
