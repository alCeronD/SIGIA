<style>

.form-wrapper {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    /* Opcional para fondo detrás del formulario */
    background-color: #f9fafb;
    z-index: 10;
}



.editar-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    width: 700px;
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    
}

.editar-form h2 {
    grid-column: span 2;
    text-align: center;
    font-weight: 700;
    font-size: 28px;
    color: #222;
    margin-bottom: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: #444;
    margin-bottom: 8px;
    font-size: 15px;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group label[for="elm_placa"], /* si usas label para texto en placa */
.form-group label[for="elm_existencia"] {
    padding: 10px 12px;
    border: 1.5px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    background-color: #fafafa;
    color: #333;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group select:focus {
    outline: none;
    border-color: #007bff;
    background-color: #fff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
}

/* Para mostrar etiquetas como texto plano (por ejemplo en placas o existencia) */
.form-group label[for="elm_placa"],
.form-group label[for="elm_existencia"] {
    padding: 10px 12px;
    background: #e9ecef;
    border-radius: 8px;
    border: 1.5px solid #ddd;
    cursor: default;
    user-select: none;
    font-weight: 500;
}

/* Acciones de botones */
.form-actions {
    grid-column: span 2;
    display: flex;
    justify-content: center;
    gap: 24px;
    margin-top: 30px;
}

.form-actions button,
.form-actions a {
    padding: 12px 28px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease;
    border: none;
    min-width: 140px;
    text-align: center;
}

.form-actions button {
    background-color: #007bff;
    color: white;
    border: 2px solid #007bff;
}

.form-actions button:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.form-actions a {
    background-color: #f0f0f0;
    color: #555;
    border: 2px solid #ccc;
    line-height: 1.3;
}

.form-actions a:hover {
    background-color: #ddd;
    border-color: #999;
    color: #333;
}

/* Responsive: en pantallas pequeñas apilar los campos */
@media (max-width: 760px) {
    .editar-form {
        grid-template-columns: 1fr;
        width: 100%;
        padding: 20px;
    }

    .form-actions {
        flex-direction: column;
        gap: 16px;
    }

    .form-actions button,
    .form-actions a {
        min-width: 100%;
    }
}

</style>

<div class="form-wrapper">
    <form class="editar-form" action="<?= getUrl('elementos', 'elementos', 'editarElemento', false, 'dashboard') ?>" method="POST">
        <h2>Editar Elemento</h2>

        <input type="hidden" name="elm_cod" value="<?= $elemento['elm_cod'] ?>">
        <input type="hidden" name="elm_cod_tp_elemento" value="<?= $elemento['elm_cod_tp_elemento'] ?>">

        <!-- Placa (No modificable) -->
        <div class="form-group">
            <label>Placa</label>
            <label><?= $elemento['elm_placa'] ?></label>
        </div>

        <!-- Nombre (Modificable) -->
        <div class="form-group">
            <label for="elm_nombre">Nombre</label>
            <input type="text" id="elm_nombre" name="elm_nombre" value="<?= $elemento['elm_nombre'] ?>" required>
        </div>

        <!-- Existencia (No modificable) -->
        <div class="form-group">
            <label>Existencia</label>
            <label><?= $elemento['elm_existencia'] ?></label>
        </div>

        <!-- Unidad de Medida (Modificable) -->
        <div class="form-group">
            <label for="elm_uni_medida">Unidad de Medida</label>
            <input type="number" id="elm_uni_medida" name="elm_uni_medida" value="<?= $elemento['elm_uni_medida'] ?>" required>
        </div>

        <!-- Tipo de Elemento (No modificable) -->
        <div class="form-group">
            <label>Tipo de Elemento</label>
            <label><?= $elemento['tipoElemento'] ?></label>
        </div>

        <!-- Área (Modificable) -->
        <div class="form-group">
            <label for="elm_area_cod">Área</label>
            <select id="elm_area_cod" name="elm_area_cod" required>
                <?php foreach ($areas as $area): ?>
                    <option value="<?= $area['codigo'] ?>" <?= ($area['codigo'] == $elemento['elm_area_cod']) ? 'selected' : '' ?>>
                        <?= $area['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Botones -->
        <div class="form-actions">
            <button type="submit">Guardar Cambios</button>
            <a href="<?= getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') ?>">Cancelar</a>
        </div>
    </form>
</div>
