<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produktverwaltung</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .form-group { margin: 15px 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 15px 30px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; margin: 10px 5px; }
        .btn-success { background: #28a745; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .alert { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Produktverwaltung</h1>
        
        <?php
        // Datenbankverbindung
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "tierfutter_webshop";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Verbindung fehlgeschlagen: " . $e->getMessage());
        }

        // Produkt hinzufügen
        if(isset($_POST['speichern'])) {
            $id = $_POST['id'];
            $bez = $_POST['bezeichnung'];
            $beschr = $_POST['beschreibung'];
            $preis = $_POST['preis'];
            $tierart = $_POST['tierart'];
            
            try {
                $stmt = $pdo->prepare("INSERT INTO Produkt (id, bez, beschr, preis, tierart) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id, $bez, $beschr, $preis, $tierart]);
                
                echo "<div class='alert alert-success'>";
                echo "<h3>✅ Produkt erfolgreich hinzugefügt!</h3>";
                echo "<p><strong>ID:</strong> " . htmlspecialchars($id) . "</p>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($bez) . "</p>";
                echo "<p><strong>Preis:</strong> " . number_format($preis, 2) . " €</p>";
                echo "<p><strong>Tierart:</strong> " . ($tierart == 1 ? '🐕 Hunde' : '🐱 Katzen') . "</p>";
                echo "</div>";
                
            } catch(PDOException $e) {
                echo "<div class='alert alert-danger'>";
                echo "<h3>❌ Fehler beim Speichern!</h3>";
                echo "<p>" . $e->getMessage() . "</p>";
                echo "</div>";
            }
        }
        ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Produkt-ID (eindeutig) *</label>
                <input type="text" name="id" required placeholder="z.B. H005 oder K005">
            </div>
            
            <div class="form-group">
                <label>Tierart *</label>
                <select name="tierart" required>
                    <option value="">-- Bitte wählen --</option>
                    <option value="1">🐕 Hunde</option>
                    <option value="2">🐱 Katzen</option>
                    <option value="3">🦜 Vögel</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Produktname *</label>
                <input type="text" name="bezeichnung" required placeholder="z.B. Premium Hundefutter">
            </div>
            
            <div class="form-group">
                <label>Beschreibung</label>
                <textarea name="beschreibung" rows="3" placeholder="Beschreibung des Produkts..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Preis (€) *</label>
                <input type="number" step="0.01" name="preis" required placeholder="0.00">
            </div>
            
            <button type="submit" name="speichern" class="btn-success">💾 Produkt hinzufügen</button>
            <a href="index.php"><button type="button" class="btn-secondary">↩️ Zurück zur Suche</button></a>
        </form>
    </div>
</body>
</html>

