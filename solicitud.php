<?php
require_once 'config/db.php';

// Obtener servicios para el select
try {
    $stmt = $pdo->query("SELECT * FROM servicios");
    $servicios = $stmt->fetchAll();
} catch (PDOException $e) {
    $servicios = [];
}

$servicio_preseleccionado = isset($_GET['servicio']) ? $_GET['servicio'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Atención - Taller Mecánico</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">Taller<span>Mecánico</span></div>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="index.php#servicios">Servicios</a></li>
                <li><a href="index.php#contacto">Contacto</a></li>
                <li><a href="admin/login.php" class="btn-login">Admin</a></li>
            </ul>
        </nav>
    </header>

        <section class="form-section container">
            <h2 class="section-title"><span>Solicitud</span> de Servicio</h2>
            
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> ¡Solicitud enviada con éxito! Nos contactaremos pronto.
                </div>
            <?php endif; ?>

            <div class="card-form">
                <form action="actions/guardar_solicitud.php" method="POST">
                    <div class="form-group-grid">
                        <div class="form-column">
                            <h3>Datos Personales</h3>
                            <div class="form-group">
                                <label for="nombre">Nombre Completo</label>
                                <input type="text" name="nombre" id="nombre" placeholder="Ej. Juan Pérez" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" name="email" id="email" placeholder="juan@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" placeholder="987654321" required>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3>Datos del Vehículo</h3>
                            <div class="form-group">
                                <label for="marca">Marca</label>
                                <input type="text" name="marca" id="marca" placeholder="Ej. Toyota" required>
                            </div>
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <input type="text" name="modelo" id="modelo" placeholder="Ej. Corolla" required>
                            </div>
                            <div class="form-group">
                                <label for="placa">Placa</label>
                                <input type="text" name="placa" id="placa" placeholder="ABC-123" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <h3>Detalles del Servicio</h3>
                        <div class="form-group">
                            <label for="id_servicio">Tipo de Servicio</label>
                            <select name="id_servicio" id="id_servicio" required onchange="updatePrice()">
                                <option value="">Seleccione un servicio...</option>
                                <?php foreach ($servicios as $s): ?>
                                    <option value="<?php echo $s['id_servicio']; ?>" 
                                            data-price="<?php echo $s['precio']; ?>"
                                            <?php echo ($servicio_preseleccionado == $s['id_servicio']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($s['nombre_servicio']); ?> - $<?php echo number_format($s['precio'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="price-display" class="price-info" style="display: none; margin-bottom: 20px; font-weight: bold; color: var(--secondary);">
                            Precio Estimado: $<span id="selected-price">0.00</span>
                        </div>

                        <script>
                        function updatePrice() {
                            const select = document.getElementById('id_servicio');
                            const display = document.getElementById('price-display');
                            const priceSpan = document.getElementById('selected-price');
                            const selectedOption = select.options[select.selectedIndex];
                            
                            if (selectedOption && selectedOption.value) {
                                const price = selectedOption.getAttribute('data-price');
                                priceSpan.innerText = parseFloat(price).toFixed(2);
                                display.style.display = 'block';
                            } else {
                                display.style.display = 'none';
                            }
                        }
                        // Ejecutar al cargar por si hay preselección
                        window.onload = updatePrice;
                        </script>
                        
                        <div class="form-group">
                            <label for="fecha_deseada">Fecha Deseada</label>
                            <input type="date" name="fecha_deseada" id="fecha_deseada" 
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" 
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción del Problema</label>
                            <textarea name="descripcion" id="descripcion" rows="4" placeholder="Describa brevemente lo que le sucede a su vehículo..." required></textarea>
                        </div>
                        <button type="submit" class="btn-primary full-width">Enviar Solicitud</button>
                    </div>
                </form>
            </div>
        </section>

    <footer>
        <div class="container">
            <p>&copy; 2026 Tarea N°04 - Taller Mecánico Profesional. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
