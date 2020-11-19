create table queryLog (
  id int(11) auto_increment,
  tname varchar(250),
  op varchar(250),
  tid int,
  querySql text,
  rftId int,
  stamp datetime,
  primary key (id),
  key tname (tname,id)
)
