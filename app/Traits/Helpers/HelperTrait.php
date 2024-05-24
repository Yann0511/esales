<?php

namespace App\Traits\Helpers;

use Illuminate\Support\Facades\Storage;

trait HelperTrait
{


    public function storeFile($file, $document_path, $model)
    {
        if (is_array($file)) {
            $path = [];

            foreach ($file as $f) {
                $filenameWithExt = $f->getClientOriginalName();
                $filename = strtolower(str_replace(' ', '-', time() . '-' . $filenameWithExt));
                $p = $document_path . $filename;
                Storage::disk('public')->put($p, $f->getContent());
                array_push($path, $document_path . $filename);
            }

            $path = implode(", ", $path);
        } else {
            $filenameWithExt = $file->getClientOriginalName();
            $filename = strtolower(str_replace(' ', '-', time() . '-' . $filenameWithExt));
            $path = $document_path . $filename;
            Storage::disk('public')->put($path, $file->getContent());
        }


        return $path;
    }

    public function storeFileLocal($file, $document_path, $model)
    {
        $filenameWithExt = $file->getClientOriginalName();
        $filename = strtolower(str_replace(' ', '-', time() . '-' . $filenameWithExt));
        $path = $document_path . "/" . $filename;
        Storage::disk('local')->put($path, $file->getContent());

        return $path;
    }
}
