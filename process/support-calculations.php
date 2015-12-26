<?php

//Label: Tiempo Efectivo Ticket
/*
 *Tiempo Efectivo
 *El tiempo que ha permanecido en estado "Abierto"
 *Desde que fue bloqueado (es decir lo tomó un agente para su atención) 
 *hasta que cambió a estado "Solucionado"                                                  
*/
function calculaTiempoEnStatusTicket($ticket, $tipoStatus){
    
    //print "<br/> Ticket: ".$ticket->getTn()."<br/>";
    
    //print " Ticket Matriz Historico: ".print_r($ticket->getMatrizHistoricoTicket())."<br/>";

                                                     //status Abierto 
                                                     $sumOpenStatus = 0;
                                                     
                                                     
                                                     //status Pendiente Recordatorio
                                                     $sumPendienteRecordatorio = 0;
                                                     
                                                     
                                                     //status Solucionado
                                                     $sumSolucionadoStatus = 0;
                                                     
                                                     //status Cerrado sin Solución
                                                     $sumCerradosinSolucionStatus = 0;
                                                     
                                                     //status Pending auto close-
                                                     $sumPendingAutoCloseStatus = 0;
                                                     
                                                     //status sumPreCierre  
                                                     $sumPreCierre = 0;
                                                             
                                                     $arrayDates = array();
                                                     $arrayStatusPeriod = array();
                                                     $arrayDatesGrafico = array();
                                                     $statusPeriodo = "";
                
                                                     foreach ($ticket->getMatrizHistoricoTicket() as $arrayCambioStatus) {
                    
                                                        //print print_r($key); //." = ". $value ."<br>";
                                                        $i = 0;
                                                        foreach ($arrayCambioStatus as $key) {
                                                             //print " (".$i. ") - " .$key[0]. " - ".$key[1]. " - ".$key[2]."<br>";
                                                             $arrayDates[$i] = $key[2];
                                                             //asignar los datos de los cambios de estatus del ticket
                                                             $arrayStatusPeriod[$i] = $key[1];
                                                             $i++;
                                                        }
                    
                                                     }

                
                                                     //print " Fecha de Inicio : ". $ticket->getCreate_time() . "<br/>";
                                                     //print " Fecha de Cierre : ". $ticket->getFechaCierre() . "<br/>";
                                
              
                                                     //Total de Minutos Ticket
                                                     $tMT = 0;
                
                                                     //Tiempo Minutos del Periodo
                                                     $tMP = 0;                
                                                     $x = 0;
                
                                                     //foreach principal                                                     
                                                     
                                                     foreach ($arrayDates as $dt) {
                    
                                                        if($x == 0){
                        
                                                           //print " Es indice 0 <br/>";
                                                           $dT1 = $arrayDates[0];  //Mayor
                                                           $dT2 = $ticket->getCreate_time(); //Menor
                                                        }else{
                         
                                                           $dT1 = $arrayDates[ $x ]; //Mayor
                                                           $dT2 = $arrayDates[($x-1) ]; //Menor                         
                         
                                                        }
                    
                                                        $dateTime1 = new DateTime( $dT1 ); //Mayor
                                                        $dateTime2 = new DateTime( $dT2 ); //Menor
                                                        $diffDateTime = $dateTime2->diff($dateTime1);
                    
                                                        //Calculos en Diferencia de Minutos y Horas
                                                        //DAdT - Diferencia Antiguedad del ticket
                                                        $DAdTy     = $diffDateTime->y;
                                                        $DAdTm     = $diffDateTime->m;
                                                        $DAdTd     = $diffDateTime->d;
                                                        $DAdTh     = $diffDateTime->h;
                                                        $DAdTmm    = $diffDateTime->i;
                                                        //$DAdTCalc  = $diffDateTime->format('%R%a');
                                                        $DAdTCalc  = $diffDateTime->format('%a');
                     
                                                        //print " (".$x. ") - " . $dT1 ." Date: ". $dT2 . "<br/>";
                                                        //print ' No Total dias: '.$DAdTCalc . ' |  años: '. $DAdTy . ' meses: '. $DAdTm . ' dias: '.$DAdTd. ' horas: '.$DAdTh. ' minutos: '.$DAdTmm. "<br/>";
                     
                                                        //Calculos
                                                        $calcDias    = ( ( $DAdTCalc ) * 1440 ); 
                                                        $calcHoras   = ( ( $DAdTh ) * 60 );
                                                        $calcMinutos = ( ( $DAdTmm ) ); 
                                                        $tMP = ( $calcDias + $calcHoras + $calcMinutos);
                     
                                                        $tMT+= ( $calcDias + $calcHoras + $calcMinutos); 
                                                        //print ' Total de Minutos Ticket: '.$tMT . "<br/>";
                                                        //print ' Minutos del Periodo de Ticket: '.$tMP . "<br/>";
                     
                                                        //calculos status
                                                        //print ' Stado Periodo : '.$arrayStatusPeriod[$x] . "<br/>";                     
                                                        $lstStatus = explode("%%", $arrayStatusPeriod[$x] );
    
                                                        //print_r($lstStatus);
                                                        //print "X: ".$x."<br/>";
                                                        if( (count($arrayStatusPeriod) - 1) == $x ){
                                                             //print "<h1>(".$x.") Status Ticket Final: ".$lstStatus[2]."</h1>";
                                                             $statusPeriodo =  md5( trim($lstStatus[2]) );
                                                        }else{
                                                             //print "<h1>(".$x.")Status Ticket: ".$lstStatus[1]."</h1>";
                                                             $statusPeriodo = md5( trim($lstStatus[2]) ); //trim($lstStatus[1]);
                                                        }    
    

                                                        //1 - Total de Minutos Estatus Solucionado
                                                        //md5 Solucionado = 8d07e6822952648710dea9ffdd021d75
                                                        if($statusPeriodo == "8d07e6822952648710dea9ffdd021d75"){
                                                            $sumSolucionadoStatus = $sumSolucionadoStatus + $tMP;                                                            
                                                        }                                                       


                                                        //2- Total de Minutos Cerrado sin Solución
                                                        //md5 Cerrado sin Solucion d10f8af05830793506ee3072556a3fb7 
                                                        if($statusPeriodo == "d10f8af05830793506ee3072556a3fb7"){
                                                            $sumCerradosinSolucionStatus = $sumCerradosinSolucionStatus + $tMP;                                                            
                                                        }                                                        

                                                        //3 - Total de Minutos Estatus Abierto
                                                        //md5 Abierto = 12ec8f316bcb560d33dd087ecfa68fc0
                                                        if($statusPeriodo == "12ec8f316bcb560d33dd087ecfa68fc0"){
                                                            $sumOpenStatus = $sumOpenStatus + $tMP;                                                            
                                                        }
                                                        
                                                        //4 - Total de Minutos Estatus Pendiente Recordatorio
                                                        //md5 Pendiente Recordatorio = fabe31a639e8de4dd6d5e3c513d08774
                                                        if($statusPeriodo == "fabe31a639e8de4dd6d5e3c513d08774"){
                                                            $sumPendienteRecordatorio = $sumPendienteRecordatorio + $tMP;                                                            
                                                        }                                                        

                                                        //5 - Total de Minutos pending auto close-
                                                        //md5 pending auto close- 44df6ac4ce024123f289ee2ea04854ff
                                                        if($statusPeriodo == "44df6ac4ce024123f289ee2ea04854ff"){
                                                            $sumPendingAutoCloseStatus = $sumPendingAutoCloseStatus + $tMP;                                                            
                                                        }                                                        
                                                        
                                                        //6 - Total de Minutos Pre cierre
                                                        //md5 Pre cierre c608266ea633ff0a9a83aad1b11a4e35
                                                        if($statusPeriodo == "c608266ea633ff0a9a83aad1b11a4e35"){
                                                            $sumPreCierre = $sumPreCierre + $tMP;                                                            
                                                        }                                                        
                                                        
                                                        //print "0: ".$lstStatus[0]."<br/>";
                                                        //print "1: ".$lstStatus[1]."<br/>";
                                                        //print "2: ".$lstStatus[2]."<br/>";
                                                        //print "3: ".$lstStatus[3]."<br/><br/><br/>";

                                                        
                                                        //Reset de Datos para calculo
                                                        $dateTime1 = NULL;
                                                        $dateTime2 = NULL;
                                                        $diffDateTime = NULL;
                                                        $calcDias    = 0; 
                                                        $calcHoras   = 0;
                                                        $calcMinutos = 0;
                           
                                                        $tMP = 0;
                     
                                                        //DAdT - Diferencia Antiguedad del ticket
                                                        $DAdTy     = 0;
                                                        $DAdTm     = 0;
                                                        $DAdTd     = 0;
                                                        $DAdTh     = 0;
                                                        $DAdTm     = 0;
                                                        $DAdTCalc  = 0;                       
                     
                                                        $x++;
                                                     
                                                     
                                                     }                                        
 
            switch($tipoStatus){
                
                case "statusAbierto":                    
                    $totalMinutos = $sumOpenStatus;
                    break;
                
                case "statusPendienteRecordatorio":
                    $totalMinutos = $sumPendienteRecordatorio;
                    break;
                
                case "statusSolucionado":
                    $totalMinutos = $sumSolucionadoStatus;
                    break;
                
                case "statusCerrado":
                    $totalMinutos = $sumCerradosinSolucionStatus;
                    break;  
                
                case "statusPendingautoclose":
                    $totalMinutos = $sumPendingAutoCloseStatus;
                    break;                  
                
                case "statusPreCierre":
                    $totalMinutos = $sumPreCierre;
                    break;                                  

                case "statusTotalTiempo":
                    $totalMinutos = ($sumOpenStatus + $sumPendienteRecordatorio + $sumSolucionadoStatus + $sumCerradosinSolucionStatus + $sumPendingAutoCloseStatus + $sumPreCierre);
                    break;                
                
            }
            
            return $totalMinutos;                                                     
                                                     
                                                     
}

