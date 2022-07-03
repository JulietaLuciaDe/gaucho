var fechaTurno = document.getElementById('fechaTurno');
let hoy = new Date();
let DIA_EN_MILISEGUNDOS = 24 * 60 * 60 * 1000;
let manana = new Date(hoy.getTime() + DIA_EN_MILISEGUNDOS);
fechaTurno.setAttribute('value',manana.toISOString().split('T')[0]);

//ESTO NO ESTA FUNCIONANDO PORQUE NO RECONOCE EL FORM ( TRAE NULL)
var form = document.getElementById("turnoForm");
 document.addEventListener("DOMContentLoaded", function() {
        form.addEventListener("submit", validarFormularioTurno);
      });
      //alert(form);
  
  function validarFormularioTurno(evento) {
    evento.preventDefault();
    
        if (fechaTurno.value == '0000-00-00') {
            
            alert('Ingrese una fecha');
            return;
        
        
    }

    this.submit();

}


$("#fechaTurno").flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1),
    defaultDate: new Date().fp_incr(1),
    "disable": [
        function(date) {
           return (date.getDay() === 0 || date.getDay() === 6);  // disable weekends
        }
    ],
    "locale": {
        "firstDayOfWeek": 0 // set start day of week to Monday
    }
});

$("#hour").keydown(function() {
    return false
  });

  $("#fechaTurno").keydown(function() {
    return false
  });


 



