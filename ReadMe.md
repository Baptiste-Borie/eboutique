# 🎬 Swatch Series – Boutique de Films (Symfony)

Bienvenue sur **Swatch Series**, une application Symfony 6 permettant d’explorer, gérer et commander des films au format physique.  
Elle propose une gestion avancée des utilisateurs, des rôles et un panier complet avec confirmation de commande.

---

## 🚀 Fonctionnalités

### 🎥 Côté utilisateur

- 🔍 Parcourir les films par catégorie
- 🛒 Ajouter des films au panier avec gestion des quantités
- ✅ Confirmation de commande avec formulaire d'adresse
- 💾 Possibilité d’enregistrer une adresse dans son profil

### 🛠️ Côté administrateur

- 📁 CRUD des films et des catégories
- 👥 Gestion des utilisateurs :
  - Affichage des rôles
  - Promotion / rétrogradation `ROLE_ADMIN`
  - Suppression d’un utilisateur
- 🔐 Sécurité :
  - Accès restreint à certaines routes selon les rôles
  - Protection CSRF sur les actions sensibles

---

## 🧱 Technologies

- **Symfony 6**
- **Doctrine ORM**
- **Twig**
- **Bootstrap 5.3**
- **MySQL**

---

## ⚙️ Installation

1. Clone du projet :

```bash
git clone https://github.com/Baptiste-Borie/eboutique.git
cd eboutique
```
