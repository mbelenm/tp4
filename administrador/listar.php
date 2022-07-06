<?php
$_SERVER["DOCUMENT_ROOT"]=dirname(__DIR__);
ini_set('display_errors', 1);

require_once '../includes/php/conexion.php';

$dbh = conectarDB();

$query = "select u.idusuario ,u.nombre as usuario ,per.apellido ,per.nombre ,per.numerodocumento ,per.email ,td.nombre as tipodocumento ,tu.nombre as tipousuario from persona per inner join tipodocumento td using(idtipodocumento) inner join usuario u using(idpersona)inner join tipousuario tu using(idtipousuario)";


$stmt = $dbh->query($query);

$datos=$stmt->fetchAll(PDO::FETCH_OBJ);


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>Usuario Gugler</title>
<link type="text/css" rel="stylesheet" href="../includes/css/estilos.css">
<link type="text/css" rel="stylesheet" href="estiloAdm.css">
</head>
<body>

<div class="wraper">

	<?php require_once  '../includes/php/header.php'; ?>

	<fieldset>
		<legend>Usuarios Gugler</legend>
		<div class="tabla">
		<table >
			<tr>
				<th class="separador">Id Usuario</th>
				<th class="separador">Usuario</th>
				<th class="separador">Tipo user</th>
				<th class="separador">Apellido - Nombre</th>
				<th class="separador">Documentp</th>
				<th class="separador">Email</th>
				<th class="separador">Administrar</th>
		
			
			<?php foreach ( $datos as $dato ) { ?>
			<tr>
				<td ><?= $dato->idusuario ?></td>
				<td><?= $dato->usuario ?></td>
				<td ><?= $dato->tipousuario ?></td>
				<td><?= $dato->apellido ?>, <?= $dato->nombre ?></td>
				<td >(<?= $dato->tipodocumento ?>) <?= $dato->numerodocumento ?></td>
				<td><?= $dato->email ?></td> 
				<td>
					<a href="editar.php?id=<?= $dato->idusuario ?>" title="Editar"><strong>Editar </strong> </a>
					<a href="eliminar.php?id=<?= $dato->idusuario ?>" title="Eliminar"><strong> Eliminar</strong></a>
				</td>
			</tr>
			<?php } ?>
			
		</table>
		<?php require_once 'opcionesAdm.php'; ?>
		</div>
	</fieldset>
	
	<div class="push"></div>
	
</div>

<?php require_once  '../includes/php/footer.php'; ?>
</body>
</html>