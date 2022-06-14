//VALIDACION FORMULARIO DE REGISTRO
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener("submit", validarFormularioRegistro); 
  });
  
  function validarFormularioRegistro(evento) {
    evento.preventDefault();
    var name = document.getElementById("nombre").value;
    var lastName = document.getElementById("apellido").value;
    var dni = document.getElementById("dni").value;
    var pass = document.getElementById("clave").value;
    
    if(name.length<1) {
      alert("El nombre no puede estar vacío");
      return;
    }
    if(name.length>10) {
        alert("El nombre no puede tener mas de 10 caracteres");
        return;
    }
    if(lastName.length<1) {
        alert("El apellido no puede estar vacío");
        return;
    }
    if(lastName.length>10) {
          alert("El apellido no puede tener mas de 10 caracteres");
          return;
    }
    if(isNaN(dni)) {
        alert("El DNI debe ser numérico");
        return;
    }
    if(dni.length!=8) {
        alert("El DNI ingresado es inválido");
        return;
    }
    if(user.length>20) {
        alert("El usuario no puede tener mas de 20 caracteres");
        return;
    }
    if(pass.length>20) {
        alert("La contraseña no puede tener mas de 20 caracteres");
        return;
    }
    
    this.submit();
  }
  