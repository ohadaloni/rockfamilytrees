create table artists (
	id int auto_increment,
	name varchar(255),
	createdOn date,
	createdBy int,

	primary key ( id ),
	unique key ( name ),
	key ( createdBy )
)
