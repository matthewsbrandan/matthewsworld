drop database if exists bd_lworld;
create database bd_lworld;
use bd_lworld;
	create table tbluser
    (
		user_id int(6) not null auto_increment primary key,
        user_nome varchar(30) not null,
        user_email varchar(30) not null,
        user_senha varchar(100) not null
    );
	create table tbleditora
    (
		editora_id int(6) not null auto_increment primary key,
        editora_nome varchar(30) not null,
        editora_qtdEscritores int(6) default 0,
        editora_qtdLivros int(6) default 0,
        editora_user_id int(6) not null,
        foreign key (editora_user_id) references tbluser(user_id)
    );
    create table tblescritor
    (
		escritor_id int(6) not null auto_increment primary key,
        escritor_nome varchar(30) not null,
        escritor_qtdLivros int(6) default 0,
        escritor_qtdSagas int(6) default 0,
        escritor_editora_id int(6) not null,
        escritor_user_id int(6) not null,
        foreign key (escritor_editora_id) references tbleditora(editora_id),
        foreign key (escritor_user_id) references tbluser(user_id)
    );
    create table tblsaga
    (
		saga_id int(6) not null auto_increment primary key,
        saga_nome varchar(50) not null,
        saga_qtdLivros int(6) default 0,
        saga_escritor_id int(6) not null,
        saga_user_id int(6) not null,
        foreign key (saga_escritor_id) references tblescritor(escritor_id),
        foreign key (saga_user_id) references tbluser(user_id)
    );
    create table tbllivro
    (
		livro_id int(6) not null auto_increment primary key,
        livro_nome varchar(50) not null,        
        livro_qtdPag int(6) not null,
        livro_pagAtual int(6) default 0,
        livro_status enum('Lendo','Em Espera','Lido'),
        livro_comprado boolean default false,
        livro_img varchar(50) default 'padrao.jpg',
        livro_saga_id int(6) not null,
        livro_escritor_id int(6) not null,
        livro_editora_id int(6) not null,
        livro_user_id int(6) not null,
        foreign key (livro_saga_id) references tblsaga(saga_id),
        foreign key (livro_escritor_id) references tblescritor(escritor_id),
        foreign key (livro_editora_id) references tbleditora(editora_id),
        foreign key (livro_user_id) references tbluser(user_id)
    );
    
