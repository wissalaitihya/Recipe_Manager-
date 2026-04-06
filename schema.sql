create database recette_manager;

use recette_manager;

create table users (
    id int auto_increment primary key,
    username varchar(150) not null unique,
    email varchar(150) not null unique,
    password varchar(150)
);

create table categories(
    id int auto_increment primary key,
    name enum ("Entrées","Plats","Desserts","Boissons") not null
);


create table recette(
    id int auto_increment primary key,
    users_id int,
    categories_id int,
    title varchar(150) not null,
    create_time timestamp NOT NULL DEFAULT current_timestamp(),
    temp_de_production varchar(100),
    ingredient varchar(255),
    portions varchar(100),
    foreign key (users_id)references users(id) on delete cascade,
    foreign key (categories_id)references categories(id) on delete cascade
);



