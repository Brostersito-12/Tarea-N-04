<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Taller Mecánico</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-wrapper-card">
        <div class="login-card register-card">
            <div class="login-header">
                <div class="logo">Taller<span>Mecánico</span></div>
                <h2>Crear Cuenta</h2>
                <p>Únete al equipo administrativo</p>
            </div>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert error">
                    <i class="fas fa-times-circle"></i> 
                    <?php 
                        if($_GET['error'] == 'email_exists') echo "El correo ya está registrado.";
                        else echo "Hubo un error al procesar tu registro.";
                    ?>
                </div>
            <?php endif; ?>

            <form action="actions/register_process.php" method="POST" class="login-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" name="apellido" id="apellido" placeholder="Tu apellido" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="correo" id="correo" placeholder="ejemplo@correo.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="btn-login-submit">Registrarse <i class="fas fa-user-plus"></i></button>
            </form>
            
            <div class="login-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
                <a href="../index.php" class="back-link"><i class="fas fa-home"></i> Volver al Inicio</a>
            </div>
        </div>
    </div>
</body>
</html>