/*
 *  Tiempo Real de Vida = Fecha de Cierre - Fecha de Creación
 */
function calculaTiempoRealdeVidadeTicket($fechaCreacion, $fechaCierre){
    
             //print "Tiempo Real Ticket Fecha Inicial: ".$fechaCreacion."<br/>"; 
             //print "Tiempo Real Ticket Fecha Final: ".$fechaCierre."<br/>";

             $d1 = new DateTime( $fechaCierre );
             $d2 = new DateTime( $fechaCreacion );
             $diffTiempoDeVida = $d2->diff($d1);
        
             //TdV Tiempo de Vida
             $TdVy     = $diffTiempoDeVida->y;
             $TdVm     = $diffTiempoDeVida->m;
             $TdVd     = $diffTiempoDeVida->d;
             $TdVh     = $diffTiempoDeVida->h;
             $TdVmm     = $diffTiempoDeVida->i;
             //$TdVdCalc = $diffTiempoDeVida->format('%R%a días');
             $TdVdCalc = $diffTiempoDeVida->format('%R%a');
        
             //print ' No dias: '.$diffTiempoDeVida->format('%R%a días') . "<br/>";
        
             //Combined
             //echo ' años: '.$TdVy. '<br/>';
             //echo ' meses: '.$TdVmm. '<br/>';
             //echo ' dias: '.$TdVd. '<br/>';
             //echo ' horas: '.$TdVh. '<br/>';
             //echo ' minutos: '.$TdVmm. '<br/>';
             
        
             //print ' Timestamp Unix: '.print_r($diffTiempoDeVida);
             //print ' No dias: '.$TdVdCalc;
             
             $noTotalDias    = ($TdVdCalc * 1440);
             $noTotalHoras   = ($TdVh * 60);
             $noTotalMinutos = ($TdVmm);
             $totalMinutos   = ($noTotalDias + $noTotalHoras + $noTotalMinutos);
             
             //$txtCalculo = " Años: ". $TdVy . " meses: ". $TdVmm ." dias: ". $TdVd ." horas: " . $TdVh . " minutos: " . $TdVmm." ";
             
             return $totalMinutos;                                                     
                                                     
}

