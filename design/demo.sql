create database steffenphp charset=utf8;

use steffenphp;


create table if not exists users(
    id mediumint unsigned not null,
    username varchar(32) not null,
    password char(32) not null,
    primary key (id),
    unique key uk_username (username)
)charset=utf8,engine=innodb;

insert into users(username,password) values('SteffenKong',md5('123456'));