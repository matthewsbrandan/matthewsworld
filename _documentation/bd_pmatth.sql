drop database if exists bd_pmatth;
	create database bd_pmatth;
    use bd_pmatth;
    
    create table album(
		album_id int(3) not null auto_increment primary key,
        album_nome varchar(50) not null,
        album_img varchar(50) not null,
        album_user_id int(6) not null
    );
    
    create table musica(
		musica_id int(3) not null auto_increment primary key,
        musica_nome varchar(50) not null,
        musica_album_id int(3) not null,
        musica_letra varchar(2000) null,
        musica_audio varchar(50) null,
        musica_historia varchar(1500) null,
        musica_cifra varchar(2000) null,
        musica_user_id int(6) not null,
        foreign key (musica_album_id) references album(album_id)
    );
    
    select * from album;
    select * from musica;
    select count(*) from musica where musica_album_id=3;
    select count(*),album_nome,album_img,album_id from musica inner join album on musica_album_id=album_id where album_id=1 group by album_id;