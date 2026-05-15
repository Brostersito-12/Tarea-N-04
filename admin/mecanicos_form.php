<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$m = ['nombre' => '', 'apellido' => '', 'telefono' => '', 'especialidad' => '', 'estado' => 'Activo'];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM mecanicos WHERE id_mecanico = ?");
    $stmt->execute([$id]);
    $m = $stmt->fetch();
}
?>

<h2 class="admin-page-title"><?php echo $id ? 'Editar Mecánico' : 'Nuevo Mecánico'; ?></h2>

<div class="card-form admin-form">
    <form action="actions/guardar_mecanico.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($m['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($m['apellido']); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($m['telefono']); ?>">
        </div>

        <div class="form-group">
            <label for="especialidad">Especialidad</label>
            <input type="text" name="especialidad" id="especialidad" value="<?php echo htmlspecialchars($m['especialidad']); ?>" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" id="estado">
                <option value="Activo" <?php echo ($m['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                <option value="Inactivo" <?php echo ($m['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Mecánico</button>
            <a href="mecanicos.php" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'views/footer_admin.php'; ?>
