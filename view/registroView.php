<div class="container contenido justify-content-center content">
    <h2 class="p-4">Registrate y comenzá esta maravillosa aventura!</h2>
    <form action="/registro/registrar" method="POST" id="formulario" enctype="multipart/form-data" class="row justify-content-center p-5 formulario" >
        <div class="col-12 row div-form">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="col-12 row div-form">
            <label for="apellido" >Apellido</label>
            <input type="text" id="apellido" name="apellido" required> 
        </div>
        <div class="col-12 row div-form">
            <label for="dni" >DNI</label>
            <input type="number" id="dni" name="dni" required>  
        </div>
        <div class="col-12 row div-form">
            <label for="email"  >Email</label>
            <input type="email" id="email" name="email" required>            
        </div>
        <div class="col-12 row div-form">
            <label for="user" >Usuario</label>
            <input type="text"  id="user" name="user" required>            
        </div>
        <div class="col-12 row div-form">
            <label for="clave" >Contraseña</label>
            <input type="password" name="clave" id="clave" required>            
        </div>
        <div class="col-12 row div-form">
            <input type="submit" value="Registrarme" class="btn-primary btn-registro">
        </div>
    </form>
</div>