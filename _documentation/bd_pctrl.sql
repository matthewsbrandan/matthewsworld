drop database if exists bd_pctrl;
	create database bd_pctrl;
    use bd_pctrl;
    create table usuario(
		id int(6) not null primary key auto_increment,
        nome varchar(30) not null,
		sobrenome varchar(30) not null,
		email varchar(30) unique not null,
		celular	varchar(12) not null,
        senha varchar(100) not null
    );
	create table parcela(
		id int(6) not null primary key auto_increment,
        rotatividade enum('Diário','Semanal','Quinzenal','Mensal','Bimestral','Trimestral','Semestral','Anual') not null,
		quantidade int(2) not null,
		total decimal(14,2) default 0,
		usuario_id int(6) not null,
		foreign key (usuario_id) references usuario(id) 
    );
    create table conta(
		id int(6) not null primary key auto_increment,
        nome varchar(30) not null,
		saldo decimal(10,2) default 0,
		usuario_id int(6) not null,
        foreign key (usuario_id) references usuario(id)
    );
    create table categoria(
		id int(6) not null primary key auto_increment,
        nome varchar(30) not null,
		usuario_id int(6) not null,
        foreign key (usuario_id) references usuario(id)
    );
    create table caixa(
		id int(6) not null primary key auto_increment,
        mesano date not null,
		inicial	decimal(13,2) default 0,
		final decimal(13,2) default 0,
		inicial_parcial	decimal(13,2) default 0,
		final_parcial decimal(13,2) default 0,
		meta decimal(13,2) default 0,
		usuario_id int(6) not null,
        foreign key (usuario_id) references usuario(id)
    );
    create table movimento(
		id int(6) not null primary key auto_increment,
        tipo enum('Receita','Despesa') not null,
		data_ date not null,
		valor decimal(10,2) not null,
		descricao varchar(30) not null,
		obs varchar(150) null,
		relevancia enum('1','2','3','4','5') default '3',
		status bool default false,
		parcela	varchar(30) null,
		parcela_id int(6) null,
		conta_id int(6) null,
		categoria_id int(6) not null,
		caixa_id int(6) null,
		usuario_id int(6) not null,
		foreign key (parcela_id) references parcela(id),
		foreign key (conta_id) references conta(id),
		foreign key (categoria_id) references categoria(id),
		foreign key (caixa_id) references caixa(id),
		foreign key (usuario_id) references usuario(id)
    );
    create table objetivo(
		id int(6) not null primary key auto_increment,
        nome varchar(30) not null,
		valor decimal(14,2) not null,
		relevancia enum('1','2','3','4','5') not null,
		status bool default false,
		usuario_id	int(6) not null,
		foreign key (usuario_id) references usuario(id)
    );
	
    delimiter $$
    drop procedure if exists prc_update_caixa $$
    create procedure prc_update_caixa(in pid int(6)) begin
		declare varid int(6) default 0;
		declare vardata date;
        declare varcount int(3) default (select count(*) from caixa where usuario_id=pid);
        declare vari int(3) default 0;
        declare varvalori decimal(13,2) default 0;
        declare varvalorf decimal(13,2) default 0;
        declare varvalorip decimal(13,2) default 0;
        declare varvalorfp decimal(13,2) default 0;
        if(varcount=0) then select false;
        else
			repeticao : loop
				if(vari<varcount) then
					set varid = (select id from caixa where usuario_id=pid order by mesano limit 1 offset vari);
					set vardata = (select mesano from caixa where usuario_id=pid order by mesano limit 1 offset vari);
                    set varvalori = 0; set varvalorip = 0;
                    set varvalorf = (select sum(valor) from movimento where year(data_)=year(vardata) and month(data_)=month(vardata) and  usuario_id=pid and status=true);
                    set varvalorfp = (select sum(valor) from movimento where year(data_)=year(vardata) and month(data_)=month(vardata) and  usuario_id=pid);                    
                    if(vari>0) then
						set vari = vari-1;
                        set varvalori = (select final from caixa where usuario_id=pid order by mesano limit 1 offset vari);
                        set varvalorf = varvalorf+varvalori;                        
                        set varvalorip = (select final_parcial from caixa where usuario_id=pid order by mesano limit 1 offset vari);
                        set varvalorfp = varvalorfp+varvalorip;
                        set vari = vari+1;
					end if;
                    update caixa set inicial=if(varvalori,varvalori,0),final=if(varvalorf,varvalorf,0),inicial_parcial=if(varvalorip,varvalorip,0),final_parcial=if(varvalorfp,varvalorfp,0) where id=varid;
					set vari = vari+1;
                else leave repeticao;
                end if;
            end loop repeticao;
        end if;
        call prc_update_saldo(pid);
    end $$
    drop procedure if exists prc_new_transferencia $$
    create procedure prc_new_transferencia(in pconta1 int(6),in pconta2 int(6),in pdata date,in pvalor decimal(10,2),in prelevancia enum('1','2','3','4','5'),in pid int(6),in pobs varchar(150),in pstatus bool) begin
		declare categoriap int(6);
        declare idparcela int(6) default 0;
        declare valorp decimal(10,2) default (if(pvalor<0,pvalor,pvalor*(-1)));
        declare nomec1 varchar(30) default concat('Trans. de ',(select nome from conta where id=pconta1 and usuario_id=pid));
        declare nomec2 varchar(30) default concat('Trans. p/ ',(select nome from conta where id=pconta2 and usuario_id=pid));
        declare obsp varchar(150) default  (select if(length(pobs)=0,null,pobs));
        if((select count(*) from categoria where nome="Transferências" and usuario_id=pid)=0) then
			insert into categoria(nome,usuario_id) values ("Transferências",pid);
            set categoriap = (select id from categoria where nome="Transferências" and usuario_id=pid);
		else set categoriap = (select id from categoria where nome="Transferências" and usuario_id=pid);
        end if;
        insert into parcela(rotatividade,quantidade,total,usuario_id) values ('Diário',2,0,pid);
        set idparcela = (select id from parcela where total=0 and usuario_id=pid order by id desc limit 1);
        insert into movimento(tipo,data_,valor,descricao,relevancia,parcela,parcela_id,conta_id,categoria_id,usuario_id,obs,status) values ('Despesa',pdata,valorp,nomec1,prelevancia,'1/2',idparcela,pconta1,categoriap,pid,obsp,pstatus);
        set valorp = valorp*(-1);
        insert into movimento(tipo,data_,valor,descricao,relevancia,parcela,parcela_id,conta_id,categoria_id,usuario_id,obs,status) values ('Receita',pdata,valorp,nomec2,prelevancia,'2/2',idparcela,pconta2,categoriap,pid,obsp,pstatus);
    end $$
    drop procedure if exists prc_update_saldo $$
    create procedure prc_update_saldo(in pid int(6)) begin
		declare qtdConta int(6) default (select count(*) from conta where usuario_id=pid);
        declare cont int(6) default 0;
        declare total decimal(13,2) default 0;
        declare idConta int (6) default 0;
        repeticao : loop
			if(cont<qtdConta) then
				set idConta = (select id from conta where usuario_id=pid limit 1 offset cont);
                set total = (select sum(valor) from movimento where usuario_id=pid and conta_id=idConta and status=true);
                update conta set saldo=total where usuario_id=pid and id=idConta;
                set cont=cont+1; set idConta=0; set total=0;
			else leave repeticao;
            end if;
        end loop repeticao;
    end $$
    drop procedure if exists prc_update_status $$
    create procedure prc_update_status(in pconta int(6),in pmovimento int(6),in pid int(6),in pstatus bool) begin 
		if((select count(*) from movimento where usuario_id=pid and id=pmovimento and status!=pstatus)=1) then
			update movimento set status=pstatus, conta_id=pconta where usuario_id=pid and id=pmovimento;
            call prc_update_caixa(pid);
		end if;
    end $$
    drop procedure if exists prc_new_parcela $$
    create procedure prc_new_parcela(in ptipo enum('Receita','Despesa'),in pdata date,in pvalor decimal(10,2),in pdescricao varchar(30),in pobs varchar(150),in prelevancia enum('1','2','3','4','5'),in pcategoria int(6),in protatividade enum('Diário','Semanal','Quinzenal','Mensal','Bimestral','Trimestral','Semestral','Anual'),in pqtd int(2),in pid int(6)) begin
		declare idParcela int(6) default 0;
        declare cont int(2) default 1;
        declare intervalo int(2) default 0;
        insert into parcela(rotatividade,quantidade,total,usuario_id) values (protatividade,pqtd,(pvalor*pqtd),pid);
        set idParcela = (select id from parcela where rotatividade=protatividade and quantidade=pqtd and usuario_id=pid order by id desc limit 1);
        case protatividade
			when 'Diário'		then set intervalo = 1;
			when 'Semanal'		then set intervalo = 7;
			when 'Quinzenal'	then set intervalo = 15;
			when 'Mensal' 		then set intervalo = 1;
			when 'Bimestral'	then set intervalo = 2;
			when 'Trimestral'	then set intervalo = 3;
			when 'Semestral' 	then set intervalo = 6;
			when 'Anual' 		then set intervalo = 1;
			else select false;
        end case;
        repeticao : loop
			if(cont<=pqtd) then
				insert into movimento(tipo,data_,valor,descricao,obs,relevancia,categoria_id,usuario_id,parcela,parcela_id) values (ptipo,pdata,pvalor,pdescricao,pobs,prelevancia,pcategoria,pid,concat(cont,'/',pqtd),idParcela);
                if(day(pdata)>28) then set pdata = concat(year(pdata),'-',month(pdata),'-28'); end if;
                if((protatividade='Diário')or(protatividade='Semanal')or(protatividade='Quinzenal')) then
					set pdata = date_add(pdata,interval intervalo day);
				else if((protatividade='Mensal')or(protatividade='Bimestral')or(protatividade='Trimestral')or(protatividade='Semestral')) then 
					set pdata = date_add(pdata,interval intervalo month);
				else if(protatividade='Anual') then
					set pdata = date_add(pdata,interval intervalo year);
				end if; end if; end if;
                set cont = cont+1;
			else leave repeticao;
            end if;
        end loop repeticao;
    end $$
    drop trigger if exists tgr_new_user $$
    create trigger tgr_new_user after insert on usuario for each row begin
		insert into conta(nome,usuario_id) values ('Físico',new.id);
        insert into conta(nome,usuario_id) values ('Poupança',new.id);
        insert into categoria(nome,usuario_id) values ('Outros',new.id);
        insert into categoria(nome,usuario_id) values ('Transferência',new.id);
        insert into categoria(nome,usuario_id) values ('Margem de Erro',new.id);
        insert into caixa(mesano,usuario_id) values (concat(year(now()),'-',month(now()),'-01'),new.id);
    end $$
    drop trigger if exists tgr_new_movi $$
    create trigger tgr_new_movi before insert on movimento for each row begin
		if(new.tipo='Despesa' and new.valor>0) then set new.valor = new.valor*(-1);
		else if(new.tipo='Receita' and new.valor<0) then set new.valor = new.valor*(-1); end if;
        end if;
        if((select count(*) from caixa where year(mesano)=year(new.data_) and month(mesano)=month(new.data_) and usuario_id=new.usuario_id)=0) then
			insert into caixa(mesano,usuario_id) values(concat(year(new.data_),'-',month(new.data_),'-01'),new.usuario_id);
        end if;
        set new.caixa_id = (select id from caixa where year(mesano)=year(new.data_) and month(mesano)=month(new.data_) and usuario_id=new.usuario_id);
    end $$
    delimiter ;
    
    select * from caixa where mesano='2020-03-01' and usuario_id=1;
    select * from movimento m inner join parcela p on m.parcela_id=p.id where m.parcela_id=8 and m.usuario_id=1 order by data_;
    select * from movimento where usuario_id='1' and status=0 and year(data_)=year(now()) and month(data_)=month(now())-1;
    select * from movimento where id=31;
    select * from movimento m inner join categoria c on m.categoria_id=c.id where m.categoria_id=1 and m.usuario_id=1 order by data_;