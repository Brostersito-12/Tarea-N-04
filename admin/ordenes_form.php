<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$o = [
    'id_cliente' => '',
    'id_vehiculo' => '',
    'id_mecanico' => '',
    'descripcion' => '',
    'estado' => 'pendiente',
    'fecha_ingreso' => date('Y-m-d')
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM ordenes_trabajo WHERE id_orden = ?");
    $stmt->execute([$id]);
    $o = $stmt->fetch();
} elseif (isset($_GET['nueva_de_solicitud'])) {
    $id_sol = $_GET['nueva_de_solicitud'];
    $stmt = $pdo->prepare("SELECT * FROM solicitudes WHERE id_solicitud = ?");
    $stmt->execute([$id_sol]);
    $sol = $stmt->fetch();
    
    if ($sol) {
        // Intentar encontrar al cliente por nombre (o crearlo si no existe, pero por ahora solo buscamos)
        $stmtC = $pdo->prepare("SELECT id_cliente FROM clientes WHERE nombre LIKE ? LIMIT 1");
        $stmtC->execute(['%' . $sol['nombre_cliente'] . '%']);
        $resC = $stmtC->fetch();
        if ($resC) $o['id_cliente'] = $resC['id_cliente'];

        // Intentar encontrar el vehículo por placa
        $stmtV = $pdo->prepare("SELECT id_vehiculo FROM vehiculos WHERE placa = ? LIMIT 1");
        $stmtV->execute([$sol['placa']]);
        $resV = $stmtV->fetch();
        if ($resV) $o['id_vehiculo'] = $resV['id_vehiculo'];

        $o['descripcion'] = $sol['descripcion_problema'];
    }
}

// Obtener datos para los selects
$clientes = $pdo->query("SELECT id_cliente, nombre, apellido FROM clientes WHERE estado != 'eliminado' ORDER BY nombre ASC")->fetchAll();
$vehiculos = $pdo->query("SELECT id_vehiculo, placa, marca, modelo FROM vehiculos WHERE estado != 'eliminado' ORDER BY placa ASC")->fetchAll();
$mecanicos = $pdo->query("SELECT id_mecanico, nombre, apellido FROM mecanicos WHERE estado != 'eliminado' ORDER BY nombre ASC")->fetchAll();
$servicios = $pdo->query("SELECT id_servicio, nombre_servicio, precio FROM servicios WHERE estado != 'eliminado' ORDER BY nombre_servicio ASC")->fetchAll();

// Si es edición, obtener servicios ya asignados
$servicios_seleccionados = [];
if ($id) {
    $stmtS = $pdo->prepare("SELECT id_servicio FROM detalle_orden_servicios WHERE id_orden = ?");
    $stmtS->execute([$id]);
    $servicios_seleccionados = $stmtS->fetchAll(PDO::FETCH_COLUMN);
} elseif (isset($sol) && $sol) {
    // Si viene de una solicitud, pre-marcar el servicio solicitado
    $servicios_seleccionados = [$sol['tipo_servicio']];
}
?>

<h2 class="admin-page-title"><?php echo $id ? 'Editar Orden de Trabajo' : 'Nueva Orden de Trabajo'; ?></h2>

<div class="card-form admin-form">
    <form action="actions/guardar_orden.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label>Servicios a Realizar</label>
            <div class="services-selection-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #252525; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php foreach ($servicios as $s): ?>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="servicios[]" value="<?php echo $s['id_servicio']; ?>" 
                               <?php echo in_array($s['id_servicio'], $servicios_seleccionados) ? 'checked' : ''; ?>>
                        <span><?php echo htmlspecialchars($s['nombre_servicio']); ?> ($<?php echo number_format($s['precio'], 2); ?>)</span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="id_cliente">Cliente</label>
                <select name="id_cliente" id="id_cliente" required>
                    <option value="">Seleccione un cliente...</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?php echo $c['id_cliente']; ?>" <?php echo ($o['id_cliente'] == $c['id_cliente']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['nombre'] . ' ' . $c['apellido']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_vehiculo">Vehículo (Placa)</label>
                <select name="id_vehiculo" id="id_vehiculo" required>
                    <option value="">Seleccione un vehículo...</option>
                    <?php foreach ($vehiculos as $v): ?>
                        <option value="<?php echo $v['id_vehiculo']; ?>" <?php echo ($o['id_vehiculo'] == $v['id_vehiculo']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($v['placa'] . ' - ' . $v['marca'] . ' ' . $v['modelo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="id_mecanico">Mecánico Asignado</label>
                <select name="id_mecanico" id="id_mecanico">
                    <option value="">Seleccione un mecánico...</option>
                    <?php foreach ($mecanicos as $m): ?>
                        <option value="<?php echo $m['id_mecanico']; ?>" <?php echo ($o['id_mecanico'] == $m['id_mecanico']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado de la Orden</label>
                <select name="estado" id="estado">
                    <option value="pendiente" <?php echo ($o['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="en proceso" <?php echo ($o['estado'] == 'en proceso') ? 'selected' : ''; ?>>En Proceso</option>
                    <option value="finalizado" <?php echo ($o['estado'] == 'finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción del Trabajo / Diagnóstico</label>
            <textarea name="descripcion" id="descripcion" rows="4" required><?php echo htmlspecialchars($o['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="fecha_ingreso">Fecha de Ingreso</label>
            <input type="date" name="fecha_ingreso" id="fecha_ingreso" value="<?php echo $o['fecha_ingreso']; ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Orden</button>
            <a href="ordenes.php" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'views/footer_admin.php'; ?>
