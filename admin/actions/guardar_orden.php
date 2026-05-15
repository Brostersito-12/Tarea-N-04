<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $id_cliente = $_POST['id_cliente'];
    $id_vehiculo = $_POST['id_vehiculo'];
    $id_mecanico = !empty($_POST['id_mecanico']) ? $_POST['id_mecanico'] : null;
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $servicios_seleccionados = isset($_POST['servicios']) ? $_POST['servicios'] : [];

    try {
        $pdo->beginTransaction();

        if ($id) {
            $sql = "UPDATE ordenes_trabajo SET id_cliente = ?, id_vehiculo = ?, id_mecanico = ?, descripcion = ?, estado = ?, fecha_ingreso = ? WHERE id_orden = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_cliente, $id_vehiculo, $id_mecanico, $descripcion, $estado, $fecha_ingreso, $id]);
            $id_orden = $id;

            // Limpiar servicios anteriores
            $pdo->prepare("DELETE FROM detalle_orden_servicios WHERE id_orden = ?")->execute([$id_orden]);
        } else {
            $sql = "INSERT INTO ordenes_trabajo (id_cliente, id_vehiculo, id_mecanico, descripcion, estado, fecha_ingreso) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_cliente, $id_vehiculo, $id_mecanico, $descripcion, $estado, $fecha_ingreso]);
            $id_orden = $pdo->lastInsertId();
        }

        // Insertar servicios seleccionados y calcular subtotal
        if (!empty($servicios_seleccionados)) {
            $stmtS = $pdo->prepare("INSERT INTO detalle_orden_servicios (id_orden, id_servicio, subtotal) VALUES (?, ?, ?)");
            foreach ($servicios_seleccionados as $sid) {
                // Obtener el precio del servicio para el subtotal
                $precio = $pdo->query("SELECT precio FROM servicios WHERE id_servicio = $sid")->fetchColumn();
                $stmtS->execute([$id_orden, $sid, $precio]);
            }
        }

        $pdo->commit();
        header("Location: ../ordenes.php?status=success");
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error al guardar la orden: " . $e->getMessage());
    }
}
?>
