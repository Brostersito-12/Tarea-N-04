<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="admin-page-header">
    <h2 class="admin-page-title">Gestión de Servicios</h2>
    <a href="servicios_form.php" class="btn-primary"><i class="fas fa-plus"></i> Nuevo Servicio</a>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql = "SELECT * FROM servicios WHERE estado != 'eliminado'";
                $stmt = $pdo->query($sql);

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td>{$row['id_servicio']}</td>
                            <td>{$row['nombre_servicio']}</td>
                            <td>{$row['descripcion']}</td>
                            <td>$" . number_format($row['precio'], 2) . "</td>
                            <td><span class='badge {$row['estado']}'>{$row['estado']}</span></td>
                            <td>
                                <a href='servicios_form.php?id={$row['id_servicio']}' class='btn-action edit'><i class='fas fa-edit'></i></a>
                            </td>
                          </tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='6'>Error al cargar los servicios: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'views/footer_admin.php'; ?>
