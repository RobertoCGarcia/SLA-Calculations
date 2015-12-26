<?php

ini_set('post_max_size','100M');
ini_set('upload_max_filesize','100M');
ini_set('max_execution_time','18000');
ini_set('max_input_time','5500');

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

date_default_timezone_set("America/Mexico_City");

$dateTime = " Last Update: " . date("h:i:sa");

 /*
  * Funcion para revisar si la fecha es fin de semana o no
  *
  */
function isWeekend($date) {


   print "Date: ".$date."<br/>";
   
   return ( date('N', strtotime($date)) >= 6 );
}

?> 
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title> Calculos SLA </title>
</head>

<body>

<p>&nbsp;</p>

<table width="250" border="1" align="center">
  <tr>
    <td>
    
       <div align="center">       
               <h1>
                  Datos y Calculos del SLA
              </h1>
       </div>    
    
    </td>
  </tr>
  <tr>
    <td>
    
<div align="center">       
   <tt>     
     Reporte Generado <?php print $dateTime; ?> 
   </tt>
 </div>    
    
    </td>
  </tr>
</table>

   
<div id="wrapper">
 
  <section id="content">
  <div class="container">
    <div class="row">
      <div>
                            
        <article>
                <h1>Calculos SLA</h1>

                <p>&nbsp;</p>
                
                <div align="center">
                    <img src="graficoSLAtime.php">
                </div>


                <?php

                        $FI = "2015-12-01 03:09:47";
                        $FF = "2015-12-31 14:44:42";
                         

                        $SLACalculations = 0;

                         print "tmpFI: ".$FI."</br>";
                         print "tmpFF: ".$FF."</br>";

                      
                        for ($date = strtotime($FI); $date < strtotime($FF); $date = strtotime("+1 day", $date)) {
                           
                               

                               echo date("Y-m-d", $date)."<br/>";
                               //echo date("Y-m-d", $date)." isWeekend= ".isWeekend($date." 00:00:00")."<br/>";

                               //if(isWeekend($date) == 0){                                  
                               //     echo date("Y-m-d", $date)."<br/>";
                               //}

                        }
                ?>
                

        </article>
  
  
      </div>    
            
    
    </div>
            
            
                <?php 
                      
                       $mtime = microtime();
                       $mtime = explode(" ",$mtime);
                       $mtime = $mtime[1] + $mtime[0];
                       $endtime = $mtime;
                       $totaltime = ($endtime - $starttime);
                       print "Reporte Generado en ".$totaltime." Segundos<br/>";                     
                ?>            
            
  </div>
            
  </section>
    
</div>    
    

</body>
</html>
