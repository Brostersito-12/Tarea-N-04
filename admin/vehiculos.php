<?php
require_once '../config/db.php';
include 'views/header_admin.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="admin-page-header">
    <h2 class="admin-page-title">Gestión de Vehículos</h2>
    <a href="vehiculos_form.php" class="btn-primary"><i class="fas fa-plus"></i> Nuevo Vehículo</a>
</div>

<div class="search-container">
    <form action="vehiculos.php" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Buscar por placa, marca o cliente..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Color</th>
                <th>Cliente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $sql = "SELECT v.*, c.nombre as cliente_nombre FROM vehiculos v 
                        LEFT JOIN clientes c ON v.id_cliente = c.id_cliente 
                        WHERE v.estado = 'activo'";
                if ($search) {
                    $sql .= " AND (v.placa LIKE ? OR v.marca LIKE ? OR c.nombre LIKE ?)";
                    $stmt = $pdo->prepare($sql);
                    $term = "%$search%";
                    $stmt->execute([$term, $term, $term]);
                } else {
                    $stmt = $pdo->query($sql);
                }

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td><strong>{$row['placa']}</strong></td>
                            <td>{$row['marca']}</td>
                            <td>{$row['modelo']}</td>
                            <td>{$row['color']}</td>
                            <td>{$row['cliente_nombre']}</td>
                            <td>
                                <a href='vehiculos_form.php?id={$row['id_vehiculo']}' class='btn-action edit'><i class='fas fa-edit'></i></a>
                                <a href='actions/eliminar_logico.php?tabla=vehiculos&id={$row['id_vehiculo']}' class='btn-action delete' onclick='return confirm(\"¿Está seguro de eliminar este vehículo?\")'><i class='fas fa-trash'></i></a>
                            </td>
                          </tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='6'>Error al cargar los vehículos: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'views/footer_admin.php'; ?>
