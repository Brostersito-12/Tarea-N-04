<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$v = ['placa' => '', 'marca' => '', 'modelo' => '', 'anio' => '', 'color' => '', 'id_cliente' => ''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM vehiculos WHERE id_vehiculo = ?");
    $stmt->execute([$id]);
    $v = $stmt->fetch();
}

// Obtener clientes para el select
$clientes = $pdo->query("SELECT id_cliente, nombre FROM clientes WHERE estado = 'activo' ORDER BY nombre ASC")->fetchAll();
?>

<h2 class="admin-page-title"><?php echo $id ? 'Editar Vehículo' : 'Nuevo Vehículo'; ?></h2>

<div class="card-form admin-form">
    <form action="actions/guardar_vehiculo.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label for="id_cliente">Cliente Dueño</label>
            <select name="id_cliente" id="id_cliente" required>
                <option value="">Seleccione un cliente...</option>
                <?php foreach ($clientes as $c): ?>
                    <option value="<?php echo $c['id_cliente']; ?>" <?php echo ($v['id_cliente'] == $c['id_cliente']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="placa">Placa</label>
            <input type="text" name="placa" id="placa" value="<?php echo htmlspecialchars($v['placa']); ?>" required>
        </div>

        <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" name="marca" id="marca" value="<?php echo htmlspecialchars($v['marca']); ?>" required>
        </div>

        <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" name="modelo" id="modelo" value="<?php echo htmlspecialchars($v['modelo']); ?>" required>
        </div>

        <div class="form-group">
            <label for="anio">Año</label>
            <input type="number" name="anio" id="anio" value="<?php echo htmlspecialchars($v['anio']); ?>">
        </div>

        <div class="form-group">
            <label for="color">Color</label>
            <input type="text" name="color" id="color" value="<?php echo htmlspecialchars($v['color']); ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Vehículo</button>
            <a href="vehiculos.php" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'views/footer_admin.php'; ?>
