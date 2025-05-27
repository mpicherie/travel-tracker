<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Déclaration de trajet - SVI</title>
  <link rel="stylesheet" href="assets/style.css">
  <script>
    function toggleTransportFields() {
      const type = document.getElementById('transport').value;
      document.getElementById('transport_id_field').style.display =
        (type === 'avion' || type === 'train') ? 'block' : 'none';
      document.getElementById('compagnie_field').style.display =
        (type === 'train') ? 'block' : 'none';
    }
  </script>
</head>
<body>
  <h2>Déclare ton trajet</h2>

  <form method="POST" action="submit.php">
    <label>Nom :
      <input name="nom" placeholder="Ton nom" required>
    </label>

    <label>Prénom :
      <input name="prenom" placeholder="Ton prénom" required>
    </label>

    <label>Email :
      <input type="email" name="email" placeholder="exemple@email.com" required>
    </label>

    <label>Lieu de départ :
      <input name="lieu_depart" required>
    </label>

    <label>Lieu d’arrivée :
      <input name="lieu_arrivee" required>
    </label>

    <label>Date de départ :
      <input type="date" name="date_depart" required>
    </label>

    <label>Date d’arrivée :
      <input type="date" name="date_arrivee" required>
    </label>

    <label>Moyen de transport :
      <select name="moyen_transport" id="transport" onchange="toggleTransportFields()" required>
        <option value="">-- Choisir --</option>
        <option value="avion">Avion</option>
        <option value="train">Train</option>
        <option value="voiture">Voiture</option>
      </select>
    </label>

    <div id="transport_id_field" style="display:none;">
      <label>Numéro de vol ou de train :
        <input name="transport_id" placeholder="Ex: AF123 ou TGV8721">
      </label>
    </div>

    <div id="compagnie_field" style="display:none;">
      <label>Compagnie ferroviaire :
        <select name="compagnie">
          <option value="">-- Choisir la compagnie --</option>
          <option value="SNCF">SNCF (France)</option>
          <option value="NS">NS (Pays-Bas)</option>
          <option value="DB">DB (Allemagne)</option>
          <option value="iRail">SNCB (Belgique)</option>
        </select>
      </label>
    </div>

    <button type="submit">Envoyer le trajet</button>
  </form>
</body>
</html>
