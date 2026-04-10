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

- Recherche de Recettes : Ajouter une barre de recherche qui filtre les recettes par titre ou ingrédients.
- Recettes Favorites : Permettre à un utilisateur de marquer des recettes comme "favorites".

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