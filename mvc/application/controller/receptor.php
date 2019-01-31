<?php


class Receptor extends Controller
{
    
    public function index(){
        $log=new Logs();
         $log->lwrite(json_encode($_POST),'recibido');
            $log->lclose();
            
            print_r($_POST);

    }

   


    

    

    


  
    
}
