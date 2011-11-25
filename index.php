<?php
$time = microtime(); 
$time = explode(" ", $time); 
$time = $time[1] + $time[0]; 
$start = $time; 
?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" lang="en"> <![endif]-->
<html lang="en">

<head>
<meta charset="utf-8" />
<title>El Terrible Estado de la Industria del Cine en M&eacute;xico</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
<!-- 1140px Grid styles for IE -->
<!--[if lte IE 9]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" /><![endif]-->

<!-- The 1140px Grid - http://cssgrid.net/ -->
<link rel="stylesheet" href="css/1140.css" type="text/css" media="screen" />
	
<!-- Your styles -->
<link rel="stylesheet" href="css/styles.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/chosen.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<!-- Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Podkova' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Goudy+Bookletter+1911' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Kameron:700' rel='stylesheet' type='text/css'>
	
<!--css3-mediaqueries-js - http://code.google.com/p/css3-mediaqueries-js/ - Enables media queries in some unsupported browsers-->
<script type="text/javascript" src="js/css3-mediaqueries.js"></script>

<!-- Modernizer -->
<script type="text/javascript" src="js/modernizr-latest.js"></script>

<!-- Highcharts -->	
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 
<script type="text/javascript" src="js/highcharts.js"></script>
<script type="text/javascript" src="js/themes/grid.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>

<!-- Chosen -->	
<script type="text/javascript" src="js/chosen.jquery.js"></script>
</head>
<?php

// Array Sorter
function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

// Including lastRSS.
require_once('inc/lastRSS.php');

// Create lastRSS object.
$rss = new lastRSS;

// Cache dir, time limit, and encoding.
$rss->cache_dir = './temp';
$rss->cache_time = 14400;
$rss->cp = 'UTF-8';

// Defining start variables.
$complejo_id = "1";
$cine_array = array();
$listado = array();

// Get 450 URLs automatically by changing ID in URL.
do {
  $complejo = 'http://www.cinepolis.com.mx/rss/feed.ashx?idcomplejo='.$complejo_id;
  $cine_array[] = $complejo;
  $complejo_id++;
} while ($complejo_id <= "450");

// Defining some more variables.
$suma_total_complejo_dob = "0";
$suma_total_complejo_sub = "0";
$total_complejos = "0";
$select = '';

// Checking every single URL stored in the array.
foreach ($cine_array as $i => $value) {

// Fetch RSS using lastRSS.
  $rs = $rss->get($cine_array[$i]);
    
  $titulo = $rs['title'];
  $longitud = strlen($titulo);

  #echo '<p><a href="'.$rss->channel['link'].'">'.$rss->channel['title'].'</a></p>';

// If title lenght is less than 10 characters then movie complex is empty.
  if ($longitud > "10") {
  $hay_peliculas = $rs['items'][0]['title'];
  
  if (!empty($hay_peliculas)) {
    $total_complejos++;

// Replace bad encoding and remove titles.
    $titulo = str_replace("Cartelera Cin&eacute;polis ", "", $titulo);
    $titulo = str_replace("Cartelera ", "", $titulo);

    $complejo_id_suma = $i+1;    

    #$select .= '<option value="'.$complejo_id_suma.'">'.$titulo.'</option>'."\n";
    #$link = $rs['link'];
    #$link = str_replace('&','&amp;  ',$link);
    #echo '<h2><a href="'.$link.'">Channel Title: ' . $titulo. '</a></h2>'."\n";
    #echo "<ul>"."\n";

    $total_dob = "0";
    $total_sub = "0";

// If the movie complex has movies they are stored in an array; retrieve it.
    foreach ($rs['items'] as $item) {
      $title = $item['title'];
      $longitud = strlen($title);
      
      #echo '<li><h3>'.$title.'</h3></li>'."\n";
      
      $dob = "Dob";
      $sub = "Sub";

// Check Movie title for 'Sub' or 'Dob'; if title doesn't not include either we assume it's subbed.
      $conteo_dob = strpos($title,$dob);
      $conteo_sub = strpos($title,$sub);
      
      if (!empty($conteo_dob)) {
        #echo '<p>Es una pelicula Doblada</p>';
        $total_dob++;
        $suma_total_complejo_dob++;
      }
      
      if (!empty($conteo_sub)) {
        #echo '<p>Es una pelicula Subtitulada</p>';
        $total_sub++;
        $suma_total_complejo_sub++;
      }
      
      if (empty($conteo_dob) && empty($conteo_sub)) {
        #echo '<p>Es una pelicula Subtitulada</p>';
        $total_sub++;
        $suma_total_complejo_sub++;
      }

    }

    #echo "</ul>"."\n";
    #echo '<p>Total Dob:'.$total_dob.'</p>'."\n";
    #echo '<p>Total Sub:'.$total_sub.'</p>'."\n";

// Getting percentages for each movie complex invididual total.
    $total = $total_dob + $total_sub;

    $pcnt_dob = ($total_dob/$total)*100;
    $pcnt_sub = ($total_sub/$total)*100;

    $pcnt_dob = number_format($pcnt_dob, 2);
    $pcnt_sub = number_format($pcnt_sub, 2);

    
    #echo '<h2>Estadisticas</h2>'."\n";
    #echo '<h3>Total de peliculas</h3>'."\n";
    #echo '<p>'.$total.'</p>'."\n";
    #echo '<p>Porcentaje Doblado: '.$pcnt_dob.'%</p>'."\n";
    #echo '<p>Porcentaje Subtitulado: '.$pcnt_sub.'%</p>'."\n";

// Store movie complex ID, name, and sub/dub percentages in an array; it will be filtered later.
    $listado[] = array('id'=>$complejo_id_suma,
                        'nombre'=>$titulo,
                        'total'=>$total,
                        'total_dob'=>$total_dob,
                        'total_sub'=>$total_sub,
                        'pcnt_dob'=>$pcnt_dob,
                        'pcnt_sub'=>$pcnt_sub);
    
    }
  } 
}

