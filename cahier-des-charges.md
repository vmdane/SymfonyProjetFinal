📘 Cahier des charges – Bibliothèque communautaire

1. Présentation du projet

Le projet "Bibliothèque communautaire" est une plateforme web collaborative développée avec Symfony 7 permettant aux utilisateurs de prêter, emprunter et recommander des livres au sein d'une communauté. Il s’inscrit dans une démarche de partage de savoirs et de valorisation des échanges entre particuliers.

2. Objectifs

Créer un espace d’échange de livres entre particuliers.
Offrir une navigation fluide, responsive et intuitive.
Permettre aux utilisateurs de gérer leurs emprunts, favoris et avis.
Intégrer des données enrichies via l’API Google Books.

3. Pourquoi ce projet ?

Dans un contexte où les pratiques écoresponsables gagnent du terrain, ce projet répond à une volonté de réutilisation des ressources existantes. Il incarne les principes de l’économie collaborative et de l’accès à la culture pour tous.

4. Fonctionnalités principales (actuellement développées)

🔍 Page d’accueil : liste des livres disponibles avec recherche par titre/auteur.
📖 Page de détail d’un livre : description, disponibilité, bouton d’emprunt, historique des emprunts, avis.
👤 Profil utilisateur : livres donnés, favoris, emprunts en cours/passés, avis.
🗂️ Page des catégories : consultation des livres par catégorie.
📘 Ajout de livres via Google Books API.
🔔 Notifications pour les actions clés.
⭐ Avis utilisateurs (note/commentaire après emprunt).
🔐 Système d’authentification sécurisé (ROLE_USER actif).
🧪 Tests unitaires et fonctionnels.
🌐 Site déployé en ligne :
👉 https://bibliotheque.karen-gueppois.fr

5. Fonctionnalités à venir

📧 Envoi d’emails (ex : rappels d’emprunt, notifications importantes).
🛠️ Espace Admin : gestion des utilisateurs, contenus, catégories.
🙋‍♂️ Rôle Giver : interface dédiée pour les donneurs de livres (prêts, retours).
📦 Gestion des points de rendez-vous physiques (optionnel).
💬 Chat entre utilisateurs (optionnel).
📊 Statistiques de lectures et prêts.

6. Architecture technique

Backend : Symfony 7
Frontend : Twig + Tailwind CSS
BDD : MySQL / Doctrine ORM
API externe : Google Books
Déploiement : Production sur hébergement distant
CI/CD : GitHub Actions (tests, lint, PHPStan)

7. Entités (10)

User
Book
Author
BookShelf
Category
Genre
Language
Loan
Notification
Review
Héritage
En prévision : héritage potentiel via discrimination entre Admin, User, Giver.
Relations
✅ 2 ManyToMany :
User <-> Book (Favoris)
Book <-> Author
✅ 8+ OneToMany :
User -> Book (Donneur)
User -> Loan
User -> Review
User -> Notification
Book -> Review
Book -> Loan
Category -> Book
Genre -> Book
Language -> Book

8. Sécurité

🔐 Authentification complète avec encodage des mots de passe.
🔒 Sécurisation des routes.
🧩 En cours : voter personnalisé.
👥 Rôles prévus : ROLE_USER (actif), ROLE_ADMIN, ROLE_GIVER (en cours de mise en place).
9. Tests

✅ 1 test unitaire (ex : validité d’un prêt).
✅ 1 test fonctionnel.

10. Déploiement

Le projet est déployé à l’adresse suivante :

🔗 https://bibliotheque.karen-gueppois.fr

11. Conclusion

Bibliothèque Communautaire est un projet ambitieux, déjà doté d’un socle fonctionnel solide. Il incarne les valeurs de l’accès partagé à la culture, de l’écoresponsabilité et de l’entraide. En poursuivant le développement des fonctionnalités prévues (interface admin, envoi de mails, rôle Giver...), la plateforme pourra devenir une véritable référence en matière de partage littéraire collaboratif.
