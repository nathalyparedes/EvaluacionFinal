<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/right_side.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body>
    <div class="sidebar">
        <button class="collapse-btn">☰</button>
        <ul>
        <li><a href="../dashboard/dashboard.php"><span class="material-icons-sharp">home</span><span class="text">Inicio</span></a></li>
            <li><a href="../peliculas/peliculas.php"><span class="material-icons-sharp">movie</span><span class="text">Películas</span></a></li>
            <li><a href="../actores/actores.php"><span class="material-icons-sharp">person</span><span class="text">Actores</span></a></li>
            <li><a href="../actores_peliculas/actores_peliculas.php"><span class="material-icons-sharp">contacts</span><span class="text">Actores y Películas</span></a></li>
        </ul>
        
        <footer>
    <div class="container-fluid">
        <div class="bg-light rounded-top p-4">
            <div class="row">
                <div class="col-12 col-sm-6 text-center text-sm-start">
                    &copy; <a href="#">NathalyP</a>
                </div>
                <div class="col-12 col-sm-6 text-center text-sm-end">
                    By me
                </div>
            </div>
        </div>
    </div>
</footer>
        
    </div>
    <div class="topbar">
        <h1>Evaluación Final</h1>
    </div>


    </body>
    <script>
        document.querySelector('.collapse-btn').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.topbar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('collapsed');
});

    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
