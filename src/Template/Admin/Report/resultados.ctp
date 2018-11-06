<?php
    $results = [];
    foreach ($stores as $store){
        if(isset($store->users[0])){
            if(!($store->users[0]->first_access)){
                $id = $store->id;
                $storename = $store->name;
                $cnpj = $store->users[0]->username;
                $name = $store->users[0]->name;
                $email = $store->users[0]->email;
                $balconistas = 0;
                $quiz = 0;
                foreach ($store->users as $user){
                    if($user->role_id == 6){
                        $balconistas++;
                        if (isset($user->course_progress[0])){
                            foreach($user->course_progress as $progress){
                                if ($progress->course_id == 3){
                                    $quiz++;
                                    break;
                                }
                            }
                        }
                    }
                }
                array_push($results,['ID'=>$id, 'LOJA'=>$store->name, 'CNPJ'=>(string)$cnpj, 'NOME'=>$name, 'EMAIL'=>$email, 'BALCONISTAS'=>$balconistas, 'VIDEO AULA ASSISTIDA'=>$quiz, 'QUIZ RESPONDIDO'=>$quiz]);
            }
        }
    }

    // die(debug($results));

    $filename = 'Cimento Mauá - Quem entende vende - Resultados '.date('(d-m-Y)').'.csv';
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