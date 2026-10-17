# TG2D - Site Vitrine Officiel & Espace Administration

> **TG2D · Bâtissons pour l'avenir durable**  
> Site web institutionnel de l'entreprise TG2D (Travaux Généraux de Développement Durable).

---

## 🏗️ Structure du Projet

- **Pages Vitrine** :
  - `index.html` : Page d'accueil avec navigation moderne et identité visuelle corporate.
  - `a-propos.html` : Présentation institutionnelle, vision et équipe dirigeante.
  - `expertises.html` : Lots et domaines d'expertises TCE (Gros œuvre, Électricité, Fluides, etc.).
  - `projets.html` / `realisations.html` : Réalisations phares (Bricoma Dar Bouazza, Centre Logistique Bouskoura, Villas de Standing, etc.).
  - `carrieres.html` : Espace recrutement et candidatures.
  - `contact.html` : Formulaire de contact et devis, coordonnées, localisation.
- **Espace Administration** :
  - `admin/` : Dashboard de gestion avec KPIs, gestion des devis, bordereaux imprimables, filtres par lot.
  - `api/` : API PHP pour le traitement des formulaires et l'enregistrement des devis.
- **Styles & Scripts** :
  - `styles.css` : Design system corporate moderne (palette TG2D, typographie Inter).
  - `app.js` : Interactions dynamiques, modales et navigation responsive.

---

## 🚀 Déploiement en Production

Le site est hébergé sur **NindoHost** (`tg2d.ma`) et peut être déployé via :
1. **GitHub Actions** (`.github/workflows/deploy.yml`) lors d'un push sur `main`.
2. **cPanel Git Version Control** via `.cpanel.yml`.
