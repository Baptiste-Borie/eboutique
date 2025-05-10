# 🎬 Swatch Series – Boutique de Films (Symfony)

Bienvenue sur **Swatch Series**, une application Symfony 6 permettant d’explorer, gérer et commander des films au format physique.

🔗 Le site est en ligne ici : [https://borie.alwaysdata.net/](https://borie.alwaysdata.net/)

---

## 🚀 Fonctionnalités

### 🎥 Côté utilisateur

| Fonctionnalité                                            | État  |
| --------------------------------------------------------- | ----- |
| 🔍 Parcourir les films par catégorie                      | ✅ OK |
| 🛒 Ajouter des films au panier avec gestion des quantités | ✅ OK |
| ✅ Confirmation de commande avec formulaire d'adresse     | ✅ OK |
| 💾 Adresse enregistrée dans le profil utilisateur         | ✅ OK |
| 👤 Page "Mon compte" modifiable                           | ✅ OK |
| 🛑 Gestion des erreurs utilisateur (ex: panier vide)      | ✅ OK |

### 🛠️ Côté administrateur

| Fonctionnalité                            | État  |
| ----------------------------------------- | ----- |
| 📁 CRUD des films                         | ✅ OK |
| 📂 CRUD des catégories                    | ✅ OK |
| 👥 Affichage des utilisateurs et rôles    | ✅ OK |
| 🔁 Promotion/rétrogradation des rôles     | ✅ OK |
| 🗑️ Suppression sécurisée d’un utilisateur | ✅ OK |
| 🔐 Sécurité des routes (ROLE_ADMIN)       | ✅ OK |
| 🧪 CSRF sur actions sensibles             | ✅ OK |

---

## 🧱 Technologies utilisées

- **Symfony 6**
- **Doctrine ORM**
- **Twig**
- **Bootstrap 5.3**
- **MySQL**
- **ImportMap / Webpack Encore**

---

## ⚙️ Installation en local

1. Clone du projet :

```bash
git clone https://github.com/Baptiste-Borie/eboutique.git
cd eboutique
```

## 👤 Comptes de test

Email Mot de passe Rôle
admin@boutique.fr root123 ROLE_ADMIN
LaBete@gmail.fr azerty ROLE_USER
