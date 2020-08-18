<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Superbid</title>
</head>
<body>
    <form action="../controllers/upload.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" id="" placeholder="Nombre">
        <input type="number" name="documento" id="documento" placeholder="Documento">
        <input type="file" name="file" id="">
        <input type="submit" name="btnenviar" value="Enviar">

    </form>
</body>
</html>