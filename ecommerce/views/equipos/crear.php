<?php
/**
 * @var array $clientes
 * @var mixed $preClienteId
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>💻 Registrar Nuevo Equipo</h1>
    <p>Asocie una computadora, laptop u otro dispositivo a un cliente</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('equipo') ?>" class="btn btn-outline">
      🔙 Volver a Equipos
    </a>
  </div>
</div>

<div class="card" style="max-width:800px;margin:0 auto;">
  <div class="card-header">
    <h3>📝 Datos del Dispositivo</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('equipo/crear') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="form-group" style="grid-column:1 / -1;">
          <label for="id_cliente">Cliente Propietario *</label>
          <select name="id_cliente" id="id_cliente" class="form-control" required>
            <option value="">-- Seleccione un cliente --</option>
            <?php foreach ($clientes as $c): ?>
              <option value="<?= $c['id_cliente'] ?>" <?= ($preClienteId == $c['id_cliente']) ? 'selected' : '' ?>>
                <?= e($c['nombres_apellidos']) ?> (Tel: <?= e($c['telefono']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="tipo_equipo">Tipo de Dispositivo *</label>
          <select name="tipo_equipo" id="tipo_equipo" class="form-control" required>
            <option value="Laptop">Laptop / Portátil</option>
            <option value="Computadora Torre">Computadora Torre / PC Gamer</option>
            <option value="All-in-One">All-in-One (Todo en Uno)</option>
            <option value="Impresora">Impresora / Multifuncional</option>
            <option value="Consola / Otro">Consola / Servidor / Otro</option>
          </select>
        </div>

        <div class="form-group">
          <label for="marca">Marca *</label>
          <input type="text" id="marca" name="marca" class="form-control" required placeholder="Ej: Lenovo, ASUS, HP, Dell, Personalizada">
        </div>

        <div class="form-group">
          <label for="modelo">Modelo *</label>
          <input type="text" id="modelo" name="modelo" class="form-control" required placeholder="Ej: Legion 5 Pro, Inspiron 15, Custom Build">
        </div>

        <div class="form-group">
          <label for="numero_serie">Número de Serie (S/N)</label>
          <input type="text" id="numero_serie" name="numero_serie" class="form-control" placeholder="Ej: PF2ABCD1">
        </div>

        <div class="form-group">
          <label for="codigo_patrimonial">Código Patrimonial / Placa</label>
          <input type="text" id="codigo_patrimonial" name="codigo_patrimonial" class="form-control" placeholder="Opcional para empresas">
        </div>

        <div class="form-group">
          <label for="color_detalles">Color y Detalles Físicos</label>
          <input type="text" id="color_detalles" name="color_detalles" class="form-control" placeholder="Ej: Gris oscuro, sticker manzana en tapa">
        </div>
      </div>

      <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= url('equipo') ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow">💾 Guardar Equipo</button>
      </div>
    </form>
  </div>
</div>
