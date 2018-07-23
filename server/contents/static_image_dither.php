<?php
    require_once('libs/gd-indexed-color-converter/src/GDIndexedColorConverter.php');
    //Place your image in folder "static_image". This code will take a random image
    $contents = scandir('contents/static_image');
    if(!count($contents)){
        exit;
    }
    foreach ($contents as $content) {
        $contentFile = pathinfo($content);

        if($contentFile['extension'] == "png" OR $contentFile['extension'] == "jpg" OR $contentFile['extension'] == "jpeg"){
            $someFile['ext'] = $contentFile['extension'];
            $someFile['path'] = "contents/static_image/".$content;
            $allFiles[] = $someFile;
        }
    }

        $content = $allFiles[array_rand($allFiles)];

        if($content['ext'] == "png"){
            $imageSource = imagecreatefrompng($content['path']);

        }
        if($content['ext'] == "jpg" OR $content['ext'] == "jpeg" ){
            $imageSource = imagecreatefromjpeg($content['path']);

        }

        list($origW, $origH) = getimagesize($content['path']);

    $width = $origW;
    $height = $origH;

    $maxW = $displayWidth ;
    $maxH = $displayHeight;

    $Woffset=0;
    $Hoffset=0;

    if ($height > $maxH) {
        $width = ($maxH / $height) * $width;
        $height = $maxH;
    }

    if ($width > $maxW) {
        $height = ($maxW / $width) * $height;
        $width = $maxW;
    }

    if ($height < $displayHeight) {
	    $Hoffset=($displayHeight-$height)/2;
    }
    if ($width < $displayWidth) {
	    $Woffset=($displayWidth-$width)/2;
    }

    $convertor = new GDIndexedColorConvertor();
    $imageNew = imagecreate($displayWidth, $displayHeight);

    $background_color=ImageColorAllocate($imageNew, 255,255,255);
    imagefilledrectangle($imageNew, 0,0, $displayWidth, $displayHeight, $background_color);

    imagecopyresampled($imageNew, $imageSource, 0, 0, 0, 0, $width, $height, $origW, $origH);
    $ditherImage = $convertor->convertToIndexedColor($imageNew, $palette, .8);
    
    imagecopymerge($im, $ditherImage, $Woffset, $Hoffset, 0, 0, $displayWidth, $displayHeight, 100);

    imagedestroy($ditherImage);
    imagedestroy($imagenew);

?>
