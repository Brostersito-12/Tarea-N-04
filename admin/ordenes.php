<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="admin-page-header">
    <h2 class="admin-page-title">Órdenes de Trabajo</h2>
    <a href="ordenes_form.php" class="btn-primary"><i class="fas fa-plus"></i> Nueva Orden</a>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>N° Orden</th>
                <th>Cliente</th>
                <th>Vehículo</th>
                <th>Mecánico</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql = "SELECT ot.*, c.nombre as cliente_nombre, v.placa, m.nombre as mecanico_nombre 
                        FROM ordenes_trabajo ot 
                        JOIN clientes c ON ot.id_cliente = c.id_cliente 
                        JOIN vehiculos v ON ot.id_vehiculo = v.id_vehiculo 
                        LEFT JOIN mecanicos m ON ot.id_mecanico = m.id_mecanico 
                        ORDER BY ot.id_orden DESC";
                $stmt = $pdo->query($sql);

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td><strong>ORD-" . str_pad($row['id_orden'], 5, '0', STR_PAD_LEFT) . "</strong></td>
                            <td>{$row['cliente_nombre']}</td>
                            <td>{$row['placa']}</td>
                            <td>" . ($row['mecanico_nombre'] ?: 'No asignado') . "</td>
                            <td><span class='badge {$row['estado']}'>{$row['estado']}</span></td>
                            <td>{$row['fecha_ingreso']}</td>
                            <td>
                                <a href='ordenes_form.php?id={$row['id_orden']}' class='btn-action edit'><i class='fas fa-edit'></i></a>
                                <a href='pagos_form.php?id_orden={$row['id_orden']}' class='btn-action money'><i class='fas fa-dollar-sign'></i></a>
                            </td>
                          </tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='7'>No hay órdenes registradas o hubo un error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'views/footer_admin.php'; ?>
