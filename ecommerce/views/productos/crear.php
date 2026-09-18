<?php
/**
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
    <h1>Subir Nuevo Producto al Catálogo</h1>
    <p>Ingrese los detalles del artículo, precio de venta, existencia en inventario e imagen de producto.</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('producto') ?>" class="btn btn-outline">
      &larr; Volver al Listado
    </a>
  </div>
</div>

<div class="card" style="max-width:850px;margin:0 auto;">
  <div class="card-header">
    <h3>Detalles del Producto Comercial</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('producto/crear') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="form-group" style="grid-column: 1 / -1;">
          <label for="nombre">Nombre Comercial del Producto *</label>
          <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Ej: Laptop ASUS TUF Gaming A15 Ryzen 7 16GB 512GB RTX 4050" autofocus>
        </div>

        <div class="form-group">
          <label for="id_categoria">Categoría *</label>
          <select name="id_categoria" id="id_categoria" class="form-control" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categorias as $cat): ?>
              <option value="<?= (int)$cat['id_categoria'] ?>"><?= e($cat['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="marca">Marca / Fabricante *</label>
          <input type="text" id="marca" name="marca" class="form-control" required placeholder="Ej: ASUS, Lenovo, Logitech, Kingston">
        </div>

        <div class="form-group">
          <label for="precio">Precio de Venta (S/ PEN) *</label>
          <input type="number" step="0.01" min="0.01" id="precio" name="precio" class="form-control" required placeholder="Ej: 3499.00">
        </div>

        <div class="form-group">
          <label for="precio_anterior">Precio Normal / Tachado (Opcional)</label>
          <input type="number" step="0.01" min="0.00" id="precio_anterior" name="precio_anterior" class="form-control" placeholder="Ej: 3999.00 (para mostrar descuento)">
        </div>

        <div class="form-group">
          <label for="stock">Cantidad en Stock / Inventario *</label>
          <input type="number" min="0" id="stock" name="stock" class="form-control" required value="10" placeholder="Ej: 15">
        </div>

        <div class="form-group">
          <label for="etiqueta">Etiqueta Promocional</label>
          <select name="etiqueta" id="etiqueta" class="form-control">
            <option value="">Sin etiqueta</option>
            <option value="Nuevo">Nuevo</option>
            <option value="Hot">Hot / Más Vendido</option>
            <option value="Oferta">Oferta Especial</option>
            <option value="-10%">Descuento -10%</option>
            <option value="-15%">Descuento -15%</option>
            <option value="-20%">Descuento -20%</option>
            <option value="-25%">Descuento -25%</option>
            <option value="-30%">Descuento -30%</option>
          </select>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label for="descripcion">Descripción y Especificaciones Técnicas</label>
          <textarea id="descripcion" name="descripcion" class="form-control" rows="4" placeholder="Detalles de hardware, conectividad, puertos, pantalla, garantía, etc."></textarea>
        </div>

        <!-- Carga de Imagen -->
        <div class="form-group">
          <label for="imagen">Subir Fotografía Real (JPG, PNG, WebP)</label>
          <input type="file" id="imagen" name="imagen" class="form-control" accept="image/jpeg,image/png,image/webp">
          <small style="color:var(--color-shadow);font-size:0.75rem;display:block;margin-top:0.25rem;">
            Resolución recomendada: 600x600 px o superior con fondo limpio.
          </small>
        </div>

        <div class="form-group">
          <label for="imagen_preset">O seleccionar imagen de referencia:</label>
          <select name="imagen_preset" id="imagen_preset" class="form-control">
            <option value="prod_1.jpg">Laptop Gamer RTX (prod_1.jpg)</option>
            <option value="prod_2.jpg">Monitor IPS QHD (prod_2.jpg)</option>
            <option value="prod_3.jpg">Teclado Mecánico RGB (prod_3.jpg)</option>
            <option value="prod_4.jpg">Mouse Ergonómico (prod_4.jpg)</option>
            <option value="prod_5.jpg">Audífonos Gamer 7.1 (prod_5.jpg)</option>
            <option value="prod_6.jpg">SSD NVMe PCIe Gen4 (prod_6.jpg)</option>
            <option value="prod_7.jpg">Laptop Ultrabook Core i7 (prod_7.jpg)</option>
            <option value="prod_8.jpg">PC Gamer Ryzen RTX (prod_8.jpg)</option>
            <option value="prod_9.jpg">Memoria RAM DDR5 (prod_9.jpg)</option>
            <option value="prod_10.jpg">Impresora Multifuncional (prod_10.jpg)</option>
            <option value="prod_11.jpg">Router WiFi 6 (prod_11.jpg)</option>
            <option value="prod_12.jpg">Hub USB-C 7 en 1 (prod_12.jpg)</option>
          </select>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;display:flex;gap:2rem;margin-top:0.5rem;">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
            <input type="checkbox" name="destacado" value="1" checked style="width:18px;height:18px;">
            <span style="font-weight:600;">Destacar en Portada / Home</span>
          </label>
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
            <input type="checkbox" name="activo" value="1" checked style="width:18px;height:18px;">
            <span style="font-weight:600;">Producto Activo para la Venta</span>
          </label>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;gap:1rem;justify-content:flex-end;border-top:1px solid #edf0f5;padding-top:1.25rem;">
        <a href="<?= url('producto') ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.5rem;">
          <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <span>Publicar Producto en Catálogo</span>
        </button>
      </div>
    </form>
  </div>
</div>
