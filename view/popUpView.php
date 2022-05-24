<?php
  
  class PopUp{
    

    public function __construct($title,$message){
      $this->title=$title;
      $this->message=$message;
      echo"<div class='modal' tabindex='-1' style='display:block; background-color:rgba(0,0,0,0.5);' id='modal'>
            <div class='modal-dialog'>
              <div class='modal-content'>
                <div class='modal-body'>
                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=cerrarPopUp();></button>
                  <div><h3>".$title."</h3></br><p>".$message."</p></div>
                </div>
              </div>
            </div>
          </div>";
    }


    
  }
