# 📚 Bibliothèque - Symfony

## 🌍 Site en ligne

👉 [https://bibliotheque.karen-gueppois.fr](https://bibliotheque.karen-gueppois.fr)

---

## 🔧 Prérequis

- PHP >= 8.3
- Composer
- MySQL
- Node.js + npm

---

## 🚀 Installation locale

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
```
