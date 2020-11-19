create table favoriteBands (
	id int auto_increment,
	rftId int,
	bandId int,
	createdOn date,
	primary key ( id ),
	unique key (rftId, bandId)
)
