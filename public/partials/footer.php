<!-- TODO: -->
<!-- 
Implementar o buscar la forma de como implementar los scripts de manera dinamica, dependiendo del modulo que se este visualizando.

-->

<!-- <script src="../public/assets/libraries/bootstrap/js/bootstrap.js"></script> -->
<?php

/**
 * En el archivo footer.php sería nuestro menú que va encabezar todo
*/

?>
<footer class="bg-info text-white text-center" id="">
    <nav id="mainMenu">
        <ul class="horizontalMenu">
            <li>
                <a href="dashboard.php?value=5">Inicio</a>
            </li>
            <li>
                <a href="#">Prestamos</a>
                <ul class="verticalMenu">
                    <li>
                        <a href="dashboard.php?value=3">Solicitar</a>
                    </li>
                    <li>
                        <a href="dashboard.php?value=4">Consultar prestamos</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">Elementos</a>
            </li>
            <li>
                <a href="#">Usuarios</a>
            </li>
            <li>
                <a href="#">Configuración</a>
                <!-- Elementos internos del menú -->
                <ul class="verticalMenu">
                    <li>
                        <a href="dashboard.php?value=7">Areas</a>
                    </li>
                    <li>
                        <a href="dashboard.php?value=8">Tipo documento</a>
                    </li>
                    <li>
                        <a href="#">Marcas</a>
                    </li>
                    <li>
                        <a href="#">Categorias</a>
                    </li>
                </ul>
            </li>
        </ul>

    </nav>


</footer>
</body>

</html>