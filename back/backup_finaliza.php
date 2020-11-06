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
        <div class="bg-dark col-lg-12 rounded pb-3 text-light" style="box-shadow: 0px 0px 50px #601C55;">
          <div class="d-block bg-<?php echo inweb==0?'warning':'info';?> text-center mt-3 rounded"><?php echo inweb==0?'off-line':'on-line';?></div>
          <h1 class="mt-2">Backup Part. II - <?php echo "bd_".$_GET['page']; ?> </h1>
          <div class="table-responsive mt-4">
            <?php if(testConect('bd_'.$_GET['page'])){ ?>
              <div class="alert alert-light text-center" role="alert"> Banco Conectado! </div>
            <?php }else{ ?>
              <div class="alert alert-danger text-center" role="alert"> Falha na Conexão com o Banco! </div>
            <?php }
            function isView($column){
                $retorno = false;
                switch($column){ case 'detalhelivros': $retorno = true; break; }
                return $retorno;
            }
            function format_array($arr){
                $retorno = "";
                for($i = 0; $i < count($arr); $i++)
                    for($c = 0; $c<count($arr[$i]) ; $c++ ) foreach($arr[$i][$c] as $value) $retorno.=$value;
                return $retorno;
            }
            //Mapeando Tabelas
            if(isset($_GET['tbl']) && ($_GET['tbl']=='todos')){
                $res = enviarComand('show tables;','bd_'.$_GET['page']);
                while($linha = $res->fetch_assoc()){ if(!isView($linha[key($linha)])) $keytable[] = $linha[key($linha)]; }
            }else $keytable[0] = $_GET['tbl'];          
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
                if(format_array($arrayall)==$_POST['bupDesc']){
            ?>
                <div class="alert alert-success text-center" role="alert"> Descrição do Banco de Dados Válidada! </div>
                <?php
                    $arrSql = explode(';',$_POST['bupDados']);
                    foreach($arrSql as $sql){ if(!empty(trim($sql))){
                        $result = enviarComand($sql,'bd_'.$_GET['page']); if($result){
                ?>
                <div class="alert alert-success text-center mt-3" role="alert"> Banco de Dados Sincronizado! </div>
                <?php } else{ ?>
                <div class="alert alert-danger text-center mt-3" role="alert"> Houve um erro ao fazer o Backup do Banco! <hr/>
                <?php print_r($arrSql); ?>
                </div>
                <?php } } } ?>
                <?php } else { ?>
                <div class="alert alert-danger text-center" role="alert"> Os Bancos não são Equivalentes! <hr/>
                <div class="table-responsive">
                <?php echo format_array($arrayall)."<br>".$_POST['bupDesc']; ?>
                </div>
                </div>
                <?php }?>
            <?php } ?>
            <div class="mx-auto m-4" style="max-width: 400px;">
            <a href="http://localhost/matthewsworld.life/dashboard/" class="btn btn-outline-primary btn-block mx-auto">LocalHost</a>
            <a href="https://www.matthewsworld.life/dashboard/" class="btn btn-outline-primary btn-block mx-auto">Hospedagem Web</a>
            </div>
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