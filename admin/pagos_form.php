<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$id_orden = isset($_GET['id_orden']) ? $_GET['id_orden'] : null;
$monto_sugerido = 0;
$cliente_nombre = "";

if ($id_orden) {
    try {
        // Calcular el monto total sumando los subtotales de detalle_orden_servicios
        $sql = "SELECT ot.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido, 
                       (SELECT COALESCE(SUM(subtotal), 0) FROM detalle_orden_servicios WHERE id_orden = ot.id_orden) as total_cobrar
                FROM ordenes_trabajo ot 
                JOIN clientes c ON ot.id_cliente = c.id_cliente 
                WHERE ot.id_orden = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_orden]);
        $data = $stmt->fetch();
        
        if ($data) {
            $cliente_nombre = $data['cliente_nombre'] . " " . $data['cliente_apellido'];
            $monto_sugerido = $data['total_cobrar'];
        }
    } catch (Exception $e) {
        // Fallback
    }
}

// Obtener métodos de pago
$metodos = ['Efectivo', 'Tarjeta', 'Transferencia', 'Yape/Plin'];
?>

<h2 class="admin-page-title">Registrar Pago</h2>

<div class="card-form admin-form">
    <form action="actions/guardar_pago.php" method="POST">
        <div class="form-group">
            <label for="id_orden">N° de Orden</label>
            <input type="text" name="id_orden" id="id_orden" value="<?php echo $id_orden; ?>" readonly required>
        </div>

        <div class="form-group">
            <label>Cliente</label>
            <input type="text" value="<?php echo htmlspecialchars($cliente_nombre); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="monto">Monto a Cobrar ($)</label>
            <input type="number" step="0.01" name="monto" id="monto" value="<?php echo $monto_sugerido; ?>" required>
            <?php if ($monto_sugerido > 0): ?>
                <small style="color: #2ecc71;">* Precio sugerido según el servicio solicitado.</small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="metodo_pago">Método de Pago</label>
            <select name="metodo_pago" id="metodo_pago" required>
                <?php foreach ($metodos as $m): ?>
                    <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_pago">Fecha de Pago</label>
            <input type="date" name="fecha_pago" id="fecha_pago" value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Confirmar Pago y Finalizar</button>
            <a href="ordenes.php" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'views/footer_admin.php'; ?>
