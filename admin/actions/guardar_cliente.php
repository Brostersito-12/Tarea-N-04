<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    try {
        if ($id) {
            $sql = "UPDATE clientes SET nombre = ?, apellido = ?, correo = ?, telefono = ?, direccion = ? WHERE id_cliente = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellido, $correo, $telefono, $direccion, $id]);
        } else {
            $sql = "INSERT INTO clientes (nombre, apellido, correo, telefono, direccion, estado) VALUES (?, ?, ?, ?, ?, 'activo')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellido, $correo, $telefono, $direccion]);
        }
        header("Location: ../clientes.php?status=success");
    } catch (PDOException $e) {
        die("Error al guardar cliente: " . $e->getMessage());
    }
}
?>