/*
 * Se considera Tiempo efectivo de atención el tiempo que pasa desde que 
 * se generó el ticket hasta que un agente genera la primer respuesta hacia el 
 * usuario que está atendiendo.
 **/ 
function calculaTiempoEfectivodeAtencion($fechaCreacion, $fechaPrimerContacto){
    
             //print "Tiempo Real Ticket Fecha Inicial: ".$fechaCreacion."<br/>"; 
             //print "Tiempo Real Ticket Fecha Final: ".$fechaCierre."<br/>";

             $d1 = new DateTime( $fechaPrimerContacto );
             $d2 = new DateTime( $fechaCreacion );
             $diffTiempoDeVida = $d2->diff($d1);
        
             //TdV Tiempo de Vida
             $TdVy     = $diffTiempoDeVida->y;
             $TdVm     = $diffTiempoDeVida->m;
             $TdVd     = $diffTiempoDeVida->d;
             $TdVh     = $diffTiempoDeVida->h;
             $TdVmm     = $diffTiempoDeVida->i;
             //$TdVdCalc = $diffTiempoDeVida->format('%R%a días');
             $TdVdCalc = $diffTiempoDeVida->format('%R%a');
        
             //print ' No dias: '.$diffTiempoDeVida->format('%R%a días') . "<br/>";
        
             //Combined
             //echo ' años: '.$TdVy. '<br/>';
             //echo ' meses: '.$TdVmm. '<br/>';
             //echo ' dias: '.$TdVd. '<br/>';
             //echo ' horas: '.$TdVh. '<br/>';
             //echo ' minutos: '.$TdVmm. '<br/>';
             
        
             //print ' Timestamp Unix: '.print_r($diffTiempoDeVida);
             //print ' No dias: '.$TdVdCalc;
             
             $noTotalDias    = ($TdVdCalc * 1440);
             $noTotalHoras   = ($TdVh * 60);
             $noTotalMinutos = ($TdVmm);
             $totalMinutos   = ($noTotalDias + $noTotalHoras + $noTotalMinutos);
             
             //$txtCalculo = " Años: ". $TdVy . " meses: ". $TdVmm ." dias: ". $TdVd ." horas: " . $TdVh . " minutos: " . $TdVmm." ";
             
             return $totalMinutos;                                                     
             
    
}



