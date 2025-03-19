🌟 Association Ensemble Frédéric Mistral

Bienvenue sur le projet Ensemble Frédéric Mistral, une plateforme web permettant aux utilisateurs de s'inscrire, d'acheter, de devenir adhérents, de vendre des produits issus de dons et de mettre en avant les activités de l'association.

✨ Fonctionnalités Principales
📅 Gestion des adhésions : Inscription des membres et renouvellement.
🛒 E-commerce solidaire : Achat du produits issus de dons.
🛒 E-commerce solidaire : Vente de produits issus de dons.
👥 Gestion des utilisateurs : Profils, administration et droits d'accès.
📝 Publication d'actualités : Articles et événements de l'association.
🔒 Sécurisation des données : Protection des informations personnelles.

🛠 Technologies Utilisées
Backend : Symfony 7.2, PHP 8.2
Frontend : Bootstrap 5.3, JavaScript, Twig
Base de données : MySQL
Outils de conception : Canva, Figma
Gestion de projet : Jira, Miro
Contrôle de version : Git, GitHub

🌐 Installation & Lancement du Projet

1. Prérequis
PHP 8.2
Composer
Symfony CLI
MySQL
Node.js & npm (pour les dépendances front-end)

2. Installation
Clonez le dépôt Git :
git clone https://github.com/EFM34/efm.git
cd efm

Installez les dépendances PHP :
composer install

Installez les dépendances JavaScript :
npm install

3. Configuration
configuratin base de données :
cp .env.example .env

Modifiez le fichier .env pour définir les accès MySQL :
DATABASE_URL="mysql://root:password@127.0.0.1:3306/efm_db"

4. Démarrer le projet
Générez les clés de sécurité et la base de données :
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

Lancez le serveur Symfony :
symfony serve

Accédez à l'application via : http://localhost:8000

🌟 Contribution
Les contributions sont les bienvenues ! Pour contribuer :

Dépôt
Crée une branche : git checkout -b feature-nouvelle-fonctionnalite
Fais tes modifications et commit : git commit -m "Ajout d'une fonctionnalité"
Push la branche : git push origin feature-nouvelle-fonctionnalite
Ouvre une pull request

🛡️ Sécurité
Nous suivons les bonnes pratiques de sécurité. Pour signaler une vulnérabilité, merci de contacter l'équipe via GitHub.

👥 Auteurs
Nom 1 - Développeur Backend
Nom 2 - Développeur Frontend
Nom 3 - Designer UX/UI

🌟 Licence

Ce projet est sous licence MIT - voir le fichier LICENSE pour plus d'informations.
