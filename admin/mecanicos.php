<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="admin-page-header">
    <h2 class="admin-page-title">Gestión de Mecánicos</h2>
    <a href="mecanicos_form.php" class="btn-primary"><i class="fas fa-plus"></i> Nuevo Mecánico</a>
</div>

<div class="search-container">
    <form action="mecanicos.php" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Buscar por nombre o especialidad..." value="<?php echo htmlspecialchars($search); ?>">
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
                <th>Teléfono</th>
                <th>Especialidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql = "SELECT * FROM mecanicos WHERE estado != 'eliminado'";
                if ($search) {
                    $sql .= " AND (nombre LIKE ? OR especialidad LIKE ?)";
                    $stmt = $pdo->prepare($sql);
                    $term = "%$search%";
                    $stmt->execute([$term, $term]);
                } else {
                    $stmt = $pdo->query($sql);
                }

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td>{$row['id_mecanico']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['apellido']}</td>
                            <td>{$row['telefono']}</td>
                            <td>{$row['especialidad']}</td>
                            <td><span class='badge {$row['estado']}'>{$row['estado']}</span></td>
                            <td>
                                <a href='mecanicos_form.php?id={$row['id_mecanico']}' class='btn-action edit'><i class='fas fa-edit'></i></a>
                            </td>
                          </tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='7'>Error al cargar los mecánicos: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'views/footer_admin.php'; ?>
