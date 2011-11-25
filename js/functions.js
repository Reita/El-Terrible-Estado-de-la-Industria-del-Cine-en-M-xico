$(document).ready(function() {
    $('.chzn-select').chosen();
});

$(document).ready(function(){
  $('select').change(function () {
  
  var complejo_id = $(this).val();
  var selected = $(this).find('option:selected');
  var nombre = selected.data('nombreComplejo');
  var total = selected.data('total');
  var dob = selected.data('dob');
  var sub = selected.data('sub');
  var dobpcnt = selected.data('dobPcnt');
  var subpcnt = selected.data('subPcnt');
  
  if (complejo_id == 'todos') {
    $('#datos_complejo').slideUp('slow', function(){
      $('#datos_complejo').removeClass('visible').addClass('hidden');
      $('#datos').removeClass('hidden').addClass('visible');
      $('#datos').slideDown(1500,'easeOutBounce');
    });
    getChart(dobpcnt,subpcnt);
  }
  else {
    getChart(dobpcnt,subpcnt);
    getData(nombre,total,dob,sub);
	}
  });
});

function getChart(dob,sub) {
  $('#chart').fadeOut('slow', function(){
    $('#chart').fadeIn(1500);
      var selectedSeries = chart.series;	
			jQuery.each(selectedSeries, function(i, series) {
				series.remove(false);
			});

      var options = {
				type: 'pie',
				name: 'Cinepolis',
				data: [
					['Dobladas', dob], 
					['Subtituladas', sub]
				]
			};

      chart.addSeries(options, false);
			chart.redraw();
  });
}

function getData(nombre,total,dob,sub) {
  if ($('#datos').hasClass('visible')) {
    $('#datos').slideUp('slow', function(){
      $('#datos').removeClass('visible').addClass('hidden');

      $(".info_nombre").html("Estad&iacute;sticas "+nombre);
      $(".info_total strong").html(total);
      $(".info_dob strong").html(dob);
      $(".info_sub strong").html(sub);

      $('#datos_complejo').slideDown(1500,'easeOutBounce');
      $('#datos_complejo').removeClass('hidden').addClass('visible');
    });
  }

  if ($('#datos_complejo').hasClass('visible')) {
    $('#datos_complejo').slideUp('slow', function(){

      $(".info_nombre").html("Estad&iacute;sticas "+nombre);
      $(".info_total strong").html(total);
      $(".info_dob strong").html(dob);
      $(".info_sub strong").html(sub);

      $('#datos_complejo').slideDown(1500,'easeOutBounce');
    });
  }
}

$(document).ready(function() {
var w = $(window).width();
var h = $(window).height();

  if (h <= '840') {
    $('footer').css('position','relative');
  }
  
  if (w <= '1225') {
    $('header').css('font-size','9.3px');
  }
});

$(window).resize(function() {
var w = $(window).width();
var h = $(window).height();
 
  if (w <= '1225') {
    $('header').css('font-size','9.3px');
  }

  if (w >= '1225') {
    $('header').css('font-size','12px');
  }

  if (h <= '840') {
    $('footer').css('position','relative');
  }

  if (h >= '840') {
    $('footer').css('position','absolute');
  }
});

$(document).ready(function() {
	$('.fancybox').fancybox({
		'transitionIn'	  :	'fade',
		'transitionOut'	  :	'fade',
		'speedIn'		      :	600, 
		'speedOut'		    :	200,
		'showCloseButton' : 'true',
		'height'          : 700,
		'width'           : 400,
		'autoDimensions'  : false
	});
});