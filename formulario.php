<?php

// Ruta del archivo JSON
$jsonFile = 'participantes.json';

// Función para leer datos del archivo JSON
function leerDatos() {
    global $jsonFile;
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        return json_decode($jsonData, true);
    } else {
        return [];
    }
}

// Función para guardar datos en el archivo JSON
function guardarDatos($data) {
    global $jsonFile;
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($jsonFile, $jsonData);
}

// Inicializar variables
$id = $nombre_completo = $correo_electronico = $telefono = $conocer_proyecto = $interes_proyecto = $comentarios = "";
$isEdit = false;

// Insertar datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nombre_completo = htmlspecialchars($_POST['nombre_completo']);
    $correo_electronico = htmlspecialchars($_POST['correo_electronico']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $conocer_proyecto = isset($_POST['conocer_proyecto']) ? implode(", ", $_POST['conocer_proyecto']) : '';
    $interes_proyecto = isset($_POST['interes_proyecto']) ? implode(", ", $_POST['interes_proyecto']) : '';
    $comentarios = htmlspecialchars($_POST['comentarios']);

    $nuevoParticipante = [
        "id" => time(),
        "nombre_completo" => $nombre_completo,
        "correo_electronico" => $correo_electronico,
        "telefono" => $telefono,
        "conocer_proyecto" => $conocer_proyecto,
        "interes_proyecto" => $interes_proyecto,
        "comentarios" => $comentarios
    ];

    $datos = leerDatos();
    $datos[] = $nuevoParticipante;
    guardarDatos($datos);
    echo "Nuevo registro creado exitosamente";
}

// Eliminar datos
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $datos = leerDatos();
    $datos = array_filter($datos, function($participante) use ($id) {
        return $participante['id'] !== $id;
    });
    guardarDatos($datos);
    echo "Registro eliminado exitosamente";
}

// Actualizar datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nombre_completo = htmlspecialchars($_POST['nombre_completo']);
    $correo_electronico = htmlspecialchars($_POST['correo_electronico']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $conocer_proyecto = isset($_POST['conocer_proyecto']) ? implode(", ", $_POST['conocer_proyecto']) : '';
    $interes_proyecto = isset($_POST['interes_proyecto']) ? implode(", ", $_POST['interes_proyecto']) : '';
    $comentarios = htmlspecialchars($_POST['comentarios']);

    $datos = leerDatos();
    foreach ($datos as &$participante) {
        if ($participante['id'] === $id) {
            $participante['nombre_completo'] = $nombre_completo;
            $participante['correo_electronico'] = $correo_electronico;
            $participante['telefono'] = $telefono;
            $participante['conocer_proyecto'] = $conocer_proyecto;
            $participante['interes_proyecto'] = $interes_proyecto;
            $participante['comentarios'] = $comentarios;
            break;
        }
    }
    guardarDatos($datos);
    echo "Registro actualizado exitosamente";
}

// Cargar datos para editar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $datos = leerDatos();
    foreach ($datos as $participante) {
        if ($participante['id'] === $id) {
            $nombre_completo = $participante['nombre_completo'];
            $correo_electronico = $participante['correo_electronico'];
            $telefono = $participante['telefono'];
            $conocer_proyecto = $participante['conocer_proyecto'];
            $interes_proyecto = $participante['interes_proyecto'];
            $comentarios = $participante['comentarios'];
            $isEdit = true;
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
         body {
            background-image: url(/propuesta_de_recoleccion_de_agua_de_lluvia/Graficos/cuerpo4.png);
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }


        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }

        /* Reducir el tamaño de los cuadros de texto en un 10% */
        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 90%;
        }

        input[type="submit"],
        input[type="reset"],
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover,
        button:hover {
            background-color: #0056b3;
        }

        .success {
            color: #28a745;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            label {
                margin-bottom: 10px;
            }

            input[type="submit"],
            input[type="reset"],
            button {
                width: 100%;
            }

            th, td {
                font-size: 14px;
            }
        }

