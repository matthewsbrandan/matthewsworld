<!-- 
    ÁRVORE DE DIRETÓRIOS RELEVANTES
    index
    dashboard/index

    himym/index
    himym/play

    lworld/index
    lworld/autoral/index
    lworld/biblico/index
    lworld/secular/index

    mmart/index

    mtech/index

    pctrl/dashboard/index
    pctrl/dashboard/imp

    pmatth/index
 -->

 <?php
    //Função retorna uma string com os retornos até a raiz do projeto
    function locPath(){
        $retornos="";
        $locPath = getcwd();
        if(!inweb) $locPath = str_replace('\\','/',$locPath);
        $locPath = substr($locPath,strpos($locPath,'matthewsworld.life'));
        if(strlen($locPath)>18){
            $locPath = substr($locPath,18);
            $qtdRetornos = substr_count($locPath,"/");
            if($qtdRetornos>0){
                $retornos = str_repeat('../',$qtdRetornos);
            }
        }
        return $retornos;
    }
    //Essa função deve ser chamada no topo da página, encontrar alguma forma de chamá-la.
    function validateUser(){
        if(!(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0)){
            if(isset($_COOKIE['mtworldPass'])&&isset($_COOKIE['mtworldKey'])){
                $sql="select * from usuario where email='{$_COOKIE['mtworldPass']}' and senha='{$_COOKIE['mtworldKey']}';";
                if($linha = (enviarComand($sql,'bd_mtworld'))->fetch_assoc()){
                $_SESSION['user_mtworld'] = $linha['id'];
                $_SESSION['user_mtworld_nome'] = $linha['nome'];
                $_SESSION['user_mtworld_email'] = $linha['email'];
                } 
            }
        }
        return isset($__SESSION[user_mtword])?true:false;
    }

    if(!isset($retornos)) $retornos = locPath();
    
    if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){
        include($retornos.'function/ctrlm.php');
        include($retornos.'function/mnav.php');
        include($retornos.'function/arty.php');
        include($retornos.'function/wmatth.php');
    }
?>