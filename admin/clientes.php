<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="admin-page-header">
    <h2 class="admin-page-title">Gestión de Clientes</h2>
    <a href="clientes_form.php" class="btn-primary"><i class="fas fa-plus"></i> Nuevo Cliente</a>
</div>

<div class="search-container">
    <form action="clientes.php" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Buscar por nombre o correo..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql = "SELECT * FROM clientes WHERE estado = 'activo'";
                if ($search) {
                    $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR correo LIKE ?)";
                    $stmt = $pdo->prepare($sql);
                    $term = "%$search%";
                    $stmt->execute([$term, $term, $term]);
                } else {
                    $stmt = $pdo->query($sql);
                }

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td>{$row['id_cliente']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['apellido']}</td>
                            <td>{$row['correo']}</td>
                            <td>{$row['telefono']}</td>
                            <td>
                                <a href='clientes_form.php?id={$row['id_cliente']}' class='btn-action edit'><i class='fas fa-edit'></i></a>
                                <a href='actions/eliminar_logico.php?tabla=clientes&id={$row['id_cliente']}' class='btn-action delete' onclick='return confirm(\"¿Está seguro de eliminar este cliente?\")'><i class='fas fa-trash'></i></a>
                            </td>
                          </tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='6'>Error al cargar los clientes: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'views/footer_admin.php'; ?>
