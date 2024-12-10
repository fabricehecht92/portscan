<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Portscanner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            width: 300px;
            padding: 5px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>PHP Portscanner</h1>
    <form method="POST" action="">
        <label for="target">Ziel (URL oder IP-Adresse):</label>
        <input type="text" id="target" name="target" placeholder="z. B. 192.168.1.1" required>
        <label for="ports">Portbereich (z. B. 1-100):</label>
        <input type="text" id="ports" name="ports" placeholder="z. B. 1-1024" required>
        <input type="submit" value="Scannen">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $target = filter_var($_POST['target'], FILTER_SANITIZE_STRING);
        $ports = filter_var($_POST['ports'], FILTER_SANITIZE_STRING);

        // Portbereich analysieren
        if (preg_match('/^(\d+)-(\d+)$/', $ports, $matches)) {
            $startPort = (int)$matches[1];
            $endPort = (int)$matches[2];

            if ($startPort > $endPort || $startPort < 1 || $endPort > 65535) {
                echo "<p style='color: red;'>Ungültiger Portbereich!</p>";
                exit;
            }

            echo "<h2>Scan-Ergebnisse für $target (Ports $startPort-$endPort):</h2>";
            echo "<table>";
            echo "<tr><th>Port</th><th>Status</th></tr>";

            // Scan-Prozess
            for ($port = $startPort; $port <= $endPort; $port++) {
                $connection = @fsockopen($target, $port, $errno, $errstr, 0.3);
                if ($connection) {
                    echo "<tr><td>$port</td><td style='color: green;'>Offen</td></tr>";
                    fclose($connection);
                } else {
                    echo "<tr><td>$port</td><td style='color: red;'>Geschlossen</td></tr>";
                }
            }

            echo "</table>";
        } else {
            echo "<p style='color: red;'>Ungültiges Portformat! Bitte gib einen Bereich wie 1-100 an.</p>";
        }
    }
    ?>
</body>
</html>
