-- Création de la table "CONTACT"
CREATE TABLE CONTACT (
    id_contact INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    sujet VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    date_contact DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'Non lu'
);

-- Création de la table "CLIENT"
CREATE TABLE CLIENT (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    adresse_livraison TEXT
);

-- Création de la table "CATEGORIE"
CREATE TABLE CATEGORIE (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL
);

-- Création de la table "PRODUIT" (dépend de CATEGORIE)
CREATE TABLE PRODUIT (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom_produit VARCHAR(150) NOT NULL,
    description_produit TEXT,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    stocks_disponibles INT DEFAULT 0,
    id_categorie INT,
    CONSTRAINT fk_produit_categorie 
        FOREIGN KEY (id_categorie) REFERENCES CATEGORIE(id_categorie)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Création de la table "COMMANDE" (dépend de CLIENT)
CREATE TABLE COMMANDE (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut_commande VARCHAR(50),
    prix_total DECIMAL(10, 2),
    id_client INT,
    CONSTRAINT fk_commande_client 
        FOREIGN KEY (id_client) REFERENCES CLIENT(id_client)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Création de la table de liaison "CONTENIR" (Relation n,n)
CREATE TABLE CONTENIR (
    id_commande INT,
    id_produit INT,
    quantite INT NOT NULL,
    PRIMARY KEY (id_commande, id_produit),
    CONSTRAINT fk_contenir_commande 
        FOREIGN KEY (id_commande) REFERENCES COMMANDE(id_commande)
        ON DELETE CASCADE,
    CONSTRAINT fk_contenir_produit 
        FOREIGN KEY (id_produit) REFERENCES PRODUIT(id_produit)
        ON DELETE CASCADE
);

-- Création de la table "AVIS"
CREATE TABLE AVIS (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    id_produit INT NOT NULL,
    note INT NOT NULL,
    commentaire TEXT NOT NULL,
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_avis_client
        FOREIGN KEY (id_client) REFERENCES CLIENT(id_client)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_avis_produit
        FOREIGN KEY (id_produit) REFERENCES PRODUIT(id_produit)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT uq_avis_client_produit UNIQUE (id_client, id_produit)
);
