<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Superbid | Gracias</title>
<link href="css/main-styles.css" rel="stylesheet"></head>
<body>
    <header class="sb-header py-4 fixed-top bg-secondary">
        <div class="container">
            <div class="d-flex justify-content-between nav-container">
                <div class="sb-nav-brand">
                    <a href="https://www.superbidcolombia.com/"><img src="./static/logo-superbid.svg" alt="" class="img-fluid"></a>
                </div>
                <div class="sb-nav-menu">
                    <ul class="nav justify-content-center mt-3 d-none d-lg-flex">
                        <li class="nav-item"><a href="tel:0317438088" class="nav-link"><i class="fas fa-phone-alt"></i> 1-7438088</a>
                        </li>
                        <li class="nav-item"><a href="https://api.whatsapp.com/send?phone=573107078989&text=Hola,%20necesito%20informaci%C3%B3n%20sobre" class="nav-link" target="_blank"><i class="fab fa-whatsapp"></i> 310 707 8989</a>
                        </li>
                        <li class="nav-item"><a href="mailto:subastas@superbid.com.co" class="nav-link"><i class="fas fa-envelope"></i>
                                subastas@superbid.com.co</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <main class="sb-main pt-5">
        <div class="sb-content text-center py-5">
            <div class="container">
                <div class="icon-check">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="sb-title">GRACIAS</h2>
                <hr class="sb-sub-divider">
                <p>Sus documentos se han cargado exitosamente</p>
                <div class="alert alert-success py-4" role="alert">
                    <h4 class="alert-heading mb-2"><b>Su número de radicado es:</b></h4>
                    <p style="font-size: 20px; font-family: Arial, helvetica, sans-serif" class="m-0"><?php echo $_GET['rad']; ?></p>
                </div>
                <p>Por favor guarde este <b>número de radicado</b> para el seguimiento de sus solicitudes</p>
                <a href="index.html" class="btn btn-primary rounded-pill mt-3">Regresar</a>
            </div>
        </div>
    </main>

    <footer class="text-center sb-footer">
        <div class="sb-top-footer py-5 bg-dark text-light">
            <div class="container">
                <img src="./static/logo-superbid.svg" alt="" class="img-fluid">
                <ul class="nav justify-content-center mt-3">
                    <li class="nav-item"><a href="tel:0317438088" class="nav-link"><i class="fas fa-phone-alt"></i> 1-7438088</a></li>
                    <li class="nav-item"><a href="https://api.whatsapp.com/send?phone=573107078989&text=Hola,%20necesito%20informaci%C3%B3n%20sobre" class="nav-link" target="_blank"><i class="fab fa-whatsapp"></i> 310 707 8989</a>
                    </li>
                    <li class="nav-item"><a href="mailto:subastas@superbid.com.co" class="nav-link"><i class="fas fa-envelope"></i>
                            subastas@superbid.com.co</a></li>
                </ul>
            </div>
        </div>
        <div class="sb-bottom-footer py-3">
            <p class="m-0">SUPERBID 2020 | Todos los derechos reservados</p>
        </div>
    </footer><script src="js/bundle.js"></script></body>
</html>