<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $especialidad = $_POST['especialidad'];
    $estado = $_POST['estado'];

    try {
        if ($id) {
            $sql = "UPDATE mecanicos SET nombre = ?, apellido = ?, telefono = ?, especialidad = ?, estado = ? WHERE id_mecanico = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellido, $telefono, $especialidad, $estado, $id]);
        } else {
            $sql = "INSERT INTO mecanicos (nombre, apellido, telefono, especialidad, estado) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellido, $telefono, $especialidad, $estado]);
        }
        header("Location: ../mecanicos.php?status=success");
    } catch (PDOException $e) {
        die("Error al guardar mecánico: " . $e->getMessage());
    }
}
?>
