<?php
date_default_timezone_set("America/Mexico_City");
require('../saves/conexion.php');

session_start();
$getusers="SELECT * FROM usuarios WHERE team_member='true'";
$users=$mysqli->query($getusers);


?>

<!DOCTYPE html>
<html>
<head>
  <title>Tiro</title>
  <style>
    body{
      background: #f3f3f3;
      padding: 0;
      margin: 0;
      font-family: sans-serif;
    }
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #fff;
    border-bottom: 1px solid #e7e7e7;
}

li {
    float: left;
    border-right:1px solid #e7e7e7;
}

li:last-child {
    border-right: none;
}

li a{
    display: block;
    color: #404C5B;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
li span{
  padding: 5px;
  background: #1D89E4;
  color:#fff;
}

li a:hover:not(.active){
    
}

.active {
    
}
.users-container{
  margin: 0 auto;
  text-align: left;

}
.member{
  
  width: 25%;
  display: inline-block;
  vertical-align: top;
  margin:-2px;


}
.member-content{
  border:1px solid #e7e7e7;
  background: #fff;
  border-radius: 4px;
  width: 95%;
  margin: 10px auto 5px auto;
}
.member-header{

}
.member-header p{
  text-align: left;
  margin-top: 10px;
  font-size: 30px;
  margin-bottom: 10px;
  color: #404C5B;
  text-indent: 10px;
}
.member-photo{
  width: 40%;
  display: inline-block;
  vertical-align: top;
  text-align: center;
  margin: 0 -2px;
  
}
.member-photo img{
  width: 70%;
  display: block;
  margin: 13px auto 0 auto;
  border-radius: 50%;
  border:1px solid #e7e7e7;
}
.member-info{
  width: 80%;
  display: inline-block;
  vertical-align: top;
  background: #fff;
}
.member-name{
color: #404C5B;
position: relative;
padding:5px 10px;
font-size: 35px;

}
.timer-area{
 
  position: relative;
padding:5px 10px;
font-size: 35px;
background: #ccc;
text-align: center;
color: #fff;
}
.member-body{
  width: 100%;
  height: 125px;
  position: relative;
}
.personal-timer{
  width: 100%;
  text-align: left;
  
}
.personal-timer span{
  padding: 5px 15px;
  background: #ACB7BF;
  color:#fff;
  font-size: 26px;
  border-radius: 30px;
}
.member-name-timer{
  display: inline-block;
  vertical-align: top;
  width: 60%;
  margin: 0 -2px;
}
.disabled{
  opacity: 0.6;
}
/* usuario en tiro */
.tiro{
  background: #5EAF46;
}
.tiro rect{
  fill: #303231!important;
  color: #303231!important;
}
.tiro rect[x="25"]{
  fill: #95CF98!important;
  color: #95CF98!important;
}
.tiro text{
  fill: #ffffff!important;
  color: #ffffff!important;
}
.tiro span{
  background: #95CF98!important;
}
.tiro p{
  color:#fff!important;
}
.tiro img{
  border:solid 1px #95CF98;
}

/* usuario en alerta */
.alerta{
  background: #FCCC00;
}
.alerta rect{
  fill: #2B2B2D!important;
  color: #2B2B2D!important;
}
.alerta rect[x="25"]{
  fill: #fff!important;
  color: #fff!important;
}
.alerta text{
  fill: #C09C00!important;
  color: #C09C00!important;
}
.alerta span{
  background: #2B2B2D!important;
}
.alerta p{
  color:#2B2B2D!important;
}
.alerta img{
  border:solid 1px #C09C00;
}

.backdrop
    {
      position:absolute;
      top:0px;
      left:0px;
      width:100%;
      height:100%;
      background:#000;
      opacity: .0;
      filter:alpha(opacity=0);
      z-index:50;
      display:none;
    }
 
 
    .box{
      position:absolute;
      top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      width:900px;
      background:#ffffff;
      z-index:51;
      padding:10px;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      -moz-box-shadow:0px 0px 5px #444444;
      -webkit-box-shadow:0px 0px 5px #444444;
      box-shadow:0px 0px 5px #444444;
      display:none;
    }
    .modal-body{
      text-align: center;
    }
.option{
  width: 180px;
  height: 180px;
  background: #05BDE3;
  position: relative;
  color: #fff;
  display: inline-block;
  vertical-align: top;
  margin: 5px;
}
.option div{
  width: 90%;
  position: absolute;
  top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 25px;
}
  </style>
  <!-- bar chart -->
    <script type="text/javascript" src="../js/libs/google_api.js"></script> 
  <!-- pie 
       <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Type', 'ETE'],['ETE ', 40],['MUDA', 60] ]);
      var muda_color;
      
        var options = {chartArea: {width: '90%',  height: '90%'},
                       
                       pieSliceTextStyle: {color: 'white', fontSize: 16},
                       
                       legend: 'none',
                    pieSliceText: 'label',
                       is3D:false,                                               
                      // enableInteractivity: false,
                       colors: ['#84b547','#ededed' ],
                                           
                       backgroundColor: 'transparent'
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>-->
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart(iduser,dispon,desemp,calidad) {
      /*
      console.log('el id es: '+iduser);
      var data = google.visualization.arrayToDataTable([
          ['valor', 'porcentaje'],['DISPONIBILIDAD',dispon, 'Foo text'],['DESEMPEÑO',desemp, 'Foo text'],['CALIDAD',calidad, 'Foo text'],]);
      
      
        var options = { // api de google chats, son estilos css puestos desde js
            
            width: "100%", 
            height: "100%",
            chartArea: {left: 25, top: 10, width: "100%", height: "80%"},
            legend: 'none',
            enableInteractivity: true,                                               
            fontSize: 12,
            hAxis: {
                    textStyle: {
                      color: '#00927B'
                    }
                  },
            vAxis: {
                textStyle: {
                      color: '#00927B'
                    },
            viewWindowMode:'explicit',
            viewWindow: {
              max:100,
              min:0
            }
        },
            colors: ['#05BDE3'],    
            backgroundColor: 'transparent'
        };
      var chart = new google.visualization.ColumnChart(iduser);
      chart.draw(data,options);

      */
      var data = new google.visualization.DataTable();
  data.addColumn('string', 'Name');
  data.addColumn('number', 'Value');
  data.addColumn({type: 'string', role: 'annotation'});
  
  data.addRows([
    ['DISPONIBILIDAD', dispon, 'DISPONIBILIDAD'],
    ['DESEMPEÑO', desemp, 'DESEMPEÑO'],
    ['CALIDAD', calidad, 'CALIDAD']
  ]);

  var view = new google.visualization.DataView(data);
  view.setColumns([0, 1, 1, 2]);

  var chart = new google.visualization.ComboChart(iduser);

  chart.draw(view, {
    height:"100%",
    width: "100%",
    enableInteractivity: false,
    chartArea: {left: 25, top: 10, width: "100%", height: "80%"},
     hAxis: {
                    textStyle: {
                      color: '#00927B'
                    }
                  },
            vAxis: {
                textStyle: {
                      color: '#00927B'
                    }},
              viewWindowMode:'explicit',
            viewWindow: {
              max:100,
              min:0
            }
        ,
    colors: ['#05BDE3'],    
    backgroundColor: 'transparent',
    series: {
      0: {
        type: 'bars'
      },
      1: {
        type: 'line',
        color: 'grey',
        lineWidth: 0,
        legend: false,
        pointSize: 0,
        visibleInLegend: false
      }
    },
    vAxis: {
      maxValue: 100
    }
  });
  }
  </script>

   
  <script src="../js/libs/jquery.min.js"></script>
    <script src="../js/easytimer.min.js"></script>

      
   

<link rel="stylesheet" href="../css/softkeys-0.0.1.css?v=3">
</head>
<body>
<ul>
  <li><a class="active" href="#home">Opciones</a></li>
  
  <li style="float:right"><a href="#about">Orden actual: <span>FDFDFD</span> </a></li>
</ul>
<div class="members-container">
<?php while ($user=mysqli_fetch_assoc($users)) {
  
 ?>
<!-- credencial --> 

<div class="member">
<div class="member-content <?=($user['logged_in']=='Naomi')? 'tiro' :(($user['logged_in']=='Alejandro')? 'alerta' :'' )?>" data-id="<?=$user['id']?>">
  <div class="member-header">
  <div class="member-photo">
    <img src="../<?=((!empty($user['foto']))? $user['foto'] :'images/default.jpg')?>">
  </div>
  <div class="member-name-timer">
    <p><?=$user['logged_in']?></p>
    <div class="personal-timer">
<span id="<?=$user['id'] ?>-timer">00:00:00</span>
  
</div>
  </div>
  
</div>
<div class="member-body">
 <div id="<?=$user['id']?>" style="top:5px;width: 98%;left: 1px; height: 120px; position:absolute;"></div>
  
</div>
</div>
</div>  
<!-- credencial --> 
<script>
var userid=document.getElementById(<?=$user['id'] ?>);
var timer_<?=$user['id'] ?> = new Timer();
 timer_<?=$user['id'] ?>.addEventListener('secondsUpdated', function (e) {
    $('#'+<?=$user['id'] ?>+'-timer').html(timer_<?=$user['id'] ?>.getTimeValues().toString());
});
  drawChart(userid,40,30,70);
  function start<?=$user['id'] ?>(){
    timer_<?=$user['id'] ?>.start();
  }
</script>

<?php } ?>


</div>
<div class="box">
  
</div>
<div class="backdrop"></div>
</body>
</html>
 <script>

  
 /*  
$( ".member-content" ).click(function() {
    
    var functionName='start'+$(this).data('id');
    eval(functionName + "()");
  
}); */

$( ".member-content" ).click(function() {

  var user= $( ".member-content" ).data('id');
  $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
$('.backdrop').css('display', 'block');

  $.ajax({  
                      
          type:"POST",
          url:"modal_content.php",   
          data:{user:user}, 
          success:function(data){

          $('.box').html(data);
          $('.box').animate({'opacity':'1.00'}, 300, 'linear');
          $('.box').css('display', 'block');
           
          }

          }); 
    
    
  
});


$( ".backdrop" ).click(function() {

   $('.backdrop, .box').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .box').css('display', 'none');
        });

});




 </script>