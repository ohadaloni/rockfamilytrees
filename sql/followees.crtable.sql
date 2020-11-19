create table followees (
	id int auto_increment,
	rftId int,
	followee int,
	primary key ( id ),
	unique key (rftId, followee)
)