#playPauseButton {
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #ffff;
  color: black;
  border: none;
  cursor: pointer;
}


    </style>
    <script>
        function toggleOtroEspecificar(selectElement, textElementId) {
            var textElement = document.getElementById(textElementId);
            if (Array.from(selectElement.options).some(option => option.selected && option.value === 'Otro (especificar)')) {
                textElement.style.display = 'block';
            } else {
                textElement.style.display = 'none';
                textElement.value = ''; // Clear the text if not visible
            }
        }

        function validarFormulario() {
            var nombreCompleto = document.getElementById('nombre_completo').value;
            var correoElectronico = document.getElementById('correo_electronico').value;
            var telefono = document.getElementById('telefono').value;
            var conocerProyecto = document.getElementById('conocer_proyecto').selectedOptions;
            var interesProyecto = document.getElementById('interes_proyecto').selectedOptions;

            var regexNombre = /^[A-Za-z\s]+$/;
            var regexTelefono = /^[0-9]{10}$/;
            var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!regexNombre.test(nombreCompleto)) {
                alert('El nombre completo solo debe contener letras y espacios.');
                return false;
            }

            if (!regexTelefono.test(telefono)) {
                alert('El teléfono debe contener exactamente 10 dígitos.');
                return false;
            }

            if (!regexCorreo.test(correoElectronico)) {
                alert('El correo electrónico debe contener un símbolo de arroba (@) y un dominio válido.');
                return false;
            }

            if (conocerProyecto.length === 0) {
                alert('Debe seleccionar al menos una opción en "¿Cómo se enteró del proyecto?".');
                return false;
            }

            if (interesProyecto.length === 0) {
                alert('Debe seleccionar al menos una opción en "¿Qué le interesa del proyecto?".');
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nombre_completo').addEventListener('input', function() {
                var regexNombre = /^[A-Za-z\s]+$/;
                if (!regexNombre.test(this.value)) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            });

            document.getElementById('correo_electronico').addEventListener('input', function() {
                var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regexCorreo.test(this.value)) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            });

            document.getElementById('telefono').addEventListener('input', function() {
                var regexTelefono = /^[0-9]{10}$/;
                if (!regexTelefono.test(this.value)) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            });
        });
    </script>
    <
