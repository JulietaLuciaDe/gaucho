<?php

if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1){
  $menu ="<a href='index.php?module=logIn&method=exit'>Cerrar Sesion</a>";
}else{
  $menu ="<a href='index.php?module=registro'>Registrarse</a>
  <a href='index.php?module=logIn'>Ingresar</a>";
  
}
echo"<nav class='navbar navbar-dark bg-dark nav row align-items-center justify-content-around m-0'>
  
    
    <div class='col-4'>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarToggleExternalContent' aria-controls='navbarToggleExternalContent' aria-expanded='false' aria-label='Toggle navigation'>
          <span class='navbar-toggler-icon'></span>
        </button>
    </div>
    <div class='col-4'>
        <div class='buscador'>
        <form action='index.php?module=buscador&method=buscar' method='get'>
            <input type='text' placeholder='Busque su viaje...' id='viajeABuscar'>
            <input type='submit' content='Buscar'>
        </form>
        </div>
    </div>
    <div class='col-4 menu'> 
       <a href='index.php' class='logo'><img src='public/logo.png' ></a>
    </div>
</nav>
<div class='collapse' id='navbarToggleExternalContent'  >
  <div class='bg-dark p-4 row dropdwn' >".$menu.
        
        
  "</div>
</div>";
?>