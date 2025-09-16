<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management API</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            margin: 2rem;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        .subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .feature {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .feature h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
        }
        .feature p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        .links {
            margin-top: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5a6fd8;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .status {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 School Management API</h1>
        <p class="subtitle">API completa para la gestión escolar con Laravel y Swagger</p>
        
        <div class="status">
            ✅ API funcionando correctamente
        </div>

        <div class="features">
            <div class="feature">
                <h3>🔐 Autenticación JWT</h3>
                <p>Sistema seguro de autenticación con tokens</p>
            </div>
            <div class="feature">
                <h3>👥 Gestión de Usuarios</h3>
                <p>Administradores, profesores y estudiantes</p>
            </div>
            <div class="feature">
                <h3>📚 Cursos y Matrículas</h3>
                <p>Gestión completa de cursos académicos</p>
            </div>
            <div class="feature">
                <h3>📊 Calificaciones</h3>
                <p>Registro y seguimiento de notas</p>
            </div>
            <div class="feature">
                <h3>📅 Asistencia</h3>
                <p>Control de asistencia estudiantil</p>
            </div>
            <div class="feature">
                <h3>📖 Documentación</h3>
                <p>API completamente documentada con Swagger</p>
            </div>
        </div>

        <div class="links">
            <a href="/api/documentation" class="btn" target="_blank">📖 Ver Documentación Swagger</a>
            <a href="/api/dashboard/stats" class="btn btn-secondary">📊 Estadísticas</a>
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
            <h3>Usuarios de Prueba</h3>
            <p><strong>Admin:</strong> admin@school.com / password</p>
            <p><strong>Profesor:</strong> john.teacher@school.com / password</p>
            <p><strong>Estudiante:</strong> alice.student@school.com / password</p>
        </div>
    </div>
</body>
</html>