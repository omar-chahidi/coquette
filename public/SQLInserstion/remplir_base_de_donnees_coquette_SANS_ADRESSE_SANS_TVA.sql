use COQUETTE;

SET FOREIGN_KEY_CHECKS = 0;

-- Contenu de la table pays
truncate table pays;
INSERT INTO pays (code, nom) VALUES
('033', 'France'),	('034', 'Espagne'),	('035', 'Angleterre'),	('039', 'Italie');

-- Contenu de la table ville
truncate table ville;
INSERT INTO ville (`code_postale`, `nom_ville`, `pays_id`) VALUES
('13000', 'Marseille', '033'),	('24200', 'Sarlat', '033'),		('24300', 'Carsac', '033'),		('24400', 'Aillac', '033'),		
('59000', 'Lille', '033'),		('69000', 'LION', '033'),		('75011', 'Paris 11', '033'),	('75012', 'Paris 12', '033'),
('75019', 'Paris XIX', '033'),	('78000', 'Versailles', '033'),	('94100', 'Vincennes', '033'),	('94200', 'St Mandé', '033'),
('99391', 'ROME', '039'),		('99392', 'MILAN', '039');

-- Contenu de la table utilisateur
truncate table utilisateur;
INSERT INTO utilisateur (id, nom_utilisateur, prenom, adresse, date_naissance, ville_id, telephone, email, password, date_ajout, activation) VALUES
(1, 'Buguet', 'Pascal', '18 Square Jean Lurçat', '1955-10-03', '1', '0601370000', 'af@free.fr', '123', CURDATE(), 'OUI'),
(2, 'Buguet', 'MJ', '18 Square Jean Lurçat', '1948-08-22', '2', '0700000031', '2@free.fr', '123', CURDATE(), 'OUI'),
(3, 'Fassiola', 'Annabelle', '18 Square Jean Lurçat', '1985-05-10', '3', '0700000031', '3@free.fr', '123A', CURDATE(), 'OUI'),
(4, 'Roux', 'Françoise', '18 Square Jean Lurçat', '1950-10-10', '4', '0901370031', '4@free.fr', '123E', CURDATE(), 'OUI'),
(5, 'Tintin', 'Albert', '18 Square Jean Lurçat', '1950-10-10', '5', '010000031', '5@free.fr', '123R', CURDATE(), 'OUI'),
(6, 'Sordi', 'Alberto', '18 Square Jean Lurçat', '1950-10-10', '6', '010000031', '6@free.fr', '123F', CURDATE(), 'OUI'),
(7, 'Muti', 'Ornella', '18 Square Jean Lurçat', '1950-10-10', '7', '010000031', '7@free.fr', '123F', CURDATE(), 'OUI'),
(8, 'Milou', 'Le chien', '18 Square Jean Lurçat', '1950-10-10', '8', '0101370031', '8@free.fr', '123S', CURDATE(), 'OUI'),
(9, 'Tournesol', 'Bruno', '18 Square Jean Lurçat', '1950-10-10', '9', '0700000031', '9@free.fr', '123Q', CURDATE(), 'OUI'),
(10, 'Roberts', 'Julia', '18 Square Jean Lurçat', '1965-10-03', '10', '0700000031', '10@free.fr', '1234D', CURDATE(), 'OUI'),
(11, 'CHAHIDI', 'Omar', '18 Square Jean Lurçat', '1984-04-14', '11', '0601370031', 'oci@gmail.com', '123', CURDATE(), 'OUI');
  
-- Contenu de la table commandes
truncate table commande;
INSERT INTO commande (id, dat_commande, utilisateur_id) VALUES
(1, '2005-10-03', 1),	(2, '2005-10-10', 2),	(3, '2005-11-01', 1),	(4, '2000-11-01', 1),	(5, '2000-12-10', 2),
(6, CURDATE(), 1),		(7, CURDATE(), 2),		(8, CURDATE(), 5),		(9, CURDATE(), 4),		(10, CURDATE(), 11); 

-- Contenu de la table facture
truncate table facture;
INSERT INTO facture (date_facture, commande_id) VALUES
('2005-10-03', 1),	('2005-10-10', 2),	('2005-11-01', 3),	('2000-11-01', 4),	('2000-12-10', 5),
(CURDATE(), 6),		(CURDATE(), 7),		(CURDATE(), 8),		(CURDATE(), 9),		(CURDATE(), 10); 

