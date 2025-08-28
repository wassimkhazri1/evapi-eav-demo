
## 👨💻 Auteur

**Wassim KHAZRI**
- Email : wassim_khazri@hotmail.fr
- LinkedIn : https://www.linkedin.com/in/wassim-khazri-ab923a14b/
- GitHub : https://github.com/wassimkhazri1


# Gestionnaire de Projets EAV

## 📖 Description

Un projet de démonstration développé dans le cadre de mon apprentissage de **Symfony** et du modèle de données **EAV (Entity-Attribute-Value)**.
Cette application web permet de gérer des projets avec un système flexible d'attributs dynamiques.


## ✨ Fonctionnalités

- **🔐 Système d'Authentification** : Inscription et connexion sécurisées.
- **👨💻 Espace Administrateur** :
  - Dashboard pour visualiser tous les projets de tous les utilisateurs.
  - Modification du statut et des détails des projets.
  - Gestion des attributs EAV (Création, modification).
- **👤 Espace Utilisateur** :
  - Visualisation et gestion exclusive de ses propres projets.
  - Création et modification de projets.
  - Création de nouveaux attributs pour ses projets.
  - Détail complet de chaque projet avec ses attributs et valeurs.


## 🛠️ Stack Technique

- **Framework Backend** : Symfony 6.4 / 7.0
- **Langage** : PHP 8.2+
- **Base de données** : MySQL / MariaDB
- **ORM** : Doctrine
- **Templating** : Twig
- **(Optionnel) Frontend** : Bootstrap ? Tailwind CSS ? (Mets ce que tu as utilisé)



## 🚀 Installation et Démarrage



1.  **Cloner le dépôt**
    ```bash
    git clone https://github.com/wassimkhazri1/evapi-eav-demo.git
    cd evapi-eav-demo
    ```

2.  **Installer les dépendances**
    ```bash
    composer install
    ```

3.  **Configurer la base de données**
    *   Créer une base de données nommée `eav_demo` (ou le nom de ton choix).
    *   Configurer le fichier `.env` selon tes paramètres locaux (DATABASE_URL).

4.  **Créer les tables et charger les fixtures (si disponibles)**
    ```bash
    # Créer le schéma de la base de données
    php bin/console doctrine:migrations:migrate
    # (Optionnel) Charger des données de test
    php bin/console doctrine:fixtures:load
    ```

5.  **Démarrer le serveur local**
    ```bash
    symfony server:start
    ```
    L'application sera accessible à l'adresse : `http://localhost:8000`


## 📦 Structure EAV (Entity-Attribute-Value)

Ce projet implémente un modèle EAV pour permettre une gestion flexible et dynamique des attributs des projets. La structure principale est la suivante :
- **Project** : L'entité de base (ex: "Site web pour Client X").
- **Attribute** : La définition d'un attribut (ex: "Couleur", "Priorité", "URL de staging").
- **Value** : La valeur liant un Project à un Attribute (ex: Project ID 1 + Attribute ID 3 -> "Rouge").


## 📝 Objectifs Pédagogiques

Ce projet m'a permis de monter en compétences sur :
- L'architecture MVC de Symfony.
- La création d'entités, de repositories et de contrôleurs.
- La gestion des relations complexes avec Doctrine ORM.
- La mise en place d'un système de sécurité (authentification, autorisation avec les rôles).
- La construction d'un modèle de données EAV.


## 👨💻 Auteur

**Wassim KHAZRI**
- Email : wassim_khazri@hotmail.fr
- LinkedIn : https://www.linkedin.com/in/wassim-khazri-ab923a14b/
- GitHub : https://github.com/wassimkhazri1

