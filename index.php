<?php
  session_start();
  include('conn/function.php');
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Matthew's World</title>
  <!-- Icone -->
  <link rel="icon" href="img/ac_unit.svg" type="image/svg+xml">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/scroll.css" rel="stylesheet">
  <style>
      body { background-color: black; overflow: hidden; }
      main {
        height: 100vh;
        background: url('img/neve1920.jpg') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
        overflow: auto;
      }
      section{ display: none; }
      #logado{ display: none; }
      .font-n{ font-weight: 200 }
      .nav-link{ transition: .3s background; border-radius: 3px; }
      .nav-link:hover{ background: rgba(250,250,250,.2); }
      .card-log{ 
          background: transparent;
          text-align: center;
          color: #ddd;
          border: none;
      }
      .input-transparente{
          background: rgba(200,200,200,.1);
          border-left: none; border-right: none; border-top: none; border-top-left-radius: 12px;
          max-width: 600px;
          margin:auto;
          color: white;
      }
      .card-log button,.modal-body button{ min-width: 180px; border-top-left-radius: 15px; }
      .card-log button:hover,.modal-body button:hover{
          background: transparent; color: white;
          box-shadow: 0px 0px 5px white;
      }
      .input-transparente::-webkit-input-placeholder   { color: #bbb; }
      .input-transparente:-moz-placeholder             { color: #bbb; }
      .input-transparente::-moz-placeholder            { color: #bbb; }
      .input-transparente:-ms-input-placeholder        { color: #bbb; }
      #nao-log{ cursor: pointer; border-radius: 5px; }
      #nao-log:hover{ text-shadow: 0px 0px 5px black; }
      .card-title a{ color: #040F16; }
      .modal-light{
          background: transparent;
          border: .5px solid rgba(250,250,250,.3);
          box-shadow: 0px 0px 20px black;
      }
      .modal-light .modal-title{ color: white; font-weight: 500; }
      .cursor{ cursor: pointer; }
      #divDadosGeraisCard{ min-width: 34rem; }
      @media(max-width: 700px){
        #divDadosGeraisCard{ min-width: 85%; }        
      }
      @media(max-width: 992px){
        #aMatthNavigate{
          display: none;
        }
      }
  </style>
  <script src="jquery/jquery.js"></script>
  <script>
      var msg = [
         /*0*/"<b>Poxa, infelizmente não há divulgação de novos projetos!</b> Mas continue acompanhando para não perder nada.",
         /*1*/"<b class='text-success'>Usuário Cadastrado com Sucesso!</b><br/>Efetue o login para acessar sua conta",
         /*2*/"<b class='text-danger'>Erro de Cadastramento!</b>",
         /*3*/"<b class='text-danger'>Email não encontrado!</b>",
         /*4*/"<b class='text-danger'>Senha Incorreta!</b>",
         /*5*/"<b class='text-info'>Faça o login para poder acessar o Site</b>",
         /*6*/"<div class='text-warning bg-dark p-2 px-4 my-2 rounded font-weight-bold'>Em Desenvolvimento</div>",
         /*7*/"<b class='border-bottom'>Status Pendente:</b><br/><div class='text-left'><b>1.</b> Caso você já tenha uma conta no site basta clicar no link e fazer o login para vinculá-lo.<br/><b>2.</b> Caso você <b class='text-danger'>não</b> tenha, clique no link e cadastra-se no site.</div>"
      ];
      function reload(){ window.location.reload(); }
      function fmsg(p){ 
         $('#modalMsg .modal-body div').html(msg[p]);
         $('#modalMsg').modal('show');
      }
      function logar(p){
          if(p){
              $('.container-logado').show('slow');              
              $('.container-log').hide('slow');
              $('#navbarResponsive ul li a[value=1]').html('Home');
          }
          else{
              $('.container-log').show('slow');              
              $('.container-logado').hide('slow');
          }
      }
      function logandoForm(p){
          switch(p){
              case 0: if($('#email').val()&&$('#senha').val()) $('#btnCConectar').click(); break;
              case 1: $('#formLog').attr("action","back/log.php?conectar=0"); $('#formLog').submit(); break;
              case 2: $('#formLog').attr("action","back/log.php?conectar=1"); $('#formLog').submit(); break;
          }
      }
      function toggleListHorizontal(elem,div){
          $('.list-group-horizontal li').removeClass('active');
          elem.addClass('active');
          if(div!='#divSitesVinculados') $('#divSitesVinculados').hide();
          if(div!='#divDadosGerais')     $('#divDadosGerais').hide();
          if(div!='#divPerfil')          $('#divPerfil').hide();
          if(div!='#divNotifica')        $('#divNotifica').hide();
          $(div).show('slow');
      }
      $(function(){
          v = <?php 
            if(isset($_GET['contato'])) echo 4;
            else echo isset($_GET['page'])?$_GET['page']:1;   
          ?>;
          $('.nav-item').removeClass('active');
          $('.nav-item:nth-child('+v+')').addClass('active'); v++;
          $('section:nth-child('+v+')').show('slow');
          <?php if(isset($_GET['msg'])){ echo " fmsg(".$_GET['msg']."); "; } ?>
          <?php if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){ echo " logar(true); "; } ?>
      });
  </script>
</head>
<body class="m-0 p-0"><main class="pt-1 pb-5">
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-bottom">
    <div class="container">
      <a class="navbar-brand" href="index.php" onclick="reload()">Matthew's World</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index.php" value="1">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=2" value="2">Sites</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=3" value="3">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=4" value="4">Entrar em Contato</a>
          </li>
          <?php if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){ ?>
          <li class="navbar-toggler border-0 p-0">
            <a class="nav-link" href="#" value="5" onclick="$('#matthNavigate').modal('show');">
              <span class="material-icons align-middle">ac_unit</span>
            </a>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <?php if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){ ?>
      <a class="text-light" style="opacity: .9" id="aMatthNavigate" onclick="$('#matthNavigate').modal('show');" href="#">
        <span class="material-icons align-middle px-1">ac_unit</span>
      </a>
    <?php } ?>
  </nav>
  <!-- Page Content | Matthews World -->
  <section>
    <!--Logar-->
    <div class="container container-log">
      <!--Header-->
      <div class="row">
        <div class="col-lg-6">
          <h1 class="mt-5 text-white">Matthew's World</h1>
          <p style="color: #bbb;">Bem vindo ao Matthew's World! Neste site está maior parte das minhas produções artísticas e intelectuais; como uma plataforma de ensino musical, um sistema de gerenciamento de finanças pessoais, um site de organização literária e um reprodutor de música com minhas músicas autorais, além de outros desenvolvimentos meus e link para outras obras.</p>
        </div>
      </div>
      <!-- Login -->
      <div class="card card-log mt-3">
          <form method="POST" id="formLog">
              <label for="email" class="h4 font-n">Login</label>
              <div class="form-group">
                <input type="email" class="form-control input-transparente" id="email" name="email" placeholder="Digite o E-mail..." required autofocus>
              </div>
              <div class="form-group">
                <input type="password" class="form-control input-transparente" id="senha" name="senha" placeholder="Digite a Senha..." required>
              </div>
              <div class="form-group">
                  <button type="button" class="btn btn-outline-light" id="btnCadastrar" name="btnCadastrar" data-toggle="modal" data-target="#modalCadastrar">Cadastrar</button>
                  <button type="button" class="btn btn-outline-light" id="btnLogar" name="btnLogar" onclick="logandoForm(0)">Entrar</button>
              </div>
              <!--MODAL MANTER CONECTADO-->
              <button type="button" class="d-none" id="btnCConectar" name="btnCConectar" data-target="#modalConectar" data-toggle="modal"></button>
              <div class="modal fade" tabindex="-1" role="dialog" id="modalConectar" aria-labelledby="#">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title text-dark">Matthews World <i class="material-icons align-calendar">speaker_notes</i></h5>
                          </div>
                          <div class="modal-body pt-2">
                              <div class="d-block text-center p-2 mb-2 text-muted" id="modalMsgBody">Manter-se Conectado?</div>
                              <div class="btn-group" role="group">
                                <button type="button" class="btn btn-dark active text-light btn-sm font-weight-bold" onclick="logandoForm(1);" style="min-width: 140px;">Não</button>
                                <button type="button" class="btn btn-danger active text-light btn-sm font-weight-bold" onclick="logandoForm(2);" style="min-width: 140px;">Sim</button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
      </div>
    </div>
    <!--Logado-->
    <div class="container container-logado" style="display: none;">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="mt-5 text-white"><?php echo $_SESSION['user_mtworld_nome']; ?></h1>
          <!--NavOptions-->
          <ul class="list-group list-group-horizontal">
              <li class="list-group-item cursor active py-1" title="Sites Vinculados" onclick="toggleListHorizontal($(this),'#divSitesVinculados');">
                  <span class="material-icons align-middle">usb</span>
              </li>
              <li class="list-group-item cursor py-1" title="Dados Gerais" onclick="toggleListHorizontal($(this),'#divDadosGerais');">
                  <span class="material-icons align-middle">all_inclusive</span>
              </li>
              <li class="list-group-item cursor py-1" title="Perfil" onclick="toggleListHorizontal($(this),'#divPerfil');">
                  <span class="material-icons align-middle">grading</span>
              </li>
              <li class="list-group-item cursor py-1" title="Perfil" onclick="toggleListHorizontal($(this),'#divNotifica');">
                  <span class="material-icons align-middle">model_training</span>
              </li>
              <li class="list-group-item cursor py-1" title="Perfil">
                <a href="back/log.php?logoff" class="text-danger active">
                  <span class="material-icons align-middle">power_settings_new</span>
                </a>
              </li>
          </ul>
          <!--Vinculados-->
          <div class="row mt-4" id="divSitesVinculados">
              <div class="col-lg-6">
                <?php if(isset($_GET['vinculo'])){ if($_GET['vinculo']==1){ ?>
                <!-- Alert - Sites Vinculados com Sucesso -->
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sites Vinculados!</strong> Os sites com Status 'Pendente' necessitam do primeiro acesso para preencher as informações finais. <span class="material-icons" style="vertical-align: -7px; cursor:pointer" title="As contas são vinculadas através do e-mail, por isso todas as contas relacionadas devem estar com o mesmo email cadastrado." onclick="fmsg(7);">help_outline</span>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <?php } else { ?>
                <!-- Alert - Erro ao Vincular -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Erro ao Vincular-se aos Sites!</strong> Tente mais tarde ou entre em contato comigo através do WhatsMatth para solucionar este problema.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <?php }} ?>
                <div class="table-responsive">
                  <table class="table">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">Sites Vinculado</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-light">
                        <?php
                            $data = enviarComand("select ss.id,us.status,ss.nome,ss.link from user_sites us inner join sites ss on us.sites_id=ss.id where usuario_id='{$_SESSION['user_mtworld']}';","bd_mtworld");
                            $entrou = false;
                            $bloqSites = array();
                            while($result = $data->fetch_assoc()){ $entrou = true; $bloqSites[] = $result['id'];
                        ?>
                        <tr>
                            <td><a href="<?php echo $result['link'].($result['status']=='pendente'?'index.php?mtworld':''); ?>" class="text-light link-navigate"><?php echo $result['nome']; ?></a></td>
                            <td><?php echo ucfirst($result['status']); ?></td>
                        </tr>
                        <?php } if(!$entrou) { ?>
                        <tr>
                            <td class="text-center" colspan="2">Você não está vinculado a nenhum Site!</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <td colspan="2">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalSolicita">Solicitar Acesso!</button>
                            </td>
                        </tr>
                    </tfooter>
                  </table>
              </div>
              </div>
          </div>
          <!--Dados Gerais-->
          <div class="row mt-2" id="divDadosGerais" style="display: none;">
            <div class="card m-3 p-2 bg-dark" id="divDadosGeraisCard">
              <h3 class="text-light">Dados Gerais</h3>
              <?php
                $data = enviarComand("select ss.id,us.status,ss.nome,ss.link from user_sites us inner join sites ss on us.sites_id=ss.id where usuario_id='{$_SESSION['user_mtworld']}';","bd_mtworld");
                $entrou = false;
                while($res = $data->fetch_assoc()){ $entrou = true;
              ?>
              <div class="card mb-2">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $res['nome']; ?></h5><!--Nome do Site-->
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $_SESSION['user_mtworld_email']; ?></h6>
                    <!--
                     Os usuário serão vinculados com o site através do login, então o email que mostrará encima será o de login, que não é necessariamente o mesmo do login matthews world, e a ideia é que o usuário possa ter mais de uma conta no mesmo site, como no ATM podendo ter um login diferente para cada aula especifica.  
                    -->
                    <p class="card-text p-2 rounded border text-center text-muted bg-dark">
                     <em class="text-warning">Em Desenvolvimento.</em><br/>
                     <span>
                        Está area mostrará detalhes da sua aplicação com os status e notificações principais.
                     </span>
                    </p>
                    <!--
                       Dados do Site, com status importantes do usuário. Lembrar de não colocar mais target=_blank e de colocar um caminho de retorno em todos os sites, uma opção é colocar um dropdown no botão sair para o usuário escolher se fará o logoff ou se retornará para MatthewsWorld. Terá um card para cada site sendo como acorddion
                    -->
                    <!--
                       Em Desenvolvimento 
                       <a href="#" class="card-link">Acessar</a>
                       <a href="#" class="card-link">Ações aqui mesmo</a> 
                    -->
                  </div>
              </div>
              <?php } if(!$entrou) { ?>
              <p class="text-muted">Não há sites vinculados</p>
              <?php } ?>
            </div>
          </div>
          <!--Perfil-->
          <div class="row mt-4 mx-2" id="divPerfil" style="display: none;">
            <ul class="list-group list-group-flush">
              <li class="list-group-item text-center py-1 font-weight-bold">Perfil</li>
              <li class="list-group-item">
                <?php echo $_SESSION['user_mtworld_email']; ?>
                <!-- 'Abrirá um modal para alterar o email do usuário' -->
                <span class="material-icons align-middle ml-2 rounded text-primary border cursor" onclick="fmsg(6);">edit</span>
              </li>
              <li class="list-group-item">
                <!-- alert('Abrirá um modal para alterar o nome do usuário') -->
                <button type="button" class="btn btn-sm btn-dark btn-block mx-auto" onclick="fmsg(6);">Alterar Nome</button>
              </li>
              <li class="list-group-item">
                <!-- 'Abrirá um modal para alterar a senha do usuário' -->
                <button type="button" class="btn btn-sm btn-danger btn-block mx-auto" onclick="fmsg(6);">Alterar Senha</button>
              </li>
              <li class="list-group-item text-center py-1 font-weight-lighter">
                Sites Vinculado: <span class="badge badge-success">
                <?php
                  $data = enviarComand("select count(*) total from user_sites us inner join sites ss on us.sites_id=ss.id where usuario_id='{$_SESSION['user_mtworld']}';","bd_mtworld");
                  if($res = $data->fetch_assoc()) echo $res['total']; ?>
                </span>
              </li>
            </ul>
          </div>
          <!--Notifica-->
          <div class="row mt-4 mx-2" id="divNotifica" style="display: none;">
            <ul class="list-group list-group-flush">
              <li class="list-group-item text-center py-1 font-weight-bold">Notificações</li>
              <?php
                $arr = [];
                foreach($arr as $val){ echo "<li class='list-group-item'>$val</li>"; }
                if(count($arr)==0) echo "<li class='list-group-item text-center font-weight-lighter'>Não há nenhuma notificação</li>";
              ?>
              <li class="list-group-item text-center py-1 font-weight-lighter">--</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Page Content | Sites -->
  <section>
    <div class="container"> 
      <!-- Page Heading -->
      <h1 class="mt-5 mb-4 text-white">Sites <small>(Plataformas e Gerenciadores)</small></h1>
      <div class="row">
        <?php 
            $sql = "select * from sites;";
            $res = enviarComand($sql,'bd_mtworld');
            while($linha = $res->fetch_assoc()){ $ir = false;
                if(isset($_SESSION['user_mtworld'])){ if(in_array($linha['id'],$bloqSites)) $ir=true; }
                else{ if(!$linha['oculto']) $ir=true; }
                if($ir){
        ?>
        <div class="col-lg-4 col-sm-6 mb-4">
          <div class="card h-100">
            <a href="#"><img class="card-img-top" src="img/<?php echo $linha['logo']; ?>" alt=""></a>
            <div class="card-body">
              <i class="material-icons float-right align-bottom" title="<?php echo isset($_SESSION['user_mtworld'])?'lock_open':($linha['publico']?'Site Público':'Faça o login para acessar'); ?>"> <?php echo isset($_SESSION['user_mtworld'])?'lock_open':($linha['publico']?'lock_open':'lock'); ?> </i>
              <h4 class="card-title">
                <a href="<?php echo isset($_SESSION['user_mtworld'])?$linha['link']:($linha['publico']?$linha['link']:"index.php"); ?>" class="link-navigate"><?php echo $linha['nome']; ?></a>
              </h4>
              <p class="card-text"><?php echo $linha['descricao']; ?></p>
              <?php if($linha['publico'] || isset($_SESSION['user_mtworld'])) { ?>
              <a href="<?php echo $linha['link']; ?>" class="link-navigate"><?php echo $linha['link']; ?></a>
              <?php } else{ ?>
                  <footer class="blockquote-footer"><cite title="Clique em Entrar">Faça o Login</cite></footer>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php }} ?>
        <!-- + -->
        <div class="col-lg-4 col-sm-6 mb-4">
          <div class="card h-100">
            <a href="#"><img class="card-img-top pt-5 pb-2 border" src="img/logo-futuros.png" alt=""></a>
            <div class="card-body">
              <h4 class="card-title">
                <a href="#" onclick="fmsg(0)">Novos Projetos</a>
              </h4>
              <p class="card-text">Outros Projetos ainda estão em desenvolvimento e estarão disponíveis aqui embreve. Acompanhe o site para não perder as novidades!</p>
              <footer class="blockquote-footer"><cite title="Desenvolvido Por...">Att. Mateus Brandão</cite></footer>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Page Content | Sobre -->
  <section>
    <div class="container">
      <div class="row">
        <div class="col-lg-6 text-muted">
          <h1 class="mt-5 text-white">Sobre</h1>
          <h6 class="text-white">O SITE</h6>
          <p style="color: #bbb;">O Matthew's World é uma página de redirecionamento onde se encontram os sites e plataformas desenvolvidas por mim. Sendo cadastrado no Matthew's World você consegue ter um cadastro rápido em todos meus outros sites, tendo a mesma senha e usuário.</p>
          <h6 class="text-white">O DESENVOLVEDOR</h6>
          <p style="color: #bbb;">Meu nome é <em>Mateus Brandão</em>, tenho <?php echo (date('y'))+1; ?> anos, e sou escritor independente do Livro Transcendente, além de desenvolvedor de todas as aplicações deste site. Sou professor de teoria musical, criador de todo o conteúdo do <em>ATM</em> e componho musicas esporadicamente.
          </p>
        
        <div class="card mb-3" style="max-width: 400px;">
         <img src="img/livro-transcendente.png" class="card-img-top" alt="Livro Transcendente">
         <div class="card-body text-dark">
            <h5 class="card-title"><b>Livro:</b> Transcendente</h5>
            <div class="card-text mb-2">
               <b>Sinopse: </b>O livro trata de uma versão da realidade em que os homens já não eram mais capazes de concertar os seus próprios erros, e as consequências destes erros estavam prestes a levá-los ao fim. Para impedir isso eles precisavam de pessoas que transcendessem suas forças, seus limites; pessoas que transcendessem o impossível.<br/>
               <button
                  type="button"
                  class="btn btn-sm btn-block btn-outline-secondary my-2"
                  onclick="$(this).next().toggle('slow'); if($(this).html()=='+') $(this).html('-'); else $(this).html('+');">+</button>
               <span style="display:none">Depois de muita busca, conseguiram criar pessoas geneticamente alteradas que conseguiam superar incrivelmente as seis habilidades humanas, sendo elas: força, agilidade, flexibilidade, resistência, velocidade e inteligência. Elas sim eram capazes de concertar os erros da raça humana, mas será que a raça humana poderia conviver com uma raça superior a sua?<br/>Após ao trágico Massacre do Oriente, causado pelos Transcendentes, os humanos(não alterados) se voltaram completamente a caçá-los, e é nessa realidade que nasce Larissa Roth, descendente da raça geneticamente alterada, que ao atingir seus 17 anos seu mundo começou a desmoronar de uma forma cada vez mais crescente e devastadora. Será que todos, até mesmo seus descendentes, deveriam pagar por um erro cometido por seus pais? Será que duas raças racionais poderiam coexistir numa balança que pende para um dos lados?</span>
            </div>
            <div class="btn-group btn-block mt-2">
               <a class="btn btn-warning font-weight-bold" target="_blank" href="https://www.amazon.com/-/pt/dp/1090981813/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&dchild=1&keywords=mateus+brand%C3%A3o+transcendente&qid=1603162238&sr=8-1">
                  Livro Físico
               </a>
               <a class="btn btn-info font-weight-bold" target="_blank" href="https://www.amazon.com.br/Transcendente-Mateus-Brand%C3%A3o-ebook/dp/B07NHNQSJV/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&dchild=1&keywords=mateus+brand%C3%A3o+transcendente&qid=1603163107&sr=8-1">
                  E-Book Kindle
               </a>
            </div>
            <p class="card-text"><small class="text-muted">Escrito por Mateus Brandão.</small></p>
         </div>
        </div>
       </div>
      </div>
    </div>
  </section>
  <!-- Page Content | Entre em Contato -->
  <section>
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h1 class="mt-5 text-white">Entre em Contato</h1>
          <p style="color: #bbb;">Embreve vocês poderão entrar em contato comigo através do WhatsMatth, onde poderão mandar suas dúvidas ou registrar algum bug que eu te responderei aqui mesmo.</p>
          <p style="color: #bbb;">Caso queira ter seu próprio site, ou precise que eu desenvolva algo clique no link abaixo para me contatar e me conte suas ideias para podermos colocar tudo isso em prática.</p>
          <a class="btn btn-danger font-weight-bold px-5 active" href="mailto:mateusfleria@gmail.com?subject=Matthews%20World%20(Assunto)" target="_blank">Gmail</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Modais -->
  <!-- Modal Cadastrar -->
  <div class="modal fade" tabindex="-1" role="dialog" id="modalCadastrar" aria-labelledby="mcadNome">
      <div class="modal-dialog" role="document">
          <div class="modal-content modal-light">
              <div class="modal-header">
                  <div class="modal-title">CADASTRAR</div>
                  <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body text-light">
                <form method="POST" action="back/cadastro.php">
                    <p class="small border border-light p-2 rounded" style="background: rgba(250,250,250,.25)">Você pode usar o cadastro no Matthews World para ter acesso aos outros sites sem precisar preencher novamente todos os dados e para administrar de forma geral esses sites.</p>
                    <!--Nome-->
                    <div class="form-group">
                        <input type="text" class="form-control input-transparente" id="mcadNome" name="mcadNome" placeholder="Digite o Nome..." required>
                    </div>
                    <!--Email-->
                    <div class="form-group">
                        <input type="email" class="form-control input-transparente" id="mcadEmail" name="mcadEmail" placeholder="Digite o Email..." required>
                    </div>
                    <!--Senha-->
                    <div class="form-group">
                        <input type="password" class="form-control input-transparente" id="mcadSenha" name="mcadSenha" placeholder="Digite a Senha..." required>
                    </div>
                    <!--Confirmar Senha-->
                    <div class="form-group">
                        <input type="password" class="form-control input-transparente" id="mcadConfSenha" name="mcadConfSenha" placeholder="Confirme a Senha..." required>
                    </div>
                    <!--Finalizar-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger btn-block" id="btnFinalizar" name="btnFinalizar">
                            Finalizar
                        </button>
                    </div>
                </form>
              </div>
          </div>
      </div>
  </div>
  <!-- Modal Solicitar Acesso -->
  <div class="modal fade" tabindex="-1" role="dialog" id="modalSolicita" aria-labelledby="site1">
      <div class="modal-dialog" role="document">
          <div class="modal-content modal-light">
              <div class="modal-header">
                  <div class="modal-title">Solicitar Acesso</div>
                  <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body text-light">
                <form method="POST" action="back/vinculo.php">
                    <p class="small border border-light p-2 rounded text-center" style="background: rgba(250,250,250,.25)">Selecione os sites que você deseja vincular à sua àrea no MatthewsWorld.</p>
                    <?php
                        $sql="select * from sites where oculto = false;";
                        $datas = enviarComand($sql,'bd_mtworld');
                        $cont=0;
                        while($linhas = $datas->fetch_assoc()){ if(!in_array($linhas['id'],$bloqSites)){ $cont++;
                    ?>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <div class="input-group-text bg-dark">
                          <input type="checkbox" id="site<?php echo $cont; ?>" name="site<?php echo $cont; ?>" value="<?php echo $linhas['id']; ?>">
                        </div>
                      </div>
                      <input type="text" class="form-control" value="<?php echo $linhas['nome']; ?>" readonly>
                    </div>
                    <?php }} if($cont==0){ ?>
                    <p class="small border bg-light text-success p-2 rounded text-center">Todos os sites já foram vinculado</p>
                    <?php } ?>
                    <!--Finalizar-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger btn-block"> Vincular </button>
                    </div>
                </form>
              </div>
          </div>
      </div>
  </div>
  <!-- Modal Msg -->
  <div class="modal fade" tabindex="-1" role="dialog" id="modalMsg">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-light">
        <div class="modal-header">
          <div class="modal-title">Matthew's World</div>
          <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center rounded p-2" style="background: #eee; opacity: .85"></div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap core JavaScript -->
  <script src="jquery/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  </main>
  <?php include('function/global.php'); ?>
</body>
</html>