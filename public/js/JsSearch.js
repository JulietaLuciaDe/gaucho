let opciones  = document.getElementById("tipoVuelo")
      let cajaTexto = document.getElementById("tipoEquipo")
      
      opciones.addEventListener("change", () => {
        let eleccion = opciones.value
        
        if((eleccion === 'ED1') || (eleccion === 'ED2')) {
          cajaTexto.style.display = "block"
        } else {
          cajaTexto.style.display = "none"
        }
      })


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


