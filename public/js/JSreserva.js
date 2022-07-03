var tipoVuelo = document.getElementById("tipoVuelo").value;

if(tipoVuelo=='ED1' || tipoVuelo=='ED2'){
  

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("reservaForm").addEventListener("submit", validarFormularioReserva);
  });
  
  function validarFormularioReserva(evento) {
    evento.preventDefault();
    var origen = document.getElementById("origen").value;
    var destino = document.getElementById("destino").value;

    if(origen==destino) {
      alert("El origen y el destino no pueden ser iguales");
      return;
    }
    
    if(origen>destino) {
      alert("seleccion de tramo inválida");
      return;
    }
    


    this.submit();
    
  }
}

