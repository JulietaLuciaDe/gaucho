<?php
  
  class Recovery{

    public function __construct($email){
      $this->email=$email;
      echo"<div class='content container cont-recovery'>
      
       
       <form action='index.php?module=logIn&method=saveRecovery' method='POST' enctype='multipart/form-data' class='row recovery-form '>
           <h2>Ingresá tu nueva clave</h2>
           <label for='usuario'></label>Usuario</label>
           <input type='text' name='usuario' value = $email>
           <label for='password'>Clave</label>
           <input type='password' name='password'>
           <input type='submit' class='btn-primary btn-registro' value='Ingresar'>
       </form>
       
   </div>";
    }


    
  }