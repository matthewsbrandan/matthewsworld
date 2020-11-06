drop database if exists bd_mtworld;
	create database bd_mtworld;
    use bd_mtworld;
    
    create table usuario(
		id int(6) not null primary key auto_increment,
        nome varchar(30) not null,
        email varchar(30) not null,
        senha varchar(100) not null
    );
    create table sites(
		id int(6) not null primary key auto_increment,
        nome varchar(50) not null,
        descricao varchar(200) not null,
        link varchar(50) not null,
        bd varchar(30) not null,
        logo varchar(30) not null,
        publico bool default true
    );
    create table user_sites(
		id int(6) not null primary key auto_increment,
        usuario_id int(6) not null,
        sites_id int(6) not null,
        status enum('ativo','desativado','pendente') default 'pendente',
        login varchar(30) null,
        senha varchar(100) null,
        foreign key (usuario_id) references usuario(id),
        foreign key (sites_id) references sites(id)
	);
    create table nav_sites(
		id int(6) not null primary key auto_increment,
        sites_id int(6) not null,
        nome varchar(50) not null,
        caminho varchar(50) not null,
        home bool default false,
        foreign key(sites_id) references sites(id)
    );
    
    alter table user_sites add column login varchar(30) null;
    alter table user_sites add column senha varchar(100) null;

	#ATM
    insert nav_sites(sites_id,nome,caminho,home) values ('1','Principal','front/principal.php',true),
														('1','Aulas','front/pagPpt.php',false),
														('1','Exercícios','front/pagExerc.php',false),
														('1','Audios','front/pagAudio.php',false),
														('1','Notas','front/pagNotas.php',false),
														('1','Indicações','front/pagIndica.php',false),
														('1','Perfil','front/pagPerfil.php',false);
	#LWORLD
	insert nav_sites(sites_id,nome,caminho,home) values ('2','Dashboard','',true),
														('2','Secular','secular/',false),
														('2','Bíblico','biblico/',false),
														('2','Autoral','autoral/',false);
	#PCTRL
    insert nav_sites(sites_id,nome,caminho,home) values ('3','Dashboard','dashboard/',true),
														('3','Relatório','dashboard/imp.php',false);
    #HIMYM                                                    
	insert nav_sites(sites_id,nome,caminho,home) values ('4','Home','',true),
														('4','Continuar','play.php',false),
														('4','Aletório','play.php?random=1',false);
	#PMATTH
    insert nav_sites(sites_id,nome,caminho,home) values ('5','Albúm','',true),
														('5','Todas as Músicas','index.php?all',false);
	#DASHBOARD
    insert nav_sites(sites_id,nome,caminho,home) values ('6','Matthews World','index.php?i=1&page=mtworld',false),
														('6','Aula de Teoria Musical','index.php?i=2&page=atm',false),
														('6','Literary World','index.php?i=3&page=lworld',false),
														('6','Personal Control','index.php?i=4&page=pctrl',false),
														('6','How I Met Your Mother','index.php?i=5&page=himym',false),
														('6','Play Matth','index.php?i=6&page=pmatth',false),
														('6','Whats Matth','index.php?i=7&page=wmatth',false),
														('6','Prototipo - Univesp','index.php?i=8&page=prototipo',false);
	#MMART
    insert nav_sites(sites_id,nome,caminho,home) values ('7','Lista de Compras','',true);
    #MTECH
    insert nav_sites(sites_id,nome,caminho,home) values ('8','Home','',true);
    
    select * from sites;
    select * from nav_sites;
    select * from user_sites;
    select * from usuario;