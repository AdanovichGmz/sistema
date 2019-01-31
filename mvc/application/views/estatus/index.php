    
 <!-- bar chart -->
    <script type="text/javascript" src="<?php echo URL; ?>public/js/libs/google_api.js"></script>   
    <script type="text/javascript">

    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);

    function drawChart(iduser,dispon,desemp,calidad) {

      /*
      //antigua grafica
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
      //nueva grafica
     
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
      maxValue: 100,
      minValue: 0

    }
  });
  }
  function timeOver(){  
  animacion = function(){  
  $('.time-over').toggleClass('minifade');
}
setInterval(animacion, 590);

}
  </script>  
<style>
  body{
    background: #ededed!important;
  }
</style>
<body id="body">

<?php include 'estatus_content.php' ?>


</body>





<script src="<?php echo URL; ?>public/js/libs/jquery-ui.js"></script>
 <script>  


var myIndex = 0;
timeOver();
carousel();
function carousel() {
    var i;
    var x = document.getElementsByClassName("carousel-page");

    for (i = 0; i < x.length; i++){
       x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length){myIndex = 1
       $.ajax({  
                      
          type:"POST",
          url:"<?=URL ?>estatus/reload/",   
          data:{display:'block'}, 
          success:function(data){
            $('#body').html(data);
          }

          }); 
    }
    
    console.log('lenght: '+x.length);
    console.log('index: '+myIndex); 
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 10000);

}






 </script>