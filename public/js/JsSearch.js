let selectTipoVuelo  = document.getElementById("tipoVuelo")
let selectTipoEquipo = document.getElementById("tipoEquipo")
let LabelTipoEquipo = document.getElementById("tipoEquipoLabel")

      
selectTipoVuelo.addEventListener("change", () => {
  let eleccionVuelo = selectTipoVuelo.value
  
  if((eleccionVuelo === 'ED1') || (eleccionVuelo === 'ED2')) {
    selectTipoEquipo.style.display = "block"
    LabelTipoEquipo.style.display = "block"
  } else {
    selectTipoEquipo.style.display = "none"
    LabelTipoEquipo.style.display = "none"
  }
})




