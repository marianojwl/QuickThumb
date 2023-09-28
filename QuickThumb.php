<?php
namespace marianojwl\QuickThumb {
    class QuickThumb {
        public static function make(string $path_origin) {
            // Get information about the original file
            $fileInfo = pathinfo($path_origin);
            
            // Define the suffix for the thumbnail
            $suffix = '_thumb';
            
            // Generate the path for the thumbnail
            $path_result = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . $suffix . '.' . $fileInfo['extension'];

            // Create an image resource from the original image
            $gd = self::imageCreate($path_origin, $fileInfo['extension']);

            // If thumbnail creation is successful, return the path; otherwise, return null
            if (self::createThumb($gd, $path_result, $fileInfo['extension'])) {
                return $path_result;
            } else {
                return null;
            }
        }

        private static function imageCreate($path, $ext) {
            // Create an image resource based on the file extension
            switch($ext) {
                case "jpg":
                case "jpeg":
                    return imagecreatefromjpeg($path);
                case "png":
                    return imagecreatefrompng($path);
                case "gif":
                    return imagecreatefromgif($path);
                default:
                    // Handle unsupported image types gracefully
                    return null;
            }
        }

        private static function imageSave($gd, $path, $ext) {
            // Save the image resource to a file based on the file extension
            switch($ext) {
                case "jpg":
                case "jpeg":
                    return imagejpeg($gd, $path);
                case "png":
                    return imagepng($gd, $path);
                case "gif":
                    return imagegif($gd, $path);
                default:
                    return false;
            }
        }

        private static function createThumb($originalImage, $path_result, $ext, $thumbWidth = 100, $thumbHeight = null ) {
            // Get the dimensions of the original image
            $originalWidth = imagesx($originalImage);
            $originalHeight = imagesy($originalImage);

            // Calculate the aspect ratio
            $aspectRatio = $originalWidth / $originalHeight;

            if ($thumbHeight) {
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

            // Save the resized image
            $result = self::imageSave($resizedImage, $path_result, $ext);

            // Destroy the image resources to free up memory
            imagedestroy($originalImage);
            imagedestroy($resizedImage);

            return $result;
        }
    }

}