// Get totals for movies, sub/dub and movie complexes.
$super_total = $suma_total_complejo_dob + $suma_total_complejo_sub;

$super_pcnt_dob = ($suma_total_complejo_dob/$super_total)*100;
$super_pcnt_sub = ($suma_total_complejo_sub/$super_total)*100;

$super_pcnt_dob = number_format($super_pcnt_dob, 2);
$super_pcnt_sub = number_format($super_pcnt_sub, 2);

// Sort array by movie complex name in alphabetic order.
$listado = subval_sort($listado,'nombre');
foreach ($listado as $i => $value) {
  $select .= '<option data-nombre-complejo="'.$value['nombre'].'" data-total="'.$value['total'].'" data-dob="'.$value['total_dob'].'" data-sub="'.$value['total_sub'].'" data-dob-pcnt="'.$value['pcnt_dob'].'" data-sub-pcnt="'.$value['pcnt_sub'].'" value="'.$value['id'].'">'.$value['nombre'].'</option>'."\n";
}

#echo '<h1>Estadisticas Totales</h1>'."\n";
#echo '<h2>Total Complejos '.$total_complejos.'</h2>'."\n";
#echo '<h2>Super Total de Peliculas</h2>'."\n";
#echo '<p>'.$super_total .'</p>'."\n";
#echo '<p>Porcentaje Total Doblado: '.$super_pcnt_dob.'%</p>'."\n";
#echo '<p>Porcentaje Total Subtitulado: '.$super_pcnt_sub.'%</p>'."\n";
?>
<body>

<header>
<h1>El Terrible Estado de la Industria del Cine en M&eacute;xico</h1>
<p>
<span class="first">El terrible estado del cine</span>
<span class="second">En Mexico</span>
<span class="third">Estad&iacute;sticas tomadas directamente del sitio de Cinepolis de Pel&iacute;culas</span>
<span class="fourth">Dobladas <span class="and">&amp;</span> Subtituladas</span>
</p>
</header>

<div class="container">
<div class="row">
<div class="fourcol">

<section>
<div id="datos" class="visible">
<h2>Estad&iacute;sticas Totales</h2>
<ul>
<li>N&uacute;mero de Complejos: <strong><?php echo $total_complejos ?></strong></li>
<li>N&uacute;mero de Exhibiciones: <strong><?php echo $super_total ?></strong></li>
<li>Pel&iacute;culas Dobladas: <strong><?php echo $suma_total_complejo_dob ?></strong></li>
<li>Pel&iacute;culas Subtituladas: <strong><?php echo $suma_total_complejo_sub ?></strong></li>
</ul>
</div>
<div id="datos_complejo" class="hidden">
<h2 class="info_nombre">Estad&iacute;sticas</h2>
<ul> 
<li class="info_total">N&uacute;mero de Exhibiciones: <strong></strong></li> 
<li class="info_dob">Pel&iacute;culas Dobladas: <strong></strong></li> 
<li class="info_sub">Pel&iacute;culas Subtituladas: <strong></strong></li> 
</ul>
</div>
</section>

<section>
<h2>Busca Tu Complejo</h2>
<div class="select-container">
<select data-placeholder="Selecciona un complejo..." class="chzn-select" tabindex="2">
<option data-dob-pcnt="<?php echo $super_pcnt_dob; ?>" data-sub-pcnt="<?php echo $super_pcnt_sub; ?>" value="todos">Todos</option>
<?php echo $select ?>
</select>
</div>
</section>

