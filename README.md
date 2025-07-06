# :books: Bibliothèque- Symfony 

## :wrench: Prérequis

- PHP >= 8.3
- Composer
- MySQL
- Node.js + npm

---

## :rocket: Installation locale

```bash
git clone https://github.com/vmdane/SymfonyProjetFinal
cd SymfonyProjetFinal

# Installer les dépendances
composer install
npm install
npm run dev

# Créer la base de données
php bin/console doctrine:database:create

# Appliquer les migrations
php bin/console doctrine:migrations:migrate

# Charger les fixtures
php bin/console doctrine:fixtures:load --purge-with-truncate

# Lancer le serveur
symfony server:start  