function datePeriodStartEnd($sStartDate, $sEndDate){
                 
             // Firstly, format the provided dates.  
             // This function works best with YYYY-MM-DD  
             // but other date formats will work thanks  
             // to strtotime().  
             $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));  
             $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));  

             // Start the variable off with the start date  
             $aDays[] = $sStartDate;  

             // Set a 'temp' variable, sCurrentDate, with  
             // the start date - before beginning the loop  
             $sCurrentDate = $sStartDate;  

             // While the current date is less than the end date  
             while($sCurrentDate < $sEndDate){  
               // Add a day to the current date  
               $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  

               // Add this new day to the aDays array  
               $aDays[] = $sCurrentDate;  
             }  

             // Once the loop has finished, return the  
             // array of days.  
             return $aDays;  
                
}
        

 /*
  * Funcion para revisar si la fecha es fin de semana o no
  *
  */
function isWeekend($date) {
   return (date('N', strtotime($date)) >= 6);
}        
        

/*
 * Calcular el Tiempo del Ticket dentro del espacio del SLA L - V de 8:00 a.m. a 18:00 p.m.
 * 
 * */
function calculaTiempoDentroSLA($ticket){
    
//$ticket->getCreate_time(), $ticket->getFechaCierre() 

//"2014-01-01"
//"2014-02-01"

//status 2 es solucionado
//es decir ya tiene fecha de cierre
if ( $ticket->getTicket_state_id() == 2 ){
       $FI = $ticket->getCreate_time();
       $FF = $ticket->getFechaCierre();
}else{

       $month = date("m");
       $day = date('d');
       $year = date('y');

       $FI = $ticket->getCreate_time();
       $FF = $year."-".$month."-".$day;
}

//print "tmpFI: ".$tmpFI[0];
//print "tmpFF: ".$tmpFF[0];

print "tmpFI: ".$FI."</br>";
print "tmpFF: ".$FF."</br>";


for ($date = strtotime($FI); $date < strtotime($FF); $date = strtotime("+1 day", $date)) {
    echo date("Y-m-d", $date)."<br/>";
}

             $arrayScalaSLA = array();
    
             $arrayDates = array();
             $arrayDateCalculations = array();
             
             $arrayDates = datePeriodStartEnd($FI, $FF);              
              print "Elementos Array  ".count($arrayDates)."<br/>";    
    
              $x = 0;
    
              //Generamos el array de calculo del tiempo del SLA
              foreach($arrayDates as $dates => $day){
        
                    print " -". $day ."<br/>";
       
                    if($x == 0){
            
                       $arrayDateCalculations[] = $fechaInicial;
            
                    }elseif($x == (count($arrayDates) - 1) ){
            
                       $arrayDateCalculations[] = $fechaFinal;
            
                    }else{
                       //Dias Normales
                       $arrayDateCalculations[] = $day." 08:00:00";            
                    }
        
        
        
                  $x++;
              } 

              $acumuladoCalculo = 0;
              $acumuladoDia = 0;
    
    
              foreach($arrayDateCalculations as $aDates => $aDay){
         
         
                 $lstFechaHora = explode(" ", $aDay);
                 $xtractFecha = $lstFechaHora[0];
                 $lstHHMMSS  = explode(":",$lstFechaHora[1]);
            
                 $xtractHH = $lstHHMMSS[0];
                 $xtractMM = $lstHHMMSS[1];
                 $xtractSS = $lstHHMMSS[2];
            
         //print " Fecha: ".$xtractFecha."<br/>";
         //print " Hora: ".$lstHHMMSS."<br/>";            
            
         //print " HH: ".$xtractHH."<br/>";
         //print " MM: ".$xtractMM."<br/>";
         //print " SS: ".$xtractSS."<br/>";

         if(isWeekend($aDay) == 0){
         //Hacer el calculo de la escala SLA del dia 

            //print "-- Dia: ".$aDay." <br/>";
                      
                     for ($hh = $xtractHH; $hh < 24; $hh++) {
                        
                                           
                              switch($hh){
                           
                                  case 8:
                                       //8 AM
                                       $acumuladoDia++;
                                       //print "     The hh is: 8 <br>";
                                  
                                  break;

                                  case 9:
                                       //9 AM
                                       $acumuladoDia++;
                                       //print "     The hh is: 9 <br>";
                                       
                                  break;
                       
                                  case 10:
                                       //10 AM
                                       $acumuladoDia++;
                                       //print "      The hh is: 10 <br>";
                                  break;
                       
                                  case 11:
                                       //11 AM
                                        $acumuladoDia++;
                                       //print "      The hh is: 11 <br>";
                                  break;
                       
                                  case 12:
                                      //12 AM
                                       $acumuladoDia++;
                                      //print "      The hh is: 12 <br>";
                                      
                                  break;
                       
                       
                                  case 13:
                                      //13 AM
                                       $acumuladoDia++;
                                      ///print "     The hh is: 13 <br>";
                                      
                                  break;
                       
                       
                                  case 14:
                                      //14 AM
                                       $acumuladoDia++;
                                      //print "     The hh is: 14 <br>";
                                      
                                  break;
                       

                                  case 15:
                                      //15 AM
                                       $acumuladoDia++;
                                      //print "      The hh is: 15 <br>";
                                      
                                  break;
                       
                       
                                  case 16:
                                      //16 AM
                                       $acumuladoDia++;
                                      //print "      The hh is: 16 <br>";
                                      
                                  break;                       
                       
                       
                                  case 17:
                                      //17 AM
                                       $acumuladoDia++;
                                      //print "     The hh is: 17 <br>";
                                      
                                  break;                       
                       
                                  case 18:
                                      //18 PM
                                       $acumuladoDia++;
                                      //print "     The hh is: 18 <br>";
                                      
                                  break;
                                  
                                  default :

                                      //print "     The hh is: DEFAULT <br>";
                       
                              }
                              
                              //print "Termino Loop Hora : ".$acumuladoDia."<br/>";
                              
                              //print "     The number is: $hh <br>";
                              //print "<br/> Factor Scala SLA: ".$factorScalaSLA."<br/>";
                              
                              //$calculoScalaSLA = $calculoScalaSLA + ( ($factorScalaSLA*60) + $xtractMM);
                               
                              
                              
                              //$factorScalaSLA = 0;
                     }//for ($hh = $xtractHH; $hh < 24; $hh++) {
                     
                     $acumuladoCalculo = $acumuladoCalculo + $acumuladoDia;
                     
                     $acumuladoDia = 0;
                     
                     //print "SLA Loop: ".$acumuladoCalculo."<br/>";

            }//if($ticket->isWeekend($aDay) == 0){
            
        
       
            
    }//foreach($arrayDateCalculations as $aDates => $aDay){
    
    //print "Acumulado Tiempo SLA: ".$acumuladoCalculo."<br/>";

    $acumuladoMinutosSLA = ($acumuladoCalculo * 60);
    
    
    return $acumuladoMinutosSLA;
            
    
    
}