-- Contenu de la table bons_de_livraison
truncate table bon_de_livraison;
INSERT INTO bon_de_livraison (date_bon_livraison, commande_id) VALUES
('2005-10-03', 1),	('2005-10-10', 2),	('2005-11-01', 3),	('2000-11-01', 4),	('2000-12-10', 5),
(CURDATE(), 6),		(CURDATE(), 7),		(CURDATE(), 8),		(CURDATE(), 9),		(CURDATE(), 10);

-- Contenu de la table categorie
truncate table categorie;
insert into categorie (id, titre) VALUES	(1, 'HOMME'),	(2, 'FEMME'),	(3, 'ENFANT');

-- Contenu de la table domaine
truncate table domaine;
insert into domaine (id, titre) VALUES	(1, 'CHAUSSURES'),	(2, 'VETÊMENTS'),	(3, 'ACCESSOIRES');

-- Contenu de la table marque
truncate table marque;
insert into marque (id, titre) VALUES
(1, 'NIKE'),	(2, 'ADIDAS'),	(3, 'ETAM'),	(4, 'LACOSTE'),	(5, 'JULES'),	(6, 'ORCHESTRA');


-- Contenu de la table article
truncate table article;
insert into article (id, titre, prix, remise, marque_id, categorie_id, domaine_id, description, mot_recherche, created_at) VALUES
-- HOMME - CHAUSSURE
(1, 'React Infinity Run Flyknit', 90.50, 10, 1, 1, 1, 'React Infinity Run Flyknit', 'homme chassure nike', CURDATE()),	
(2, 'Jordan Max Aura', 35.50, 20, 1, 1, 1, 'Jordan Max Aura', 'homme chassure nike', CURDATE()),
(3, 'Sneakers Gripshot homme en toile texturée et synthétique', 200.50, 0, 4, 1, 1, 'Sneakers Gripshot homme en toile texturée et synthétique', 'homme chassure lacoste', CURDATE()),	
(4, 'CHAUSSURE NMD R1', 45.99, 30, 2, 1, 1, 'CHAUSSURE NMD R1', 'homme chassure adidas', CURDATE()),
-- HOMME - VETEMMENT	
(5, 'Sweatshirt a capuche Lacoste SPORT en molleton bicolore', 100, 40, 4, 1, 2, 'Sweatshirt a capuche Lacoste SPORT en molleton bicolore', 'homme vetemment lacoste', CURDATE()),
(6, 'Polo Lacoste slim fit en petit piqué uni', 30.99, 50, 4, 1, 2, 'Polo Lacoste slim fit en petit piqué uni', 'homme vetemment lacoste', CURDATE()),
(7, 'Veste de costume', 150.80, 60, 5, 1, 2, 'Veste de costume', 'homme vetemment jules', CURDATE()),	
(8, 'SWEAT SHIRT TREFOIL WARM UP CREW', 20.5, 70, 2, 1, 2, 'SWEAT SHIRT TREFOIL WARM UP CREW', 'homme vetemment adidas', CURDATE()),
-- HOMME - ACCESSOIRE
(9, 'sac', 100.30, 0, 5, 1, 3, 'sac', 'homme accessoire', CURDATE()),	
(10, 'Portefeuille homme horizontal en cuir marron  Marron Foncé', 20.30, 80, 5, 1, 3, 'Portefeuille homme horizontal en cuir marron  Marron Foncé', 'homme accessoire', CURDATE()),	
(11, 'Ceinture en fausse suedine', 10.50, 90, 1, 1, 3, 'Ceinture en fausse suedine', 'homme accessoire', CURDATE()),
-- FEMME - CHAUSSURE
(12, 'Sneakers T-Clip femme en cuir et daim', 100.77, 90, 4, 2, 1, 'Sneakers T-Clip femme en cuir et daim', 'femme chaussure lacoste', CURDATE()),
(13, 'AURE Sandales métallisées à talons', 60.66, 10, 3, 2, 1, 'AURE Sandales métallisées à talons', 'femme chaussure etam', CURDATE()),	
(14, 'CHAUSSURE SUPERSTAR', 200.50, 20, 2, 2, 1, 'CHAUSSURE SUPERSTAR', 'femme chaussure adidas', CURDATE()),
-- FEMME - VETEMMENT
(15, 'ROBE OFF THE SHOULDER', 100.30, 30, 2, 2, 2, 'ROBE OFF THE SHOULDER', 'femme vetement adidas', CURDATE()),	
(16, 'Robe polo Lacoste LIVE en piqué stretch avec pli épaule', 100.30, 0, 4, 2, 2, 'Robe polo Lacoste LIVE en piqué stretch avec pli épaule', 'femme vetement lacoste', CURDATE()),	
(17, 'Combinaison pantalon imprimée femme', 80.80, 40, 1, 2, 2, 'Combinaison pantalon imprimée femme', 'femme vetement nike', CURDATE()),
-- FEMME - ACCESSOIRE
(18, 'Sac à main sceau femme', 100.67, 0, 4, 2, 3, 'Sac à main sceau femme', 'femme accessoire', CURDATE()),
(19, 'Ceinture tressée dorée femme', 15.77, 50, 4, 2, 3, 'Ceinture tressée dorée femme', 'femme accessoire', CURDATE()),	
-- ENFANTS - CHAUSSURE
(20, 'Baskets basses', 88.99, 60, 6, 3, 1, 'Baskets basses', 'enfant chaussure orchestra', CURDATE()),
(21, 'Sneakers Masters Cup ado en cuir', 20.60, 0, 4, 3, 1, 'Sneakers Masters Cup ado en cuir', 'enfant chaussure lacoste', CURDATE()),	
(22, 'CHAUSSURE TENSOR', 30.30, 70, 2, 3, 1, 'CHAUSSURE TENSOR', 'enfant chaussure adidas', CURDATE()),	
-- ENFANTS - VETEMMENT
(23, 'pijama rose', 10.99, 80, 6, 3, 2, 'pijama rose', 'enfant vetemment orchestra', CURDATE()),
(24, 'Robe Fille à taille élastiquée en molleton de coton', 30.4, 90, 4, 3, 2, 'Robe Fille à taille élastiquée en molleton de coton', 'enfant vetemment lacoste', CURDATE()),
(25, 'PANTALON DE SURVÊTEMENT SST', 15.77, 10, 2, 3, 2, 'PANTALON DE SURVÊTEMENT SST', 'enfant vetemment adidas', CURDATE()),
-- ENFANTS - ACCESSOIRE
(26, 'ADDIDAS sacados', 10.38, 20, 2, 3, 3, 'ADDIDAS sacados', 'enfant accessoire', CURDATE()),
(27, 'Montre Enfant Lacoste 12_12 avec Bracelet Silicone', 50.50, 30, 4, 3, 3, 'Montre Enfant Lacoste 12_12 avec Bracelet Silicone', 'enfant accessoire', CURDATE()),
(28, 'Parapluie transparent avec Étoiles Mickey Disney printés', 10.99, 40, 4, 3, 3, 'Parapluie transparent avec Étoiles Mickey Disney printés', 'enfant accessoire', CURDATE())
;