</head>
<body>
<div class="audio-player-container">
    <audio id="audioPlayer" autoplay controls style="display: none;"> <source src="/propuesta_de_recoleccion_de_agua_de_lluvia/Audios/musicadefondo.mp3" type="audio/mpeg">
    </audio>
    <button id="playPauseButton">Detener/Reproducir música</button>
  </div>
  <script src="scriptmusic.js"></script>

    <form action="" method="post" onsubmit="return validarFormulario()">
        <fieldset>
            <legend>Información Personal</legend>
            <label for="nombre_completo"><i class="fas fa-user"></i> Nombre completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ingrese su nombre completo" required title="Ingrese su nombre completo sin números" value="<?php echo $nombre_completo; ?>"><br><br>

            <label for="correo_electronico"><i class="fas fa-envelope"></i> Correo electrónico:</label>
            <input type="email" id="correo_electronico" name="correo_electronico" placeholder="Ingrese su correo electrónico" required title="Ingrese un correo electrónico válido" value="<?php echo $correo_electronico; ?>"><br><br>

            <label for="telefono"><i class="fas fa-phone"></i> Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" placeholder="Ingrese su número de teléfono" required title="Ingrese un número de teléfono de 10 dígitos" value="<?php echo $telefono; ?>"><br><br>
        </fieldset>

        <fieldset>
            <legend>Información del Proyecto</legend>
            <label for="conocer_proyecto">¿Cómo se enteró del proyecto de recolección de agua de lluvia?</label><br>
            <select id="conocer_proyecto" name="conocer_proyecto[]" multiple required onchange="toggleOtroEspecificar(this, 'otro_conocer_proyecto')">
                <option value="Sitio web del CBTIS No. 79" <?php echo strpos($conocer_proyecto, 'Sitio web del CBTIS No. 79') !== false ? 'selected' : ''; ?>>Sitio web del CBTIS No. 79</option>
                <option value="Redes sociales del CBTIS No. 79" <?php echo strpos($conocer_proyecto, 'Redes sociales del CBTIS No. 79') !== false ? 'selected' : ''; ?>>Redes sociales del CBTIS No. 79</option>
                <option value="Volante o folleto" <?php echo strpos($conocer_proyecto, 'Volante o folleto') !== false ? 'selected' : ''; ?>>Volante o folleto</option>
                <option value="Plática informativa" <?php echo strpos($conocer_proyecto, 'Plática informativa') !== false ? 'selected' : ''; ?>>Plática informativa</option>
                <option value="Otro (especificar)" <?php echo strpos($conocer_proyecto, 'Otro (especificar)') !== false ? 'selected' : ''; ?>>Otro (especificar)</option>
            </select><br><br>

            <input type="text" id="otro_conocer_proyecto" name="otro_conocer_proyecto" placeholder="Especifique" style="display:none;"><br><br>

            <label for="interes_proyecto">¿Qué le interesa del proyecto?</label><br>
            <select id="interes_proyecto" name="interes_proyecto[]" multiple required onchange="toggleOtroEspecificar(this, 'otro_interes_proyecto')">
                <option value="Ahorro de agua" <?php echo strpos($interes_proyecto, 'Ahorro de agua') !== false ? 'selected' : ''; ?>>Ahorro de agua</option>
                <option value="Beneficios ambientales" <?php echo strpos($interes_proyecto, 'Beneficios ambientales') !== false ? 'selected' : ''; ?>>Beneficios ambientales</option>
                <option value="Impacto en la comunidad" <?php echo strpos($interes_proyecto, 'Impacto en la comunidad') !== false ? 'selected' : ''; ?>>Impacto en la comunidad</option>
                <option value="Oportunidades de aprendizaje" <?php echo strpos($interes_proyecto, 'Oportunidades de aprendizaje') !== false ? 'selected' : ''; ?>>Oportunidades de aprendizaje</option>
                <option value="Otro (especificar)" <?php echo strpos($interes_proyecto, 'Otro (especificar)') !== false ? 'selected' : ''; ?>>Otro (especificar)</option>
            </select><br><br>

            <input type="text" id="otro_interes_proyecto" name="otro_interes_proyecto" placeholder="Especifique" style="display:none;"><br><br>
        </fieldset>

        <fieldset>
            <legend>Comentarios</legend>
            <label for="comentarios"><i class="fas fa-comment"></i> ¿Tiene algún comentario o sugerencia sobre el proyecto?</label><br>
            <textarea id="comentarios" name="comentarios" rows="4" cols="50" placeholder="Escriba sus comentarios aquí"><?php echo $comentarios; ?></textarea><br><br>
        </fieldset>

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="<?php echo $isEdit ? 'update' : 'submit'; ?>" value="<?php echo $isEdit ? 'Actualizar' : 'Enviar'; ?>">
        <input type="reset" value="Resetear">
        <button type="button" onclick="window.location.href='index.php';">Cancelar</button>
    </form>

    <h2>Participantes</h2>
    <table>
        <tr>
            <th>Nombre completo</th>
            <th>Correo electrónico</th>
            <th>Teléfono</th>
            <th>¿Cómo se enteró?</th>
            <th>¿Qué le interesa?</th>
            <th>Comentarios</th>
            <th>Acciones</th>
        </tr>
        <?php
        $datos = leerDatos();

        if (count($datos) > 0) {
            foreach ($datos as $row) {
                echo "<tr>";
                echo "<td>" . $row['nombre_completo'] . "</td>";
                echo "<td>" . $row['correo_electronico'] . "</td>";
                echo "<td>" . $row['telefono'] . "</td>";
                echo "<td>" . $row['conocer_proyecto'] . "</td>";
                echo "<td>" . $row['interes_proyecto'] . "</td>";
                echo "<td>" . $row['comentarios'] . "</td>";
                echo "<td>";
                echo "<a href='?edit=" . $row['id'] . "'>Editar</a> | ";
                echo "<a href='?delete=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>Eliminar</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay participantes registrados.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
