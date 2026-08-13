<?php

namespace Core;

class File
{
    public static function get($file)
    {
        return (!empty($_FILES[$file]['name'])) ? $_FILES[$file]['name'] : false;
    }

    public static function getFileExtension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function createUniqueFilename()
    {
        return "upload_" . time() . Hash::random(8);
    }

    public static function moveFile($file, $folder, $name)
    {
        if(move_uploaded_file($_FILES[$file]['tmp_name'], UPLOAD_ROOT . "/$folder/$name")) {
            return true;
        }

        return false;
    }

    public static function removeFile($path)
    {
        if(file_exists($path)) {
            unlink($path);

            return true;
        }
    }

    public static function downloadFile($path)
    {
        if(file_exists($path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '. filesize($path));

            flush();

            readfile($path);

            return true;
        }

        return false;
    }
}
