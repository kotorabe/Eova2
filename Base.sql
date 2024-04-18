CREATE DATABASE eova;
ALTER DATABASE eova OWNER TO eova_manager;

insert into users(name, email, password, created_at, updated_at) values('Kouto', 'kouto@gmail.com', 'admin1234', now(), now());

CREATE TABLE utilisateurs(
    id_user,
    nom,
    prenom,
    numero,
    email,
    password
);

CREATE TABLE devis(
    id_devis,
    id_utilisateur,
    etat,
    reduction,
    accept,
    fini
);
;;etat = 1 // accept = 0 // fini = 0 Créer
;;etat = 2 // accept = 0 // fini = 0 Demande envoyer
;;etat = 3 // accept = 0 // fini = 0 Devis envoyer
;;etat = 3 // accept = 1 // fini = 0 Devis accepter
;;etat = 0 // accept = 0 // fini = 0 Devis à discuter(Reduction)
;;etat = 0 // accept = 0 // fini = 1 Devis Refuser

CREATE TABLE addresses(
    id,
    id_devis,
    recuperation,
    acces_recup,
    coord_recup,
    livraison,
    accces_livr,
    coord_livr,
    date_demenagement
)

CREATE TABLE type_objets(
    id,
    nom
);


CREATE TABLE tailles(
    id,
    nom('L,M,X,Xl,XXL')
);



CREATE TABLE objets(
    id,
    id_devis,
    id_taille,
    id_type
    nom,
    quantite,
    kilo,
    prix,
    total
);

CREATE TABLE equipes(
    id,
    email,
    nom,
    password,
    nombre_vhcl
);

CREATE TABLE livraisons(
    id,
    id_devis,
    id_equipe,
    date_livraison,
    img_recup,
    img_livr,
    etat,
    date_time,
);
//etat = 1 Planifier
//etat = 2 En route vers recuperation
//etat = 3 Commencer
//etat = 4 finir

INSERT INTO type_objets(nom) VALUES('Meuble'), ('Articles de decoration'), ('Equipements electroniques'), ('Articles de rangement');
INSERT INTO tailles(nom) VALUES ('L'),('M'),('X'),('XL'),('XXL');

--Lister les objets
CREATE OR REPLACE VIEW v_list_objet AS SELECT objets.id, objets.nom, objets.quantite, objets.kilo, objets.prix, objets.total, objets.updated_at as update_objet, devis.id as id_devis , devis.id_utilisateur, devis.etat, devis.fini, devis.accept, devis.updated_at as update_devis,
tailles.id as id_taille, tailles.nom as taille, type_objets.id as id_type, type_objets.nom as type_objet FROM objets JOIN devis ON devis.id = objets.id_devis JOIN tailles ON tailles.id = objets.id_taille
JOIN type_objets ON type_objets.id = objets.id_type;

--avoir la liste des devis avec les clients
CREATE OR REPLACE VIEW v_list_devis AS SELECT devis.id, devis.id_utilisateur, devis.etat, devis.accept, devis.fini, devis.reduction, devis.updated_at, devis.deleted_at, utilisateurs.nom,
utilisateurs.prenom, utilisateurs.email, utilisateurs.numero, addresses.date_demenagement, addresses.recuperation, addresses.livraison, addresses.acces_recup, addresses.acces_livr, addresses.coord_recup, addresses.coord_livr
 FROM devis JOIN utilisateurs ON utilisateurs.id = devis.id_utilisateur JOIN addresses ON addresses.id_devis = devis.id;

-- Avoir la liste des equipes avec le poids supporter
CREATE OR REPLACE VIEW v_list_equipe_categorie AS SELECT equipes.id, equipes.nom, equipes.email, equipes.etat, categories.id as id_categorie, categories.nom as categorie, categories.poids_total FROM equipes
JOIN categories ON categories.id = equipes.id_categorie;

-- Avoir les planning
CREATE OR REPLACE VIEW v_list_planning_equipe AS SELECT livraisons.id, livraisons.id_devis, livraisons.id_equipe, livraisons.date_livraison, livraisons.img_recup, livraisons.img_livr, livraisons.etat, equipes.nom as equipe, equipes.email as email_equipe,
devis.id_utilisateur, devis.etat as etat_devis, devis.accept, devis.fini, devis.created_at, devis.updated_at, devis.reduction, utilisateurs.nom as client_nom, utilisateurs.prenom as client_prenom, utilisateurs.email FROM livraisons JOIN equipes ON equipes.id = livraisons.id_equipe
JOIN devis ON devis.id = livraisons.id_devis JOIN utilisateurs ON utilisateurs.id = devis.id_utilisateur;


SELECT *
FROM v_list_objet
ORDER BY
    CASE WHEN prix = 0 THEN 0 ELSE 1 END,
    taille;
