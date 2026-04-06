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
    instructions varchar(255),
    portions varchar(100),
    foreign key (users_id)references users(id) on delete cascade,
    foreign key (categories_id)references categories(id) on delete cascade
);


INSERT INTO users (username, email, password) VALUES
('wissal', 'wissal@mail.com', '$2y$10$examplehashuser');

INSERT INTO categories (name) VALUES
('Entrées'),
('Plats'),
('Desserts'),
('Boissons');

INSERT INTO recette 
(users_id, categories_id, title, temp_de_production, ingredient, instructions, portions)
VALUES
(1, 1, 'Salade Marocaine', '15 min',
 'Tomates, concombre, oignon, huile olive',
 'Couper les légumes et mélanger avec huile et sel',
 '2 personnes'),

(1, 2, 'Poulet rôti', '1 heure',
 'Poulet, épices, ail, huile',
 'Assaisonner le poulet et cuire au four',
 '4 personnes'),

(1, 3, 'Gâteau au chocolat', '45 min',
 'Farine, chocolat, sucre, œufs',
 'Mélanger les ingrédients et cuire au four',
 '6 personnes'),

(1, 4, 'Jus d’orange', '10 min',
 'Oranges, sucre',
 'Presser les oranges et ajouter du sucre',
 '3 verres');
