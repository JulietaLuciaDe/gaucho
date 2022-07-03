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




