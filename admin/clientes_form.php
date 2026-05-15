<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$c = ['nombre' => '', 'apellido' => '', 'correo' => '', 'telefono' => '', 'direccion' => ''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$id]);
    $c = $stmt->fetch();
}
?>

<h2 class="admin-page-title"><?php echo $id ? 'Editar Cliente' : 'Nuevo Cliente'; ?></h2>

<div class="card-form admin-form">
    <form action="actions/guardar_cliente.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($c['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($c['apellido']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="correo" id="email" value="<?php echo htmlspecialchars($c['correo']); ?>" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($c['telefono']); ?>">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <textarea name="direccion" id="direccion" rows="3"><?php echo htmlspecialchars($c['direccion']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Cliente</button>
            <a href="clientes.php" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'views/footer_admin.php'; ?>
