<?php 
$_SERVER["DOCUMENT_ROOT"]=dirname(__DIR__);
$idUsuario = $_GET['id'];

require_once  '../includes/clases/Persona.php';
require_once  'objetos.php';
require_once  '../includes/php/conexion.php';

$dbh = conectarDB();

$query = "select u.idusuario ,u.nombre as usuario ,per.apellido ,per.nombre from usuario u inner join persona per using(idpersona) where u.idusuario = :idUsuario";

$stmt = $dbh->prepare($query);
$stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetchObject();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>Eliminar Usuario</title>
	<link type="text/css" rel="stylesheet" href="/tp5/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once  '../includes/php/header.php' ?>
 

	
	<form action="eliminar_usuario.php" method="post">
		
		<input type="hidden" name="idUsuario" value="<?= $idUsuario ?>">

		<div class="mensaje">

			<h3>Eliminar usuario</h3>
			<p>
			¿Realmente desea eliminar el usuario <b><?= $resultado->usuario ?></b> perteneciente a <b><?= $resultado->apellido ?>, <?= $resultado->nombre ?></b>?
			</p>

			<div class="buttons">
				<input type="button" value="No" onclick="document.location='../administrador'">
				<input type="submit" name="bt_eliminar" value="Si" onclick="document.location='../administrador'" >
			</div>
		</div>

	</form>
	<?php require_once 'opcionesAdm.php'; ?>
	<div class="push"></div>
	
</div>

<?php require_once  '../includes/php/footer.php'; ?>
</body>
</html>