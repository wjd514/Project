<?php

   require_once("../../new_head/safety_news_db_0713.php");
   
   if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
   }
   
   mysqli_query($conn,"set session character_set_connection=utf8;");
   mysqli_query($conn,"set session character_set_results=utf8;");
   mysqli_query($conn,"set session character_set_client=utf8;");



// get from work list
   $myId = $_GET[myId];

   mysqli_set_charset($conn,"utf8");
 
   $res = mysqli_query($conn,"select serialNumber from Works_On where id = '$myId' and conduct = 'orderer'");
   $result_work_list = array();


   while($row = mysqli_fetch_array($res))
   {
      array_push($result_work_list, $row[0] ); // array( 'serialNumber' => $row[0]) ); // array에 넣지 않고 하나씩 배열에 삽입.
   }


// get from confirm list
   $result_perform_list = array();

   for($i = 0; $i < count($result_work_list) ; $i++){

      $res = mysqli_query($conn,"SELECT * FROM Progress where serialNumber = '$result_work_list[$i]' ");
      $row_serialNumber = mysqli_fetch_array($res);

      if($row_serialNumber){
         for($j = 1;;){

            $res = mysqli_query($conn,"SELECT * FROM Progress where serialNumber = '$result_work_list[$i]' and discoveredNumbers = '$j' ");
            $row_discoveredNumbers = mysqli_fetch_array($res);

            if($row_discoveredNumbers){

               for($k = 1;;){
                  $res = mysqli_query($conn,"SELECT * FROM Progress where serialNumber = '$result_work_list[$i]' and discoveredNumbers = '$j' and indicationNumbers = '$k' ");
                  $row_indicationNumbers = mysqli_fetch_array($res);

                  if($row_indicationNumbers){

                     $res = mysqli_query($conn,"SELECT pro.*, mem.*, work.detectedTime, work.finishedTime, work.progressState, work.isNewFlag FROM `Progress` pro, `Member_Info` mem,  `Works_List` work WHERE pro.serialNumber = '$result_work_list[$i]' and pro.discoveredNumbers = '$j' and pro.indicationNumbers = '$k' and pro.performCount = (select max(performCount) from Progress where serialNumber =  '$result_work_list[$i]' and discoveredNumbers = '$j' and indicationNumbers = '$k') and mem.id = (select id from Works_List where serialNumber = '$result_work_list[$i]' and conduct = 'executor') and work.serialNumber =  '$result_work_list[$i]'");

                     $row = mysqli_fetch_array($res);
   
                     array_push( $result_perform_list, array('serialNumber' => $row[0], 'discoveredNumbers' => $row[1], 'discoveredMatters' => $row[2], 'indicationNumbers' => $row[3],  'requestContents' => $row[4], 'requestCount' => $row[5], 'performContents' => $row[6], 'performCount' => $row[7],'id' => $row[9], 'name' => $row[11], 'company' => $row[12], 'position' => $row[13], 'section' => $row[14],  'detectedTime' => $row[16], 'finishedTime' => $row[17], 'progressState' => $row[18], 'isNewFlag' => $row[19]));

                     $k++;

                  }
                  else{
                     $j++;
                     break;
                  }
               }
            }
            else {
               break;
            }
            
         }
      }
   }


   $json = json_encode(array("result" => $result_perform_list));

   //echo unistr_to_xnstr($json);
   echo $json;

   //echo $res;
   mysqli_close($conn);
?>