delimiter $$
    -- Procedures
    create procedure newEditora(in pnome varchar(30),in puser int(6))
    begin
		if((select count(*) from tbleditora where editora_nome=pnome and editora_user_id=puser)>0)
        then select 'Já existe uma editora cadastrada com este nome' erro;
		else insert into tbleditora(editora_nome,editora_user_id) values (pnome,puser);
        end if;
    end $$
    
    create procedure newEscritor(in pnome varchar(30),in peditora int(6),in puser int(6))
    begin
		if((select count(*) from tblescritor where escritor_nome=pnome and escritor_user_id=puser)>0)
        then select 'Já existe um escritor cadastrado com este nome' erro;
		else insert into tblescritor(escritor_nome,escritor_editora_id,escritor_user_id) values (pnome,peditora,puser);
        end if;
    end $$
    
    create procedure newSaga(in pnome varchar(50),in pescritor int(6),in puser int(6))
    begin
		if((select count(*) from tblsaga where saga_nome=pnome and saga_user_id=puser)>0)
        then
			select 'Já existe uma saga cadastrada com este nome' erro;
		else
			insert into tblsaga(saga_nome,saga_escritor_id,saga_user_id) values (pnome,pescritor,puser);
        end if;
    end $$
    
    create procedure newLivro(in pnome varchar(50),in pqtdC int(6),in pcap int(6),in pqtd int(6),in ppag int(6),in pstatus enum('Lendo','Em Espera','Lido'),in pcomprado boolean,in psaga int(6),in pescritor int(6),in peditora int(6),in puser int(6))
    begin
		if((select count(*) from tbllivro where livro_nome=pnome and livro_escritor_id=pescritor and livro_user_id=puser)>0)
        then
			select 'Já existe um livro cadastrado com este nome' erro;
		else
			insert into tbllivro(livro_nome,livro_qtdCap,livro_capAtual,livro_qtdPag,livro_pagAtual,livro_status,livro_comprado,livro_saga_id,livro_escritor_id,livro_editora_id,livro_user_id) values (pnome,pqtdC,pcap,pqtd,ppag,pstatus,pcomprado,psaga,pescritor,peditora,puser);
        end if;
    end $$
    
    create procedure statusLiterario(in puser int(6))
    begin
		declare l1 int(6)  default (select count(*) from tbllivro where livro_user_id=puser and livro_status='Lido');
        declare l2 int(6)  default (select count(*) from tbllivro where livro_user_id=puser);
        declare l3 int(10) default (select sum(livro_capAtual) from tbllivro where livro_user_id=puser);
        declare l4 int(10) default (select sum(livro_qtdCap) from tbllivro where livro_user_id=puser);
        declare l5 int(10) default (select sum(livro_pagAtual) from tbllivro where livro_user_id=puser);
        declare l6 int(10) default (select sum(livro_qtdPag) from tbllivro where livro_user_id=puser);
        declare l7 int(6)  default (select count(*) from tbllivro where livro_user_id=puser and livro_comprado=true);
        declare l8 int(6)  default (select count(*) from tbllivro where livro_user_id=puser);
        
		select concat(l1,' de ',l2) Livros_Lidos,
               concat(l3,' de ',l4) Capitulos_Lidos,
               concat(l5,' de ',l6) Paginas_Lidas,
               concat(l7,' de ',l8) Livros_Comprados;
    end $$
    
    create procedure updateImg(in pid int(6),in pimg varchar(50))
    begin
		update tbllivro set livro_img=pimg where livro_id=pid;
    end $$
    
    -- Triggers
    create trigger tgr_qtdEscritores before insert on tblescritor for each row
    begin
		update tbleditora set editora_qtdEscritores = editora_qtdEscritores + 1 where editora_id = new.escritor_editora_id and editora_user_id = new.escritor_user_id;
    end $$
    
    create trigger tgr_qtdSagas before insert on tblsaga for each row
    begin
		update tblescritor set escritor_qtdSagas = escritor_qtdSagas + 1 where escritor_id = new.saga_escritor_id and escritor_user_id = new.saga_user_id;
    end $$
    
    create trigger tgr_qtdLivros before insert on tbllivro for each row
    begin
		update tbleditora set editora_qtdLivros = editora_qtdLivros + 1 where editora_id = new.livro_editora_id and editora_user_id = new.livro_user_id;
        update tblescritor set escritor_qtdLivros = escritor_qtdLivros + 1 where escritor_id = new.livro_escritor_id and escritor_user_id = new.livro_user_id;
        update tblsaga set saga_qtdLivros = saga_qtdLivros + 1 where saga_id = new.livro_saga_id and saga_user_id = new.livro_user_id;
        
    end $$
    -- Views  drop view detalheLivros $$
    create view detalheLivros as
		select livro_id,livro_nome Livro,saga_nome Saga,escritor_nome Escritor,editora_nome Editora, 
        concat(livro_capAtual,' de ',if(livro_qtdCap=0,'N',livro_qtdCap)) Capítulos_Lidos,
        concat(livro_pagAtual,' de ',if(livro_qtdPag=0,'N',livro_qtdPag)) Paginas_Lidas,livro_status Status,
        if(livro_comprado=true,'Sim','Não') Comprado,livro_img Img,livro_user_id Usuario from tbllivro li 
        inner join tblsaga sa on li.livro_saga_id=sa.saga_id 
        inner join tblescritor es on li.livro_escritor_id=es.escritor_id
        inner join tbleditora ed on li.livro_editora_id=ed.editora_id order by saga_nome $$
delimiter ;
    -- Usuário
    insert into tbluser(user_nome,user_email,user_senha) values ('Mateus Brandão','mateufleria@gmail.com','arti');
    
    -- Nome da Editora e Id do Usuário
    call newEditora('Intrinseca',1);
    call newEditora('Rocco - Jovens Leitores',1);
    call newEditora('Seguinte',1);
    call newEditora('Arqueiro',1);
    call newEditora('WMF Martins Fontes',1);
    call newEditora('Quadrangular',1);
    call newEditora('Thomas Nelson',1);
    call newEditora('Novo Caminho',1);
    
    -- Nome do Escritor, Id da Editora e Id do Usuário
    call newEscritor('Rick Riordan',1,1);
    call newEscritor('Veronica Roth',2,1);
    call newEscritor('Victoria Aveyard',3,1);
    call newEscritor('Barney Stinson',1,1);
    call newEscritor('Jojo Moyes',1,1);
    call newEscritor('Colleen Houck',4,1);
    call newEscritor('C.S. Lewis',5,1);
    call newEscritor('Julio Rosa',6,1);
    call newEscritor('Renato & Cristiane',7,1);
    call newEscritor('Edino Melo',8,1);
    
    -- Nome da Saga, Id do Escritor e Id do Usuário
    call newSaga('Percy Jackson e os Olimpianos',1,1);
    call newSaga('Heróis do Olimpo',1,1);
    call newSaga('As Provações de Apolo',1,1);
    call newSaga('Especial do Rick Riordan',1,1);
    call newSaga('Divergente',2,1);
    call newSaga('Crave a Marca',2,1);
    call newSaga('Rainha Vermelha',3,1);
    call newSaga('Crônicas de Nárnia',7,1);
    call newSaga('Como eu era antes de Você',5,1);
    call newSaga('How I Met Your Mother',4,1);
    call newSaga('A maldição do Tigre',6,1);
    call newSaga('O Milênio',8,1);
    call newSaga('Namoro Blindado',9,1);
    call newSaga('100 dias',10,1);
    
    -- Nome do Livro, Quantidade de páginas, Página atual, Status('Lendo','Em Espera','Lido'), Se foi comprado ou não, Id da Saga, Id do Escritor, Id da Editora, Id do Usuário
