<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="admin-page-header">
    <h2 class="admin-page-title">Historial de Pagos</h2>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID Pago</th>
                <th>N° Orden</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql = "SELECT p.*, ot.id_orden, c.nombre as cliente_nombre 
                        FROM pagos p 
                        LEFT JOIN ordenes_trabajo ot ON p.id_orden = ot.id_orden 
                        LEFT JOIN clientes c ON ot.id_cliente = c.id_cliente 
                        ORDER BY p.id_pago DESC";
                $stmt = $pdo->query($sql);

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td>#{$row['id_pago']}</td>
                            <td>ORD-" . str_pad($row['id_orden'], 5, '0', STR_PAD_LEFT) . "</td>
                            <td>{$row['cliente_nombre']}</td>
                            <td><strong>$" . number_format($row['monto'], 2) . "</strong></td>
                            <td>{$row['metodo_pago']}</td>
                            <td>{$row['fecha_pago']}</td>
                            <td><span class='badge {$row['estado']}'>{$row['estado']}</span></td>
                          </tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='7'>No se pudieron cargar los pagos: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'views/footer_admin.php'; ?>
