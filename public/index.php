<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Superbid</title>
</head>
<body>
    <form action="../controllers/upload.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="rg-nombre-subasta" id="rg-nombre-subasta" placeholder="Nombre subasta">
        <select name="rg-tipo-persona" id="rg-tipo-persona">
            <option value="p-juridica">Persona Juridica</option>
            <option value="p-natural">Persona Natural</option>
        </select>

        <select name="rg-tipo-doc" id="rg-tipo-doc">
            <option value="cedula-ciudadania">Cedula ciudadania</option>
            <option value="nit">nit</option>
        </select>
        <input type="number" name="rg-num-doc" id="documento" placeholder="Documento">
        <input type="file" name="rg-field-documento" id="rg-field-documento">
        <input type="file" name="rg-field-rut" id="rg-field-rut">
        <input type="file" name="rg-field-carta-tercero" id="rg-field-carta-tercero">
        <input type="file" name="rg-field-soporte-pago-lote" id="rg-field-soporte-pago-lote">

        <input type="radio" name="rg-type-document" id="rg-type-registro" value="registro">
        <label for="rg-type-registro">Registro</label>
        <br>
        <input type="radio" name="rg-type-document" id="rg-type-pagos" value="pagos">
        <label for="rg-type-pagos">Pagos</label>
        <input type="submit" name="btnenviar" value="Enviar">
    </form>
</body>
</html>