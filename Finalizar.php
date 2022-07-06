<?php

$_SERVER["DOCUMENT_ROOT"]=dirname(__DIR__);
require_once 'includes/clases/Persona.php';
require_once 'includes/php/conexion.php';


session_start();

$dbh= conectarDB();

$oPersona = $_SESSION['Persona'];

$dbh->beginTransaction();

try
{


$query = "insert into persona (idtipodocumento,apellido,nombre,numerodocumento,sexo,nacionalidad,email,telefono,celular,provincia,localidad)
			values (:idTipoDocumento,
			:apellido,
			:nombre,
			:documento,
			:sexo,
			:nacionalidad,
			:email,
			:telefono,
			:celular,
			:provincia,
			:localidad)";

	$stmt = $dbh->prepare($query);

	$stmt->bindValue(':idTipoDocumento', $oPersona->getTipoDocumento()->getIdTipoDocumento(),PDO::PARAM_INT);
	$stmt->bindValue(':apellido', $oPersona->getApellido(),PDO::PARAM_STR);
	$stmt->bindValue(':nombre', $oPersona->getNombre(),PDO::PARAM_STR);
	$stmt->bindValue(':documento', $oPersona->getNumeroDocumento(),PDO::PARAM_INT);
	$stmt->bindValue(':sexo', $oPersona->getSexo()->getIdSexo(),PDO::PARAM_STR);
	$stmt->bindValue(':nacionalidad', $oPersona->getNacionalidad(),PDO::PARAM_STR);
	$stmt->bindValue(':email', $oPersona->getEmail()->getValor(),PDO::PARAM_STR);
	$stmt->bindValue(':telefono', $oPersona->getTelefono()->getValor(),PDO::PARAM_STR);
	$stmt->bindValue(':celular', $oPersona->getCelular()->getValor(),PDO::PARAM_STR);
	$stmt->bindValue(':provincia', $oPersona->getProvincia()->getDescripcion(),PDO::PARAM_STR);
	$stmt->bindValue(':localidad', $oPersona->getLocalidad(),PDO::PARAM_STR);

	$stmt->execute();

	$idPersona = $dbh->lastInsertId();

	$query = "insert into usuario (idpersona,idtipousuario,nombre,contrasenia)
			values(:idPersona,2,:usuario,:contrasenia)";

	$stmt = $dbh->prepare($query);

	$stmt->bindValue(':idPersona',$idPersona,PDO::PARAM_INT);
	$stmt->bindValue(':usuario',$oPersona->getUsuario()->getNombre(),PDO::PARAM_STR);
	$stmt->bindValue(':contrasenia',$oPersona->getUsuario()->getContrasenia(),PDO::PARAM_STR);

	$stmt->execute();

	$dbh->commit();

	session_destroy();
	header('location: Paso1.php');
}
catch (PDOException $e) {
	 	$dbh->rollBack();
		echo 'Error: ' . $e->getMessage();
	 }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Formulario de Inscripc&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once 'includes/php/header.php'; ?>
	<?php require_once 'opciones.php'; ?>

	<div class="mensaje">
		<h3>No se Pudo registrar el Usuario</h3>
		<p>
			Error al intentar registrar el usuario. <br>
            Por favor intentelo nuevamente.
		</p>
		<div class="buttons">
			<input type="button" value="Anterior" onclick="document.location='Paso3.php'">
		</div>
	</div>

	<div class="push"></div>

</div>

<?php require_once 'includes/php/footer.php'; ?>
</body>
</html>