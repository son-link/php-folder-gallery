<?php
# Generador de Thumbnails para galerías de imágenes
# (CC) Alfonso Saavedra "Son Link"
# Bajo GPLv3

if (!empty($_GET['img'])){
	$size  = 150; // Tamaño a definir (ancho)
	$square = true;
	$img = $_GET['img'];
	if ( !preg_match_all('[^\/|\.\/|\.\.\/]', $img) && is_file($img)){
		$img_info = pathinfo($img);
		# obtenemos las extensiones de los archivos para llamar a la función correspondiente
		$ext = strtolower($img_info['extension']);
		if (eregi('jpg', $ext) || eregi('gif', $ext) || eregi('png', $ext)){
			if (eregi('jpg', $ext)){
				$image = ImageCreateFromJPEG($img);
				$format = 'jpg';
			}else if (eregi('gif', $ext) ){
				$image = ImageCreateFromGIF($img);
				$format = 'gif';
			}else if ( eregi('png', $ext) ){
		 		$image = ImageCreateFromPNG($img);
				$format = 'png';
			}

			# Obtenemos el ancho y el alto de la imagen
			$width  = imagesx($image) ;
			$height = imagesy($image) ;
			if ($width <= $size){
				imagedestroy($image);
				header("location: $img");
			}else{
				if ($square == true){
					$w_ratio = ($size / $width);
					$h_ratio = ($size / $height);

					if ($width > $height ) {
						$crop_w = round($width* $h_ratio);
						$crop_h = $size;
					} elseif ($width < $height ) {
						$crop_h = round($height * $w_ratio);
						$crop_w = $size;
					} else {
						$crop_w = $size;
						$crop_h = $size;
					}
					$thumb = imagecreatetruecolor($size,$size);
					imagecopyresampled($thumb, $image, 0 , 0 , 0, 0, $crop_w, $crop_h, $width, $height);
					# Si el ancho de la imagen es igual o menor del indicado en new_width redirigimos directamente a la imagen
				}else{
					# En caso contrario se crea el thumbnail
					$new_height = ($size * $height) / $width ; // tamaño proporcional
					$thumb = imagecreatetruecolor($size,$new_height);
					imagecopyresized($thumb,$image,0,0,0,0,$size,$new_height,$width,$height);
				}
				#mostramos la imagen generada
				if ($format == 'jpg'){
					header( "Content-type: image/jpeg" );
					ImageJPEG($thumb);
					imagedestroy($thumb);
				}else if ($format == 'gif'){
					header( "Content-type: image/gif" );
					ImageGIF($thumb);
					imagedestroy($thumb);
				}else if ($format == 'png'){
					header( "Content-type: image/png" );
					ImagePNG($thumb);
					imagedestroy($thumb);
				}
				# Y liberamos memoria
		 		imagedestroy($image);
			}
		}
	}
}
?>
