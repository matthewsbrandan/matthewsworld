drop database if exists bd_wmatth;
	create database bd_wmatth;
    
	use bd_wmatth;
    create table usuario(
		id int(3) not null auto_increment primary key,
        id_user int(3) not null,
        nome varchar(50) not null,
        usuario varchar(70) not null,
        senha varchar(100) not null,
        site varchar(30) not null
	);
    create table chat(
		id int(3) not null auto_increment primary key,
        id_usuario int(3) not null,
        msg varchar(500) not null,
        data_msg date not null,
        msg_user bool not null,
        msg_view bool default false,
        foreign key(id_usuario) references usuario(id)
    );
    
    delimiter $$
    create procedure enviar(in pidU int(3),in pmsg varchar(500), in pmsgU bool)
    begin
		insert into chat(id_usuario,msg,data_msg,msg_user) values (pidU,pmsg,concat(Year(now()),'/',month(now()),'/',day(now())),pmsgU);
    end $$
    create procedure userAtm(in pid int(3),in pnome varchar(50),in psenha varchar(100))
    begin
		insert into usuario(id_user,nome,usuario,senha,site) values (pid,pnome,pnome,psenha,'ATM');
        call enviar((select id from usuario where id_user=pid and nome=pnome and senha=psenha),'Seja bem vindo ao site - Aula de Teoria Musical. Eu sou Mateus, seu professor. Você pode estar enviando suas dúvidas por aqui que estarei respondendo o mais rápido possível. Muito Obrigado!',false);
    end $$;
    delimiter ;