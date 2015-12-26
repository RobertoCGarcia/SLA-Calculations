<?php
require_once 'phplot/phplot.php';

date_default_timezone_set("America/Mexico_City");
$dateTime = " Last Update: " . date("h:i:sa");

//Array de datos del Grafico
$data = array();

# The first data point was recorded at this date/time: (always top of an hour)
# Example: 5/1/2011 at 10:00am
//$year = 2015;
//$month = 8;
//$day = 17;
//$hour = 00;

# Number of hourly data points, e.g. 168 for 1 week's worth:
//$n_points = 168;
# ==================================================================

# Timestamp for the first data point:
//$time0 = mktime($hour, 0, 0, $month, $day, $year); // H M S m d y

//mt_srand(1); // For repeatable, identical results

/*
 * Regresar una sección especifica de la Fecha y hora
 * que se pasa como parametro
 * La fecha es en formato mysql
 */
function getSectionDateTime($opHora, $fecha){

                
            @$fechaTmp = explode(" ",$fecha);

            @$fecha = explode("-",$fechaTmp[0]);
            @$hora  = explode(":",$fechaTmp[1]);

            @$ano   = $fecha[0];
            @$mes   = $fecha[1];
            @$dia   = $fecha[2];

            @$hh    = $hora[0];
            @$min   = $hora[1];
            @$seg   = $hora[2];       

            switch($opHora){

                case 'day':
                     $opcSecc = $dia;
                break;

                case 'month':
                     $opcSecc = $mes;
                break;

                case 'year':
                     $opcSecc = $ano;
                break;
                            	
                case 'hh':
                     $opcSecc = $hh;
                break;

                case 'mm':
                     $opcSecc = $min;
                break;

                case 'ss':
                     $opcSecc = $seg;
                break;

            }

            return $opcSecc;

}


/**
 * Convert time to timestamp.
 *
 * @param string $time The time to convert
 *
 * @return integer The time as a decimal value.
 */
function DateTimetoTimeStamp($DateTime) {

    $day   = getSectionDateTime("day", $DateTime);
    $month = getSectionDateTime("month", $DateTime);
    $year  = getSectionDateTime("year", $DateTime);
        
        
    $hh  = getSectionDateTime("hh", $DateTime);
    $mm  = getSectionDateTime("mm", $DateTime); 
    $ss  = getSectionDateTime("ss", $DateTime);
        
    $timeStampUnix = mktime($hh, $mm, $ss, $month, $day, $year);
            
    return $timeStampUnix;
}



//Read the data from the file and make the array of the graph

$logFile = "process/dataGraphicTicket.log";

if (file_exists($logFile) && is_readable ($logFile)) {
    
	$fh = fopen($logFile, "r");
        
	# Processing
        #Examples: 2015-04-08 16:53:58 | 16
        while (!feof($fh)) {
           $line = fgets($fh);
           
           if(strlen($line) > 0){
           
              //list($dt, $h) = explode(' | ', $line);
              $rowData = explode ("|", trim($line));
              //echo $line;
              $data[] = array(" ", DateTimetoTimeStamp($rowData[0]),  $rowData[1]);          
               
           }     

        }
        
	fclose($fh);
}


