<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FileController extends Controller
{
    public function serve($folder = null, $subfolder = null, $filename = null)
    {
        if (!$folder || !$subfolder) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Missing file.");
        }

        // If $filename is null, then $subfolder is actually the filename
        if ($filename === null) {
            $filename = $subfolder;
            $path = WRITEPATH . 'uploads/' . $folder . '/' . $filename;
        } else {
            $path = WRITEPATH . 'uploads/' . $folder . '/' . $subfolder . '/' . $filename;
        }

        if (!is_file($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Image not found.");
        }

        return $this->response
                    ->setHeader('Content-Type', mime_content_type($path))
                    ->setBody(file_get_contents($path));
    }
}

?>