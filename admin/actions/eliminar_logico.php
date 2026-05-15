<?php
require_once '../../config/db.php';

$tabla = $_GET['tabla'];
$id = $_GET['id'];

// Solo permitimos eliminar de ciertas tablas por seguridad
$tablas_permitidas = ['clientes', 'vehiculos'];

if (in_array($tabla, $tablas_permitidas)) {
    try {
        $pk = ($tabla == 'clientes') ? 'id_cliente' : 'id_vehiculo';
        $stmt = $pdo->prepare("UPDATE $tabla SET estado = 'eliminado' WHERE $pk = ?");
        $stmt->execute([$id]);
        header("Location: ../$tabla.php?status=deleted");
    } catch (PDOException $e) {
        die("Error al eliminar: " . $e->getMessage());
    }
} else {
    die("Acción no permitida.");
}
?>
