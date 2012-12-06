<html>
	<head>
		<title>Galeria en subcarpetas</title>
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
ini_set("display_errors", 1);
$path = 'fotos';
$limit = 20; # Cuantas imáenes se mostraran por pagina
$limit_file = 5; # Imágenes a mostrar por linea en la tabla
$n = 0;
$desde;
$hasta;
$list = array();
# Comprobamos si es un directorio y si lo es nos movemos a el
if (is_dir($path)){
	if (!isset($_GET['gal'])){
		$folders = glob("$path/*", GLOB_ONLYDIR);
		if ($folders){
			foreach($folders as $folder) {
				print $folder;
				$folder = preg_replace("/$path\//" ,'', $folder);
				print "<a href=\"?gal=$folder\">" . $folder . "</a><br />\n";
			}
		}else{
			print "$path no contiene ninguna subcarpeta";
		}
	}else{
		$dir = $path.'/'.$_GET['gal'];
		# Recorremos los ficheros que hay en el directorio y cogemos solamente aquellos cuya extensión
		# sea jpg, gif y png y la guardamos en una lista
		foreach(glob("$dir/*.*" , GLOB_BRACE ) as $file) {
			if (preg_match("#([\w\s]+)\.(gif|GIF|jpg|JPG|png|PNG)#is",$file)){
				$list[] = $file;
			}
		}

		# Contamos el total de elementos en la lista
		$total = count($list);
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
			print "<a href ='?gal={$_GET['gal']}&pg=$p'>$pg</a> ";
		}
	}
	print "</p>\nHay un total de $total imagen(es) en $paginas paginas(s)";
	}
}else{
	print "$path no es un directorio";
}
?>
	</body>
</html>