-- Contenu de la table photo
truncate table photo;
insert into photo (id, titre_photo, master, article_id) VALUES
-- HOMME - CHAUSSURE
(1, 'NIKE_NOIRE__chaussure_de_running_react_infinity_run_flyknit.jpg', 1, 1),	
(2, 'NIKE_GRIS__chaussure_de_running_react_infinity_run_flyknit.jpg', 0, 1),	
(3, 'NIKE_BLANC__chaussure_de_running_react_infinity_run_flyknit.jpg', 0, 1),		
(4, 'NOIRE__BLANC__Jordan_Max_Aura.jpg', 1, 2),	
(5, 'BLANC__Jordan_Max_Aura.jpg', 0, 2),	
(6, 'BLANC__bis__Jordan_Max_Aura.jpg', 0, 2),
(7, '1_BLANC__Sneakers_Gripshot_homme_en_toile_texturee_et_syntheique.jpg', 1, 3),	
(8, '2_BLANC__Sneakers_Gripshot_homme_en_toile_texturee_et_synthetique.jpg', 0, 3),	
(9, '3_BLANC__Sneakers_Gripshot_homme_en_toile_texturee_et_synthetique.jpg', 0, 3),	
(10, 'BLANC_BIS__CHAUSSURE_NMD_R1.jpg', 1, 4),	
(11, 'BLANC_CHAUSSURE_NMD_R1.jpg', 0, 4),	
(12, 'NOIRE__CHAUSSURE_NMD_R1.jpg', 0, 4),	
(13, 'VERRE__CHAUSSURE_NMD_R1.jpg', 0, 4),		
-- HOMME - VETEMMENT
(14, 'blanc__Sweatshirt_à_capuche_Lacoste_SPORT_en_molleton_bicolore.jpg', 1, 5),
(15, 'gris__Sweatshirt_à_capuche_Lacoste_SPORT_en_molleton_bicolore.jpg', 0, 5),
(16, 'noire__Sweatshirt_à_capuche_Lacoste_SPORT_en_molleton_bicolore.jpg', 0, 5),
(17, 'rouge1___Sweatshirt_à_capuche_Lacoste_SPORT_en_molleton_bicolore.jpg', 0, 5),
(18, 'rouge2___Sweatshirt_à_capuche_Lacoste_SPORT_en_molleton_bicolore.jpg', 0, 5),
(19, 'rouge3___Sweatshirt_à_capuche_Lacoste_SPORT_en_molleton_bicolore.jpg', 0, 5),
(20, 'BLANC___Polo_Lacoste_slim_fit_en_petit_pique_uni.jpg', 1, 6),
(21, 'BLEU__Polo_Lacoste_slim_fit_en_petit_pique_uni.jpg', 0, 6),
(22, 'ROUGE__Polo_Lacoste_slim_fit_en_petit_pique_uni.jpg', 0, 6),
(23, 'BLEUE___Veste_de_costume.jpg', 1, 7),	
(24, 'BLEUE__BIS__Veste_de_costume.jpg', 0, 7),	
(25, 'GRIS___Veste_de_costume.jpg', 0, 7),	
(26, 'GRIS__BIS___Veste_de_costume.jpg', 0, 7),	
(27, 'BLANC__SWEAT_SHIRT_TREFOIL_WARM_UP_CREW.jpg', 1, 8),
(28, 'VERRE__SWEAT_SHIRT_TREFOIL_WARM_UP_CREW.jpg', 0, 8),
-- HOMME - ACCESSOIRE
(29, 'JULES_sac1.jpg', 1, 9),	
(30, 'JULES_sac2.jpg', 0, 9),	
(31, 'Portefeuille_homme_horizontal_en_cuir_1.jpg', 1, 10),	
(32, 'Portefeuille_homme_horizontal_en_cuir_2.jpg', 0, 10),	
(33, 'Ceinture_en_fausse_suedine___Bleu Marine__bis.jpg', 1, 11),
(34, 'Ceinture_en_fausse_suedine___Bleu_Marine.jpg', 0, 11),

