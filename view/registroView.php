<div class="contenido registro">
    <form action="index.php?module=registro&method=registrar" method="POST" id="formulario">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre">
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido">
        <label for="dni">DNI:</label>
        <input type="number" id="dni" name="dni">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control">
        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" id="clave" class="form-control">
        <input type="submit" value="Registrarme">
        <span id="mensaje" class="error">

        </span>
    </form>
</div>