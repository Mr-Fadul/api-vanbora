<?php

namespace AppBundle\Service;

class UploadService {

   public static function compress($source, $destination, $quality) {
      $info = getimagesize($source);
      if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);
      elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);
      elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

      imagejpeg($image, $destination, $quality);
      return $destination;
    }

    public static function validation($source, $destination)
    {
      if ($source->getMimeType() == 'application/msword' || $source->getMimeType() == 'application/pdf' || $source->getMimeType() == 'application/x-pdf') {
          move_uploaded_file($source->getPathname(), $destination);
      } else {
        throw new \Exception("Formato inválido!");
      }

      return $destination;

    }
}
