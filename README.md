# Projet GoodFood

**Projet réalisé par :** BABACHANAKH Kateryna groupe S3A

**Lien vers le dépôt :** https://github.com/babachanakh-kateryna/project-GoodFood.git

## Description du projet

GOODFOOD est un système d’information développé pour améliorer la gestion et le service dans les restaurants de la société GOODFOOD, présente dans plusieurs villes et aéroports européens. Ce projet vise à informatiser et structurer les informations essentielles liées aux commandes, aux serveurs, aux tables et aux plats, afin d’améliorer la prestation de services.

Ce système est conçu pour :

- Gérer les informations sur les salles, tables, et clients.
- Suivre les commandes et les paiements.
- Effectuer des analyses et afficher des rapports de service (plats servis, chiffre d’affaire, etc.).
  
L’application web est développée en PHP et utilise MySQL pour la gestion des données.

---

## Table des matières

- [Modélisation des données du système](#modélisation-des-données-du-système)
- [Fonctionnalités](#fonctionnalités)
- [Script de base de données](#script-de-base-de-données)
- [Installation et configuration](#installation-et-configuration)
- [Structure du projet](#structure-du-projet)
- [Exemples d’utilisation](#exemples-dutilisation)

---

##  Modélisation des données du système

#### **Dépendances Fonctionnelles :** 

numCom → numTab, datCom, nbPers, datPaie, modPaie, montCom

numPlat → libelle, type, prixUnit

numServ → nomServ, grade

numTab → nbPlac

numCom, numPlat → quantite

numTab, datAff → numServ

#### **Les clés minimales :** 

numCom, numPlat - ce sont les seuls clés qui ne dépendent d’aucun autre attribut 

####  **Vérification 3NF pour chaque table :**
  
  1. Commande (numcom, numtab, datcom, nbpers, datpaie, modpaie, montcom)

     - Dépendances fonctionnelles : numcom → numtab, datcom, nbpers, datpaie, modpaie, montcom
     - Clé primaire : numcom
     - Analyse : Tous les attributs dépendent uniquement de numcom. Pas de dépendance transitive entre les attributs non-clés. Table en 3NF.

  2. Plat (numplat, libelle, type, prixunit)
  
     - Dépendances fonctionnelles : numplat → libelle, type, prixunit
     - Clé primaire : numplat
     - Libelle, type, et prixunit dépendent directement de numplat. Table en 3NF.
       
  3. Serveur (numserv, nomserv, grade)

     - Dépendances fonctionnelles : numserv → nomserv, grade
     - Clé primaire : numserv
     - Nomserv et grade dépendent uniquement de numserv. Table en 3NF.
  
  4. Tabl (numtab, nbplace)

     - Dépendances fonctionnelles : numtab → nbplace
     - Clé primaire : numtab
     - Nbplace dépend uniquement de numtab. Table en 3NF.

  5. Contient (numcom, numplat, quantite)

     - Dépendances fonctionnelles : (numcom, numplat) → quantite
     - Clé primaire : (numcom, numplat)
     - Quantite dépend uniquement des clés composites numcom et numplat. Table en 3NF.
  
  6. Affecter (numtab, dataff, numserv)

     - DF: (numtab, dataff) → numserv
     - Clé primaire : (numtab, dataff)
     - Numserv dépend directement des clés composites numtab et dataff. Table en 3NF.

---

## Fonctionnalités

L’application propose plusieurs fonctionnalités accessibles via un menu de navigation :

| Fonctionnalité                     | Description                                                                   | 
|------------------------------------|-------------------------------------------------------------------------------|
| Détermination des plats servis | Affiche la liste des plats (numéro et nom) qui ont été servis dans une période donnée. | 
| Affichage des plats jamais commandés | Affiche la liste des plats (numéro et nom) qui n'ont jamais été commandés pendant une période spécifique. | 
| Liste des serveurs par table | Montre les serveurs (nom et date) ayant servi une table particulière dans une période donnée. | 
| Rapport du chiffre d’affaire par serveur | Affiche, de manière décroissante, le chiffre d'affaire et le nombre de commandes réalisés par chaque serveur dans une période donnée. | 
| Liste des serveurs sans chiffre d’affaire | Affiche les serveurs qui n'ont pas réalisé de chiffre d'affaire durant une période donnée. | 
| Calcul du montant total d'une commande | Calcule et affiche le montant total d'une commande spécifique, et met à jour la table de commandes avec le montant calculé. | 


---

## Script de base de données
   
Le script goodfood.sql dans le dossier conf contient toutes les commandes SQL nécessaires pour créer et remplir la base de données avec les tables et données nécessaires. Le script goodfood_triggers.sql dans le dossier conf contient les deux triggers

---

## Installation et configuration

Prérequis:

- Serveur Apache avec PHP 7.4 ou supérieur.
- Serveur MySQL.

1. Importez le fichier SQL database.sql dans votre serveur MySQL pour créer les tables, insérer les données initiales, et définir les triggers.
2. Configuration de la base de données : dans le fichier config.ini.

---

## Structure du projet

```
project-GoodFood/
├── conf/
│   ├── db.config.ini              # Fichier de configuration pour la base de données
│   ├── goodfood.sql               # Script SQL pour créer les tables et insérer les données
│   └── goodfood_triggers.sql      # Script SQL pour créer les triggers dans la base de données
├── css/
│   └── styles.css                 # Feuille de styles pour le design de l'application
├── src/
│   ├── classes/
│   │   ├── action/                # Dossier contenant les classes d'actions pour chaque fonctionnalité
│   │   │   ├── Action.php                       # Classe abstraite de base pour les actions
│   │   │   ├── ActionCalculMontantCommande.php  # Action pour calculer le montant d'une commande
│   │   │   ├── ActionChiffreAffaireServeurs.php # Action pour afficher le chiffre d'affaire par serveur
│   │   │   ├── ActionPlatsNonCommandes.php      # Action pour afficher les plats jamais commandés
│   │   │   ├── ActionPlatsServis.php            # Action pour afficher les plats servis
│   │   │   ├── ActionServeursParTable.php       # Action pour afficher les serveurs par table
│   │   │   ├── ActionServeursSansChiffreAffaire.php # Action pour afficher les serveurs sans chiffre d'affaire
│   │   │   └── DefaultAction.php                # Action par défaut (page d'accueil)
│   │   ├── dispatch/
│   │   │   └── Dispatcher.php                   # Classe pour gérer le routage des actions
│   │   └── repository/
│   │       └── GoodFoodRepository.php           # Classe pour gérer les opérations sur la base de données
├── vendor/                                      # Dossier contenant les dépendances gérées par Composer
├── index.php                                    # Point d'entrée principal de l'application
└── README.md                                    # Fichier README contenant la documentation du projet
```


---

## Exemples d’utilisation

Voici un exemple pour récupérer les plats servis dans une période :

Étapes :
  1. Accédez à /index.php?action=getPlatsServis.
  2. Sélectionnez les dates de début et de fin.
  3. Cliquez sur "Voir les plats servis".

Résultat attendu : Une liste de plats avec leurs numéros et noms, servis dans la période spécifiée
