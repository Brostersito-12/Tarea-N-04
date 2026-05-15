<?php
require_once '../config/db.php';
include 'views/header_admin.php';

// Obtener estadísticas
try {
    $total_clientes = $pdo->query("SELECT COUNT(*) FROM clientes WHERE estado != 'eliminado'")->fetchColumn();
    $total_vehiculos = $pdo->query("SELECT COUNT(*) FROM vehiculos WHERE estado != 'eliminado'")->fetchColumn();
    $servicios_pendientes = $pdo->query("SELECT COUNT(*) FROM solicitudes WHERE estado = 'pendiente'")->fetchColumn();
    $servicios_finalizados = $pdo->query("SELECT COUNT(*) FROM ordenes_trabajo WHERE estado = 'finalizado'")->fetchColumn();
    $pagos_recibidos = $pdo->query("SELECT COALESCE(SUM(monto), 0) FROM pagos")->fetchColumn();
} catch (PDOException $e) {
    $total_clientes = 0; $total_vehiculos = 0; $servicios_pendientes = 0; $servicios_finalizados = 0; $pagos_recibidos = 0;
}
?>

<h2 class="admin-page-title">Panel de Control</h2>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon clients"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3>Clientes</h3>
            <p><?php echo $total_clientes; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon vehicles"><i class="fas fa-car"></i></div>
        <div class="stat-info">
            <h3>Vehículos</h3>
            <p><?php echo $total_vehiculos; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <h3>Solicitudes Pendientes</h3>
            <p><?php echo $servicios_pendientes; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon finished"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <h3>Órdenes Finalizadas</h3>
            <p><?php echo $servicios_finalizados; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon money"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h3>Pagos Recibidos</h3>
            <p>$<?php echo number_format($pagos_recibidos, 2); ?></p>
        </div>
    </div>
</div>

<section class="recent-requests">
    <h3>Solicitudes Recientes</h3>
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT s.*, ser.nombre_servicio as servicio_nombre FROM solicitudes s 
                                         LEFT JOIN servicios ser ON s.tipo_servicio = ser.id_servicio 
                                         ORDER BY s.id_solicitud DESC LIMIT 5");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                                <td>{$row['nombre_cliente']}</td>
                                <td>{$row['marca_vehiculo']} {$row['modelo_vehiculo']} ({$row['placa']})</td>
                                <td>{$row['servicio_nombre']}</td>
                                <td>{$row['fecha_deseada']}</td>
                                <td><span class='badge {$row['estado']}'>{$row['estado']}</span></td>
                                <td>
                                    <a href='ordenes_form.php?nueva_de_solicitud={$row['id_solicitud']}' class='btn-action'><i class='fas fa-plus'></i> Crear Orden</a>
                                </td>
                              </tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='6'>No se pudieron cargar las solicitudes recientes. " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'views/footer_admin.php'; ?>