//$data = array();
//$data[] = array("1", DateTimetoTimeStamp("2015-04-08 16:53:58"),  16);
//$data[] = array("2", DateTimetoTimeStamp("2015-04-08 16:55:25") , 16);
//$data[] = array("3", DateTimetoTimeStamp("2015-06-01 11:39:18") , 11);
//$data[] = array("4", DateTimetoTimeStamp("2015-06-01 11:47:20") , 11);
//$data[] = array("5", DateTimetoTimeStamp("2015-06-12 17:09:49") , 17);
//$data[] = array("6", DateTimetoTimeStamp("2015-06-12 17:44:50") , 17);
//$data[] = array("7", DateTimetoTimeStamp("2015-06-18 16:25:47") , 16);
//$data[] = array("8", DateTimetoTimeStamp("2015-06-18 19:06:01") , 19);
//$data[] = array("9", DateTimetoTimeStamp("2015-06-19 17:15:54") , 17);
//$data[] = array("10", DateTimetoTimeStamp("2015-06-19 17:19:57") , 17);
//$data[] = array("11", DateTimetoTimeStamp("2015-06-23 15:46:03") , 15);
//$data[] = array("12", DateTimetoTimeStamp("2015-06-23 16:20:35") , 16);
//$data[] = array("13", DateTimetoTimeStamp("2015-06-23 16:26:21") , 16);
//$data[] = array("14", DateTimetoTimeStamp("2015-06-23 17:43:56") , 17);
//$data[] = array("15", DateTimetoTimeStamp("2015-06-30 09:10:47") , 9);
//$data[] = array("16", DateTimetoTimeStamp("2015-06-30 09:12:58") , 9);
//$data[] = array("17", DateTimetoTimeStamp("2015-07-06 09:28:32") , 9);
//$data[] = array("18", DateTimetoTimeStamp("2015-07-06 11:17:10") , 11);

/*
$ts = $time0;
$tick_anchor = NULL;
$d = 5; // Data value
for ($i = 0; $i < $n_points; $i++) {

    # Decode this point's timestamp as hour 0-23:
    $hour = date('G', $ts);

    # Label noon data points with the weekday name, all others unlabelled.
    $label = ($hour == 12) ? strftime('%A', $ts) : '';

    # Remember the first midnight datapoint seen for use as X tick anchor:
    if (!isset($tick_anchor) && $hour == 0)
        $tick_anchor = $ts;

    # Make a random data point, and add a row to the data array:
    $d += mt_rand(-200, 250) / 100;
    //$d = 10;
    $data[] = array($label, $ts, $d);

    # Step to next hour:
    $ts += 3600;
}
*/


$plot = new PHPlot(800, 600);
//                 Alto Ancho
//$plot = new PHPlot(2000, 1500);
$plot->SetImageBorderType('plain'); // For presentation in the manual
$plot->SetTitle('Analisis Estados Ticket ' );
$plot->SetDataType('data-data');
$plot->SetDataValues($data);
$plot->SetPlotType('lines');

# Make the X tick marks (and therefore X grid lines) 24 hours apart:
$plot->SetXTickIncrement(60 * 60 * 24);

# Incremento de Y
$plot->SetYTickIncrement(1);

$plot->SetDrawXGrid(True);

# Anchor the X tick marks at midnight. This makes the X grid lines act as
# separators between days of the week, regardless of the starting hour.
# (Except this messes up around daylight saving time changes.)
//$plot->SetXTickAnchor($tick_anchor);

# We want both X axis data labels and X tick labels displayed. They will
# be positioned in a way that prevents them from overwriting.
$plot->SetXDataLabelPos('plotdown');
$plot->SetXTickLabelPos('plotdown');

# Increase the left and right margins to leave room for weekday labels.
$plot->SetMarginsPixels(50, 50);

# Tick labels will be formatted as date/times, showing the date:
$plot->SetXLabelType('time', '%Y-%m-%d');
# ... but then we must reset the data label formatting to no formatting.
$plot->SetXDataLabelType('');

//Datos del Tiempo
$plot->SetDrawXDataLabelLines(True);


# Show tick labels (with dates) at 90 degrees, to fit between the data labels.
$plot->SetXLabelAngle(90);
# ... but then we must reset the data labels to 0 degrees.
$plot->SetXDataLabelAngle(0);

# Force the Y range to 0:100.
$plot->SetPlotAreaWorld(NULL, 0, NULL, 23);



//$plot->SetYTickLabelPos('none');
//$plot->SetYTickPos('none');
//$plot->SetXDataLabelPos('plotin');
//$plot->SetDrawXGrid(False);
//$plot->SetDrawYGrid(False);

# Now draw the graph:
$plot->DrawGraph();