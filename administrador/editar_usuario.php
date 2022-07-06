<?php
$_SERVER["DOCUMENT_ROOT"]=dirname(__DIR__);
require_once '../includes/clases/Persona.php';
require_once 'objetos.php';
require_once '../includes/php/conexion.php';

$persona = new Persona();

$validar = array(
	'validarTipoDocumento' => false,
	'validarSexo' => false,
	'validarContrasenia' => false,
	'validarProvincia' => false,
	'validarEmail' => false,
	'validarTelefono' => false,
	'validarCelular' => false,
);

if ( isset($_POST['bt_guardar']) == true )
{
	$idUsuario = ( isset($_POST['idUsuario']) == true ) ? $_POST['idUsuario'] : 0;
	$usuario = ( isset($_POST['nombre_usuario']) == true ) ? $_POST['nombre_usuario'] : '';
	$contrasenia = ( isset($_POST['contrasenia']) == true ) ? $_POST['contrasenia'] : '';
	$apellido = ( isset($_POST['apellido']) == true ) ? $_POST['apellido'] : '';
	$nombre = ( isset($_POST['nombre']) == true ) ? $_POST['nombre'] : '';
	$tipoDocumento = ( isset($_POST['tipo_documento']) == true ) ? $_POST['tipo_documento'] : '';
	$documento = ( isset($_POST['numero_documento']) == true ) ? $_POST['numero_documento'] : '';
	$sexo = ( isset($_POST['sexo']) == true ) ? $_POST['sexo'] : '';
	$nacionalidad = ( isset($_POST['nacionalidad']) == true ) ? $_POST['nacionalidad'] : '';
	$tipoUsuario = ( isset($_POST['tipo_usuario']) == true ) ? $_POST['tipo_usuario'] : 2;

	$email = ( isset($_POST['email']) == true ) ? $_POST['email'] : '';
	$telefono = ( isset($_POST['telefono']) == true ) ? $_POST['telefono'] : '';
	$celular = ( isset($_POST['celular']) == true ) ? $_POST['celular'] : '';
	$provincia = ( isset($_POST['provincia']) == true ) ? $_POST['provincia'] : '';
	$localidad = ( isset($_POST['localidad']) == true ) ? $_POST['localidad'] : '';

	$oUsuario = new Usuario($usuario, $contrasenia);

	if ( $oUsuario->validarContrasenia() == true )
	{
		$validar['validarContrasenia'] = true;
		$persona->setUsuario($oUsuario);
	}

	foreach ( $aTipoDocumento as $oTipoDocumento )
	{
		if ( $tipoDocumento == $oTipoDocumento->getIdTipoDocumento() )
		{
			$persona->setTipoDocumento($oTipoDocumento);
			$validar['validarTipoDocumento'] = true;
		}
	}

	foreach ( $aSexo as $oSexo )
	{
		if ( $sexo == $oSexo->getIdSexo() )
		{
			$persona->setSexo($oSexo);
			$validar['validarSexo'] = true;
		}
	}

	foreach ( $aProvincia as $oProvincia )
	{
		if ( $oProvincia->getIdProvincia() == $provincia )
		{
			$validar['validarProvincia'] = true;
			$persona->setProvincia($oProvincia);
		}
	}

	$oEmail = new Contacto(Contacto::TIPO_EMAIL, $email);
	if ( $oEmail->validar() == true )
	{
		$validar['validarEmail'] = true;
		$persona->setEmail($oEmail);
	}

	$oTelefono = new Contacto(Contacto::TIPO_TELEFONO, $telefono);
	if ( $oTelefono->validar() == true )
	{
		$validar['validarTelefono'] = true;
		$persona->setTelefono($oTelefono);
	}

	$oCelular = new Contacto(Contacto::TIPO_TELEFONO, $celular);
	if ( $oCelular->validar() == true )
	{
		$validar['validarCelular'] = true;
		$persona->setCelular($oCelular);
	}

	$persona->setApellido($apellido);
	$persona->setNombre($nombre);
	$persona->setNumeroDocumento($documento);
	$persona->setNacionalidad($nacionalidad);
	$persona->setLocalidad($localidad);
}

$validarCorrectas = true;

foreach ( $validar as $validacion )
{
	if ( $validacion == false )
	{
		$validarCorrectas = false;
		break;
	}
}