#Rick Riordan
    #PJO
    call newLivro('1- O Ladrão de Raios',22,22,385,385,'Lido',true,1,1,1,1);
    call newLivro('2- O Mar de Monstros',20,20,286,286,'Lido',true,1,1,1,1);
    call newLivro('3- A Maldição do Titã',20,20,316,316,'Lido',true,1,1,1,1);
    call newLivro('4- A Batalha do Labirinto',20,20,367,367,'Lido',true,1,1,1,1);
    call newLivro('5- O Último Olimpiano',23,23,383,383,'Lido',true,1,1,1,1);
    
    #HDO
    call newLivro('1- O Herói Perdido',56,56,440,440,'Lido',false,2,1,1,1);
    call newLivro('2- O Filho de Netuno',52,52,432,432,'Lido',false,2,1,1,1);
    call newLivro('3- A Marca de Atena',52,52,480,480,'Lido',true,2,1,1,1);
    call newLivro('4- A Casa de Hades',78,78,496,496,'Lido',false,2,1,1,1);
    call newLivro('5- O Sangue do Olimpo',58,58,432,432,'Lido',false,2,1,1,1);
    
    #TOA
    call newLivro('1- O Oráculo Oculto',39,39,320,320,'Lido',true,3,1,1,1);
    call newLivro('2- A Profecia das Sombras',42,0,336,0,'Em Espera',false,3,1,1,1);
    call newLivro('3- O Labirinto de Fogo',47,0,368,0,'Em Espera',false,3,1,1,1);
    
    #ESPECIAIS
    call newLivro('1- Os Arquivos do Semideus',15,15,165,165,'Lido',true,4,1,1,1);
    call newLivro('2- Os Diários do Semideus',15,15,288,288,'Em Espera',false,4,1,1,1);

#Veronica Roth    
    #DIVERGENTE
    call newLivro('1- Divergente',0,0,0,0,'Lido',true,5,2,2,1);
    call newLivro('2- Insurgente',0,0,0,0,'Lido',true,5,2,2,1);
    call newLivro('3- Convergente',0,0,0,0,'Lido',true,5,2,2,1);
    call newLivro('Quatro (Extra)',0,0,0,0,'Lido',true,5,2,2,1);
    
    #CRAVE A MARCA
    call newLivro('1- Crave a Marca',0,0,0,0,'Lido',true,6,2,2,1);
    call newLivro('2- The Fates Divide',0,0,0,0,'Em Espera',false,6,2,2,1);
    
#Victória Aveyard
	#RAINHA VERMELHA
    call newLivro('1- Rainha Vermelha',0,0,0,0,'Lido',true,7,3,3,1);
    call newLivro('2- Espada de Vidro',0,0,0,0,'Lido',true,7,3,3,1);
    call newLivro('Coroa Cruel (Extra)',0,0,0,0,'Lido',true,7,3,3,1);
    call newLivro('3- Prisão do Rei',0,0,0,0,'Lido',true,7,3,3,1);
    call newLivro('4- Tempestade de Guerra',0,0,0,0,'Lendo',true,7,3,3,1);
    
#C.S. Lewis
	#CRÔNICAS DE NÁRNIA
    call newLivro('1- O Sobrinho do Mago',0,0,0,0,'Lido',true,8,7,5,1);
    call newLivro('2- O Leão, a Feiticeira e o Guarda-Roupas',0,0,0,0,'Lido',true,8,7,5,1);
    call newLivro('3- O Cavalo e o seu Menino',0,0,0,0,'Lido',true,8,7,5,1);
    call newLivro('4- O Principe Caspian',0,0,0,0,'Lido',true,8,7,5,1);
    call newLivro('5- A Viagem do Peregrino da Alvorada',0,0,0,0,'Lido',true,8,7,5,1);
    call newLivro('6- Cadeira de Prata',0,0,0,0,'Lido',true,8,7,5,1);
    call newLivro('7- A Última Batalha',0,0,0,0,'Lido',true,8,7,5,1);
    
#Barney Stinson
	#HIMYM
    call newLivro('O Código Bro',0,0,0,0,'Lido',true,10,4,1,1);
    call newLivro('Playbook',0,0,0,0,'Lido',true,10,4,1,1);
    
