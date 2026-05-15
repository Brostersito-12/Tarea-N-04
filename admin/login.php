<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Taller Mecánico</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-wrapper-card">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">Taller<span>Mecánico</span></div>
                <h2>Bienvenido</h2>
                <p>Ingresa a tu cuenta administrativa</p>
            </div>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert error">
                    <i class="fas fa-times-circle"></i> Correo o contraseña incorrectos.
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['registered'])): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> Registro exitoso. ¡Inicia sesión!
                </div>
            <?php endif; ?>

            <form action="actions/login_process.php" method="POST" class="login-form">
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
                <button type="submit" class="btn-login-submit">Ingresar <i class="fas fa-arrow-right"></i></button>
            </form>
            
            <div class="login-footer">
                <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
                <a href="../index.php" class="back-link"><i class="fas fa-home"></i> Volver al Inicio</a>
            </div>
        </div>
    </div>
</body>
</html>
