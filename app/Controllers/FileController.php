<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FileController extends Controller
{
    public function serve($folder = null, $filename = null)
    {
        if (!$folder || !$filename) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Missing file.");
        }

        $path = WRITEPATH . 'uploads/' . $folder . '/' . $filename;

        if (!is_file($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Image not found.");
        }

        return $this->response
                    ->setHeader('Content-Type', mime_content_type($path))
                    ->setBody(file_get_contents($path));
    }
}

?>