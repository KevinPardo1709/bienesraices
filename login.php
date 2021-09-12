<?php 

    require 'includes/funciones.php';
    incluirTemplate('header');

?>


<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>


    <form class="formulario">
    <fieldset>
                <legend>Email y Password</legend>


                <label for="email">Email:</label>
                <input type="email" placeholder="Email" id="email">

                <label for="password">Password:</label>
                <input type="password" placeholder="Password" id="telefono">

              
            </fieldset>
            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
    </form>
</main>

<?php 

    incluirTemplate('footer');

?>