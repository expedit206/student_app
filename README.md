# Documentation de l'application Laravel

## Table des matières
- [Introduction](#introduction)
- [Fonctionnalités](#fonctionnalités)
- [Technologies utilisées](#technologies-utilisées)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contributions](#contributions)

## Introduction
Cette application Laravel est conçue pour gérer les interactions entre formateurs et apprenants, ainsi que pour fournir des fonctionnalités de chat, de gestion de notes, et de suivi de la progression.

## Fonctionnalités

### 1. Chat
- **Envoyer et recevoir des messages** : Les utilisateurs authentifiés peuvent envoyer et recevoir des messages via le système de chat.
- **Interface de chat** : Une interface utilisateur pour discuter avec d'autres utilisateurs.

### 2. Gestion des Formateurs
- **Dashboard Formateur** : Accès à un tableau de bord personnalisé pour les formateurs.
- **Mes Formations** : Les formateurs peuvent visualiser les formations qu'ils gèrent.
- **Mes Apprenants** : Affichage des apprenants inscrits à leurs formations.
- **Mes Disciplines** : Consultation des disciplines enseignées par le formateur.
- **Gestion des Notes** : Possibilité d'ajouter et de mettre à jour des notes pour les apprenants.

### 3. Gestion des Apprenants
- **Dashboard Apprenant** : Accès à un tableau de bord personnalisé pour les apprenants.
- **Mes Notes** : Consultation des notes obtenues.
- **Progression** : Suivi de la progression dans les formations.
- **Carnet** : Accès à un carnet de suivi personnel.
- **Disciplines** : Consultation des disciplines suivies.

### 4. Gestion Administrative
- **Dashboard Admin** : Accès à un tableau de bord pour les administrateurs.
- **Gestion des Formateurs, Formations, Disciplines et Apprenants** : Utilisation de ressources pour gérer les utilisateurs et les formations.
- **Associations** : Gestion des associations entre formateurs, formations et disciplines.

## Technologies utilisées
- **Laravel** : Framework PHP pour le développement web.
- **Inertia.js** : Pour une expérience utilisateur dynamique et réactive.
- **Vue.js** : Bibliothèque JavaScript pour construire des interfaces utilisateur.
- **MySQL** : Système de gestion de base de données relationnelle.
- **Tailwind css** : Pour le style et la mise en page responsive.

## Installation

### Prérequis
- PHP >= 8.2
- Composer
- Node.js et NPM
- MySQL
- Apache
- Vscode
- git
- Un navigateur web (Google chrome, ...)

### Étapes d'installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/username/repository.git
   cd repository
