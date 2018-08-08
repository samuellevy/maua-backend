<?php
    $results = [];
    foreach ($stores as $store){
        $store_name = $store->name;
        foreach ($store->users as $user){
            if($user->first_access == 0){
                $id = $user->id;
                $name = $user->name;
                if ($user->role_id == 6){
                    $role = 'Vendedor';
                }
                if ($user->role_id == 4){
                    $role = 'Lojista';
                }
                $email = $user->email;
                array_push($results,['LOJA'=>$store->name, 'ID'=>$id, 'NOME'=>$name, 'FUNÇÃO'=>$role, 'EMAIL'=>$email]);
            }
        }
    }

    $filename = 'Cimento Mauá - Quem entende vente - Participantes '.date('(d-m-Y)').'.csv';
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