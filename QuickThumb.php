<?php
namespace marianojwl\QuickThumb {
    class QuickThumb {
        private static function checkMimeFromPathAndReturnExtension($path) {
            // Get the MIME type of the file eg: image/jpeg, path eg: https://docs.google.com/spreadsheets/d/1Vzwkcp5cFKeC4YShX3MzI1LpJSLydXv_siUUM7Hlmjc/edit?gid=0#gid=0
            try {
                $mime = @mime_content_type($path);
            } catch (Exception $e) {
                // Handle the exception
                return "png";
            }

            // Define an array of MIME types and their corresponding file extensions
            $mimeTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif'
            ];

            // Return the file extension based on the MIME type
            if(array_key_exists($mime, $mimeTypes)) {
                return $mimeTypes[$mime];
            } else {
                return "png";
            }
        }

        public static function makeFromString(string $imageData, string $dir, string $fileName, int $thumbWidth = 100) {
          /*
          // Get information about the original file
          $fileInfo = pathinfo($path_origin);
          $extension = $fileInfo['extension']??self::checkMimeFromPathAndReturnExtension($path_origin);
          
          // Define the suffix for the thumbnail
          $suffix = '_thumb';
          
          // Generate the path for the thumbnail
          $path_result = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . $suffix . '.' . $extension;
          */
          // Create an image resource from the original image
          $suffix = '_thumb';
          $extension = "png";
          $path_result = $dir . '/' . $fileName . $suffix . '.' . $extension;
          $gd = imagecreatefromstring($imageData);

          // If thumbnail creation is successful, return the path; otherwise, return null
          if (self::createThumb($gd, $path_result, $extension, $thumbWidth)) {
              return $path_result;
          } else {
              return null;
          }
      }

        public static function make(string $path_origin, int $thumbWidth = 100) {
            // Get information about the original file
            $fileInfo = pathinfo($path_origin);
            $extension = $fileInfo['extension']??self::checkMimeFromPathAndReturnExtension($path_origin);
            
            // Define the suffix for the thumbnail
            $suffix = '_thumb';
            
            // Generate the path for the thumbnail
            $path_result = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . $suffix . '.' . $extension;

            // Create an image resource from the original image
            $gd = self::imageCreate($path_origin, $extension);

            // If thumbnail creation is successful, return the path; otherwise, return null
            if (self::createThumb($gd, $path_result, $extension, $thumbWidth)) {
                return $path_result;
            } else {
                return null;
            }
        }

        private static function imageCreate($path, $ext) {
            // Create an image resource based on the file extension
            switch($ext) {
                case "jpg":
                case "JPG":
                case "jpeg":
                case "JPEG":
                    return imagecreatefromjpeg($path);
                case "png":
                case "PNG":
                    return imagecreatefrompng($path);
                case "gif":
                case "GIF":
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
                case "JPEG":
                case "JPG":
                    return imagejpeg($gd, $path);
                case "png":
                case "PNG":
                    return imagepng($gd, $path);
                case "gif":
                case "GIF":
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