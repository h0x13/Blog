<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Image extends BaseController
{
    function image($filename)
    {
        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $mimeType = mime_content_type($path);
        return $this->response->setHeader('Content-Type', $mimeType)->setBody(file_get_contents($path));
    }
}