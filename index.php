<!DOCTYPE html>
<html>

<head>
    <title>Formulir Contoh</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #e9e9e9;
        }

        form {
            width: 400px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border: 2px solid #800000; /* Maroon border */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 2px solid #800000; /* Maroon border */
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #800000; /* Maroon background */
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            cursor: pointer;
            font-size: 18px;
        }

        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        th,
        td {
            border: 2px solid #800000; /* Maroon border */
            padding: 14px;
            text-align: left;
        }

        th {
            background-color: #800000; /* Maroon background */
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        tr:hover {
            background-color: #ddd;
        }

    </style>
</head>
<body>
    <form action="" method="post">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id"><br>

        <label for="f_name">First Name:</label>
        <input type="text" id="f_name" name="f_name"><br>

        <label for="l_name">Last Name:</label>
        <input type="text" id="l_name" name="l_name"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>

        <label for="email2">Confirm Email:</label>
        <input type="email" id="email2" name="email2"><br>

        <label for="profesi">Profesi:</label>
        <input type="text" id="profesi" name="profesi"><br>

        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    // Memeriksa apakah formulir telah disubmit
    if (isset($_POST['submit'])) {
        // Mengambil nilai dari formulir
        $id = $_POST['id'];
        $f_name = $_POST['f_name'];
        $l_name = $_POST['l_name'];
        $email = $_POST['email'];
        $email2 = $_POST['email2'];
        $profesi = $_POST['profesi'];

        // Memeriksa apakah email dan konfirmasi email cocok
        if ($email === $email2) {
            // Menyiapkan data untuk ditambahkan ke file CSV
            $newData = "$id,$f_name,$l_name,$email,$email2,$profesi\n";

            // Menyimpan data ke file CSV lokal pada server
            $csvFilePath = 'datapribadi.csv';
            file_put_contents($csvFilePath, $newData, FILE_APPEND);

            // Mengunggah kembali file CSV ke URL dengan curl
            $csvUrl = 'datapribadi.csv';
            $ch = curl_init($csvUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($csvFilePath));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                // Menampilkan pesan sukses
                echo "<p>Data telah berhasil disimpan.</p>";
            } else {
                // Menampilkan pesan kesalahan jika pengunggahan gagal
                echo "<p>Gagal mengunggah data.</p>";
            }
        } else {
            // Menampilkan pesan kesalahan jika email dan konfirmasi email tidak cocok
            echo "<p>Email dan konfirmasi email tidak cocok.</p>";
        }
    }

    // Menampilkan tabel dengan data dari file CSV
    $csvUrl = 'datapribadi.csv';
    $csvData = file_get_contents($csvUrl);

    echo "<table>";
    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Confirm Email</th><th>Profesi</th></tr>";
    
    $lines = explode("\n", $csvData);
    foreach ($lines as $line) {
        $fields = explode(",", $line);
        if (count($fields) === 6) {
            echo "<tr>";
            echo "<td>{$fields[0]}</td><td>{$fields[1]}</td><td>{$fields[2]}</td><td>{$fields[3]}</td><td>{$fields[4]}</td><td>{$fields[5]}</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    ?>
</body>
</html>