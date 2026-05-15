<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$s = ['nombre_servicio' => '', 'descripcion' => '', 'precio' => '', 'estado' => 'Activo'];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id_servicio = ?");
    $stmt->execute([$id]);
    $s = $stmt->fetch();
}
?>

<h2 class="admin-page-title"><?php echo $id ? 'Editar Servicio' : 'Nuevo Servicio'; ?></h2>

<div class="card-form admin-form">
    <form action="actions/guardar_servicio.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label for="nombre_servicio">Nombre del Servicio</label>
            <input type="text" name="nombre_servicio" id="nombre_servicio" value="<?php echo htmlspecialchars($s['nombre_servicio']); ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3"><?php echo htmlspecialchars($s['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio ($)</label>
            <input type="number" step="0.01" name="precio" id="precio" value="<?php echo htmlspecialchars($s['precio']); ?>" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" id="estado">
                <option value="Activo" <?php echo ($s['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                <option value="Inactivo" <?php echo ($s['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Servicio</button>
            <a href="servicios.php" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'views/footer_admin.php'; ?>