-- FEMME - CHAUSSURE
(35, 'Sneakers_T_Clip_femme_en_cuir_et_daim_1.jpg', 1, 12),
(36, 'Sneakers_T_Clip_femme_en_cuir_et_daim_2.jpg', 0, 12),
(37, 'Sneakers_T_Clip_femme_en_cuir_et_daim_3.jpg', 0, 12),
(38, '1__AURE_Sandales_metallisees_a_talons.jpg', 1, 13),
(39, '2__AURE_Sandales_metallisees_a_talons.jpg', 0, 13),
(40, '3__AURE_Sandales_metallisees_a_talons.jpg', 0, 13),
(41, '2__adidas___ChaussureSuperstarBlanc.jpg', 1, 14),
(42, 'adidas___ChaussureSuperstarBlanc.jpg', 0, 14),

-- FEMME - VETEMMENT
(43, 'NOIRE__1___ROBE_OFF_THE_SHOULDER.jpg', 1, 15),
(44, 'NOIRE__2____ROBE_OFF_THE_SHOULDER.jpg', 0, 15),
(45, 'NOIRE__3____ROBE_OFF_THE_SHOULDER.jpg', 0, 15),
(46, 'VERRE__1____ROBE_OFF_THE_SHOULDER.jpg', 0, 15),
(47, 'BLEU_MARINE___Robe_polo_Lacoste_LIVE_en_pique_stretch_avec_pli_epaule.jpg', 1, 16),
(48, 'ROSE___Robe_polo_Lacoste_LIVE_en_pique_stretch_avec_pli_epaule.jpg', 0, 16),
(49, 'ROSE_1___Robe_polo_Lacoste_LIVE_en_pique_stretch_avec_pli_epaule.jpg', 0, 16),
(50, 'ROSE_2___Robe_polo_Lacoste_LIVE_en_pique_stretch_avec_pli_epaule.jpg', 0, 16),
(51, '1__Combinaison_pantalon_imprimee_femme.jpg', 1, 17),
(52, '2__Combinaison_pantalon_imprimee_femme.jpg', 0, 17),
(53, 'Combinaison_pantalon_imprimee_femme.jpg', 0, 17),
(54, 'NOIRE__Combinaison_pantalon_imprimee_femme.jpg', 0, 17),

