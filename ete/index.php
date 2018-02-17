<!DOCTYPE html>
<html>
<head>
  <title>ETE</title>
  <script src="../js/libs/jquery.min2_1_4.js"></script>
  <style>
    body{
      margin: 0;
      padding:0;
      background:#1D1A1D;
    }
    .section{
      width: 100%;

    }
    ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    height: 65px;
    /* background-color: #002D44; */
    background-color: #212121;
    border-bottom: 1px solid #5C5C5C;
}
li{
  float: left;
}
li span:not(.span-button) {
    display: block;
    color: white;
    font-size: 28px;
    font-family: 'monse-medium';
    text-align: center;
    line-height: 65px;
    padding: 0 18px;
    text-decoration: none;
}
.live-indicator {
    padding: 10px;
    background: #343434;
    border: 1px solid #5C5C5C;
    color: #CCCCCC;
    font-weight: bolder;
    border-radius: 3px;
    margin: 7px 15px;
    font-size: 20px;
}
.square-button {
    width: 150px;
    height: 150px;
    background: black;
    display: block;
    display: inline-block;
    margin: 5px;
    border-radius: 3px;
    cursor: pointer;
    position: relative;
    -webkit-box-shadow: 2px 2px 3px 0px rgba(50, 50, 50, 0.31);
    -moz-box-shadow: 2px 2px 3px 0px rgba(50, 50, 50, 0.31);
    box-shadow: 2px 2px 3px 0px rgba(50, 50, 50, 0.31);
}
.square-button img {
    position: absolute;
    width: 70%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.yellow {
    background: #F6BB43;
}
.green {
    background: #35BC9B;
}
.purple {
    background: #434A54;
}
.blue {
    background: #4C89DC;
}
.red {
    background: #E9573E;
}
.timer-container {
    width: 100%;
    text-align: center;
    position: relative;
    margin-bottom: 20px;
}
#timer {
    width: 470px;
    background: #22242A;
    height: 140px;
    margin: 0 auto;
    border-radius: 3px;
    border: solid 2px #868686;
}
#timer span {
    line-height: 140px;
    font-size: 110px;
    font-weight: bold;
    color: #fff;
}
.button-panel{
  text-align: center;
}
.pausetext {
    color: #fff;
    font-family: "monse-bold";
    position: absolute;
    right: 25px;
    top: 14px;
    font-size: 25px;
}
.endOfDay {
    width: 250px;
    height: 60px;
    position: relative;
    border-radius: 3px;
    float: left;
    margin-left: 20px;
    cursor: pointer;
}
.pauseicon {
    height: 45px;
    width: 45px;
    position: absolute;
    left: 15px;
    top: 8px;
}
.pauseicon img {
    width: 100%;
}
.pause {
    width: 180px;
    height: 60px;
    position: relative;
    border-radius: 3px;
    float: right;
    margin-right: 20px;
    cursor: pointer;
}
.modal-footer{
  width: 670px;
  margin: 0 auto;
}

  </style>
</head>
<body>
<ul>
  <li><span style="color: #CECECE; font-size:20px;">Adan | Corte</span></li>
  <li><div class="live-indicator">Tiros Realizados: 0</div></li>
    <input type="hidden" id="realtime" value="00:18:36">
    <input type="hidden" id="mach" value="1"> 
     <input type="hidden" id="el" value="58">         
  <li style="float:right"></li>
   <li style="float:right"><span id="hora"></span></li>
    <li style="float:right ;" id="avancerealtime">



 <div class="live-indicator">Produccion Esperada: 186</div>
 
</li>
              
</ul>


<div id="ajuste" class="section">
  <div class="button-panel">
                        <div id="parts" class="square-button purple">
                          <img src="../images/elegir.png">
                        </div>
                        <div id="stop" class="square-button blue">
                          <img src="../images/guard.png">
                        </div>
                        
                        <div class="square-button green" >
                          <img src="../images/dinner2.png">
                        </div>
                        <div class="square-button yellow" >
                          <img src="../images/alerts.png">
                        </div>
  </div>
<div class="timer-container">
    <div id="chronoExample">
        <div id="timer"><span class="values">00:10:34</span></div>
            <input type="hidden" id="elemvirtual" name="elemvirtual">
            <input type="hidden" id="idelemvirtual" name="idelemvirtual">
            <input type="hidden" id="odtvirtual" name="odtvirtual">
            <input type="hidden" id="timee" name="tiempo">
            <input type="hidden" id="ontime" name="ontime" value="true">
        </div>
    <div id="chronoExample2" style="display: none;">
        <div id="timer2"><span class="values">00:00:00</span></div>
                                </div>
                                </div>
                                <div class="modal-footer">
                        <form id="pauseorder" action="opp.php" method="post">
                          <input type="hidden" value="" name="numodt">
                          <input type="hidden" name="action" value="pause">
                          <input type="hidden" name="pausetime" id="pausetime">
                          <input type="hidden" name="pausetime">
                        </form>
                        
                            <div class="row "> <a href="logout.php"> 
                                 <div class="pause red"><div class="pauseicon"><img src="../images/exit-door.png"></div><div class="pausetext">SALIR</div></div></a>
                                 
                                 <div class="endOfDay blue" onclick="endOfDay()"><div class="pauseicon"><img src="../images/reloj.png"></div><div class="pausetext">FIN DEL DIA</div></div>
                            </div>
                        </div>
</div>
<div id="tiro" class="section"></div>
<div id="encuesta" class="section"></div>

</body>
</html>
<script>
  $(document).ready(function () {
    var win=$(window).height();
    $('.section').height(win-66);
    console.log(win);
  });
</script>