 <?php
 date_default_timezone_set("America/Mexico_City");
if( !session_id() )
{
    session_start();
    

}
if(@$_SESSION['logged_in'] != true){
    echo '
     <script>
        alert("No has iniciado sesion");
        self.location.replace("../index.php");
    </script>';
}else{
    echo '';
}
    ?>


     <?php
     require('../saves/conexion.php');
        
    ?>



  
<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>

    <link rel="stylesheet" type="text/css" href="../css/custom_datepicker.css" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>REPORTE ETE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
   

    <link href="../css/estilosadmin.css" rel="stylesheet" />
<link href="../css/font-awesome.css" rel="stylesheet" />
   
      <link rel="stylesheet" href="../fonts/style.css">
  



<script type="text/javascript">

    
(function(document) {
  'use strict';

  var LightTableFilter = (function(Arr) {

    var _input;

    function _onInputEvent(e) {
      _input = e.target;
      var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
      Arr.forEach.call(tables, function(table) {
        Arr.forEach.call(table.tBodies,  _filter);
      });
    }

    function _filter(row) {
      
      var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
      row.style.display = text.indexOf(val) === -1 ? 'none' : '';
    }

    return {
      init: function() {
        var inputs = document.getElementsByClassName('light-table-filter');
        Arr.forEach.call(inputs, function(input) {
          input.oninput = _onInputEvent;
        });
      }
    };
  })(Array.prototype);

  document.addEventListener('readystatechange', function() {
    if (document.readyState === 'complete') {
      LightTableFilter.init();
    }
  });

})(document);
</script>



<style type="text/css">



.ui-datepicker-header a{
  color: #fff!important;
}
.ui-datepicker-header a:hover{
 text-decoration: none!important;
}

.week-picker{
  width: 400px;
  background:#fff;
  position: relative;
  height: 530px;
  margin: 30px auto;
  box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
  border-radius: 4px;
  overflow:hidden;
  text-align: center;
}
.date-form button{
  background:#4CAF50;
  color: #fff;
  padding: 10px;
  border-radius: 3px;
  margin: 10px auto;
  border:none;
}
.date-form{
  width: 100%;
  height: 115px;
  
  position: absolute;
  bottom: 0;
}
.date-form label,.date-form{
  color: #adadad;
  margin-top: 10px;
  margin-bottom: 0;
}
.r-title{
  background:#007BAC;
  color: #fff;
  width: 100%;
  height: 30px;
  line-height: 30px;

}

</style>
  


</head>
<body style=";">

<?php include("topbar.php");  ?>


 <div class="div-tabla">
<div class="week-picker">
<div class="r-title">Reporte de Taller</div>
<div class="date-form">
<label>Semana del</label>  <span id="startDate"> --- </span> <label>al</label> <span id="endDate"> --- </span>
  <form target="_blank" method="POST" action="../pdfrepajustemaquina/reporteTaller.php">
  <input type="hidden" id="inicio" name="dias[]">
  <input type="hidden" id="dia2" name="dias[]">
  <input type="hidden" id="dia3" name="dias[]">
  <input type="hidden" id="dia4" name="dias[]">
  <input type="hidden" id="dia5" name="dias[]">
  <input type="hidden" id="fin" name="dias[]">
     <button name="pdf">Generar Reporte PDF</button>
      <button name="xlsx" style="display: none;">Reporte EXCEL</button>
  </form>
</div>
 
</div>


</div>











 
  
  

  
</body>
</html>


<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>

    function noSunday(date){ 
          var day = date.getDay(); 
                      return [(day > 0), '']; 
      }; 

 


   (function($){
  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '&#x3c;Ant',
    nextText: 'Sig&#x3e;',
    currentText: 'Hoy',
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
    'Jul','Ago','Sep','Oct','Nov','Dic'],
    dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
    dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};
  $.datepicker.setDefaults($.datepicker.regional['es']);
})(jQuery);





$(function() {
    var startDate;
    var endDate;
    
    var selectCurrentWeek = function() {
        window.setTimeout(function () {
            $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }
    
    $('.week-picker').datepicker( {
      nextText: '',
    prevText: '',
        showOtherMonths: true,
        selectOtherMonths: true,
        firstDay: 1,
        dateFormat: 'dd-mm-yy',
        onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
            dayTwo = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 2);
            dayTree = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 3);
            dayFour = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 4);
            dayFive = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 5);
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);

            
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            console.log('dateformat: '+dateFormat);
            $('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            
            $('#inicio').val($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#fin').val($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            $('#dia2').val($.datepicker.formatDate( dateFormat, dayTwo, inst.settings ));
            $('#dia3').val($.datepicker.formatDate( dateFormat, dayTree, inst.settings ));
            $('#dia4').val($.datepicker.formatDate( dateFormat, dayFour, inst.settings ));
            $('#dia5').val($.datepicker.formatDate( dateFormat, dayFive, inst.settings ));
            
            selectCurrentWeek();
        },
        beforeShowDay: function(date) {
           var day = date.getDay();
            var cssClass = '';
           

             if (day == 0) {
                return [false, "somecssclass"]
            } else {
               if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
            }

        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
    });
    /*
    $('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
    $('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });
    */
});
    </script>
