<!--
Desarrollo Web
semana 12
Actividad: consumir el api de pokemons del sition pokeapi.co

-->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumiendo API Desarrollo Web - Pokémon</title>

</head>
<body>
    <form method="POST">
        <select name="id">
            <option value="">Seleccione un Pokémon</option>
            <?php
            for ($i = 1; $i <= 150; $i++) {
                echo "<option value='$i'" . (isset($_POST['id']) && $_POST['id'] == $i ? " selected" : "") . ">$i</option>";
            }
            ?>
        </select>
        <input type="submit" value="Buscar">
    </form>

    <?php
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        
        // Primera llamada a la API para obtener el nombre del Pokémon
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "https://pokeapi.co/api/v2/pokemon/$id/");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch1);
        curl_close($ch1);
        
        if ($resp === false) {
            echo "Error al conectarse a la API para obtener el nombre del Pokémon.";
        } else {
            $poke = json_decode($resp, true);
            
            if ($poke && isset($poke['name'])) {
                $pokemonName = $poke['name'];
                
                // Segunda llamada a la API para obtener los detalles del Pokémon
                $ch = curl_init();
                $url = "https://pokeapi.co/api/v2/pokemon/" . $pokemonName;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    echo 'Error al conectarse al API: ' . curl_error($ch);
                } else {
                    curl_close($ch);
                    $data = json_decode($response, true);
                    
                    if ($data) {
                        echo '<h1>' . ucfirst($data['name']) . '</h1>';
                        echo '<img src="' . $data['sprites']['front_default'] . '" alt="' . $data['name'] . '">';
                        echo '<ul>';
                        echo '<li><strong>Nombre: </strong>' . ucfirst($data['name']) . '</li>';
                        echo '<li><strong>Altura: </strong>' . $data['height'] . ' dm</li>';
                        echo '<li><strong>Peso: </strong>' . $data['weight'] . ' hg</li>';           
                        echo '<li><strong>Habilidades: </strong>'; 
                        echo '<ul>';
                        foreach ($data['abilities'] as $habilidad) {
                            echo '<li>' . ucfirst($habilidad['ability']['name']) . '</li>';
                        }
                        echo '</ul>';
                        echo '</li>';
                        echo '</ul>';
                    } else {
                        echo "No se pudo decodificar la respuesta de la API.";
                    }
                }
            } else {
                echo "No se pudo obtener el nombre del Pokémon.";
            }
        }
    }
    ?>
</body>
</html>