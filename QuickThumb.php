<?php
namespace marianojwl {
    class QuickThumb {
        public static function make(string $path_origin) {
            $fileInfo = pathinfo($path_origin);
            $sufix = '_thumb';
            $path_result = $fileInfo['dirname'] .  $fileInfo['filename'] . $sufix . '.' . $fileInfo['extension'];
            $gd = self::imageCreate($path_origin, $fileInfo['extension']);
            if (self::createThumb($gd, $path_result, $fileInfo['extension']) )
                return $path_result;
        }
        private static function imageCreate($path, $ext){
            switch($ext) {
                case "jpg":
                case "jpeg":
                    return imagecreatefromjpeg($path);
                case "png":
                    return imagecreatefrompng($path);
                case "gif":
                    return imagecreatefromgif($path);
                case "bmp":
                    return imagecreatefrombmp($path);
            }
        }
        private static function imageSave($gd, $path, $ext){
            switch($ext) {
                case "jpg":
                case "jpeg":
                    return imagejpeg($gd, $path);
                case "png":
                    return imagepng($gd, $path);
                case "gif":
                    return imagegif($gd, $path);
                case "bmp":
                    return imagebmp($gd, $path);
            }
        }

        private static function createThumb($originalImage, $path_result, $ext, $thumbWidth = 100, $thumbHeight = null ) {
            $originalWidth = imagesx($originalImage);
            $originalHeight = imagesy($originalImage);
            $aspectRatio = $originalWidth / $originalHeight;

            if($thumbHeight) {
                    $newWidth = $thumbHeight * $aspectRatio;
                    $newHeight = $thumbHeight;
            } else {
                    $newWidth = $thumbWidth;
                    $newHeight = $newWidth / $aspectRatio;
            }
        
            // Create a new GD image resource for the resized image
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
            // Use a better interpolation method for resizing
            imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            $result = self::imageSave($resizedImage, $path_result, $ext);
            
            imagedestroy($originalImage);
            imagedestroy($resizedImage);

            return $result;
        }
    }
}