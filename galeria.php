<html>
	<head>
		<meta charset="utf-8">
		<title>Galería en subcarpetas</title>
		<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
		<script src="js/jquery-1.7.2.min.js"></script>
		<script src="js/lightbox.js"></script>
	</head>
	<body>
<?php
# Galería de imágenes
# (CC) Alfonso Saavedra "Son Link"
# Bajo GPLv3

# Configuración del script
# Directorio donde están las imágenes. NO usar rutas relativas (p.e: ../galeria o ./galeria)
#ini_set("display_errors", 1);
$path = 'fotos';
$multi = false; #Poner a true si quieres usar subcarpetas
$limit = 20; # Cuantas imáenes se mostraran por pagina
$limit_file = 5; # Imágenes a mostrar por linea en la tabla

function view_gallery($folder){
	global $limit, $limit_file, $path, $multi;
	$desde;
	$hasta;
	$list = Array();
	# Recorremos los ficheros que hay en el directorio y cogemos solamente aquellos cuya extensión
	# sea jpg, gif y png y la guardamos en una lista
	$images = glob("$path/*",  GLOB_BRACE);
	if ($images){
		foreach($images as $image) {
			if (preg_match("#([\w\s]+)\.(gif|GIF|jpg|JPG|png|PNG)#is",$image)){
				$list[] = $image;
			}
		}

		# Contamos el total de elementos en la lista
		$total = count($list);
		if ($total > 0){
			$paginas = ceil($total/$limit);
			if (!isset($_GET['pg'])){
				$desde = 0;
				$hasta = $desde + $limit;
			}else if((int)$_GET['pg'] > ($paginas-1)){
				# Si pg es mayor que el total de paginas se muestra un error
				print "<b>No existe esta pagina en la galería</b>
				<a href='index.php'>Volver a la galería</a>";
				die();
			}else{
				$desde = (int)$_GET['pg'];
			}
			# Y generamos los enlaces con los thumbnails
			print "<table>\n<tr>";
			$n = 0;
			for ($i=($desde*$limit);($i<=$total) && ($i<($desde*$limit)+$limit);$i++){
			# Comprobamos si existe en la lista una llave con el valor actual de $i para evitar errores
				if(array_key_exists($i, $list)){
					print "<td><a href='$list[$i]' ><img src='thumb.php?img=$list[$i]' /></a>
					</td>\n";
					$n++;
					if ($n == $limit_file){
						print "</tr>\n<tr>\n";
						$n = 0;
					}
				}
			}
			print "</tr>\n</table>\n<p id=\"paginas\">";

			# Generamos un listado de las paginas de la galería
			for ($p = 0; $p<$paginas; $p++){
				$pg = $p+1;
				if ($p == $desde){
					print "$pg ";
				}else{
					if ($multi == true){
						print "<a href ='?gal={$_GET['gal']}&pg=$p'>$pg</a> ";
					}else{
						print "<a href ='?pg=$p'>$pg</a> ";
					}
				}
			}
			print "</p>\nHay un total de $total imagen(es) en $paginas paginas(s)";
		}else{
			print "No hay ninguna imagen en $path, están dañadas o no están soportadas";
		}
	}else{
		print "No hay ninguna imagen en $path, están dañadas o no están soportadas";
	}
}

if (is_dir($path)){
	if ($multi == false){
		if (!isset($_GET['gal'])){
			view_gallery($path);
		}else{
			print 'Esta galería no existe';
		}
	}else if ($multi == true){
		if (!isset($_GET['gal'])){
			$folders = glob("$path/*", GLOB_ONLYDIR);
			if ($folders){
				foreach($folders as $folder) {
					$folder = preg_replace("/$path\//" ,'', $folder);
					print "<a href=\"?gal=$folder\">" . $folder . "</a><br />\n";
				}
			}else{
				print "$path no contiene ninguna subcarpeta";
			}
		}else{
			$dir = $path.'/'.$_GET['gal'];
			view_gallery($dir);
		}
	}
}else{
	print "$path no es un directorio";
}
?>
	</body>
</html>
