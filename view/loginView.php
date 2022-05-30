<div class="content container cont-login">
    <form action="index.php?module=logIn&method=validarSesion" method="POST" enctype="multipart/form-data" class="row login-form ">
        <h2>Ingresá a tu cuenta</h2>
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario">
        <label for="password">Clave</label>
        <input type="password" name="password">
        <input type="submit" class="btn-primary btn-registro" value="Ingresar">
        <p>Aun no sos parte de GauchoRocket?</p>
        <a href='index.php?module=registro' class="log-registro">Crear una cuenta</a>
        <a class='log-registro' href='index.php?module=login&method=recuperar&email=$email&dni=$dni'>Olvidé mi clave</a>
    </form>
    
</div>