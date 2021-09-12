<?php

// Base de Datos

require '../../includes/config/database.php';
$db = conectarDB();

// Consultar para obtener a los vendedores
$consulta  = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);


// Arreglo con mensajes de errores
$errores = [];

$titulo          = '';
$precio          = '';
$descripcion     = '';
$habitaciones    = '';
$wc              = '';
$estacionamiento = '';
$vendedorId      = '';



// Ejecutar el código despues de que el usuario envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $titulo          = mysqli_real_escape_string( $db, $_POST['titulo']);
    $precio          = mysqli_real_escape_string( $db, $_POST['precio']);
    $descripcion     = mysqli_real_escape_string( $db, $_POST['descripcion']);
    $habitaciones    = mysqli_real_escape_string( $db, $_POST['habitaciones']);
    $wc              = mysqli_real_escape_string( $db, $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento']);
    $vendedorId      = mysqli_real_escape_string( $db, $_POST['vendedor']);
    $creado          = date('Y/m/d');


    // Asignar Files hacia una variable
    $imagen = $_FILES['imagen'];

    if (!$titulo) {
        $errores[] = 'Debes añadir un título';
    }

    if (!$precio) {
        $errores[] = 'Debes añadir un precio';
    }

    if (strlen($descripcion) < 50) {
        $errores[] = 'Debes añadir una descripción y debe tener al menos 50 carácteres';
    }

    if (!$habitaciones) {
        $errores[] = 'Debes añadir cantidad de habitaciones';
    }

    if (!$wc) {
        $errores[] = 'Debes añadir cantidad de baños';
    }

    if (!$estacionamiento) {
        $errores[] = 'Debes añadir cantidad de estacionamientos';
    }

    if (!$vendedorId) {
        $errores[] = 'Debes seleccionar un vendedor';
    }

    if(!$imagen['name'] || $imagen['error']) {
        $errores[] = 'La imagen es Obligatoria';
    }

    // Validar por tamaño (5mb máximo)
    $medida = 2 * 1000 * 1000;

    if($imagen['size'] > $medida ) {
        $errores[] = 'La imagen es muy pesada';
    }

    // Revisar que el arreglo errores esté vacío

    if (empty($errores)) {

        /** SUBIDA DE ARCHIVOS  */

        // Crear Carpeta
        $carpetaImagenes = '../../imagenes/';


        if(!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        // Generar un nombre único a las imagenes
        $nombreImagen = md5( uniqid( rand(), true) ) . ".jpg";

        // Subir la imagen

        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);


        // Insertar en la BD
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId )
                  VALUES ( '$titulo', '$precio', '$nombreImagen','$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId' ) ";

        $resultado = mysqli_query($db, $query);

        if($resultado) {
            // Redireccionar al usuario
            header('location: /admin?resultado=1');
        }
    }
}


require '../../includes/funciones.php';
incluirTemplate('header');

?>


<main class="contenedor seccion contenido-centrado">
    <h1>Crear Propiedades</h1>

    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>

    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>

        </fieldset>

        <fieldset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedor">
                    <option value=""> -- Seleccionar Vendedor -- </option>
                    <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?>
                        <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellidos']; ?></option>
                    <?php endwhile; ?>
                </select>
            </fieldset>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </fieldset>

    </form>

</main>

<?php

incluirTemplate('footer');

?>