create table bandReleases (
	id int auto_increment,
	bandId int,
	name varchar(255),
	createdOn date,
	createdBy int,

	primary key ( id ),
	unique key (bandId, name)
)
