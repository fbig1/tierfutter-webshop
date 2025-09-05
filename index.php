<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tierfutter Webshop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .form-group { margin: 10px 0; }
        label { font-weight: bold; }
        select, button { padding: 10px; margin: 5px; font-size: 16px; }
        button { background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .product { border: 1px solid #ddd; padding: 15px; margin: 10px; border-radius: 5px; background: #fff; }
        .price { font-size: 18px; font-weight: bold; color: #28a745; }
        .alert { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐕 Tierfutter Webshop 🐱</h1>
        
        <!-- Suchformular -->
        <form method="POST" action="">
            <div class="form-group">
                <label>Tierart auswählen:</label>
                <select name="tierart" required>
                    <option value="">-- Bitte wählen --</option>
                    <option value="1">🐕 Hunde</option>
                    <option value="2">🐱 Katzen</option>
                    <option value="3">🦜 Vögel</option>
                </select>
                
                <label>Sortierung:</label>
                <select name="sortierung">
                    <option value="preis ASC">Preis: niedrig → hoch</option>
                    <option value="preis DESC">Preis: hoch → niedrig</option>
                    <option value="bez ASC">Name: A → Z</option>
                    <option value="bez DESC">Name: Z → A</option>
                    <option value="id ASC">Artikelnummer niedrig → hoch</option>
                    <option value="id DESC">Artikelnummer hoch → niedrig</option>
                </select>
                
                <button type="submit" name="suchen">🔍 Suchen</button>
            </div>
        </form>

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

        // Suchlogik
        if(isset($_POST['suchen'])) {
            $tierart = $_POST['tierart'];
            $sortierung = $_POST['sortierung'];
            
            $stmt = $pdo->prepare("SELECT * FROM Produkt WHERE tierart = ? ORDER BY " . $sortierung);
            $stmt->execute([$tierart]);
            $produkte = $stmt->fetchAll();
            
            $anzahl = count($produkte);
            
            if($anzahl > 0) {
                echo "<div class='alert alert-success'>";
                echo "<h3>✅ $anzahl Produkte gefunden</h3>";
                echo "</div>";
                
                foreach($produkte as $produkt) {
                    echo "<div class='product'>";
                    echo "<h4>" . htmlspecialchars($produkt['bez']) . "</h4>";
                    echo "<p>" . htmlspecialchars($produkt['beschr']) . "</p>";
                    echo "<div class='price'>" . number_format($produkt['preis'], 2) . " €</div>";
                    echo "<small>Artikel-ID: " . htmlspecialchars($produkt['id']) . "</small>";
                    echo "</div>";
                }
                
            } else {
                echo "<div class='alert alert-warning'>";
                echo "<h3>⚠️ Keine Produkte gefunden</h3>";
                echo "<p>Für die gewählte Tierart sind aktuell keine Produkte verfügbar.</p>";
                echo "</div>";
            }
        }
        ?>
        
        <hr>
        <p><a href="admin.php" style="text-decoration: none;">
            <button type="button">⚙️ Zur Produktverwaltung</button>
        </a></p>
    </div>
</body>
</html>

