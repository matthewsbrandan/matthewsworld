<?php
    session_start();
    include('../conn/function.php');
    function tblUser($p){
        $retorno = 'select count(*) tem from usuario where email=';
        switch($p){
            case 'bd_atm':      $retorno="select count(*) tem from alunos where email=";        break;
            case 'bd_lworld':   $retorno="select count(*) tem from tbluser where user_email=";  break;
        }
        return $retorno;
    }
    $email = enviarComand("select email from usuario where id='{$_SESSION['user_mtworld']}';",'bd_mtworld')->fetch_assoc()['email'];
    if(isset($_POST)&&!empty($_POST)){
        $sql="insert user_sites(usuario_id,sites_id,status) values ";
        foreach($_POST as $value){
            $bd = enviarComand('select bd from sites where id='.$value.';','bd_mtworld')->fetch_assoc()['bd'];
            if($bd=='bd_himym' || $bd=='bd_pmatth' || $bd=='bd_mmart') $status = 'ativo';
            else{
                if(enviarComand(tblUser($bd)."'$email';",$bd)->fetch_assoc()['tem']>0) $status = 'ativo';
                else $status = 'pendente';
            }
            $sql.="('{$_SESSION['user_mtworld']}','$value','$status'), ";
        }
        $sql = (substr($sql,0,-2)).";";
        $datav = enviarComand($sql,'bd_mtworld');
        if($datav) header('Location: ../index.php?vinculo=1');
        else header('Location: ../index.php?vinculo=0');
    }else header('Location: ../index.php');
?>