  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("busqueda").addEventListener("submit", validarFormularioBusqueda);
  });
  
  function validarFormularioBusqueda(evento) {
    evento.preventDefault();
    var origen = document.getElementById("origen").value;
    var destino = document.getElementById("destino").value;
    var idaVueltaRadio = document.querySelectorAll('input[name="ida"]');
    var fechaVuelta = document.getElementById("fechaVuelta").value;


    if(!isNaN(origen) || origen.length>20) {
      alert("El origen ingresado no es válido");
      return;
    }
    if(!isNaN(destino) || destino.length>20) {
      alert("El destino ingresado no es válido");
      return;
    }

    let prueba;
    for (const idaVuelta of idaVueltaRadio) {
      if (idaVuelta.checked) {
        prueba = idaVuelta.value;
        break;
      }
    }

    if(prueba=="1") {
      if(fechaVuelta==""){
        alert("Debe ingresar una fecha de regreso o seleccionar sólo ida");
        return;
      }
    }
    
    this.submit();
  }


