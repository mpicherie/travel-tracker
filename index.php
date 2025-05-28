<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Déclaration de trajet</title>
  <link rel="stylesheet" href="assets/style.css">
  <script>
    function toggleTransportFields() {
      const type = document.getElementById('transport').value;
      document.getElementById('transport_id_field').style.display =
        (type === 'avion' || type === 'train') ? 'block' : 'none';
      document.getElementById('compagnie_field').style.display =
        (type === 'train') ? 'block' : 'none';
    }

    function fetchFlightData() {
      const flightNumber = document.getElementById('transport_id').value;
      if (!flightNumber) {
        alert("Merci d’entrer un numéro de vol.");
        return;
      }

      fetch('api_vol.php?flight=' + encodeURIComponent(flightNumber))
        .then(res => res.json())
        .then(data => {
          if (data.from && data.to) {
            document.getElementById('lieu_depart').value = data.from;
            document.getElementById('lieu_arrivee').value = data.to;
            alert("Trajet trouvé : " + data.from + " → " + data.to);
          } else {
            alert("Vol introuvable ou erreur API.");
          }
        })
        .catch(err => {
          alert("Erreur de récupération du vol.");
          console.error(err);
        });
    }
  </script>
</head>
<body>
  <h2>Déclare ton trajet</h2>
  <form method="POST" action="submit.php">
    <input name="nom" placeholder="Nom" required>
    <input name="prenom" placeholder="Prénom" required>
    <input name="email" type="email" placeholder="Email" required>

    <div id="transport_id_field" style="display:none;">
      <input type="text" name="transport_id" id="transport_id" placeholder="Numéro de vol/train">
      <button type="button" onclick="fetchFlightData()">Remplir automatiquement</button>
    </div>

    <input name="lieu_depart" id="lieu_depart" placeholder="Lieu de départ">
    <input name="lieu_arrivee" id="lieu_arrivee" placeholder="Lieu d’arrivée">

    <input type="date" name="date_depart" required>
    <input type="date" name="date_arrivee" required>

    <select name="moyen_transport" id="transport" onchange="toggleTransportFields()" required>
      <option value="">-- Choisir transport --</option>
      <option value="avion">Avion</option>
      <option value="train">Train</option>
      <option value="voiture">Voiture</option>
    </select>

    <div id="compagnie_field" style="display:none;">
      <select name="compagnie">
        <option value="">-- Compagnie train --</option>
        <option value="SNCF">SNCF</option>
        <option value="NS">NS</option>
        <option value="DB">DB</option>
        <option value="iRail">iRail</option>
      </select>
    </div>

    <button type="submit">Envoyer</button>
  </form>
</body>
</html>