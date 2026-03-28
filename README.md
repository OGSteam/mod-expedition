# mod-expedition (eXpedition)

[![License: GPL-2.0](https://img.shields.io/badge/License-GPL%202.0-blue.svg)](https://opensource.org/licenses/GPL-2.0)

Module OGSpy permettant de suivre et d'analyser vos expéditions dans OGame.

## Description

**eXpedition** est un module pour [OGSpy](https://github.com/ogsteam/ogspy) qui enregistre et classe automatiquement vos rapports d'expédition. Il s'intègre avec le plugin **Xtense 2** pour importer les rapports directement depuis votre navigateur.

### Types d'expéditions suivis

| Type | Description |
|------|-------------|
| Ressources | Métal, Cristal, Deutérium ou Antimatière récoltés |
| Vaisseaux | Flotte récupérée lors de l'expédition |
| Marchand | Visite d'un représentant marchand |
| Attaques | Rencontres avec des pirates ou des espèces inconnues |
| Items | Objets obtenus (boosters de ressources, KRAKEN, DETROID, NEWTRON) |
| Nul | Expéditions sans résultat notable |

### Fonctionnalités

- **Statistiques** : visualisation de vos résultats d'expédition par période et par type
- **Hall of Fame** : classements des meilleurs joueurs (ressources, vaisseaux, cumul)
- **Détail** : consultation du détail de chaque expédition avec filtrage par date
- **Import automatique** : intégration avec Xtense 2 pour un import automatique des rapports

## Prérequis

- PHP >= 7.2.0
- OGSpy >= 3.3.6
- Module Xtense 2 (recommandé pour l'import automatique)

## Installation

1. Téléchargez la dernière version depuis la [page des releases](https://github.com/ogsteam/mod-expedition/releases).
2. Décompressez l'archive dans le dossier `mod/` de votre installation OGSpy.
3. Depuis le panneau d'administration OGSpy, installez le module **eXpedition**.
4. Assurez-vous que le module Xtense 2 est installé pour profiter de l'import automatique des rapports.

## Mise à jour

Depuis le panneau d'administration OGSpy, lancez la mise à jour du module **eXpedition**. Le script `update.php` gère les migrations de base de données entre les versions.

## Utilisation

Une fois installé et activé, le module est accessible via le menu **Expeditions** de votre OGSpy. Les rapports d'expédition sont importés automatiquement grâce au plugin Xtense 2 installé dans votre navigateur.

## Support

- **Issues** : [GitHub Issues](https://github.com/ogsteam/mod-expedition/issues)
- **Discord** : [OGSteam Discord](https://discord.gg/Azcb67b)

## Auteurs

- **DarkNoon** – Lead Developer – [GitHub](https://github.com/darknoon29)

## Licence

Ce projet est sous licence [GPL-2.0](https://opensource.org/licenses/GPL-2.0).
