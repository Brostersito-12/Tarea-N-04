<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $placa = $_POST['placa'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = !empty($_POST['anio']) ? (int)$_POST['anio'] : null;
    
    // Validar rango para tipo YEAR(4) en MySQL (1901 - 2155)
    if ($anio !== null && ($anio < 1901 || $anio > 2155)) {
        $anio = date('Y'); // Si es inválido, poner el año actual por defecto o manejar el error
    }
    
    $color = $_POST['color'];
    $id_cliente = $_POST['id_cliente'];

    try {
        if ($id) {
            $sql = "UPDATE vehiculos SET placa = ?, marca = ?, modelo = ?, anio = ?, color = ?, id_cliente = ? WHERE id_vehiculo = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$placa, $marca, $modelo, $anio, $color, $id_cliente, $id]);
        } else {
            $sql = "INSERT INTO vehiculos (placa, marca, modelo, anio, color, id_cliente, estado) VALUES (?, ?, ?, ?, ?, ?, 'activo')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$placa, $marca, $modelo, $anio, $color, $id_cliente]);
        }
        header("Location: ../vehiculos.php?status=success");
    } catch (PDOException $e) {
        die("Error al guardar vehículo: " . $e->getMessage());
    }
}
?>
