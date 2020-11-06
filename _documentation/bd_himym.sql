drop database if exists bd_himym;
	create database bd_himym;
    
    use bd_himym;
    create table eps(
		id int(3) not null auto_increment primary key,
        season enum('1','2','3','4','5','6','7','8','9') not null,
        ep int(3) not null,
        nome varchar(50) not null
    );
    create table views(
		id_mtworld int(6) not null,
        id_ep int(3) not null,
        viewed bool default false,
        favorite bool default false,
        foreign key (id_ep) references eps(id),
        primary key(id_mtworld,id_ep)
    );
    # Primeira temporada
    insert into eps(season,ep,nome) values('1',1,'Piloto'),('1',2,'Purble Giraffe'),('1',3,'Sweet Teste of Liberty'),('1',4,'Return of the Shirt'),
		('1',5,'Okay Awesome'),('1',6,'Slutty'),('1',7,'Matchmaker'),('1',8,'The Duel'),('1',9,'Belly Full of Turkey'),
		('1',10,'The Pneapple Incident'),('1',11,'The Limo'),('1',12,'The Wedding'),('1',13,'Drumroll Please'),('1',14,'Zip Zip Zip'),
        ('1',15,'Game Night'),('1',16,'Cupcake'),('1',17,'Life Among the Gorillas'),('1',18,'Nothing Good Happens After 2am'),
		('1',19,'Mary The Paralegal'),('1',20,'Best Prom Ever'),('1',21,'Milk'),('1',22,'Come On');
	# Segunda Temporada
    insert into eps(season,ep,nome) values('2',1,'Where Were We'),('2',2,'The Scorpion and the Toad'),('2',3,'Brunch'),
		('2',4,'Ted Mosby Architect'),('2',5,'Worlds Greatest Couple'),('2',6,'Aldrin Justice'),('2',7,'Swarley'),('2',8,'Atlantic City'),
		('2',9,'Slap Bet'),('2',10,'Single Stamina'),('2',11,'How Lily Stole Christmas'),('2',12,'First Time In New York'),('2',13,'Columns'),
		('2',14,'Monday Night Football'),('2',15,'Lucky Penny'),('2',16,'Stuff'),('2',17,'Arrivederci Fiero'),('2',18,'Moving Day'),
		('2',19,'Bachelor Party'),('2',20,'Showdown'),('2',21,'Something Borrowed'),('2',22,'Something Blue');
	# Terceira Temporada
    insert into eps(season,ep,nome) values('3',1,'Wait Fot It'),('3',2,'We are Not From Here'),('3',3,'Third Wheel'),('3',4,'Little Boys'),
		('3',5,'How I Met Everyone Else'),('3',6,'I am Not That Guy'),('3',7,'Dowisetrepla'),('3',8,'Spoiler Alert'),('3',9,'Slapsgiving'),
		('3',10,'The Yips'),('3',11,'The Platinum Rule'),('3',12,'No Tomorrow'),('3',13,'Ten Sessions'),('3',14,'The Bracket'),
		('3',15,'The Cain of Screaming'),('3',16,'Sandcastles in the Sand'),('3',17,'The Goat'),('3',18,'Rebound Bro'),
        ('3',19,'Everything Must Go'),('3',20,'Miracles');
	# Quarta Temporada
    insert into eps(season,ep,nome) values('4',1,'Do I Know You'),('4',2,'The Best Burger in New York'),('4',3,'I Heart NJ'),
		('4',4,'Intervention'),('4',5,'Shelter Island'),('4',6,'Happily Ever After'),('4',7,'Not a Father is Day'),
		('4',8,'Woooooo'),('4',9,'the Naked Man'),('4',10,'The Fight'),('4',11,'Little Minnesota'),
		('4',12,'Benefits'),('4',13,'Three Days of Snow'),('4',14,'The Possimpible'),('4',15,'The Stinsons'),('4',16,'Sorry Bro'),
		('4',17,'The Front Porch'),('4',18,'Old King Clancy'),('4',19,'Murtaugh'),('4',20,'Mosbius Designs'),
		('4',21,'The Three Days Rule'),('4',22,'Right Place Right Time'),('4',23,'As Fast as She can'),('4',24,'The Leap');                                      
	# Quinta Temporada
    insert into eps(season,ep,nome) values('5',1,'Definitions'),('5',2,'Double Date'),('5',3,'Robin 101'),('5',4,'The Sexless Innkeeper'),
		('5',5,'Duel Citizenship'),('5',6,'Bagpipes'),('5',7,'The Rough Patch'),('5',8,'The Playbook'),('5',9,'Slapsgiving 2 Revenge of the Slap'),
        ('5',10,'The Window'),('5',11,'Lasst Cigarret Ever'),('5',12,'Girls vs Suits'),('5',13,'Jenkins'),('5',14,'The Perfect Week'),
		('5',15,'Rabbit or Duck'),('5',16,'Hooked'),('5',17,'Of Course'),('5',18,'Say Cheese'),('5',19,'Zoo or False'),('5',20,'Home Wreckers'),
        ('5',21,'Twin Beds'),('5',22,'Robots vs Wrestlers'),('5',23,'The Wedding Bride'),('5',24,'Doppelgangers');
	# Sexta Temporada
    insert into eps(season,ep,nome) values('6',1,'Big Days'),('6',2,'Cleaning House'),('6',3,'Unfinished'),('6',4,'Subway Wars'),
		('6',5,'Architect of Destruction'),('6',6,'Baby Talk'),('6',7,'Canning Randy'),('6',8,'Natural History'),('6',9,'Glitter'),
        ('6',10,'Blitzgiving'),('6',11,'The Mermaid Theory'),('6',12,'False Positive'),('6',13,'Bad News'),('6',14,'Last Words'),
        ('6',15,'Oh Honey'),('6',16,'Desperation Day'),('6',17,'Garbage Island'),('6',18,'A Change of Heart'),('6',19,'Legendaddy'),
        ('6',20,'The Exploding Meatball Sub'),('6',21,'Hopeless'),('6',22,'The Perfect Cocktail'),('6',23,'Landmarks'),('6',24,'Challenge Accepted');
	# Sétima Temporada
    insert into eps(season,ep,nome) values('7',1,'The Best Man'),('7',2,'The Naked Truth'),('7',3,'Ducky Tie'),('7',4,'The Stinson Missile Crisis'),
		('7',5,'Field Trip'),('7',6,'Mystery vs History'),('7',7,'Noretta'),('7',8,'The Slutty Pumpkin Returns'),('7',9,'Disaster Averted'),
        ('7',10,'Tick Tick Tick'),('7',11,'The Rebound Girl'),('7',12,'Symphony of Illumination'),('7',13,'Taigate'),('7',14,'46 Minutes'),
        ('7',15,'The Burning Beekeeper'),('7',16,'The Drunk Train'),('7',17,'No Pressure'),('7',18,'Karma'),('7',19,'The Broath'),
        ('7',20,'Trilogy Time'),('7',21,'Now We are Even'),('7',22,'Good Crazy'),('7',23,'The Magician is Code part 1'),
        ('7',24,'The Magician is Code part 2');
	# Oitava Temporada
    insert into eps(season,ep,nome) values('8',1,'Farhampton'),('8',2,'The PreNup'),('8',3,'Nannies'),('8',4,'Who Wants to be a Godparent'),
		('8',5,'Pauses and Paws'),('8',6,'Splitsville'),('8',7,'The Stamp Tramp'),('8',8,'Twelve Horny Women'),('8',9,'Lobster Crawl'),
        ('8',10,'The OverCorrection'),('8',11,'The Final Page part 1'),('8',12,'The Final Page part 2'),('8',13,'Band or DJ'),('8',14,'Ring Up'),
        ('8',15,'PS I Love You'),('8',16,'Bad Crazy'),('8',17,'Weekend at Barney is'),('8',18,'Weekend at Barney is'),('8',19,'The Fortress'),
        ('8',20,'The Time Travelers'),('8',21,'Romeward Bound'),('8',22,'The Bro Mitzvah'),('8',23,'Something Old'),('8',24,'Something New');
	# Nona Temporada
    insert into eps(season,ep,nome) values('9',1,'The Locket'),('9',2,'Coming Back'),('9',3,'Last Time in New York'),('9',4,'The Broken Code'),
		('9',5,'The Poker Game'),('9',6,'Knight Vision'),('9',7,'No Questions Asked'),('9',8,'The Lighthouse'),('9',9,'Platonish'),
        ('9',10,'Mom and Dad'),('9',11,'Bedtime Stories'),('9',12,'The Rehearsal Dinner'),('9',13,'Bass Player Wanted'),
        ('9',14,'Slapsgiving 3: Slappointment in Slapmarra'),('9',15,'Unpause'),('9',16,'How Your Mother Met Me'),('9',17,'Sunrise'),
        ('9',18,'Rally'),('9',19,'Vesuvius'),('9',20,'Daisy'),('9',21,'Gary Blauman'),('9',22,'The End Of the Aisle'),
        ('9',23,'Last Forever part 1'),('9',24,'Last Forever part 2');

	delimiter $$
	create procedure progress(in pEnd int(3),in pUser int(6)) begin
		declare i int(3) default 1;
        update views set viewed=false where id_mtworld=pUser;
        repeticao : loop
			if(i<=pEnd) then
				if((select count(*) from views where id_ep=i and id_mtworld=pUser)=0) then
					insert into views values (pUser,i,true,false);
				else
					if((select count(*) from views where id_ep=i and id_mtworld=pUser and viewed=true)=0) then
						update views set viewed=true where id_ep<=pEnd and id_mtworld=pUser;
					end if;
				end if;
                set i = i+1;
            else leave repeticao;
            end if;
		end loop repeticao;
    end $$
    
    create procedure viewed(in ps enum('1','2','3','4','5','6','7','8','9'), in pe int(3), in pUser int(6)) begin
		declare pid int(3) default (select id from eps where season=ps and ep=pe);
		
        if((select count(*) from views where id_mtworld=pUser and id_ep=pid)=0) then insert into views values (pUser,pid,true,false);
		else update views set viewed=true where id_mtworld=pUser and id_ep=pid;
        end if;
        
        if(pid=208) then select * from eps where id=1;
		else select * from eps where id=(pid+1);
		end if;
    end $$
    
    create procedure toggleFavorite(in ps enum('1','2','3','4','5','6','7','8','9'),in pe int(3),in pUser int(6)) begin
		declare pid int(3) default (select id from eps where season=ps and ep=pe);
		
        if((select count(*) from views where id_mtworld=pUser and id_ep=pid)=0) then insert into views values (pUser,pid,false,true);
		else update views set favorite=!favorite where id_mtworld=pUser and id_ep=pid;
        end if;	
    end $$
    delimiter ;
    
    select count(*) from eps;
    update eps set assistido=1 where id<'160';
    select season,count(*) from eps group by season;
    select round((count(*)*100)/208) assistiu from eps where assistido=1;
    select * from eps;
    select count(*) from views where id_mtworld=2 and viewed=1;
    select * from views v inner join eps e on v.id_ep=e.id where v.id_mtworld=2 and v.viewed=1 order by v.id_ep desc;
    select * from eps e left join views v on e.id=v.id_ep where id_mtworld=2 or id_mtworld=null;
    
    select * from views v inner join eps e on v.id_ep=e.id where v.id_mtworld='2' and (v.viewed=1 or v.favorite=1) order by v.id_ep desc;
    select * from views;