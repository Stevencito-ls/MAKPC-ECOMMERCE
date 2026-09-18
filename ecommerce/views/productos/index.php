<?php
/**
 * @var array $productos
 * @var array $categorias
 * @var string $busqueda
 * @var int|null $categoriaSeleccionada
 * @var string $stockFiltro
 * @var int $totalProductos
 * @var int $productosActivos
 * @var int $stockBajo
 * @var int $agotados
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;">
      <h1 style="margin:0;display:flex;align-items:center;gap:0.5rem;">
        <svg style="width:28px;height:28px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12zm-7-8c-1.66 0-3-1.34-3-3H7c0 2.76 2.24 5 5 5s5-2.24 5-5h-2c0 1.66-1.34 3-3 3z"/></svg>
        <span>Catálogo de Productos & Inventario</span>
      </h1>
      <span class="badge badge-<?= strtolower(e(auth('rol'))) ?>" style="font-size:0.75rem;padding:0.25rem 0.65rem;">
        Rol: <?= ucfirst(e(auth('rol'))) ?>
      </span>
    </div>
    <p style="margin-top:0.35rem;">Gestione el catálogo comercial, modifique precios, actualice existencias y suba nuevos productos para la tienda.</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('producto/crear') ?>" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
      <span>Subir Nuevo Producto</span>
    </a>
    <a href="<?= url('tienda') ?>" class="btn btn-outline" target="_blank" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
      <span>Ver en Tienda</span>
    </a>
  </div>
</div>

<!-- Métricas rápidas de inventario -->
<div class="kpi-grid" style="margin-bottom:1.5rem;">
  <div class="kpi-card">
    <div class="kpi-icon blue">
      <svg viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Total Productos</h3>
      <div class="kpi-value"><?= (int)$totalProductos ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon green">
      <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Activos en Catálogo</h3>
      <div class="kpi-value"><?= (int)$productosActivos ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon yellow">
      <svg viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Stock Bajo (&le; 5)</h3>
      <div class="kpi-value" style="color:#d97706;"><?= (int)$stockBajo ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon" style="background:rgba(239, 68, 68, 0.15);color:#dc2626;">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 13.59z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Agotados</h3>
      <div class="kpi-value" style="color:#dc2626;"><?= (int)$agotados ?></div>
    </div>
  </div>
</div>

<!-- Filtros de búsqueda -->
<div class="card" style="margin-bottom:1.5rem;">
  <div class="card-body" style="padding:1.25rem;">
    <form action="<?= url('producto') ?>" method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
      <div style="flex:1;min-width:240px;">
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:0.35rem;color:var(--color-blue);">Buscar por Nombre o Marca</label>
        <input type="text" name="q" value="<?= e($busqueda) ?>" class="form-control" placeholder="Ej: Laptop gamer, Monitor, Logitech...">
      </div>

      <div style="min-width:180px;">
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:0.35rem;color:var(--color-blue);">Categoría</label>
        <select name="cat" class="form-control">
          <option value="">Todas las Categorías</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= (int)$cat['id_categoria'] ?>" <?= (string)$categoriaSeleccionada === (string)$cat['id_categoria'] ? 'selected' : '' ?>>
              <?= e($cat['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="min-width:160px;">
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:0.35rem;color:var(--color-blue);">Estado de Stock</label>
        <select name="stock" class="form-control">
          <option value="">Cualquier Stock</option>
          <option value="disponible" <?= $stockFiltro === 'disponible' ? 'selected' : '' ?>>Disponible (> 5)</option>
          <option value="bajo" <?= $stockFiltro === 'bajo' ? 'selected' : '' ?>>Stock Bajo (&le; 5)</option>
          <option value="agotado" <?= $stockFiltro === 'agotado' ? 'selected' : '' ?>>Agotado (0)</option>
        </select>
      </div>

      <div style="display:flex;gap:0.5rem;">
        <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.35rem;">
          <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
          <span>Filtrar</span>
        </button>
        <?php if (!empty($busqueda) || !empty($categoriaSeleccionada) || !empty($stockFiltro)): ?>
          <a href="<?= url('producto') ?>" class="btn btn-outline">Limpiar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- Tabla de Productos -->
<div class="card">
  <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
    <h2 style="margin:0;font-size:1.15rem;display:flex;align-items:center;gap:0.5rem;">
      <span>Artículos en Catálogo</span>
      <span style="font-size:0.85rem;color:var(--color-shadow);font-weight:400;">(<?= count($productos) ?> listados)</span>
    </h2>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th style="width:70px;">Imagen</th>
          <th>Producto / Marca</th>
          <th>Categoría</th>
          <th>Precio de Venta</th>
          <th>Stock</th>
          <th>Estado</th>
          <th style="text-align:right;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($productos)): ?>
          <tr>
            <td colspan="7" style="text-align:center;padding:3rem;color:var(--color-shadow);">
              <svg style="width:40px;height:40px;fill:var(--color-shadow);margin:0 auto 0.75rem auto;" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
              <div>No se encontraron productos que coincidan con los filtros.</div>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($productos as $p): 
            $imgSrc = !empty($p['imagen']) ? asset('img/productos/' . $p['imagen']) : asset('img/productos/prod_1.jpg');
            $stockBadge = '';
            if ((int)$p['stock'] <= 0) {
              $stockBadge = '<span class="badge" style="background:#fee2e2;color:#991b1b;border:1px solid #f87171;">Agotado (0)</span>';
            } elseif ((int)$p['stock'] <= 5) {
              $stockBadge = '<span class="badge" style="background:#fef3c7;color:#92400e;border:1px solid #fcd34d;">Bajo (' . (int)$p['stock'] . ')</span>';
            } else {
              $stockBadge = '<span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #86efac;">' . (int)$p['stock'] . ' disp.</span>';
            }
          ?>
            <tr>
              <td>
                <div style="width:50px;height:50px;background:#f1f5f9;border-radius:8px;overflow:hidden;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;">
                  <img src="<?= $imgSrc ?>" alt="<?= e($p['nombre']) ?>" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='<?= asset('img/productos/prod_1.jpg') ?>'">
                </div>
              </td>
              <td>
                <div style="font-weight:700;color:var(--color-blue);"><?= e($p['nombre']) ?></div>
                <div style="font-size:0.75rem;color:var(--color-shadow);margin-top:0.15rem;">
                  Marca: <strong><?= e($p['marca'] ?: 'MAKPC') ?></strong> | SKU: <code><?= e($p['slug']) ?></code>
                </div>
              </td>
              <td>
                <span style="font-size:0.82rem;font-weight:600;color:var(--color-celeste);">
                  <?= e($p['categoria_nombre'] ?? 'Sin categoría') ?>
                </span>
              </td>
              <td>
                <div style="font-weight:800;color:var(--color-blue);font-size:0.95rem;">
                  <?= formatPrecio($p['precio']) ?>
                </div>
                <?php if (!empty($p['precio_anterior']) && (float)$p['precio_anterior'] > (float)$p['precio']): ?>
                  <div style="font-size:0.75rem;color:var(--color-shadow);text-decoration:line-through;">
                    <?= formatPrecio($p['precio_anterior']) ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <?= $stockBadge ?>
              </td>
              <td>
                <?php if (!empty($p['activo'])): ?>
                  <span class="badge" style="background:#dcfce7;color:#166534;">Activo</span>
                <?php else: ?>
                  <span class="badge" style="background:#f1f5f9;color:#64748b;">Inactivo</span>
                <?php endif; ?>
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex;gap:0.4rem;align-items:center;">
                  <a href="<?= url('producto/editar/' . $p['id_producto']) ?>" class="btn btn-sm btn-primary" title="Editar producto">
                    Editar
                  </a>
                  <a href="<?= url('producto/toggle/' . $p['id_producto']) ?>" class="btn btn-sm btn-outline" title="Alternar visibilidad">
                    <?= !empty($p['activo']) ? 'Ocultar' : 'Activar' ?>
                  </a>
                  <?php if (hasRole('admin')): ?>
                    <a href="<?= url('producto/eliminar/' . $p['id_producto']) ?>" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;" onclick="return confirm('¿Seguro de eliminar este producto definitivamente?');" title="Eliminar">
                      &times;
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
