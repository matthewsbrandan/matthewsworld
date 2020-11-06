<?php
    session_start();
    function unsetcookie($key, $path = '', $domain = '', $secure = false){
        if(array_key_exists($key, $_COOKIE)){
            if (false === setcookie($key, null, -1, $path, $domain, $secure)){ return false; }
            unset($_COOKIE[$key]);
        }
        return true;
    }
    if(isset($_POST['email'])&&isset($_POST['senha'])){
        include('../conn/function.php');
        $sql="select * from usuario where email='{$_POST['email']}';";
        $res=enviarComand($sql,'bd_mtworld');
        if($res->fetch_assoc()){
            $senhaMd5 = md5($_POST['senha']);
            $sql="select * from usuario where email='{$_POST['email']}' and senha='$senhaMd5';";
            $res=enviarComand($sql,'bd_mtworld');
            if($linha = $res->fetch_assoc()){
                $_SESSION['user_mtworld'] = $linha['id'];
                $_SESSION['user_mtworld_nome'] = $linha['nome'];
                $_SESSION['user_mtworld_email'] = $linha['email'];
                if(isset($_GET['conectar'])&&$_GET['conectar']==1){
                    $expira = time() + 60*60*24*30; 
                    setCookie('mtworldPass',$linha['email'], $expira,'/');
                    setCookie('mtworldKey',$senhaMd5, $expira,'/');
                }
                header('Location: ../index.php');
            }else header('Location: ../index.php?msg=4');
            
        }else header('Location: ../index.php?msg=3');
    }else
    if(isset($_GET['logoff'])){
        if(isset($_COOKIE['mtworldPass'])&&isset($_COOKIE['mtworldKey'])){
            unsetcookie('mtworldPass','/');
            unsetcookie('mtworldKey','/');
        }
        session_destroy();
        header('Location: ../index.php');
    }
?>