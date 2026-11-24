# Guide de développement

## 1. Git & Gestion des branches

### 1.1. Synchroniser avec la dernière version de `main`

Pour récupérer les derniers changements et continuer votre travail sur la dernière version :

1. Allez sur GitHub et sélectionnez la branche `origin/main`.
2. Ouvrez l'onglet **Source Control** dans VS Code.
3. Cliquez sur **Sync Changes** pour synchroniser les modifications.

### 1.2. Supprimer les anciennes branches

Pour supprimer les branches qui ont déjà été mergées ou qui ne sont plus utilisées :

1. Ouvrez **Source Control** dans VS Code.
2. Cliquez sur les trois petits points (`...`) à côté de **Changes**.
3. Ouvrez le menu **Branch**.
4. Cliquez sur **Delete Branch**.
5. Sélectionnez les branches à supprimer.

> **Attention :** vérifiez que les branches sont bien mergées ou qu'elles ne contiennent plus de travail nécessaire avant de les supprimer.

---

## 2. Vérification du code avant un push

Avant de pousser vos modifications, exécutez les commandes suivantes pour vérifier et corriger le code.

### 2.1. Vérification avec VP

```bash
npx vp check --fix
```

### 2.2. Formatage avec Laravel Pint

```bash
.\vendor\bin\pint.bat
```

---

## 3. Commandes Laravel (Terminal Laragon)

### 3.1. Génération des fichiers

| Élément    | Commande                                      |
| ---------- | --------------------------------------------- |
| Controller | `php artisan make:controller NomController`   |
| Model      | `php artisan make:model NomModel`             |
| Factory    | `php artisan make:factory NomFactory`         |
| Migration  | `php artisan make:migration create_nom_table` |
| Request    | `php artisan make:request NomRequest`         |

### 3.2. Générer plusieurs éléments simultanément

Créer un Model avec sa Factory, sa Migration et son Controller :

```bash
php artisan make:model NomModel -mfc
```

Créer un Model avec sa Factory, sa Migration, son Controller et ses Requests :

```bash
php artisan make:model NomModel -mfcr
```

---

## 4. Configuration de l'environnement

### 4.1. Créer le fichier `.env`

Copier le fichier `.env.example` vers `.env` :

```bash
copy .env.example .env
```

### 4.2. Générer la clé de l'application

```bash
php artisan key:generate
```

---

## 5. Base de données & Migrations

### 5.1. Lancer les migrations

```bash
php artisan migrate
```

### 5.2. Réinitialiser la base de données

Supprimer toutes les tables et relancer les migrations :

```bash
php artisan migrate:fresh
```

Réinitialiser la base de données et exécuter les Seeders :

```bash
php artisan migrate:fresh --seed
```

> **Attention :** `migrate:fresh` supprime toutes les tables de la base de données. Toutes les données existantes seront perdues.

---

## 6. Commandes Laravel Utiles

### 6.1. Gestion du cache

Vider le cache de configuration :

```bash
php artisan config:clear
```

Vider tous les caches :

```bash
php artisan optimize:clear
```

### 6.2. Gestion des routes

Afficher la liste des routes de l'application :

```bash
php artisan route:list
```