-- FEMME - ACCESSOIRE
(55, 'ROSE__1__Sac_a_main_sceau_femme.jpg', 1, 18),
(56, 'ROSE__2__Sac_a_main_sceau_femme.jpg', 0, 18),
(57, 'ROSE__3__Sac_a_main_sceau_femme.jpg', 0, 18),
(58, 'ROSE__4__Sac_a_main_sceau_femme.jpg', 0, 18),
(59, 'ROSE__5__Sac_a_main_sceau_femme.jpg', 0, 18),
(60, '1___Ceinture_tressee_doree_femme.jpg', 1, 19),
(61, '2___Ceinture_tressee_doree_femme.jpg', 0, 19),
(62, '3___Ceinture_tressee_doree_femme.jpg', 0, 19),
(63, '4___Ceinture_tressee_doree_femme.jpg', 0, 19),

-- ENFANTS - CHAUSSURE
(64, 'Baskets_basses.jpg', 1, 20),
(65, 'BLEU___Baskets_basses.jpg', 0, 20),
(66, 'ROUGE___Baskets_basses.jpg', 0, 20),
(67, 'BLANC__1__Sneakers_Masters_Cup_ado_en_cuir.jpg', 1, 21),
(68, 'BLANC__2__Sneakers_Masters_Cup_ado_en_cuir.jpg', 0, 21),
(69, 'BLANC__3__Sneakers_Masters_Cup_ado_en_cuir.jpg', 0, 21),
(70, 'ROSE__1___ChaussureTensorRose.jpg', 1, 22),
(71, 'ROSE__2___ChaussureTensorRose.jpg', 0, 22),	

-- ENFANTS - VETEMMENT
(72, 'pijama___rose.jpg', 1, 23),
(73, 'pijama__GRIS.jpg', 0, 23),
(74, 'BLANCHE__1__LACOSTE___Robe_Fille_à_taille_élastiquée_en_molleton_de_coton.jpg', 1, 24),
(75, 'BLANCHE__2___LACOSTE___Robe_Fille_à_taille_élastiquée_en_molleton_de_coton.jpg', 0, 24),
(76, 'BLANCHE__LACOSTE___Robe_Fille_à_taille_élastiquée_en_molleton_de_coton.jpg', 0, 24),
(77, '1__ADDIDAS__PANTALON_DE_SURVETEMENT_SST.jpg', 1, 25),
(78, '2__ADDIDAS__PANTALON_DE_SURVETEMENT_SST.jpg', 0, 25),
(79, '3__ADDIDAS__PANTALON_DE_SURVETEMENT_SST.jpg', 0, 25),
(80, '4__ADDIDAS__PANTALON_DE_SURVETEMENT_SST.jpg', 0, 25),

