<?php

namespace App\Controller;

class ImageController
{
    public $code = false;

    public $link;

    public $name;

    public $publicDir;

    public $targetDir;

    public function __construct(String $path)
    {
        $this->targetDir = dirname(__FILE__) . '/../../public' . $path;
    }

    public function create_dir($folder)
    {
        if (!is_dir($this->targetDir . '/' . $folder)) {
            mkdir($this->targetDir . '/' . $folder);
        }

        return $folder;
    }

    public function delete(String $file) { return !is_file($this->targetDir . '/' . $file) ?: unlink($this->targetDir . '/' . $file) ; }

    public function upload(Array $file, String $folder)
    {
        if(!empty($file)){
            $tempFile   = $file['file']['tmp_name'];
            $this->name = explode('.', $file['file']['name']);    
            $this->name = $folder . date('YmdHis') . rand(1, intval(date('Hisu'))) . '.' . $this->name[1];        
            $newFile    = $this->targetDir . '/' . $this->create_dir($folder) . '/' . $this->name;
            $this->code = move_uploaded_file($tempFile, $newFile);
        }
    }
}
?>