<?php
$_SERVER["DOCUMENT_ROOT"]=dirname(__DIR__);
require_once  '../includes/clases/Persona.php';
require_once 'objetos.php';
require_once  '../includes/php/conexion.php';

if ( isset($_POST['bt_eliminar']) == true )
{
	$dbh = conectarDB();
	$idUsuario = ( isset($_POST['idUsuario']) == true ) ? $_POST['idUsuario'] : 0;

	$dbh->beginTransaction();

	try
	{
		$query = "select idpersona from usuario where idUsuario = :idUsuario";
		$stmt = $dbh->prepare($query);
		$stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
		$stmt->execute();
		$idPersona = $stmt->fetchColumn();

		$query = "delete from usuario where idUsuario = :idUsuario";

		$stmt = $dbh->prepare($query);

		$stmt->bindValue(':idUsuario', $idUsuario,PDO::PARAM_INT);

		$stmt->execute();

		$query = "delete from persona where idPersona = :idPersona";

		$stmt = $dbh->prepare($query);

		$stmt->bindValue(':idPersona',$idUsuario,PDO::PARAM_INT);

		$stmt->execute();

		$dbh->commit();

		session_destroy();
		header('location: administrador');
	}
	catch (Exception $e)
	{
		$dbh->rollBack();
	}
}

$validacionCorrecta = true;

foreach ( $validaciones as $validacion )
{
	if ( $validacion == false )
	{
		$validacionCorrecta = false;
		break;
	}
}


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

	<?php require_once  '../includes/php/header.php'; ?>
    <?php require_once 'opcionesAdm.php'; ?>


	<div class="mensaje">

		<h3>Error al registrar el usuario</h3>
		<p>
			Error al intentar registrar el usuario. <br>
            Por favor intentelo nuevamente.
		</p>

		<div class="buttons">
			<input type="button" value="Anterior" onclick="document.location='administrador'">
		</div>
	</div>

	<div class="push"></div>

</div>

<?php require_once '../includes/php/footer.php'; ?>
</body>
</html>