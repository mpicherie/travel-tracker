CREATE TABLE trajets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  prenom VARCHAR(100),
  email VARCHAR(150),
  lieu_depart VARCHAR(100),
  lieu_arrivee VARCHAR(100),
  date_depart DATE,
  date_arrivee DATE,
  moyen_transport ENUM('avion','train','voiture') NOT NULL,
  transport_id VARCHAR(50),
  compagnie VARCHAR(50),
  etat ENUM('prévu','en retard','en cours','arrivé','annulé') DEFAULT 'prévu',
  retard_info TEXT,
  suivi_token VARCHAR(64) UNIQUE,
  date_enregistrement TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);