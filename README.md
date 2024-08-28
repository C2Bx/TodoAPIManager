# 🚀 TodoAPIManager

`TodoAPIManager` est une API RESTful développée avec Symfony pour gérer une liste de tâches (todos). Elle permet de **créer**, **mettre à jour**, **supprimer** et **récupérer** des tâches, avec des options de filtrage et de tri. L'API intègre des validations pour assurer l'exactitude des données et fournit des messages d'erreur en français.


## 📋 Table des Matières  
1. 🛠️ [Informations Générales](#informations-générales)  
2. 💻 [Technologies](#technologies)  
3. 🚀 [Installation](#installation)  
4. 📦 [Utilisation](#utilisation)  
5. 🤝 [Collaboration](#collaboration)  
6. ❓ [FAQs](#faqs)  
7. 📜 [Licence](#licence)


## 🛠️ Informations Générales  
`TodoAPIManager` est conçu pour faciliter la gestion des tâches en offrant une API simple et efficace. Ce projet suit les principes **KISS** (Keep It Simple, Stupid) pour assurer des URL claires, une gestion rigoureuse des erreurs, et une architecture de code propre. Le projet est actuellement en **développement actif** avec des fonctionnalités en constante amélioration.


### Objectifs du Projet  
Ce projet a été développé dans le cadre d'un TP noté. Les objectifs étaient les suivants :  
- Créer une API RESTful conforme aux bonnes pratiques.  
- Valider son fonctionnement à l’aide d’outils comme **Postman** ou **Hoppscotch**.  
- Implémenter les fonctionnalités CRUD (Create, Read, Update, Delete) pour une entité Todo.  
- Assurer une gestion claire des erreurs avec des messages HTTP appropriés (400, 404, etc.).


## 💻 Technologies  
Le projet utilise les technologies suivantes :  
- **Symfony** : [Symfony](https://symfony.com) (Version 6.x)  
- **PHP** : [PHP](https://www.php.net/) (Version 8.0 ou supérieure)  
- **MySQL** : [MySQL](https://www.mysql.com/) (Version 8.x)


## 🚀 Installation  
### Prérequis  
Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre machine :  
- PHP 8.0 ou supérieur  
- Composer  
- MySQL


### Étapes d'Installation  
1. **Cloner le repository**  
   ``bash  
   git clone https://github.com/C2Bx/TodoAPIManager.git  
   cd TodoAPIManager  


2. **Installer les dépendances**  
   ``bash  
   composer install 


3. **Configurer la base de données**  
   Créez un fichier `.env.local` à la racine du projet et configurez les paramètres de la base de données :  
   ``dotenv  
   DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"  


4. **Créer la base de données et exécuter les migrations**  
   ``bash  
   php bin/console doctrine:database:create  
   php bin/console doctrine:migrations:migrate  


5. **Lancer le serveur de développement**  
   ``bash  
   symfony server:start  


## 📦 Utilisation  
### Gestion des Todos  
Pour gérer vos tâches via l'API, utilisez les méthodes HTTP suivantes :
- **Créer un Todo**  
  Méthode : POST  
  URL : `/todos`  
  Corps de la requête (JSON) :  
  ``json  
  {  
      "title": "Acheter du lait",  
      "descriptionLongue": "Aller au supermarché pour acheter du lait",  
      "dueAt": "2024-09-15T10:00:00+00:00"  
  }  


**Récupérer tous les Todos**  
  Méthode : GET  
  URL : `/todos`


**Mettre à jour un Todo**  
  Méthode : PUT ou PATCH  
  URL : `/todos/{id}`  
  Corps de la requête (JSON) :  
  ``json  
  {  
      "title": "Acheter du lait et du pain"  
  }  


**Supprimer un Todo**  
  Méthode : DELETE  
  URL : `/todos/{id}'


**Filtrer et trier les Todos**  
  Méthode : GET  
  URL : `/todos/filter?search=<mot-clé>&sort=dueAt`


### Gestion des erreurs  
L'API renvoie des messages d'erreur en français pour :  
- **Validation des données** : 400 Bad Request si un champ obligatoire manque.  
- **Todo non trouvé** : 404 Not Found pour une tentative d'accès à un ID inexistant.  
- **Méthode HTTP non autorisée** : 405 Method Not Allowed.


## 🤝 Collaboration  
Nous accueillons les contributions pour améliorer TodoAPIManager. Pour contribuer :  
1. **Forkez** le repository.  
2. **Créez** une branche pour vos modifications.  
3. **Soumettez** une pull request.

Merci de respecter les guidelines de contribution.


## ❓ FAQs  
### Comment configurer la base de données ?  
Suivez les instructions dans la section [Installation](#installation) pour créer et configurer la base de données.

### Quels sont les prérequis pour utiliser l'API ?  
PHP 8.0, Composer, et MySQL ou une autre base de données compatible.

### Comment signaler un bug ?  
Ouvrez une issue sur le repository GitHub.

### Où trouver plus d'informations sur Symfony ?  
Consultez la documentation de Symfony.


## 📜 Licence  
Ce projet est sous licence MIT. Consultez le fichier LICENSE pour plus de détails.