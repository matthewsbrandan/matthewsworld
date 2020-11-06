<?php
    include('../conn/function.php');
    //Cadastro no matthewsworld
    if($_POST['mcadNome']!=null &&
       $_POST['mcadEmail']!=null &&
       $_POST['mcadSenha']!=null){
        $senha = md5($_POST['mcadSenha']);
        $sql="insert into usuario(nome,email,senha) values ('{$_POST['mcadNome']}','{$_POST['mcadEmail']}','{$senha}');";
        $res = enviarComand($sql,'bd_mtworld');
        if($res) header('Location: ../index.php?msg=1');
        else header('Location: ../index.php?msg=2');
    }
?>