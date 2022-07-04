const tarjeta = document.querySelector('#tarjeta'),
	  btnAbrirFormulario = document.querySelector('#btn-abrir-formulario'),
	  formulario = document.querySelector('#formulario-tarjeta'),
	  numeroTarjeta = document.querySelector('#tarjeta .numero'),
	  nombreTarjeta = document.querySelector('#tarjeta .nombre'),
	  logoMarca = document.querySelector('#logo-marca'),
	  firma = document.querySelector('#tarjeta .firma p'),
	  mesExpiracion = document.querySelector('#tarjeta .mes'),
	  yearExpiracion = document.querySelector('#tarjeta .year');
	  ccv = document.querySelector('#tarjeta .ccv');

// * Volteamos la tarjeta para mostrar el frente.
const mostrarFrente = () => {
	if(tarjeta.classList.contains('active')){
		tarjeta.classList.remove('active');
	}
}

// * Rotacion de la tarjeta
tarjeta.addEventListener('click', () => {
	tarjeta.classList.toggle('active');
});

// * Boton de abrir formulario
btnAbrirFormulario.addEventListener('click', () => {
	btnAbrirFormulario.classList.toggle('active');
	formulario.classList.toggle('active');
});

// * Select del mes generado dinamicamente.
for(let i = 1; i <= 12; i++){
	let opcion = document.createElement('option');
	if(i<10){
		opcion.value = ''+0+i;
		opcion.innerText = ''+0+i;

	}else{
		opcion.value = i;
		opcion.innerText = i;
	}
	
	formulario.selectMes.appendChild(opcion);
}

// * Select del año generado dinamicamente.
const yearActual = new Date().getFullYear();
for(let i = yearActual; i <= yearActual + 8; i++){
	let opcion = document.createElement('option');
	opcion.value = i;
	opcion.innerText = i;
	formulario.selectYear.appendChild(opcion);
}

// * Input numero de tarjeta
formulario.inputNumero.addEventListener('keyup', (e) => {
	let valorInput = e.target.value;

	formulario.inputNumero.value = valorInput
	// Eliminamos espacios en blanco
	.replace(/\s/g, '')
	// Eliminar las letras
	.replace(/\D/g, '')
	// Ponemos espacio cada cuatro numeros
	.replace(/([0-9]{4})/g, '$1 ')
	// Elimina el ultimo espaciado
	.trim();

	numeroTarjeta.textContent = valorInput;

	if(valorInput == ''){
		numeroTarjeta.textContent = '#### #### #### ####';

		logoMarca.innerHTML = '';
	}

	if(valorInput[0] == 4){
		logoMarca.innerHTML = '';
		const imagen = document.createElement('img');
		imagen.src = 'img/logos/visa.png';
		logoMarca.appendChild(imagen);
	} else if(valorInput[0] == 5){
		logoMarca.innerHTML = '';
		const imagen = document.createElement('img');
		imagen.src = 'img/logos/mastercard.png';
		logoMarca.appendChild(imagen);
	}

	// Volteamos la tarjeta para que el usuario vea el frente.
	mostrarFrente();
});

// * Input nombre de tarjeta
formulario.inputNombre.addEventListener('keyup', (e) => {
	let valorInput = e.target.value;

	formulario.inputNombre.value = valorInput.replace(/[0-9]/g, '');
	nombreTarjeta.textContent = valorInput;
	firma.textContent = valorInput;

	if(valorInput == ''){
		nombreTarjeta.textContent = 'Jhon Doe';
	}

	mostrarFrente();
});

// * Select mes
formulario.selectMes.addEventListener('change', (e) => {
	mesExpiracion.textContent = e.target.value;
	mostrarFrente();
});

// * Select Año
formulario.selectYear.addEventListener('change', (e) => {
	yearExpiracion.textContent = e.target.value.slice(2);
	mostrarFrente();
});

// * CCV
formulario.inputCCV.addEventListener('keyup', () => {
	if(!tarjeta.classList.contains('active')){
		tarjeta.classList.toggle('active');
	}

	formulario.inputCCV.value = formulario.inputCCV.value
	// Eliminar los espacios
	.replace(/\s/g, '')
	// Eliminar las letras
	.replace(/\D/g, '');

	ccv.textContent = formulario.inputCCV.value;
});




$("#selectMoneda").change(function () {
	$("#selectMoneda option:selected").each(function () {

		/*REVISAR ESTA LOGICA*/
		
		var tc = 73.10;
		// monedaCambio = 'USD'
		var cobroCredito = document.getElementById("cobroCredito").innerText;
		cobroCredito
		monedaSelect = $(this).val();
		var totalConvertido = tc*cobroCredito;
		
		if(monedaSelect=='ARS'){
			tc= 150;
			var totalConvertido = tc*cobroCredito;
			
		}
		var inputTotalConvert = document.getElementById("totalConvert");
		inputTotalConvert.setAttribute('value',totalConvertido);

		});            
	});


	document.addEventListener("DOMContentLoaded", function() {
		document.getElementById("formulario-tarjeta").addEventListener("submit", validarFormularioTarjeta);
	  });
	  
	  function validarFormularioTarjeta(evento) {
		evento.preventDefault();

		var moneda = document.getElementById("selectMoneda").value;
		var mes = document.getElementById("selectMes").value;
		var anio = document.getElementById("selectYear").value;
		var ccv = document.getElementById("inputCCV").value;
		var tarjeta = document.getElementById("inputNumero").value;
		var anioActual = new Date();
		anioActual = anioActual.getFullYear();
		var mesActual = new Date();
		mesActual = mesActual.getMonth();

		  if(!(moneda=='ARS' || moneda =='USD')) {
		  alert("Ingrese una moneda de pago");
		  return;
		}

		if(anio==anioActual && mes<=mesActual) {
			alert("Tarjeta vencida");
			return;
		}
		
		if(isNaN(mes) || mes<1 || mes>12 || mes.toString().length<1 || mes.toString().length>2) {
			alert("Ingrese un mes válido");
			return;
		  }
		  
		if(isNaN(anio) || anio<anioActual || anio.toString().length<4 || anio.toString().length>4) {
			alert("Ingrese un año válido");
			return;
		}

		if(isNaN(ccv) || ccv.toString().length<3 || ccv.toString().length>3) {
			alert("Código de seguridad inválido");
			return;
		}

		if(tarjeta.length<19 || tarjeta.length>19){
			alert("Tarjeta inválido");
			return;
		}

		this.submit();
    
	}
  

