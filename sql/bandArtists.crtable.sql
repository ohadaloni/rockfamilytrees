create table bandArtists (
	id int auto_increment,
	bandId int,
	artistId int,
	createdOn date,
	createdBy int,

	primary key ( id ),
	unique key ( bandId, artistId )
)
