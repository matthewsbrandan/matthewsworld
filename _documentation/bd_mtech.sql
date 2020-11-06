drop database if exists bd_mtech;
	create database bd_mtech;
    use bd_mtech;
    
    create table acompanhamento(
		id int(6) not null auto_increment primary key,
        grupo enum('Aprovação de Protocolos','Chamados SN/TI','Acomp. OS/STD','Acomp. NFE','Aguard. Movimentação','TRADE') not null,
        emissao date null,
        prazo date null,
        registro varchar(50) null,
        problema varchar(100) null,
        objeto varchar(50) null,
        referencia varchar(50) null,
        status varchar(50) not null
    );
    select * from acompanhamento;
    insert into acompanhamento(grupo,emissao,prazo,registro,problema,objeto,referencia,status) values ('2020-07-24','2020-07-24','123.654.987','Quebrado','TV','4384520','Em Aberto');    