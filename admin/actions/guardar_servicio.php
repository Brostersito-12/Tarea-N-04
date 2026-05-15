<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre_servicio'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];

    try {
        if ($id) {
            $sql = "UPDATE servicios SET nombre_servicio = ?, descripcion = ?, precio = ?, estado = ? WHERE id_servicio = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $estado, $id]);
        } else {
            $sql = "INSERT INTO servicios (nombre_servicio, descripcion, precio, estado) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $estado]);
        }
        header("Location: ../servicios.php?status=success");
    } catch (PDOException $e) {
        die("Error al guardar servicio: " . $e->getMessage());
    }
}
?>
