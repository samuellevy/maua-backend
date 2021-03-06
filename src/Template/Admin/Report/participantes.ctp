<?php
    $results = [];
    foreach ($stores as $store){
        $store_id = $store->id;
        $store_name = $store->name;
        foreach ($store->users as $user){
            if($user->active == 1 &&($user->first_access == 0 || $user->role_id == 6)){
                $id = $user->id;
                $name = $user->name;
                if ($user->role_id == 6){
                    $role = 'Funcionário';
                }
                if ($user->role_id == 4){
                    $role = 'Lojista';
                }
                $email = $user->email;
                $phone = $user->phone;
                if($user->first_access == 0){
                    $first_access = "Sim";
                }else{
                    $first_access = "Não";
                }
                array_push($results,['ID LOJA'=>$store_id, 'LOJA'=>$store->name, 'ID USUÁRIO'=>$id, 'NOME'=>$name, 'FUNÇÃO'=>$role, 'EMAIL'=>$email, 'TELEFONE'=>$phone, 'PRIMEIRO ACESSO'=>$first_access]);
            }
        }
    }

    $filename = 'Cimento Mauá - Quem entende vende - Participantes '.date('(d-m-Y)').'.csv';
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