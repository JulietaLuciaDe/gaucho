//VALIDACION FORMULARIO BUSQUEDA

  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("busqueda").addEventListener("submit", validarFormularioBusqueda); 
  });
  
  function validarFormularioBusqueda(evento) {
    evento.preventDefault();
    var origen = document.getElementById("origen");
    var destino = document.getElementById("destino");
    var ida = document.getElementById("ida").value;
    var fechaVuelta = document.getElementById("fechaVuelta").value;
    
    if(typeof origen === 'string' || origen instanceof String) {
      alert("El destino ingresado no es válid000000o");
      return;
    }
    if(typeof destino === 'string' || destino instanceof String) {
        alert("El destino ingresado no es válido");
        return;
    }
    if(ida==1) {
      if(fechaVuelta=== null){
        alert("Debe ingresar una fecha de regreso o seleccionar sólo ida");
        return;
      } 
  }
    
    this.submit();
  }


