function validar(){
    var regexemail = /^[0-9a-zA-Z._.-]+\@[0-9a-zA-Z._.-]+\.[0-9a-zA-Z]+$/;
    var mensaje ="";
    var error =0;
    reset();
    if($("#nombre").val()==""){
        mensaje+="<p>El campo de nombre no puede estar vacío.</p>";
        error++;
        $("#nombre").addClass('error');
    }
    if($("#apellido").val()==""){
        mensaje+="<p>El campo de apellido no puede estar vacío.</p>";
        error++;
        $("#apellido").addClass('error');
    }
    if($("#dni").val()==""){
        mensaje+="<p>El campo de DNI no puede estar vacío.</p>";
        error++;
        $("#apellido").addClass('error');
    }
    if($("#dni").val().length>'8'){
        mensaje+="<p>Introduzca un DNI válido.</p>";
        error++;
        $("#apellido").addClass('error');
    }
    if(!$("#email").val().match(regexemail)){
        mensaje+= "<p>Debe ingresarse un email válido.</p>";
        error++;
        $("#email").addClass('error');
    }
    if($("#clave").val()==""){
        mensaje+="<p>La contraseña debe contener caracteres.</p>";
        error++;
        $("#clave").addClass('error');
    }
    if (error>0){
        $("#mensaje").append(mensaje);
        return false;
    }
    else{
        return true;
    }
}
function reset(){
    $("#nombre").removeClass('error');
    $("#apellido").removeClass('error');
    $("#dni").removeClass('error');
    $("#email").removeClass('error');
    $("#clave").removeClass('error');
    $("#mensaje").empty();
}
$(document).ready(function(){
    $("#formulario").submit(function(){
        return validar();
    });
    $("#nombre").keyup(function(){
        validar();
    });
    $("#apellido").keyup(function(){
        validar();
    });
    $("#dni").keyup(function(){
       validar();
    });
    $("#email").keyup(function(){
        validar();
    });
    $("#clave").keyup(function(){
        validar();
    });
})