-- ENFANTS - ACCESSOIRE
(81, 'ADIDAS__SacadosClassicXSBleu.jpg', 1, 26),
(82, 'adidas__SacadosClassicXSBleu_.jpg', 0, 26),
(83, 'lacoste__Montre_Enfant_Lacoste_12_12_avec_Bracelet_Silicone_blanche.jpg', 1, 27),
(84, 'lacoste__Montre_Enfant_Lacoste_12_12_avec_Bracelet_Silicone_Rouge.jpg', 0, 27),
(85, 'lacoste__Montre_Enfant_Lacoste_12_12_avec_Bracelet_Silicone_VERRE.jpg', 0, 27),
(86, 'parapluie_1.jpg', 1, 28),
(87, 'Parapluie_transparent_avec_Etoiles_et_ Mickey_Disney_printes.jpg', 0, 28)
;


-- Contenu de la table variante
truncate table variante;
insert into variante (article_id, taille, couleur, stocke) VALUES
-- HOMME - CHAUSSURE
(1, '42', 'NOIRE', 100),	(1, '42', 'BLANC', 50),
(2, '40', 'NOIRE', 20),	(2, '45', 'NOIRE', 20),	(2, '43', 'NOIRE', 30),	
(3, '43', 'NOIRE', 30),	
(4, '41', 'BLEU', 40),	

-- HOMME - VETEMMENT	
(5, 'L', 'NOIRE', 50),	(5, 'XL', 'NOIRE', 50),
(6, 'L', 'NOIRE',  60),	(6, 'XXL', 'NOIRE', 60),
(7, 'L', 'NOIRE', 70),	(7, 'L', 'BLANC', 70),
(8, 'L', 'NOIRE', 80),	(8, 'L', 'BLEU', 80),	(8, 'L', 'BLANC', 30),

-- HOMME - ACCESSOIRE
(9, null, null, 30),	(10, null, null, 40),	(11, null, null, 60),		

-- FEMME - CHAUSSURE
(12, '36', 'NOIRE', 20),	(12, '36', 'BLANC', 20),
(13, '35', 'NOIRE', 20),	(13, '37', 'NOIRE', 20),	(13, '39', 'NOIRE', 20),	
(14, '37', 'NOIRE', 20),

-- FEMME - VETEMMENT
(15, 'XL', 'NOIRE', 50),	(15, 'XL', 'ROSE', 50),	(15, 'XL', 'BLEU', 50),	
(16, 'L', 'NOIRE', 50),	(16, 'XL', 'NOIRE', 50),	
(17, 'M', 'BLANC', 50),	
		
-- FEMME - ACCESSOIRE
(18, null, null, 30),	(19, null, null, 30),	

-- ENFANTS - CHAUSSURE
(20, '20', 'NOIRE', 100),	(20, '20', 'BLANC', 100),	(20, '20', 'ROSE', 100),	
(21, '27', 'NOIRE', 100),	(21, '28', 'NOIRE', 100),	(21, '29', 'NOIRE', 100),	
(22, '25', 'ROSE', 100),

-- ENFANTS - VETEMMENT
(23, '4 ANS', 'BLANC', 50),	(23, '5 ANS', 'BLANC', 50),	(23, '6 ANS', 'BLANC', 50),		
(24, '4 ANS', 'BLANC', 50),	(24, '4 ANS', 'NOIRE', 50),	(24, '4 ANS', 'BLEU', 50),
(25, '10 ANS', 'BLANC', 50),	

-- ENFANTS - ACCESSOIRE
(26, null, null, 30),	(27, null, null, 30),	(28, null, null, 30)
;

-- Contenu de la table commande_produit
truncate table commande_produit;
INSERT INTO commande_produit (commande_id, variante_id, prix_unitaire, quantite) VALUES
(1, 1, 90.5, 2),	(1, 2, 35.5, 3),
(2, 3, 200.5, 2),	
(3, 4, 45.59, 6),	(3, 5, 100, 2),	(3, 6, 30.99, 1),
(4, 7, 150.80, 5),
(5, 8, 20.5, 10),
(6, 9, 100.30, 1),	(6, 10, 20.30, 1),	(6, 11, 10.50, 1),	(6, 12, 100.77, 1),
(7, 13, 60.66, 1),
(8, 14, 200.50, 10),
(9, 15, 100.30, 10),
(10, 16, 100.30, 10),	(10, 17, 80.80, 5),	(10, 18, 100.67, 2)
;



SET FOREIGN_KEY_CHECKS = 1;
