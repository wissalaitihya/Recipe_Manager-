## 🍲 Recipe Manager — MVC PHP From Scratch

Client: DigitalBite Agency
Product Owner: Marrakech Food Lovers

A custom PHP MVC web application that allows cooking enthusiasts to create, organize, and manage their personal recipes.

This project was built without any framework to deeply understand:

MVC architecture
Object-Oriented Programming with encapsulation
SQL relational modeling (Merise → MCD → MLD)
Routing with .htaccess
Agile workflow using Kanban (Jira)
## 🎯 Project Goal

Recipes were previously scattered across notebooks, photos, and Word files.
This platform centralizes them into a structured, searchable, and shareable system.

## 🧠 Architecture Overview
URL → .htaccess → index.php → Router → Controller → Model → View

Strict MVC separation:

Layer	Responsibility
Model	Business logic + Database access (PDO)
Controller	Request handling + data orchestration
View	Display only (no SQL, no logic)
## 🗂️ Project Structure (MVC)
recipe-manager/
│
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   └── core/
│       ├── Router.php
│       └── routes.php
│
├── public/
│   ├── index.php
│   └── .htaccess
## 🧩 Features (User Stories)
ID	Feature
US1	User Registration
US2	User Login
US3	Display my recipes
US4	Create a recipe
US5	Edit a recipe
US6	Delete a recipe
US7	Recipe categories
US8	Filter recipes by category
## ⭐ Bonus	Favorites recipes
## 🗃️ Database Design (Merise)
Entities
Users
Recipes
Categories
Favorites (bonus)
Relationships
One User → Many Recipes (1-N)
One Category → Many Recipes (1-N)
Many Users ↔ Many Recipes (Favorites)

MCD and MLD diagrams are included in this repository.

## 🔐 Security Practices
PDO Prepared Statements
password_hash() / password_verify()
Strict form validation
Foreign Keys for referential integrity
## ⚙️ Apache Configuration (XAMPP)

Using Apache HTTP Server.

Edit:
```
/opt/lampp/etc/httpd.conf

Enable:

LoadModule rewrite_module modules/mod_rewrite.so

Allow .htaccess:

<Directory "/opt/lampp/htdocs">
    AllowOverride All
</Directory>
```
Restart:
```
sudo /opt/lampp/lampp restart
```
## 📄 .htaccess (public)
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
## ▶️ Installation
Clone repository into:
/opt/lampp/htdocs/
Import the SQL file into phpMyAdmin
Start XAMPP
Open:
```
http://localhost/recipe-manager/public/
```
## 🔗 Routing

All routes defined in:
```
app/core/routes.php
```
Example:
```
$router->add('', 'HomeController@index');
$router->add('recipes', 'RecipeController@index');
$router->add('recipes/show', 'RecipeController@show');
```

Links in views use:
```
<a href="<?= BASE_URL ?>/recipes/show/<?= $id ?>">
```
## 🧪 SQL Deliverable
Full schema creation script
Seeding:
3 users
10 recipes
4 categories
## 🗺️ Agile Organization
Kanban board on Jira
Daily standups
Pair programming
Retrospective completed

Screenshot of the final board included.

## 🧑‍💻 Code Review Readiness

Each team member can explain:
```
The full MVC request flow
The RecipeController
SQL relations and JOINs
Encapsulation and OOP structure
## 📸 Included in Repository
MCD / MLD diagrams
Jira board screenshot
SQL script
MVC structure
This README

## 🚀 Learning Outcome

This project demonstrates a deep understanding of:
```
MVC from scratch
Routing without frameworks
SQL relational integrity
Clean OOP design
Agile teamwork
## 👥 Authors

Developed in pair-programming mode as part of a backend engineering training project.