if ( $validarCorrectas == true )
{
	$dbh = conectarDB();
	$dbh->beginTransaction();

	try
	{
		$query = "select idPersona from usuario where idUsuario = :idUsuario";
		$stmt = $dbh->prepare($query);
		$stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
		$stmt->execute();
		$idPersona = $stmt->fetchColumn();

		$query = "update persona set
				idTipoDocumento = :idTipoDocumento,
				apellido = :apellido,
				nombre = :nombre,
				numeroDocumento = :documento,
				sexo = :sexo,
				nacionalidad = :nacionalidad,
				email = :email,
				telefono = :telefono,
				celular = :celular,
				provincia = :provincia,
				localidad = :localidad
			where
				idPersona = :idPersona";

		$stmt = $dbh->prepare($query);

		$stmt->bindValue(':idTipoDocumento', $persona->getTipoDocumento()->getIdTipoDocumento(),PDO::PARAM_INT);
		$stmt->bindValue(':apellido', $persona->getApellido(),PDO::PARAM_STR);
		$stmt->bindValue(':nombre', $persona->getNombre(),PDO::PARAM_STR);
		$stmt->bindValue(':documento', $persona->getNumeroDocumento(),PDO::PARAM_INT);
		$stmt->bindValue(':sexo', $persona->getSexo()->getIdSexo(),PDO::PARAM_STR);
		$stmt->bindValue(':nacionalidad', $persona->getNacionalidad(),PDO::PARAM_STR);
		$stmt->bindValue(':email', $persona->getEmail()->getValor(),PDO::PARAM_STR);
		$stmt->bindValue(':telefono', $persona->getTelefono()->getValor(),PDO::PARAM_STR);
		$stmt->bindValue(':celular', $persona->getCelular()->getValor(),PDO::PARAM_STR);
		$stmt->bindValue(':provincia', $persona->getProvincia()->getDescripcion(),PDO::PARAM_STR);
		$stmt->bindValue(':localidad', $persona->getLocalidad(),PDO::PARAM_STR);
		$stmt->bindValue(':idPersona', $idPersona,PDO::PARAM_INT);

		$stmt->execute();

		$query = "update usuario set
				idTipoUsuario = :idTipoUsuario,
				nombre = :usuario,
				contrasenia = :contrasenia
			where
				idUsuario = :idUsuario";

		$stmt = $dbh->prepare($query);

		$stmt->bindValue(':idUsuario',$idUsuario,PDO::PARAM_INT);
		$stmt->bindValue(':idTipoUsuario',$tipoUsuario,PDO::PARAM_INT);
		$stmt->bindValue(':usuario',$persona->getUsuario()->getNombre(),PDO::PARAM_STR);
		$stmt->bindValue(':contrasenia',$persona->getUsuario()->getContrasenia(),PDO::PARAM_STR);

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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Editar Usuario</title>
	<link type="text/css" rel="stylesheet" href="includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once '../includes/php/header.php'; ?>
    <?php require_once 'opcionesAdm.php'; ?>

	<div class="mensaje">

		<?php if ( $validarCorrectas == true ) { ?>
			<h3>Error al registrar el usuario</h3>
			<p>
				Ha ocurrido un error al intentar registrar el usuario. Por favor intentelo nuevamente.
			</p>
		<?php } else { ?>
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php if ( $validar['validarContrasenia'] == false ) { ?>
					<li>La contraseña no es válida. Debe contener al menos 6 caracteres y al menos 1 letra y 1 número</li>
				<?php } if ( $validar['validarTipoDocumento'] == false ) { ?>
					<li>El tipo de documento ingresado no se encuentra registrado</li>
				<?php } if ( $validar['validarSexo'] == false ) { ?>
					<li>El sexo ingresado no se encuentra registrado</li>
				<?php } if ( $validar['validarProvincia'] == false ) { ?>
					<li>La provincia ingresada no se encuentra registrada</li>
				<?php } if ( $validar['validarEmail'] == false ) { ?>
					<li>El correo electrónico no es válido. Debe contener un símbolo "@"</li>
				<?php } if ( $validar['validarTelefono'] == false ) { ?>
					<li>El teléfono no es válido. Debe contener al menos 10 dígitos y estar separado por un "-"</li>
				<?php } if ( $validar['validarCelular'] == false ) { ?>
					<li>El celular no es válido. Debe contener al menos 10 dígitos y estar separado por un "-"</li>
				<?php } ?>
			</ul>
		<?php } ?>

		<div class="buttons">
			<input type="button" value="Anterior" onclick="document.location='editar_usuario.php'">
		</div>
	</div>

	<div class="push"></div>

</div>

<?php require_once '../includes/php/footer.php'; ?>
</body>
</html>