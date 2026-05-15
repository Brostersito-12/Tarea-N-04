<?php
require_once 'config/db.php';

// Obtener servicios de la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM servicios");
    $servicios = $stmt->fetchAll();
} catch (PDOException $e) {
    $servicios = []; // Fallback si no hay tabla servicios aún
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller Mecánico Profesional</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">Taller<span>Mecánico</span></div>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#contacto">Contacto</a></li>
                <li><a href="admin/login.php" class="btn-login">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Confianza y Calidad para su Vehículo</h1>
                <p>Especialistas en mantenimiento preventivo y correctivo con tecnología de punta.</p>
                <a href="solicitud.php" class="btn-primary">Reservar Cita Ahora</a>
            </div>
        </section>

        <section id="servicios" class="services container">
            <h2 class="section-title">Nuestros <span>Servicios</span></h2>
            <div class="services-grid">
                <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $s): ?>
                        <div class="service-card">
                            <i class="fas fa-tools"></i>
                            <h3><?php echo htmlspecialchars($s['nombre_servicio']); ?></h3>
                            <p><?php echo htmlspecialchars($s['descripcion']); ?></p>
                            <span class="price">Precio aprox: $<?php echo number_format($s['precio'], 2); ?></span>
                            <a href="solicitud.php?servicio=<?php echo $s['id_servicio']; ?>" class="btn-card">Solicitar Atención</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Servicios estáticos si la DB está vacía -->
                    <div class="service-card">
                        <i class="fas fa-oil-can"></i>
                        <h3>Cambio de Aceite</h3>
                        <p>Cambio de aceite multigrado y filtro para proteger su motor.</p>
                        <span class="price">Desde $35.00</span>
                        <a href="solicitud.php" class="btn-card">Solicitar Atención</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-car-crash"></i>
                        <h3>Revisión de Frenos</h3>
                        <p>Inspección completa de pastillas, discos y líquido de frenos.</p>
                        <span class="price">Desde $50.00</span>
                        <a href="solicitud.php" class="btn-card">Solicitar Atención</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-align-center"></i>
                        <h3>Alineación y Balanceo</h3>
                        <p>Ajuste de ángulos de las ruedas para un manejo suave y seguro.</p>
                        <span class="price">Desde $40.00</span>
                        <a href="solicitud.php" class="btn-card">Solicitar Atención</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-laptop-code"></i>
                        <h3>Diagnóstico Computarizado</h3>
                        <p>Escaneo de errores y fallas electrónicas del sistema.</p>
                        <span class="price">Desde $60.00</span>
                        <a href="solicitud.php" class="btn-card">Solicitar Atención</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="info-section">
            <div class="container info-grid">
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <h3>Horarios</h3>
                    <p>Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                    <p>Sábados: 8:00 AM - 1:00 PM</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Ubicación</h3>
                    <p>Av. Principal 123, Sector Industrial</p>
                    <p>Ciudad Capital, CP 1000</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <h3>Contacto</h3>
                    <p>Tel: (01) 456-7890</p>
                    <p>Email: contacto@tallermecanico.com</p>
                </div>
            </div>
        </section>

        <section id="contacto" class="contact container">
            <h2 class="section-title"><span>Contáctanos</span></h2>
            <div class="contact-container">
                <form class="contact-form">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" placeholder="Tu Nombre" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="Tu Email" required>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Mensaje</label>
                        <textarea placeholder="Tu Mensaje" rows="5" required></textarea>
                    </div>
                    <div style="grid-column: span 2; text-align: center;">
                        <button type="submit" class="btn-primary">Enviar Mensaje</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 Tarea N°04 - Taller Mecánico Profesional. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
