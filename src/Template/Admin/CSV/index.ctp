<?php
  $results = [ 
      [
            "name"           => "Anna Smith",  
            "email_id"      => "annabsmith@inbound.plus"  
      ],  
      [  
            "name"           => "Johnny Huck",  
            "email_id" => "johnnyohuck@inbound.plus"  
      ]  
      ];


  $filename = 'Relatorio'.date('(d-m-Y)').'.csv';
  header("Content-type: text/csv");       
  header("Content-Disposition: attachment; filename=$filename");       
  $output = fopen("php://output", "w");       
  $header = array_keys($results[0]);       
  fputcsv($output, $header);       
  foreach($results as $row)       
  {  
      fputcsv($output, $row);  
  }       
  fclose($output);       
?>  