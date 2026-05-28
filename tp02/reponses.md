# Réponses aux Questions de Réflexion — TP 02 POO PHP
## Spécialité BTS SIO — Option SLAM

Ce document regroupe les réponses détaillées et pédagogiques aux questions de réflexion posées lors du TP 02. Elles mettent en lumière les concepts clés de la Programmation Orientée Objet (POO) indispensables pour vos examens et vos oraux de projet.

---

### 📖 ÉTAPE 1 — Les Interfaces

#### 1. Pourquoi utiliser une interface plutôt qu'une classe abstraite pour `Exportable` ?
* **Indépendance de la hiérarchie (Découplage) :** Une interface définit un **contrat de comportement** sans imposer de lien de parenté. Une classe `Commande`, `Utilisateur` ou `Facture` pourrait avoir besoin d'être exportée sans pour autant hériter de la classe `Produit`. 
* **Pas d'héritage multiple en PHP :** En PHP, une classe ne peut hériter que d'une seule classe parente (`extends`), mais elle peut implémenter une infinité d'interfaces (`implements`). Utiliser une interface pour `Exportable` laisse la possibilité à nos classes de faire partie d'une autre hiérarchie d'héritage tout en restant exportables.

#### 2. Que se passerait-il si une classe `implements Exportable` mais n'implémentait pas `exporter()` ?
* **Erreur fatale de compilation / exécution :** PHP lèvera immédiatement une erreur fatale (`Fatal Error`) lors de l'exécution ou de l'analyse du script :
  > *Fatal error: Class X contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (Exportable::exporter)...*
* **Règle d'or :** Toute classe concrète (non abstraite) signant un contrat avec une interface s'engage obligatoirement à implémenter l'intégralité des méthodes de cette interface avec les mêmes signatures (types d'arguments et de retour).

---

### 🧱 ÉTAPE 2 — La Classe Abstraite `Produit`

#### 1. Pourquoi les attributs sont-ils protégés (`protected`) et non privés (`private`) ?
* **L'accessibilité dans la descendance :** 
  * Un attribut **`private`** n'est visible et utilisable *que* dans la classe exacte où il est déclaré. Les classes filles n'y ont pas accès directement.
  * Un attribut **`protected`** reste invisible depuis l'extérieur de la classe (préservant ainsi le principe d'encapsulation), mais devient accessible directement par les classes qui en héritent (`LivrePhysique`, etc.).
* **Application concrète :** Dans `LivrePhysique`, nous calculons le prix final en faisant `$this->prix + $this->fraisPort`. Si `$prix` avait été `private` dans `Produit`, PHP aurait refusé l'accès direct et nous aurions dû passer par un getter (`$this->getPrix()`).

#### 2. Pourquoi `exporter()` peut-elle appeler `getDescription()` alors qu'elle n'est pas encore définie dans `Produit` ?
* **Contrat d'implémentation (Méthodes Abstraites) :** La méthode `getDescription()` est déclarée comme `abstract` dans `Produit`. 
* **Garantie du compilateur :** En déclarant cette méthode abstraite, la classe `Produit` garantit au moteur PHP que toute sous-classe instanciable possédera obligatoirement une implémentation concrète de `getDescription()`. PHP accepte donc d'appeler cette méthode dans la classe parente car l'objet réel sur lequel elle s'exécutera sera forcément une instance d'une classe fille qui l'aura implémentée. C'est l'essence même de la liaison dynamique.

#### 3. Essayez d'instancier `Produit` directement. Que se passe-t-il ? Pourquoi ?
* **Erreur fatale :** PHP renvoie l'erreur suivante :
  > *Fatal error: Cannot instantiate abstract class Produit...*
* **Explication :** Une classe abstraite représente un concept général ou incomplet (ici, l'idée abstraite d'un "produit" sans type précis). Elle sert de modèle d'architecture pour d'autres classes. Comme elle contient des déclarations de méthodes sans corps (méthodes abstraites), l'instancier directement n'aurait pas de sens car ces méthodes ne pourraient pas être exécutées.

---

### 🌿 ÉTAPE 3 — Les Classes Concrètes (Spécialisation)

#### 1. Que se passe-t-il si vous oubliez d'implémenter l'une des méthodes abstraites dans une classe fille ?
* **Erreur fatale :** Le moteur PHP refusera de charger la classe fille et lèvera une erreur indiquant que la classe fille doit implémenter la méthode héritée manquante, ou être elle-même déclarée comme abstraite.

#### 2. Pouvez-vous ajouter des méthodes supplémentaires dans `LivreAudio` sans affecter les autres classes ?
* **Oui, absolument :** C'est le principe de la **spécialisation**. L'héritage permet à une classe fille d'enrichir le comportement hérité. L'ajout d'une méthode (ex: `getNarrateur()`) ou d'un attribut (ex: `$duree`) spécifique à `LivreAudio` n'affecte en rien les classes sœurs `LivrePhysique` ou `LivreNumerique`, car l'héritage est unidirectionnel (du parent vers l'enfant).

---

### 🔀 ÉTAPE 4 — Polymorphisme et Catalogue

#### 1. Expliquez en quoi la boucle `foreach` sur `$catalogue` illustre le polymorphisme.
* **Définition du Polymorphisme :** C'est la capacité pour un même appel de méthode à produire des comportements différents selon la classe réelle de l'objet qui le reçoit.
* **Fonctionnement dans la boucle :**
  ```php
  foreach ($catalogue as $produit) {
      echo $produit->getPrixFinal();
  }
  ```
  Ici, `$produit` est traité de manière uniforme comme un objet de type `Produit`. Cependant :
  * Si `$produit` est en réalité un `LivrePhysique`, `$produit->getPrixFinal()` ajoute les frais de port.
  * Si c'est un `LivreNumerique`, il applique la remise de 15%.
  * Si c'est un `LivreAudio`, il applique la majoration de 10%.
* **Avantage :** Nous n'avons pas besoin de faire de tests de type laborieux (comme `if ($produit instanceof LivrePhysique)`) ; PHP résout l'appel dynamiquement à l'exécution.

#### 2. Que faudrait-il modifier pour ajouter un nouveau type (ex : `Magazine`) ? Que prouve-t-il sur la qualité de la conception ?
* **Facilité d'extension :** Il suffirait simplement de créer une nouvelle classe `Magazine` qui hérite de `Produit` et d'y coder les 3 méthodes abstraites.
* **Zéro modification sur le reste du code :** Nous n'avons pas besoin de modifier la classe parente `Produit`, ni les autres classes filles, ni le code de boucle, de tri ou d'exportation dans `catalogue.php`.
* **Preuve de qualité :** Cela démontre que notre conception respecte le **principe Ouvert/Fermé (Open/Closed Principle - OCP)** du SOLID : notre code est *ouvert à l'extension* (on peut ajouter des types de produits facilement) mais *fermé à la modification* (on ne modifie pas le code existant pour ce faire). C'est le gage d'une application stable et évolutive.
