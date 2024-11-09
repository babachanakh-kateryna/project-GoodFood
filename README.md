# Projet GoodFood

**Projet réalisé par :** BABACHANAKH Kateryna groupe S3A

**Lien vers le dépôt :** 

## Description du projet

Le projet GoodFood vise à modéliser et implémenter une base de données pour une chaîne de restaurants nommée GOODFOOD, qui cherche à informatiser son système de gestion pour améliorer la qualité de service. Ce système permettra de gérer les commandes, les tables, les serveurs et les plats de manière efficace.


---

## Table des matières

- [Modélisation des données du système](#modélisation-des-données-du-système)
- [Script de base de données](#script-de-base-de-données)
- [Installation et configuration](#installation-et-configuration)
- [Structure du projet](#structure-du-projet)

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

## Script de base de données
   
Le script goodfood.sql dans le dossier conf contient toutes les commandes SQL nécessaires pour créer et remplir la base de données avec les tables et données nécessaires. Le script goodfood_triggers.sql dans le dossier conf contient les deux triggers

