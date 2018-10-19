<?php
namespace BBDO\Cms\Helpers;

use Intervention\Image\Facades\Image;

class FileUpload{


    public static function saveFile($file,$destination){

        $teller = '';
        $filename = '';

        if($file!=null){
            while(file_exists(public_path().'/uploads/'.$destination.'/'.'file'.$teller.'_'.($file->getClientOriginalName()))){
                $teller = $teller == '' ? 1:$teller+1;
            }

            $filename = 'file'.$teller.'_'.($file->getClientOriginalName());

            $file->move(public_path().'/uploads/'.$destination.'/',$filename);

            return $filename;
        }

        return '';

    }
}