<div style="display:none">
<div id="about" class="pop-up">
<h2>Acerca de</h2>
<p>Este sitio ha sido desarrollado con el &uacute;nico prop&oacute;sito de mostrar al publico el estado de la industria cinematogr&aacute;fica en M&eacute;xico y la <em>falta de suficientes opciones de pel&iacute;culas en su idioma original.</em></p>
<p>En algunas ocasiones, dependiendo de la ubicaci&oacute;n, algunos complejos cuentan con una, o a veces ninguna, proyecci&oacute;n en su idioma original, dej&aacute;ndonos con pel&iacute;culas dobladas las cuales en la mayor&iacute;a de los casos no conservan las referencias originales, son tropicalizadas y por lo general presentan un trabajo mediocre de doblaje.</p>
<p>Si bien los porcentajes aqu&iacute; mostrados algunas veces rondan entre 55% de pel&iacute;culas subtituladas y 45% de pel&iacute;culas dobladas creemos que esto no es suficiente y nos gustar&iacute;a, como m&iacute;nimo, que el <strong>75% de las pel&iacute;culas proyectadas en M&eacute;xico se hiciera en su idioma original.</strong></p>
<p>Esperamos que este sitio ayude a crear consciencia entre la poblaci&oacute;n mexicana que gusta de ver el cine en su idioma original y que sirva como una llamada de atenci&oacute;n tanto a los responsables de la industria cinematogr&aacute;fica en M&eacute;xico, como a los responsables de la industria del doblaje, esto con el objetivo de que los n&uacute;meros aqu&iacute; presentados se vean modificados y que el trabajo de doblaje eleve sus niveles de calidad.</p>
</div>
</div>

<div style="display:none">
<div id="disclaimer" class="pop-up">
<h2>Disclaimer</h2>
<p>La informaci&oacute;n aqu&iacute; mostrada es meramente de tipo informativo y todos los datos aqu&iacute; presentados han sido obtenidos directamente de los feeds RSS del sitio de <a href="http://www.cinepolis.com.mx/">Cin&eacute;polis M&eacute;xico</a>; es decir, son completamente p&uacute;blicos.</p>
<p>Las estad&iacute;sticas aqu&iacute; presentadas tienen una duraci&oacute;n en cache de 4 horas, las cuales son actualizadas pasado el tiempo de cache.</p>
<h3>¿Por qu&eacute; Cin&eacute;polis y no Cinemex?</h3>
<p>Debido a que Cinemex <em>no cuenta con informaci&oacute;n RSS de su cartelera</em> no es posible analizar la misma; si en alg&uacute;n futuro Cinemex opta por ofrecer su cartelera en RSS se podr&aacute; añadir al an&aacute;lisis aqu&iacute; presentado.</p>


</div>
</div>

</div>
<div class="eightcol last">
<div id="chart"></div>
</div>
</div>
</div>

<footer>
<ul>
<li><a href="http://treebit.fm">Treebit</a></li>
<li><a class="fancybox" href="#about">Acerca de</a></li>
<li><a class="fancybox" href="#disclaimer">Disclaimer</a></li>
<li class="twitter-container">
<a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a>
</li>
<li class="gplus-container">
<div class="g-plusone" data-size="medium" data-count="false"></div>
</li>
<li class="facebook-container">
<iframe src="http://www.facebook.com/plugins/like.php?href=http://cine.treebit.fm/&amp;layout=button_count&amp;locale=en_US"></iframe>
</li>
</ul>
</footer>

<!-- Chart --> 
<script type="text/javascript">
  var chart;
  $(document).ready(function() {
    chart = new Highcharts.Chart({
      chart: {
        renderTo: 'chart',
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false
      },
      title: {
        text: 'Peliculas Subtituladas vs Dobladas en Cinepolis'
      },
      tooltip: {
        formatter: function() {
          return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
        }
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: false
          },
          showInLegend: true
        }
      },
        series: [{
        type: 'pie',
        name: 'Cinepolis',
        data: [
          ['Dobladas',   <?php echo $super_pcnt_dob; ?>],
          ['Subtituladas',  <?php echo $super_pcnt_sub; ?>]
        ]
      }]
    });
  });
</script>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1271844-5']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</body>
</html>
<!-- <?php
$time = microtime(); 
$time = explode(" ", $time); 
$time = $time[1] + $time[0]; 
$finish = $time; 
$totaltime = ($finish - $start); 
printf ("This page took %f seconds to load", $totaltime);
?> -->