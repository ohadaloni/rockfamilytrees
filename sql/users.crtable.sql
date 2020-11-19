create table users (
	id int auto_increment,
	avatar varchar(255),
	passwd varchar(255),
	created date,
	numOps int default 0,
	lastOp date,

	primary key ( id )
)
