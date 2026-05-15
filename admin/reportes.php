<?php
require_once '../config/db.php';
include 'views/header_admin.php';

// Reporte de órdenes por estado
$ordenes_estado = $pdo->query("SELECT estado, COUNT(*) as total FROM ordenes_trabajo GROUP BY estado")->fetchAll();

// Reporte de pagos por fecha (últimos 7 días)
$pagos_fecha = $pdo->query("SELECT DATE(fecha_pago) as fecha, SUM(monto) as total FROM pagos GROUP BY DATE(fecha_pago) ORDER BY fecha DESC LIMIT 7")->fetchAll();
?>

<h2 class="admin-page-title">Reportes y Estadísticas</h2>

<div class="reports-grid">
    <div class="report-card">
        <h3>Órdenes por Estado</h3>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordenes_estado as $re): ?>
                        <tr>
                            <td><span class="badge <?php echo $re['estado']; ?>"><?php echo $re['estado']; ?></span></td>
                            <td><?php echo $re['total']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="report-card">
        <h3>Pagos Recientes (por día)</h3>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Total Recaudado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagos_fecha as $rp): ?>
                        <tr>
                            <td><?php echo $rp['fecha']; ?></td>
                            <td><strong>$<?php echo number_format($rp['total'], 2); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.reports-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}
.report-card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.report-card h3 {
    margin-bottom: 20px;
    color: var(--primary);
}
</style>

<?php include 'views/footer_admin.php'; ?>
