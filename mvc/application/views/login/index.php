<style type="text/css">
  body{
    background-image: url(<?=URL ?>public/img/paper8.jpg);
  }
</style>
<body>
 
    <div class="container">
        <div class="login-box">
        <div class="login-inner">
          <form id="logg" action="<?=URL ?>login/signIn" method="post">
        <div class="login-logo">
             <img src="<?=URL ?>public/img/logo-blanco.png" >
        </div>
        <?php 

        if (isset($_SESSION['session_messages'])) {
         echo $_SESSION['session_messages'];
         session_destroy();
        } ?>
        
            <input id="usuario" name="usuario" type="text" readonly onclick="getKeys(this.id,'pedido')" placeholder="USUARIO" class="login-input" required="" />
            <input id="password" name="pass" type="password" placeholder="CONTRASEÑA" class="login-input" readonly onclick="getKeys(this.id,'pedido')" required="" />
            <input type="button" id="singlebutton" value="ENTRAR" name="singlebutton" class="login-button">
            </form>
        </div>
            
        </div>

    </div> 
   <div id="panelkeyboard2" class="keyboard">
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>
    
      <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="images/ex.png">
                        </div>
    
    
</div>
 
</body>
<script type="text/javascript">
var kb=false;
    $(document).ready(function(event) {
        $( "#singlebutton").click(function() {
                                          
                                                      
             $( "#logg" ).submit();                                      
                                             
    });
    });
    $(function() {
        console.log('fecha: '+localStorage.getItem('fecha')); 
      var currentdate = new Date().toJSON().slice(0,10).replace(/-/g,'/');
  console.log("fecha actual: "+currentdate);
console.log("fecha guardada: "+localStorage.getItem('fecha'));
if (localStorage.getItem('fecha')!==currentdate) {
  localStorage.removeItem('horaincio');
  localStorage.removeItem('tiroactual');
  localStorage.removeItem('segundosincio');
  localStorage.removeItem('fecha');
  console.log('se eliminaron los estorages');
}
   });
     $(document).keypress(function(e) {
    if(e.which == 13) {
      event.preventDefault();
      $('#singlebutton').click();
}
});


function getKeys(id,name) {
      $('#'+id).select();
      $('.input-active').removeClass('input-active');
      $('#'+id).addClass('input-active');
      $('#softk').attr('data-target', 'input[name="'+name+'"]');
        if (kb == false) {
          
          $(".login-box").animate({ top: '-=30%' }, 200);
            $("#panelkeyboard2").animate({ bottom: '+=50%' }, 200);
            kb = true;
        }
        var bguardar;
        
        $('#softk').empty();     
         $('.softkeys').softkeys({
                    target :  $('#'+id),
                    layout : [
                        [
                            
                            ['1','!'],
                            ['2','@'],
                            ['3','#'],
                            ['4','$'],
                            ['5','%'],
                            ['6','^'],
                            ['7','&amp;'],
                            ['8','*'],
                            ['9','('],
                            ['0',')']
                        ],
                    [
                            'q','w','e','r','t','y','u','i','o','p'
                            
                        ],
                        [
                            
                            'a','s','d','f','g','h','j','k','l','ñ'
                            
                            
                            
                        ],[
                            
                            'z','x','c','v','b','n','m','←']
                            
                            ],

                    id:'softkeys'
                });
              
                $('#hidekey').parent('.softkeys__btn').addClass('hidder'); 
    $('#savekey').parent('.softkeys__btn').addClass('saver').attr('id', 'saver');;            
$('#borrar-letras').parent('.softkeys__btn').addClass('large');
            $('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
            if (id=='virtualodt'||id=='virtualelem') { $('.savebutton').show();}else{$('.savebutton').hide();}
    }  

  
$(document).ready(function () {
  var ismobile= isMobileDevice();
    var link = document.getElementById('panelkeyboard2');
    if (ismobile==false) {
      
      $('#panelkeyboard2').addClass('desktopkey');
      
    }

});

    


function isMobileDevice() {
    return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
}  
</script>
</html>


