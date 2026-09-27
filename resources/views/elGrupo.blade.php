<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Equipo - Grupo ODS 12</title>
    <link rel="stylesheet" href="{{ asset('css/grupo1.css') }}">
</head>
<body>

    <!-- Menú de navegación superior -->
    @include('partials.header')

    <section class="team" style="padding: 60px 20px; text-align: center;">
        <span class="chapter-number">El equipo</span>
        <h2>Quiénes hacemos el proyecto</h2>

        <div class="team-grid" style="display: flex; justify-content: center; gap: 30px; margin-top: 40px; flex-wrap: wrap;">
            
            <!-- Miembro 1 -->
            <div class="team-member" style="background: #f4f1ea; padding: 20px; border-radius: 12px; width: 220px;">
                <p class="role">Miembro 01</p>
                <a href="/paginaPersonal/Ricardo" style="font-weight: bold; font-size: 1.1rem;">Ricardo Martel</a>
            </div>

            <!-- Miembro 2 -->
            <div class="team-member" style="background: #f4f1ea; padding: 20px; border-radius: 12px; width: 220px;">
                <p class="role">Miembro 02</p>
                <a href="/paginaPersonal/Rosalinda" style="font-weight: bold; font-size: 1.1rem;">Rosalinda</a>
            </div>

            <!-- Miembro 3 -->
            <div class="team-member" style="background: #f4f1ea; padding: 20px; border-radius: 12px; width: 220px;">
                <p class="role">Miembro 03</p>
                <a href="/paginaPersonal/Adrian" style="font-weight: bold; font-size: 1.1rem;">Adrián</a>
            </div>

        </div>
    </section>

    @include('partials.footer')

</body>
</html>