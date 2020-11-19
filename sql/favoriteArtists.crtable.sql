create table favoriteArtists (
	id int auto_increment,
	rftId int,
	artistId int,
	createdOn date,
	primary key ( id ),
	unique key (rftId, artistId)
)
