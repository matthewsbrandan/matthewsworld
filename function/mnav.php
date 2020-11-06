<!-- 
    MatthNavigate  
    !!!INCLUIR CTRLM ANTES DESTE PARA QUE A VARIÁVEL online [js] e $retornos [php] funcionem
-->
<script>
    $(function(){
        if(!online){
            $('.link-navigate').each(function(){
                addressComplete = $(this).attr('href');
                if(addressComplete.indexOf('www.')>-1) addressNew = addressComplete.split('www.');
                else addressNew = addressComplete.split('//');
                $(this).attr('href','http://localhost/'+addressNew[1]);
            });
        }
    });
</script>
<style> .figcaption-navigate:hover{ box-shadow: inset 0 0 10px, 0 0 10px; } </style>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="matthNavigate">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body rounded" style="background-image: url('<?php echo $retornos; ?>img/branco.jpg')">
                <a  href="https://www.matthewsworld.life/"
                    class="modal-title text-decoration-none text-dark float-left link-navigate"
                    style="text-shadow: 0px 0px 10px rgba(0,0,0,.2);">
                    <span class="material-icons">home</span>
                </a>
                <a  class="modal-title text-decoration-none text-dark float-right" href="#" 
                    style="text-shadow: 0px 0px 10px rgba(0,0,0,.2);"
                    data-dismiss="modal">
                    <span class="material-icons">close</span>
                </a>
                <h5 class="modal-title text-center" style="text-shadow: 0px 0px 10px rgba(0,0,0,.4);">
                    Matth Navigate <span class="material-icons" style="font-size: 80%">ac_unit</span>
                </h5>
                <div class="my-2 d-flex justify-content-center flex-wrap">
                    <?php
                        $data = enviarComand("select s.logo,s.nome,s.id,s.link from sites s inner join user_sites us on s.id=us.sites_id where us.usuario_id={$_SESSION['user_mtworld']}",'bd_mtworld');
                        $entrou = false;
                        while($res = $data->fetch_assoc()){ $entrou = true;
                    ?>
                    <figure class="m-2 position-relative">
                        <img 
                            src="<?php echo $retornos.'img/'.$res['logo']; ?>" 
                            alt="<?php echo $res['nome']; ?>"
                            class="shadow-lg img-navigate"
                            style="width: 180px;height: 175px;object-fit: cover; border-radius: 50%">
                        <figcaption
                            class="bg-light text-center border rounded position-absolute w-100 my-2 figcaption-navigate"
                            style="opacity: .8; min-height: 30px; max-height: 150px;bottom:0; overflow: auto">
                            <a  href="#" 
                                class="text-decoration-none text-dark" 
                                onclick="$(this).siblings().toggle('slow');">
                            <?php echo $res['nome']; ?>
                            </a>
                            <div class="border-top bg-dark" style="display: none;">
                                <?php 
                                    $sql = 'select * from nav_sites where sites_id='.$res['id'];
                                    $dataList = enviarComand($sql,'bd_mtworld');
                                    while($resList = $dataList->fetch_assoc()){
                                    if($resList['home']) echo "<b>";
                                    echo "<a href='{$res['link']}{$resList['caminho']}' class='text-decoration-none text-light link-navigate'>{$resList['nome']}</a>";
                                    if($resList['home']) echo "</b>";
                                    echo "<br/>";
                                    }
                                ?>
                            </div>
                        </figcaption>
                    </figure>
                    <?php } if(!$entrou) { ?>
                    <div class="bg-dark rounded text-light text-center p-2 px-4">
                        Você não está vinculado a nenhum Site!
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>