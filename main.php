<?php 
$host = '127.0.0.1';
$user = 's257';
$password = 'C3stl1poLose';
$database = 'dbs257';

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Restauracja</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #1abc9c;
        --secondary: #16a085;
        --bg-gradient: linear-gradient(135deg, #ecf0f1, #d0e6e2);
        --text: #2c3e50;
        --white: #fff;
        --shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg-gradient);
        color: var(--text);
        animation: fadeInBody 1s ease-in;
    }

    @keyframes fadeInBody {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    nav {
        background-color: var(--primary);
        padding: 15px;
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: var(--shadow);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    nav a {
        color: var(--white);
        text-decoration: none;
        font-weight: 600;
        padding: 10px 15px;
        border-radius: 8px;
        transition: background 0.3s, transform 0.2s;
    }

    nav a:hover {
        background-color: var(--secondary);
        transform: scale(1.05);
    }

    .container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .section {
        background-color: var(--white);
        padding: 30px;
        margin-bottom: 40px;
        border-radius: 16px;
        box-shadow: var(--shadow);
        animation: fadeInUp 0.7s ease forwards;
        opacity: 0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    h1, h2 {
        margin-bottom: 20px;
        color: var(--text);
        position: relative;
    }

    h2::after {
        content: '';
        width: 60px;
        height: 4px;
        background-color: var(--primary);
        position: absolute;
        bottom: -10px;
        left: 0;
        border-radius: 2px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        animation: fadeInTable 0.8s ease-in;
    }

    @keyframes fadeInTable {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    th, td {
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid #ddd;
        transition: background 0.2s;
    }

    th {
        background-color: var(--primary);
        color: var(--white);
    }

    tr:hover td {
        background-color: #f9f9f9;
    }

    tr:last-child td {
        border-bottom: none;
    }

    p {
        font-size: 1.1em;
        margin-top: 10px;
    }

    footer {
        text-align: center;
        padding: 25px;
        font-size: 0.95em;
        color: #777;
    }
</style>

</head>
<body>

<nav>
    <a href="#start">Start</a>
    <a href="#menu">Menu</a>
    <a href="#klienci">Klienci</a>
    <a href="#zamowienia">Zamówienia</a>
    <a href="#zestawienie">Zestawienie</a>
</nav>

<div class="main">
    <section id="start" class="hero">
        <h1>Witamy w naszej Restauracji!</h1>
        <p><strong>Smak, który zapamiętasz. Jakość, którą pokochasz.</strong></p>
    </section>

    <h1 id="menu">Menu</h1>
    <table>
        <tr><th>Nazwa</th><th>Cena</th><th>Kategoria</th></tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM rest_menu");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['nazwa_pozycji']}</td>
                    <td>{$row['cena']} zł</td>
                    <td>{$row['kategoria']}</td>
                  </tr>";
        }
        ?>
    </table>

    <h1 id="klienci">Lista klientów</h1>
    <table>
        <tr><th>Imię</th><th>Nazwisko</th><th>Telefon</th></tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM rest_klienci ORDER BY nazwisko, imie");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['imie']}</td>
                    <td>{$row['nazwisko']}</td>
                    <td>{$row['numer_telefonu']}</td>
                  </tr>";
        }
        ?>
    </table>

    <h1 id="zamowienia">Zamówienia klientów</h1>
    <table>
        <tr><th>Lp</th><th>Imię</th><th>Nazwisko</th><th>Data</th><th>Nazwa</th><th>Ilość</th><th>Cena jedn.</th></tr>
        <?php
        $sql = "SELECT k.imie, k.nazwisko, z.data_zamowienia, m.nazwa_pozycji, z.ilosc, m.cena
                FROM rest_zamowienia z
                JOIN rest_klienci k ON z.id_klient = k.id_klient
                JOIN rest_menu m ON z.id_pozycja = m.id_pozycja
                ORDER BY k.nazwisko, k.imie, z.data_zamowienia";
        $result = mysqli_query($conn, $sql);
        $lp = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$lp}</td>
                    <td>{$row['imie']}</td>
                    <td>{$row['nazwisko']}</td>
                    <td>{$row['data_zamowienia']}</td>
                    <td>{$row['nazwa_pozycji']}</td>
                    <td>{$row['ilosc']}</td>
                    <td>" . number_format($row['cena'], 2) . " zł</td>
                  </tr>";
            $lp++;
        }
        ?>
    </table>

    <h1 id="zestawienie">Zestawienie</h1>
    <h2>Podsumowanie liczby zamówień wg kategorii</h2>
    <table>
        <tr><th>Lp</th><th>Kategoria</th><th>Ilość</th></tr>
        <?php
        $sql = "SELECT m.kategoria, SUM(z.ilosc) AS suma
                FROM rest_zamowienia z
                JOIN rest_menu m ON z.id_pozycja = m.id_pozycja
                GROUP BY m.kategoria";
        $result = mysqli_query($conn, $sql);
        $lp = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$lp}</td>
                    <td>{$row['kategoria']}</td>
                    <td>{$row['suma']}</td>
                  </tr>";
            $lp++;
        }
        ?>
    </table>

    <h2>Klient z najwyższą wartością zamówień</h2>
    <?php
    $sql = "SELECT k.imie, k.nazwisko, SUM(m.cena * z.ilosc) AS suma
            FROM rest_zamowienia z
            JOIN rest_klienci k ON z.id_klient = k.id_klient
            JOIN rest_menu m ON z.id_pozycja = m.id_pozycja
            GROUP BY k.id_klient
            ORDER BY suma DESC
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "<p><strong>{$row['imie']} {$row['nazwisko']}</strong> – " . number_format($row['suma'], 2) . " zł</p>";
    ?>

    <footer>
        &copy; <?php echo date("Y"); ?> Restauracja. Wszelkie prawa zastrzeżone.
    </footer>
</div>

</body>
</html>