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
```
recipe-manager/
│
├── config/
│   └── db.php
│
├── controllers/
│   ├── AuthController.php
│   ├── FavController.php
│   └── RecipeController.php
|
├── models/
│   ├── category.php
│   ├── favorites.php
│   ├── recipe.php
│   └── user.php
│
├── public/
│   └── index.php
│
├── views/
│   ├── auth/
│   │   └── login.php
│   ├── css/
│   │   └── style.css
│   ├── includes/
│   │   ├── footer.php
│   │   └── header.php
│   ├── recipes/
│   │   ├── dashboard.php
│   │   ├── favorites.php
│   │   └── home.php
│   └── index.php
│
├── schema.sql
└── README.md
```

## 🧩 Features (User Stories)
```
ID	Feature
```
```
US1	User Registration
```
<img width="1920" height="1200" alt="Capture d’écran (11)" src="https://github.com/user-attachments/assets/ba77e726-7dbd-418a-bb13-312bfdd4e6b7" />

```
US2	User Login
```
<img width="1920" height="1200" alt="Capture d’écran (10)" src="https://github.com/user-attachments/assets/175b079c-7a75-454f-82d5-55b048403281" />

```
US3	Display my recipes
```
<img width="1920" height="1200" alt="Capture d’écran (7)" src="https://github.com/user-attachments/assets/3ef41542-eb83-485e-934c-68f139f40beb" />

```
US4	Create a recipe
```
<img width="1920" height="1200" alt="Capture d’écran (12)" src="https://github.com/user-attachments/assets/31e64b87-e30b-4ddf-bf08-cdf850fa1e25" />

```
US5	Edit a recipe
```
```
US6	Delete a recipe
```
```
US7	Recipe categories
```

```
US8	Filter recipes by category
```
<img width="1920" height="1200" alt="Capture d’écran (8)" src="https://github.com/user-attachments/assets/642dd402-d406-47e4-b5eb-56afd0464667" />
```
Mes Favoris Page:
```
<img width="1920" height="1200" alt="Capture d’écran (9)" src="https://github.com/user-attachments/assets/ff2f22ab-f5fd-444c-af52-2ec6be64dc60" />
```
Home Page:
```
<img width="1920" height="1200" alt="Capture d’écran (4)" src="https://github.com/user-attachments/assets/582be35d-ff7f-4dde-afc2-abebc7902289" />
<img width="1920" height="1200" alt="Capture d’écran (5)" src="https://github.com/user-attachments/assets/2c865598-fc30-41b8-b683-bbd94e5c71c5" />
<img width="1920" height="1200" alt="Capture d’écran (6)" src="https://github.com/user-attachments/assets/a89f3f84-2570-49dc-ad70-bc38d00ce897" />
```

## ⭐ Bonus	Favorites recipes

- Recherche de Recettes : Ajouter une barre de recherche qui filtre les recettes par titre ou ingrédients.
- Recettes Favorites : Permettre à un utilisateur de marquer des recettes comme "favorites".

## 🗃️ Database Design (Merise)
<img width="1920" height="1200" alt="Capture d’écran (14)" src="https://github.com/user-attachments/assets/d528b19a-8e8e-47f4-8303-22c18f1d3f32" />

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

MVC from scratch
Routing without frameworks
SQL relational integrity
Clean OOP design
Agile teamwork

## 👥 Authors

##Pro-Joseph / wissalaitihya
Developed in pair-programming mode as part of a backend developping training project.
