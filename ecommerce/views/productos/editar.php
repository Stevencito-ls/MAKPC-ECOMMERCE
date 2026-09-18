<?php
/**
 * @var array $producto
 * @var array $categorias
 * @var string $title
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>Modificar Producto: <?= e($producto['nombre']) ?></h1>
    <p>Actualice el inventario, modifique los precios de venta, suba nueva fotografía o cambie especificaciones.</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('producto') ?>" class="btn btn-outline">
      &larr; Volver al Catálogo
    </a>
  </div>
</div>

<div class="card" style="max-width:850px;margin:0 auto;">
  <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
    <h3>Datos del Artículo #<?= (int)$producto['id_producto'] ?></h3>
    <span class="badge" style="background:#e0f2fe;color:#0369a1;">ID: <?= (int)$producto['id_producto'] ?></span>
  </div>
  <div class="card-body">
    <form action="<?= url('producto/editar/' . $producto['id_producto']) ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="form-group" style="grid-column: 1 / -1;">
          <label for="nombre">Nombre Comercial del Producto *</label>
          <input type="text" id="nombre" name="nombre" class="form-control" required value="<?= e($producto['nombre']) ?>">
        </div>

        <div class="form-group">
          <label for="slug">Identificador URL (Slug)</label>
          <input type="text" id="slug" name="slug" class="form-control" value="<?= e($producto['slug']) ?>">
          <small style="color:var(--color-shadow);font-size:0.75rem;">Dejar tal cual para conservar enlaces permanentes.</small>
        </div>

        <div class="form-group">
          <label for="id_categoria">Categoría *</label>
          <select name="id_categoria" id="id_categoria" class="form-control" required>
            <?php foreach ($categorias as $cat): ?>
              <option value="<?= (int)$cat['id_categoria'] ?>" <?= (int)$producto['id_categoria'] === (int)$cat['id_categoria'] ? 'selected' : '' ?>>
                <?= e($cat['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="marca">Marca / Fabricante *</label>
          <input type="text" id="marca" name="marca" class="form-control" required value="<?= e($producto['marca']) ?>">
        </div>

        <div class="form-group">
          <label for="precio">Precio de Venta (S/ PEN) *</label>
          <input type="number" step="0.01" min="0.01" id="precio" name="precio" class="form-control" required value="<?= (float)$producto['precio'] ?>">
        </div>

        <div class="form-group">
          <label for="precio_anterior">Precio Normal / Tachado</label>
          <input type="number" step="0.01" min="0.00" id="precio_anterior" name="precio_anterior" class="form-control" value="<?= !empty($producto['precio_anterior']) ? (float)$producto['precio_anterior'] : '' ?>" placeholder="Ej: 3999.00">
        </div>

        <div class="form-group">
          <label for="stock">Cantidad en Stock *</label>
          <input type="number" min="0" id="stock" name="stock" class="form-control" required value="<?= (int)$producto['stock'] ?>">
        </div>

        <div class="form-group">
          <label for="etiqueta">Etiqueta Promocional</label>
          <select name="etiqueta" id="etiqueta" class="form-control">
            <option value="" <?= empty($producto['etiqueta']) ? 'selected' : '' ?>>Sin etiqueta</option>
            <option value="Nuevo" <?= $producto['etiqueta'] === 'Nuevo' ? 'selected' : '' ?>>Nuevo</option>
            <option value="Hot" <?= $producto['etiqueta'] === 'Hot' ? 'selected' : '' ?>>Hot / Más Vendido</option>
            <option value="Oferta" <?= $producto['etiqueta'] === 'Oferta' ? 'selected' : '' ?>>Oferta Especial</option>
            <option value="-10%" <?= $producto['etiqueta'] === '-10%' ? 'selected' : '' ?>>Descuento -10%</option>
            <option value="-15%" <?= $producto['etiqueta'] === '-15%' ? 'selected' : '' ?>>Descuento -15%</option>
            <option value="-20%" <?= $producto['etiqueta'] === '-20%' ? 'selected' : '' ?>>Descuento -20%</option>
            <option value="-25%" <?= $producto['etiqueta'] === '-25%' ? 'selected' : '' ?>>Descuento -25%</option>
            <option value="-30%" <?= $producto['etiqueta'] === '-30%' ? 'selected' : '' ?>>Descuento -30%</option>
          </select>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label for="descripcion">Descripción y Especificaciones Técnicas</label>
          <textarea id="descripcion" name="descripcion" class="form-control" rows="4"><?= e($producto['descripcion']) ?></textarea>
        </div>

        <!-- Imagen Actual y Reemplazo -->
        <div class="form-group" style="grid-column: 1 / -1;">
          <label>Fotografía del Producto</label>
          <div style="display:flex;gap:1.5rem;align-items:center;background:#f8fafc;padding:1rem;border-radius:10px;border:1px solid #e2e8f0;">
            <div style="width:80px;height:80px;border-radius:8px;overflow:hidden;background:#fff;border:1px solid #cbd5e1;flex-shrink:0;">
              <img src="<?= asset('img/productos/' . ($producto['imagen'] ?: 'prod_1.jpg')) ?>" alt="<?= e($producto['nombre']) ?>" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='<?= asset('img/productos/prod_1.jpg') ?>'">
            </div>
            <div style="flex:1;">
              <div style="font-size:0.82rem;font-weight:600;color:var(--color-blue);margin-bottom:0.35rem;">
                Archivo actual: <code><?= e($producto['imagen'] ?: 'Sin imagen') ?></code>
              </div>
              <input type="file" id="imagen" name="imagen" class="form-control" accept="image/jpeg,image/png,image/webp">
              <small style="color:var(--color-shadow);font-size:0.75rem;margin-top:0.25rem;display:block;">
                Seleccione un nuevo archivo solo si desea reemplazar la imagen actual.
              </small>
            </div>
          </div>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;display:flex;gap:2rem;margin-top:0.5rem;">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
            <input type="checkbox" name="destacado" value="1" <?= !empty($producto['destacado']) ? 'checked' : '' ?> style="width:18px;height:18px;">
            <span style="font-weight:600;">Destacar en Portada / Home</span>
          </label>
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
            <input type="checkbox" name="activo" value="1" <?= !empty($producto['activo']) ? 'checked' : '' ?> style="width:18px;height:18px;">
            <span style="font-weight:600;">Producto Activo para la Venta</span>
          </label>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;gap:1rem;justify-content:flex-end;border-top:1px solid #edf0f5;padding-top:1.25rem;">
        <a href="<?= url('producto') ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.5rem;">
          <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <span>Guardar Cambios</span>
        </button>
      </div>
    </form>
  </div>
</div>
