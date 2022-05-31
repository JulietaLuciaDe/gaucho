<div class="content container cont-login">
    <form action="/logIn/validarSesion" method="POST" enctype="multipart/form-data" class="row login-form ">
        <h2>Ingresá a tu cuenta</h2>
        <label for="usuario">Email</label>
        <input type="text" name="usuario" required>
        <label for="password">Clave</label>
        <input type="password" name="password" required>
        <input type="submit" class="btn-primary btn-registro" value="Ingresar">
        <p>Aun no sos parte de GauchoRocket?</p>
        <a href="/registro" class="log-registro">Crear una cuenta</a>
    </form>
    
</div>