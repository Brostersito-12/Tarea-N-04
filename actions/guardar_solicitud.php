<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $placa = $_POST['placa'];
    $id_servicio = $_POST['id_servicio'];
    $fecha_deseada = $_POST['fecha_deseada'];
    $descripcion = $_POST['descripcion'];

    // Validar fecha básica para evitar errores de SQL (rango permitido)
    $year = (int)date('Y', strtotime($fecha_deseada));
    if ($year < 2000 || $year > 3000) {
        die("Error: La fecha proporcionada no es válida.");
    }

    try {
        $sql = "INSERT INTO solicitudes (nombre_cliente, correo, telefono, marca_vehiculo, modelo_vehiculo, placa, tipo_servicio, fecha_deseada, descripcion_problema, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $email, $telefono, $marca, $modelo, $placa, $id_servicio, $fecha_deseada, $descripcion]);
        
        header("Location: ../solicitud.php?status=success");
    } catch (PDOException $e) {
        die("Error al enviar la solicitud: " . $e->getMessage());
    }
} else {
    header("Location: ../solicitud.php");
}
?>
