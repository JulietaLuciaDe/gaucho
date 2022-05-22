<div class="contenido">
    <form action="index.php?module=registro&method=registrar" method="POST" >
        <div class="mb-3">
            <label for="exampleInputName1" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="exampleInputName1" aria-describedby="lastNameHelp" name="name">
        </div>
        <div class="mb-3">
            <label for="exampleInputLastName1" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="exampleInputLastName1" aria-describedby="lastNameHelp" name="lastName">
        </div>
        <div class="mb-3">
            <label for="exampleInputDNI1" class="form-label">DNI</label>
            <input type="number" class="form-control" id="exampleInputDNI1" name="dni">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo</label>
            <input type="email" class="form-control" id="exampleInputEmail1" name="user">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="pass">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>