#Jojo Moyes
	#COMO EU ERA ANTES DE VOCÊ
    call newLivro('1- Como eu era antes de você',0,0,0,0,'Lido',false,9,5,1,1);
    call newLivro('2- Depois de você',0,0,0,0,'Em Espera',true,9,5,1,1);
    
#Colleen Houck
	#A MALDIÇÃO DO TIGRE
    call newLivro('1- A Maldição do Tigre',0,0,0,0,'Em Espera',false,11,6,4,1);
    call newLivro('2- O Resgate do Tigre',0,0,0,0,'Em Espera',true,11,6,4,1);
    
#Julio Rosa
	#O Milênio
    call newLivro('O Milênio',0,0,0,0,'Lido',true,12,8,6,1);
    -- Id da Saga, Id do Escritor, Id da Editora, Id do Usuário

#Renato & Cristiane
	#Namoro Blindado
    call newLivro('Namoro Blindado',0,0,0,0,'Lendo',true,13,9,7,1);
    
#Edino Melo
	#100 dias
    call newLivro('100 dias',0,0,0,0,'Lendo',true,14,10,8,1);
    
    -- Id do Usuário
    call statusLiterario('1'); -- Ver se os valores estão corretos
    -- Atualizar Imagens
    call updateImg('1','001ladrao-raios-pjo.jpeg');call updateImg('2','002mar-monstros-pjo.jpeg');call updateImg('3','003maldicao-tita-pjo.jpeg');
	call updateImg('4','004batalha-labirinto-pjo.jpeg');call updateImg('5','005olimpiano-pjo.jpeg');call updateImg('6','006heroi-perdido-hdo.jpeg');
	call updateImg('7','007filho-netuno-hdo.jpeg');call updateImg('8','008marca-atena-hdo.jpeg');call updateImg('9','009casa-hades-hdo.jpeg');
	call updateImg('10','010sangue-olimpo-hdo.jpeg');call updateImg('11','011oraculo-toa.jpeg');call updateImg('12','012profecia-toa.jpeg');
	call updateImg('13','013labirinto-toa.jpeg');call updateImg('14','014arquivos-semideus.jpg');call updateImg('15','015diario-semideus.jpg');
	call updateImg('16','016divergente-d.jpg');call updateImg('17','017insurgente-d.jpg');call updateImg('18','018convergente-d.jpg');
	call updateImg('19','019quatro-d.jpeg');call updateImg('20','020crave-marca-cm.jpg');call updateImg('21','021destino-dividido-cm.jpg');
	call updateImg('22','022rainha-vermelha-rv.jpg');call updateImg('23','023espada-vidro-rv.jpg');call updateImg('24','024coroa-cruel-rv.jpeg');
	call updateImg('25','025prisao-rei-rv.jpeg');call updateImg('26','026tempestade-rv.jpg');call updateImg('27','027cronicas-narnia.jpeg');
    call updateImg('28','028cronicas-narnia.jpeg');call updateImg('29','029cronicas-narnia.jpeg');call updateImg('30','030cronicas-narnia.jpeg');
	call updateImg('31','031cronicas-narnia.jpeg');call updateImg('32','032cronicas-narnia.jpeg');call updateImg('33','033cronicas-narnia.jpeg');
	call updateImg('34','034codigo-bro.jpeg');call updateImg('35','035playbook.jpg');call updateImg('36','036antes-de-voce-ceeav.jpg');
	call updateImg('37','037depois-de-voce-ceeav.jpeg');call updateImg('38','038maldicao-tigre.jpeg');call updateImg('39','039resgate-tigre.jpg');
	call updateImg('40','040milenio.jpg');call updateImg('41','041namoro.jpeg');call updateImg('42','042cem-dias.jpg');
    
    -- Select
    select * from detalheLivros order by livro_id desc;
    select * from tbluser;
    select * from tbleditora;
    select * from tblescritor;
    select * from tblsaga;
    select livro_qtdCap-livro_capAtual diferenca from tbllivro where livro_id=12;
    update tbllivro set livro_nome='3- O Cavalo e o seu Menino', livro_qtdCap='0', livro_capAtual='0', livro_qtdPag='0', livro_pagAtual='0', livro_status='Lido', livro_comprado='1', livro_img='027cronicas-narnia.jpeg', livro_saga_id='8', livro_escritor_id='7', livro_editora_id='5' where livro_id='29' and livro_user_id='1';
    
    select * from tbllivro;
    
    #Executar no Servidor
    alter table tbllivro modify column livro_img varchar(50) default 'padrao.jpg';
    drop procedure updateImg;
    delimiter $$
    create procedure updateImg(in pid int(6),in pimg varchar(50))
    begin
		update tbllivro set livro_img=pimg where livro_id=pid;
    end $$