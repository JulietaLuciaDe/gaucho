<div class="contenido registro">
    <form action="index.php?module=registro&method=registrar" method="POST" id="formulario"  class="row g-3 needs-validation">
        <div class="col-md-2">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="apellido" class="form-label" >Apellido:</label>
            <input type="text" id="apellido" name="apellido" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="dni" class="form-label">DNI:</label>
            <input type="number" id="dni" name="dni" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="email" class="form-label" >Email:</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="user" class="form-label" >Usuario:</label>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend">@</span>
                <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
            </div>
         </div>
        <div class="col-md-2">
            <label for="clave" class="form-label">Contraseña:</label>
            <input type="password" name="clave" id="clave" class="form-control">
        </div>
        <div class="col-md-12">
        <input type="submit" class="btn btn" value="Registrarme">
        </div>
        <span id="mensaje" class="error"> </span>
    </form>
</div>