<?php session_start(); if(isset($_GET['page'])&&isset($_POST)&& !empty($_POST)){ include('../conn/function.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>MW - Backup Database</title>
  <!-- Icone -->
  <link rel="icon" href="../img/arrow-icon.jpg" type="image">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Bootstrap core CSS -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/scroll.css" rel="stylesheet">
  <style>
  </style>
  <script src="jquery/jquery.js"></script>
  <script>
  </script>
</head>
<body>
  <!-- Page Content | Matthews World -->
  <section>
    <div class="container p-2">
      <div class="row">
        <div class="col-lg-12 rounded pb-3 bg-dark text-light" style="box-shadow: 0px 0px 50px #601C55;">
          <div class="d-block bg-<?php echo inweb==0?'warning':'info';?> text-center mt-3 rounded"><?php echo inweb==0?'off-line':'on-line';?></div>
          <h1 class="mt-2">Backup Database - <?php echo "bd_".$_GET['page']; ?> </h1>
          <div class="table-responsive">
            <form method="POST" action="<?php echo inweb==1?'http://localhost/matthewsworld.life/back/':'https://www.matthewsworld.life/back/';?>backup_finaliza.php?page=<?php echo $_GET['page'].'&tbl='; echo isset($_POST['todos'])?$_POST['todos']:$_POST['tabela']; ?>">
                <?php
                function isView($column){
                    $retorno = false;
                    switch($column){ case 'detalhelivros': $retorno = true; break; }
                    return $retorno;
                }
                function excecaoColumn($column,$arrorder){
                    $retorno = null;
                    switch($_GET['page']){
                        case 'atm':
                            if($column=="objaluno"){
                                if(!(in_array('alunos',$arrorder))) $retorno = true;
                                else if(!(in_array('objetivo',$arrorder))) $retorno = true;
                                else $retorno = false;
                            }else
                            if($column=="pagamento"){
                                if(!(in_array('alunos',$arrorder))) $retorno = true;
                                else $retorno = false;
                            }else
                            if($column=="respondeexerc"){
                                if(!(in_array('alunos',$arrorder))) $retorno = true;
                                else if(!(in_array('exerc',$arrorder))) $retorno = true;
                                else $retorno = false;
                            }
                            break;
                        case 'pmatth':
                            if($column=="musica") if(!(in_array('album',$arrorder))) $retorno = true;
                            else $retorno = false;
                            break;
                        case 'wmatth':
                            if($column=="chat") if(!(in_array('usuario',$arrorder))) $retorno = true;
                            else $retorno = false;
                            break;
                        default: die('Coluna Indefinida. Sem Padrão ou Exceção para está Foreign Key');
                    }
                    return $retorno;
                }
                function analizaPadrao($column,$keyt,$arrorder){
                    $retorno = null;
                    $excecao = false;
                    if(!preg_match('/id/',$column)) $excecao = true;
                    //Padrão 3 - Tabelas com tblNomeDaTabela (TODAS TABELAS DEVEM TER TBL)
                    else if(preg_match('/tbl/',$keyt[0])){
                        if(in_array("tbl".substr(strstr($column,'_'),1,-3),$keyt)){
                            if(!(in_array("tbl".substr(strstr($column,'_'),1,-3),$arrorder))) $retorno = true;
                            else $retorno = false;
                        }else $excecao = true;
                    }
                    //Padrão 1 - nomedatabela_id
                    else if(preg_match('/_/',$column)){
                        if(in_array(substr($column,0,-3),$keyt)){
                            if(!(in_array(substr($column,0,-3),$arrorder))) $retorno = true;
                            else $retorno = false;
                        }else $excecao = true;
                    }
                    //Padrão 2 - nomeDaTabelaId
                    else{
                        if(in_array(substr($column,0,-2),$keyt)){
                            if(!(in_array(substr($column,0,-2),$arrorder))) $retorno = true;
                            else $retorno = false;
                        }else $excecao = true;
                    }
                    //Exceção
                    if($excecao) $retorno = excecaoColumn($column,$arrorder);
                    return $retorno;
                }
                function format_array($arr){
                    $retorno = "";
                    for($i = 0; $i < count($arr); $i++)
                        for($c = 0; $c<count($arr[$i]) ; $c++ ) foreach($arr[$i][$c] as $value) $retorno.=$value;
                    return $retorno;
                }
                //Mapeando Tabelas
                if(isset($_POST['todos'])){
                    $res = enviarComand('show tables;','bd_'.$_GET['page']);
                    while($linha = $res->fetch_assoc()){ if(!isView($linha[key($linha)])) $keytable[] = $linha[key($linha)]; }
                }else $keytable[0] = $_POST['tabela'];                                                   
                if(isset($keytable)){
                    //Descrição das Tabelas
                    for($i = 0; $i < count($keytable); $i++){
                        $sql = 'desc ' . $keytable[$i] . ';';
                        $res = enviarComand($sql,'bd_'.$_GET['page']);
                        $c=0;
                        while($linha = $res->fetch_assoc()){
                            $arrayall[$i][$c]['Table']   = $keytable[$i];
                            $arrayall[$i][$c]['Field']   = $linha['Field'];
                            $arrayall[$i][$c]['Type']    = $linha['Type'];
                            $arrayall[$i][$c]['Null']    = $linha['Null'];
                            $arrayall[$i][$c]['Key']     = $linha['Key'];
                            $arrayall[$i][$c]['Default'] = $linha['Default'];
                            $arrayall[$i][$c]['Extra']   = $linha['Extra'];                        
                            $c++;
                        }
                    }
                    $arrayorder = array();
                    if(count($keytable)==1) $arrayorder[] = $keytable[0];
                    //Ordenação de Tabelas
                    else for($i = 0; $i < count($keytable); $i++){
                        if(isset($arrayorder) && count($keytable)==count($arrayorder))$i=count($keytable);
                        else {
                            $temfk = false;
                            if(!(isset($arrayorder)) || !in_array($arrayall[$i][0]['Table'],$arrayorder)){
                                for($c = 0; $c<count($arrayall[$i]) ; $c++ ){
                                    if(in_array('MUL',$arrayall[$i][$c])){   
                                        if(analizaPadrao($arrayall[$i][$c]['Field'],$keytable,$arrayorder)){
                                            $temfk=true;
                                            $c=count($arrayall[$i]);
                                        }
                                    }
                                }
                                if(!$temfk){ $arrayorder[] = $arrayall[$i][0]['Table']; }
                            }
                            if(($i+1) == count($keytable)){
                                if(count($keytable)!=count($arrayorder)) $i=-1;
                            }
                        }
                    }
                ?>
                <div class="form-group mt-1">
                    <label>Descrição do Banco:</label>
                    <textarea class="form-control" rows="5" id="bupDesc" name="bupDesc"><?php echo format_array($arrayall); ?></textarea>
                </div>
                <div class="form-group mt-3">
                    <textarea class="form-control" rows="5" id="bupDados" name="bupDados"><?php
                    //Delete - Ordem Descendente
                    for($desc = (count($keytable)-1); $desc >= 0; $desc--){
                        $sql = " delete from {$arrayorder[$desc]}; ";
                        echo $sql;
                    }
                    //Insert - Ordem Ascendente
                    for($asc = 0; $asc < count($keytable); $asc++){                    
                        $sql = "select * from {$arrayorder[$asc]};";
                        $res = enviarComand($sql,'bd_'.$_GET['page']);
                        while($linha = $res->fetch_assoc()){
                            $backupSql = " insert {$arrayorder[$asc]}(";
                            $insertConteudo = "values (";
                            foreach($linha as $key=>$value){
                                $backupSql.= $key.",";
                                $insertConteudo.= "'".$value."',";
                            }
                            $backupSql = substr($backupSql,0,-1); $backupSql.=") ";
                            $insertConteudo = substr($insertConteudo,0,-1); $insertConteudo.="); ";

                            echo $backupSql.$insertConteudo;
                        }
                    }
                }?></textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-block" id="btnEnvia" name="btnEnvia">Enviar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Bootstrap core JavaScript -->
  <script src="../jquery/jquery.min.js"></script>
  <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php }else echo "Está Página está protegida!"; ?>