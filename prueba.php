<?php declare(strict_types=1);

  /*------------//BD UNIDAS ITEM-TRANSACCION-ACTIVIDAD_EVENTO-ACTIVIDAD//--------------*/
  $leftjoinItemActvidad="((item LEFT JOIN transaccion ON item.transaccion_id=transaccion.id) LEFT JOIN actividad_evento ON item.evento_id=actividad_evento.id) LEFT JOIN actividad ON actividad_evento.actividad_id=actividad.id";

  function mostrarProovedores(string $conxServername, string $conxUsername, string $conxPassword, string $conxDatabase){
    $provIDArray = array();
    /*------------//GENERA EL BUFFERING TEMPORAL DE PHP//--------------*/
    ob_start();

    $conx = mysqli_connect($conxServername, $conxUsername, $conxPassword,$conxDatabase);

    /*------------//LISTA DE PROVEEDORES//--------------*/
    $queryProv =  "SELECT proveedor.id as provId, proveedor.nombre as provNombre ".
                  "FROM proveedor ";

    $exProv = mysqli_query($conx,$queryProv);

    /*------------//BD UNIDAS ITEM-TRANSACCION-ACTIVIDAD_EVENTO-ACTIVIDAD//--------------*/
    GLOBAL $leftjoinItemActvidad;

    while($arrProv = mysqli_fetch_array($exProv))
    {
      array_push($provIDArray, $arrProv['provId']);

      echo "<table border='1'>
      <tr>";

      /*------------//FORMATO CON URL SIMBOLICA//--------------*/
      /*echo "<th><a href='https://admin.entrekids.cl/proveedor/".$arrProv['provId']."'>".$arrProv['provNombre'] . "</a></tH>";*/

      /*------------//FORMATO URL FUNCIONAL ASUMIENDO DIRECCION ACTUALA ES ./PROVEEDOR/INDEX.HTML//--------------*/
      echo "<th><a href=".$arrProv['provId'].">".$arrProv['provNombre'] . "</a></tH>";

      echo "<th>VENTA EXP.</th>
      <th>VENTA PROD.</th>
      </tr>";

      /*------------//LISTA DE COMPRAS POR PAQUETE Y ENTRADA POR PROVEEDOR//--------------*/
      $queryVentas =  "SELECT month(transaccion.created) AS mes, SUM(CASE WHEN entrada.item_id=item.id THEN transaccion.total ELSE 0 END) AS entradaCant, SUM(CASE WHEN paquete.item_id=item.id THEN transaccion.total ELSE 0 END) AS entradaPaq
                      FROM ((".$leftjoinItemActvidad.") LEFT JOIN paquete ON paquete.item_id=item.id) LEFT JOIN entrada ON entrada.item_id=item.id
                      WHERE transaccion.estado!='CANCELADO'
                      AND actividad.proveedor_id=".$arrProv['provId']."
                      GROUP BY mes";

      $exVentas = mysqli_query($conx,$queryVentas);

      while($arrVentas = mysqli_fetch_array($exVentas))
      {
        echo "<tr>";
        echo "<td>" . date("F", mktime(0, 0, 0, (int)$arrVentas['mes'], 10)) . "</td>";
        echo "<td>".$arrVentas['entradaCant']."</td>";
        echo "<td>".$arrVentas['entradaPaq']."</td>";
        echo "</tr>";
      }
      echo "</table><br>";
    }
    mysqli_close($conx);

    /*------------//TERMINA EL BUFFERING TEMPORAL DE PHP Y GENERA UN HTML//--------------*/
    file_put_contents('proveedores.html', ob_get_contents());
    return $provIDArray;
  }

  function mostrarProovedorID (string $conxServername, string $conxUsername, string $conxPassword, string $conxDatabase, int $IDproov=0){
    /*------------//GENERA EL BUFFERING TEMPORAL DE PHP//--------------*/
    ob_start();

    $conx = mysqli_connect($conxServername, $conxUsername, $conxPassword,$conxDatabase);

    /*------------//BD UNIDAS ITEM-TRANSACCION-ACTIVIDAD_EVENTO-ACTIVIDAD//--------------*/
    GLOBAL $leftjoinItemActvidad;

    echo "<table border='1'>
    <tr>
    <th>MES</tH>
    <th>CANT. MAS VENDIDA</th>
    <th>ACT. MAS GANACIA</th>
    <th>ACT. MAS CANCELADO</th>
    </tr>";

    /*------------//LISTA DE MESES CON CUALQUIER TIPO DE ACTIVIDAD DE CADA PROVEEDOR//--------------*/
    $queryMeses =  "SELECT month(transaccion.created) as mes
    FROM ".$leftjoinItemActvidad."
    WHERE actividad.proveedor_id=".$IDproov."
    GROUP BY item.evento_id, mes
    ORDER BY month(transaccion.created) ASC";

    $exMeses = mysqli_query($conx,$queryMeses);
    while($arrMeses = mysqli_fetch_array($exMeses))
    {
      echo "<tr>";
      echo "<td>" . date("F", mktime(0, 0, 0, (int)$arrMeses['mes'], 10)) . "</td>";

      /*------------//LISTA DE CANTIDAD MAS VENDIDA POR MES DE CADA PROVEEDOR//--------------*/
      $queryMasVend =  "SELECT SUM(item.cantidad) AS cant, month(transaccion.created) as mes
                        FROM ".$leftjoinItemActvidad."
                        WHERE actividad.proveedor_id=".$IDproov." AND month(transaccion.created)=".$arrMeses['mes']."
                        AND transaccion.estado!='CANCELADO'
                        GROUP BY item.evento_id, mes
                        ORDER BY cant DESC LIMIT 1";

      $exMasVend = mysqli_query($conx,$queryMasVend);
      $arrMasVend = mysqli_fetch_array($exMasVend);
      echo "<td>".$arrMasVend['cant']."</td>";

      /*------------//LISTA DE PRODUCTO CON MAS INGRESOS POR MES DE CADA PROVEEDOR//--------------*/
      $queryMasIngr =  "SELECT SUM(item.cantidad) AS cant, SUM(transaccion.total) AS totalt, month(transaccion.created) as mes, actividad.nombre as nombre
                        FROM ".$leftjoinItemActvidad."
                        WHERE transaccion.estado!='CANCELADO'
                        AND actividad.proveedor_id=".$IDproov." AND month(transaccion.created)=".$arrMeses['mes']."
                        GROUP BY item.evento_id, mes
                        ORDER BY totalt DESC LIMIT 1";

      $exMasIngr = mysqli_query($conx,$queryMasIngr);
      $arrMasIngr = mysqli_fetch_array($exMasIngr);
      echo "<td>".$arrMasIngr['nombre']."</td>";

      /*------------//LISTA DE PRODUCTO CON MAS CANCELADOS POR MES DE CADA PROVEEDOR//--------------*/
      $queryMasCanc =  "SELECT SUM(item.cantidad) AS cant, month(transaccion.created) as mes, actividad.nombre as nombre
                        FROM ".$leftjoinItemActvidad."
                        WHERE transaccion.estado='CANCELADO'
                        AND actividad.proveedor_id=".$IDproov." AND month(transaccion.created)=".$arrMeses['mes']."
                        GROUP BY item.evento_id, mes
                        ORDER BY cant DESC LIMIT 1";

      $exMasCanc = mysqli_query($conx,$queryMasCanc);
      $arrMasCanc = mysqli_fetch_array($exMasCanc);
      echo "<td>".$arrMasCanc['nombre']."</td>";
      echo "</tr>";
    }
    echo "</table><br>";

    mysqli_close($conx);

    /*------------//TERMINA EL BUFFERING TEMPORAL DE PHP Y GENERA UN HTML//--------------*/
    file_put_contents($IDproov.'.html', ob_get_contents());
    return;
  }

  /*------------//CONEXION MYSQL//--------------*/
  $conxServername = "localhost";
  $conxUsername = "root";
  $conxPassword = "";
  $conxDatabase = "entrekids";

  /*------------//PUNTO 3 - LISTA DE PROOVEDORES CON VENTAS//--------------*/
  $prov = mostrarProovedores($conxServername,$conxUsername,$conxPassword,$conxDatabase);

  /*------------//PUNTO 4 - DETALLE PROOVEDORES CON PAGINAS INDIVIDUALES//--------------*/
  foreach ($prov as &$provID) {
      mostrarProovedorID($conxServername,$conxUsername,$conxPassword,$conxDatabase,(int)$provID);
  }

?>
