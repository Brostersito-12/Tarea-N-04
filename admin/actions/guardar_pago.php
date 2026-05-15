<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_orden = $_POST['id_orden'];
    $monto = $_POST['monto'];
    $metodo_pago = $_POST['metodo_pago'];
    $fecha_pago = $_POST['fecha_pago'];

    try {
        $pdo->beginTransaction();

        // 1. Insertar el pago
        $sqlPago = "INSERT INTO pagos (id_orden, monto, metodo_pago, fecha_pago, estado) 
                    VALUES (?, ?, ?, ?, 'Pagado')";
        $stmtPago = $pdo->prepare($sqlPago);
        $stmtPago->execute([$id_orden, $monto, $metodo_pago, $fecha_pago]);

        // 2. Finalizar la orden de trabajo
        $sqlOrden = "UPDATE ordenes_trabajo SET estado = 'finalizado', fecha_salida = ? WHERE id_orden = ?";
        $stmtOrden = $pdo->prepare($sqlOrden);
        $stmtOrden = $stmtOrden->execute([$fecha_pago, $id_orden]);

        // 3. Intentar marcar la solicitud como finalizada (basado en el cliente y vehículo de la orden)
        $sqlGetInfo = "SELECT id_cliente, id_vehiculo FROM ordenes_trabajo WHERE id_orden = ?";
        $stmtInfo = $pdo->prepare($sqlGetInfo);
        $stmtInfo->execute([$id_orden]);
        $info = $stmtInfo->fetch();

        if ($info) {
            $idCliente = $info['id_cliente'];
            $idVehiculo = $info['id_vehiculo'];
            
            // Buscamos el nombre del cliente y la placa del vehículo
            $c = $pdo->query("SELECT nombre FROM clientes WHERE id_cliente = $idCliente")->fetch();
            $v = $pdo->query("SELECT placa FROM vehiculos WHERE id_vehiculo = $idVehiculo")->fetch();
            
            if ($c && $v) {
                $sqlSol = "UPDATE solicitudes SET estado = 'finalizado' 
                           WHERE nombre_cliente = ? AND placa = ? AND estado = 'pendiente'";
                $stmtSol = $pdo->prepare($sqlSol);
                $stmtSol->execute([$c['nombre'], $v['placa']]);
            }
        }

        $pdo->commit();
        header("Location: ../pagos.php?status=success");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error al procesar el pago: " . $e->getMessage());
    }
}
?>
