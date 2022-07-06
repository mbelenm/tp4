<?php
$_SERVER["DOCUMENT_ROOT"]=dirname(__DIR__);
ini_set('display_errors', 1);

require_once '../includes/php/conexion.php';

$dbh = conectarDB();

$query = "select
		u.idusuario
		,u.nombre as usuario
		,p.apellido
		,p.nombre
		,p.numerodocumento
		,p.email
		,td.nombre as tipodocumento
		,tu.nombre as tipousuario
	from persona p
	inner join tipodocumento td using(idtipodocumento)
	inner join usuario u using(idpersona)
	inner join tipousuario tu using(idtipousuario)";


$stmt = $dbh->query($query);

$filas=$stmt->fetchAll(PDO::FETCH_OBJ);


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>Usuario Gugler</title>
	<link type="text/css" rel="stylesheet" href="/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once  '../includes/php/header.php'; ?>
	<?php require_once 'opcionesAdm.php'; ?>

	<fieldset>
		<legend>Usuarios Gugler</legend>
		
		<table>
			
			<tr>
				<th>ID</th>
				<th>USUARIO</th>
				<th>TIPO</th>
				<th>APELLIDO Y NOMBRE</th>
				<th>DOC</th>
				<th>EMAIL</th>
				<th>ACCIONES</th>
			</tr>
			
			<?php foreach ( $filas as $fila ) { ?>
			<tr>
				<td class="text-right"><?= $fila->idusuario ?></td>
				<td><?= $fila->usuario ?></td>
				<td class="text-center"><?= $fila->tipousuario ?></td>
				<td><?= $fila->apellido ?>, <?= $fila->nombre ?></td>
				<td class="text-right">(<?= $fila->tipodocumento ?>) <?= $fila->numerodocumento ?></td>
				<td><?= $fila->email ?></td>
				<td class="text-center">
					<a href="editar.php?id=<?= $fila->idusuario ?>" title="Editar"><img alt="Modificar" src="/tp5/includes/img/edit.png"></a>
					<a href="eliminar.php?id=<?= $fila->idusuario ?>" title="Eliminar"><img alt="Eliminar" src="/tp5/includes/img/delete.png"></a>
				</td>
			</tr>
			<?php } ?>
			
		</table>
		
	</fieldset>
	
	<div class="push"></div>
	
</div>

<?php require_once  'includes/php/footer.php'; ?>
</body>
</html>