<h1 class="nombre-pagina">Panel de Administración</h1>
<?php 
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Servicios solicitados en el mes</h2>

<?php if (isset($servicios) && count($servicios) === 0) { ?>
    <p>No hay servicios registrados en el mes.</p>
<?php } elseif (isset($servicios)) { ?>
    <ul class="servicios-mes">
        <?php 
        foreach ($servicios as $index => $servicio) { 
            $clase = '';
            if ($index === 0) {
                $clase = 'mas-alto';
            } elseif ($index === 1) {
                $clase = 'alto';
            } elseif ($index === 2) {
                $clase = 'medio';
            }
        ?>
            <li class="<?php echo $clase; ?>">
                <p>Servicio: <?php echo $servicio['nombre']; ?></p>
                <p>Cantidad: <?php echo $servicio['cantidad']; ?></p>
            </li>
        <?php } ?>
    </ul>
<?php } ?>



<h2>Días de la semana más frecuentes para citas</h2>

<ul class="dias-frecuentes">
    <?php 
    // Ordena los días por la cantidad de citas
    $diasFrecuentesOrdenados = $diasConteo;
    arsort($diasFrecuentesOrdenados);
    $indice = 0;
    foreach ($diasFrecuentesOrdenados as $numeroDia => $cantidad) {
        $clase = '';
        if ($indice === 0) {
            $clase = 'mas-alto';
        } elseif ($indice === 1) {
            $clase = 'alto';
        } elseif ($indice === 2) {
            $clase = 'medio';
        }
        $indice++;
    ?>
        <li class="<?php echo $clase; ?>">
            <p>Día: <?php echo $diasSemana[$numeroDia]; ?></p>
            <p>Cantidad: <?php echo $cantidad; ?></p>
        </li>
    <?php } ?>
</ul>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>
