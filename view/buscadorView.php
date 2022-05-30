<?php
function imprimirResultado($data){
    echo "<div class='content'>
                    <table class='table table-responsive'>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Destino</th>
                                <th>Origen</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>";
    foreach ($data as $fila){
        echo "<tr>
            <td>".$fila['id']."</td>
            <td>".$fila['destino']."</td>
            <td>".$fila['origen']."</td>
            <td>".$fila['fecha']."</td>
        </tr>";
    }
    echo "</table>
          </div>";
}?>