<?php
    class GetInfoByURLModel {      
      function __construct() {}
         
      public function getInformation($url){   
        $curl = curl_init();	 
        ini_set('max_execution_time', 300);
  			curl_setopt($curl, CURLOPT_URL, $url);  
  			curl_setopt($curl, CURLOPT_HEADER, 0);				
  			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
          
 			  $data = curl_exec($curl);          
  	    curl_close($curl);      
            
        $res_data = json_decode($data, true);
            
			  return $res_data;
		  }

      public function getInformationNoParcer($url){   
        $curl = curl_init();   
        ini_set('max_execution_time', 300);
        curl_setopt($curl, CURLOPT_URL, $url);  
        curl_setopt($curl, CURLOPT_HEADER, 0);        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
          
        $data = curl_exec($curl);          
        curl_close($curl);      
            
        return $data;
      } 

      function __destruct() {}     
    }
?>