create table artistReleases (
	id int auto_increment,
	artistId int,
	name varchar(255),
	createdOn date,
	createdBy int,

	primary key ( id ),
	unique key (artistId, name)
)
