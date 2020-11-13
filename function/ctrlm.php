<!-- 
    É NECESSÁRIO QUE EM ALGUMA PARTE DO CÓDIGO CONN/FUNCTION.PHP TENHA SIDO IMPORTADO.
 -->
<?php
    $retornos = "";
    $locPath = getcwd();
    if(!inweb) $locPath = str_replace('\\','/',$locPath);
    $locPath = substr($locPath,strpos($locPath,'matthewsworld.me'));
    if(strlen($locPath)>16){
        $locPath = substr($locPath,16);
        $qtdRetornos = substr_count($locPath,"/");
        if($qtdRetornos>0){
            $retornos = str_repeat('../',$qtdRetornos);
        }
    }
?>
<link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
<script>
    var online = false;
    $(function(){
        online =  document.URL.indexOf('localhost/')>-1?false:true;
        $('#comand').on('keypress',function(e){ if(e.which == 13) detect(); });
    });
        
    var pressedCtrl = false;

    $(document).on('keydown',function(e) { if(e.which == 17) pressedCtrl = true; });
    $(document).on('keyup',function(e) { if(e.which == 17) pressedCtrl = false; });
    $(document).on('keyup',function(e) { if(e.which == 77 && pressedCtrl) {  
        $('#divComand .subDiv').show('slow');
        if($('#divComand .subDiv .badge').html()==""){ 
            $('#divComand .subDiv .badge')
                .addClass(online?'badge-danger':'badge-light')
                .html(online?'online':'host');
        }
        $('#comand').focus();
        pressedCtrl = false; 
    }});
    function detect(){
        arrf = {
            'list' : 'Listar as opções',
            'local' : 'Intercalar Host/Online',
            'nav' : 'Chamar Matth Navigate'
        };
        switch($('#comand').val()){
            case 'list': console.log(arrf); break;
            case 'local': migrateHost(); break;
            case 'nav':
                $('#matthNavigate').modal('show');
                // console.log($('#matthNavigate'));
                break;
            default: alert('Não há função programada'); break;
        }
        $('#divComand .subDiv').hide('slow');
        $('#comand').val('');
    }
    function migrateHost(){
        addressComplete = document.URL;
        if(addressComplete.indexOf('localhost/')>-1){
            addressNew = addressComplete.split('localhost/');
            console.log(addressNew);
            window.location.href = addressNew[0]+'www.'+addressNew[1];
        }else{
            if(addressComplete.indexOf('www.')>-1)
                addressNew = addressComplete.split('www.');
            else addressNew = addressComplete.split('//');
            window.location.href = 'http://localhost/'+addressNew[1];
        }
    }
</script>

<!-- Ctrl M -->
<div class="p-1 fixed-top d-flex justify-content-center text-monospace" id="divComand">
    <div 
        class="bg-dark border border-secondary shadow-lg p-3 pb-0 mb-0 rounded subDiv"
        style="width: 38.3rem;right: auto;left: auto; display: none;background-image: url('<?php echo $retornos; ?>img/preto.jpg'); background-size: contain;"
    >
        <label
            class="text-center d-block text-light"
            style="font-family: 'Russo One', sans-serif;"
            for="comand"
        >
            Ctrl M { <span class="badge"></span> }
        </label>
        <input
            class="form-control bg-dark"
            type="text"
            id="comand"
            placeholder="Comand..."
            style="color: #e4f0ff;"
        />
        <button class="btn btn-block btn-sm btn-dark active mt-1" onclick="detect()">{ }</button>
        <button 
            class="btn btn-block btn-sm btn-outline-dark mt-1"
            onclick="$('#divComand .subDiv').hide('slow');"
        >Abort</button>
    </div>
</div>