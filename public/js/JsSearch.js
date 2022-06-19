$( "#idaVuelta" ).on( "click", function(){
  document.getElementById("fechaVuelta").required = true;
});



$( "#ida" ).on( "click", function(){
  document.getElementById("fechaVuelta").required = false;
});
  
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("busqueda").addEventListener("submit", validarFormularioBusqueda);
  });
  
  function validarFormularioBusqueda(evento) {
    evento.preventDefault();
    var origen = document.getElementById("origen").value;
    var destino = document.getElementById("destino").value;

    if(!isNaN(origen) || origen.length>20) {
      alert("El origen ingresado no es válido");
      return;
    }
    if(!isNaN(destino) || destino.length>20) {
      alert("El destino ingresado no es válido");
      return;
    }


    this.submit();
    
  }


