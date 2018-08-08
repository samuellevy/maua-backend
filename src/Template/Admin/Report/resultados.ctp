<?php
    $results = [];
    foreach ($stores as $store){
        $id = $store->id;
        $name = $store->name;
        $cnpj = $store->users[0]->username;
        $balconistas = 0;
        $quiz = 0;
        foreach ($store->users as $user){
            if($user->role_id == 6){
                $balconistas++;
                if (isset($user->course_progress[0])){
                    $quiz++;
                }
            }
        }
        array_push($results,['LOJA'=>$store->name, 'CNPJ'=>$cnpj, 'ID'=>$id, 'BALCONISTAS'=>$balconistas, 'VIDEO AULA ASSISTIDA'=>$quiz, 'QUIZ RESPONDIDO'=>$quiz]);
    }

    $filename = 'Cimento Mauá - Quem entende vente - Resultados '.date('(d-m-Y)').'.csv';
    header("Content-type: text/csv; charset=utf-8");       
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