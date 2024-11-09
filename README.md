# Projet GoodFood

**Projet réalisé par :** BABACHANAKH Kateryna groupe S3A

**Lien vers le dépôt :** 

## Description du projet
---

## Table des matières

- [Modélisation des données du système de restauration GOODFOOD](#modélisation-des-données)
- [Installation et configuration](#installation-et-configuration)
- [Structure du projet](#structure-du-projet)

---

##  Modélisation des données du système de restauration GOODFOOD

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

