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

<style>
/* Estilos Glassmorphism para la edición de productos */
.glass-container {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 16px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    padding: 2rem;
    max-width: 900px;
    margin: 0 auto;
}

.glass-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid rgba(0,0,0,0.05);
    padding-bottom: 0.5rem;
}

.glass-tab-btn {
    background: transparent;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    color: var(--color-shadow);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.glass-tab-btn:hover {
    background: rgba(255, 255, 255, 0.5);
    color: var(--color-blue);
}

.glass-tab-btn.active {
    background: #fff;
    color: var(--color-blue);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.glass-tab-content {
    display: none;
    animation: fadeIn 0.4s ease forwards;
}

.glass-tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}

.glass-input {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.glass-input:focus {
    background: #fff;
    border-color: var(--color-yellow);
    box-shadow: 0 0 0 3px rgba(253, 224, 71, 0.3);
    outline: none;
}
</style>

<div class="page-header">
  <div>
    <h1>Modificar Producto: <?= e($producto['nombre']) ?></h1>
    <p>Organiza la información de forma estructurada para mantener tu catálogo perfecto.</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('producto') ?>" class="btn btn-outline">
      &larr; Volver al Catálogo
    </a>
  </div>
</div>

<div class="glass-container">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h3>Gestión Integral del Artículo</h3>
    <span class="badge" style="background:rgba(3, 105, 161, 0.1);color:#0369a1;backdrop-filter:blur(5px);border:1px solid rgba(3,105,161,0.2);">ID: <?= (int)$producto['id_producto'] ?></span>
  </div>
  
  <form action="<?= url('producto/editar/' . $producto['id_producto']) ?>" method="POST" enctype="multipart/form-data" id="form-producto">
    <?= csrf_field() ?>

    <div class="glass-tabs">
        <button type="button" class="glass-tab-btn active" data-target="tab-basico">
            <svg style="width:18px;height:18px;fill:currentColor;" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            Información Básica
        </button>
        <button type="button" class="glass-tab-btn" data-target="tab-precios">
            <svg style="width:18px;height:18px;fill:currentColor;" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
            Precios e Inventario
        </button>
        <button type="button" class="glass-tab-btn" data-target="tab-multimedia">
            <svg style="width:18px;height:18px;fill:currentColor;" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
            Multimedia y Visibilidad
        </button>
    </div>

    <!-- Pestaña 1: Información Básica -->
    <div class="glass-tab-content active" id="tab-basico">
        <div class="form-grid">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="nombre">Nombre Comercial del Producto *</label>
                <input type="text" id="nombre" name="nombre" class="form-control glass-input" required value="<?= e($producto['nombre']) ?>">
            </div>

            <div class="form-group">
                <label for="slug">Identificador URL (Slug)</label>
                <input type="text" id="slug" name="slug" class="form-control glass-input" value="<?= e($producto['slug']) ?>">
                <small style="color:var(--color-shadow);font-size:0.75rem;">Se generará automáticamente si lo dejas en blanco.</small>
            </div>

            <div class="form-group">
                <label for="id_categoria">Categoría *</label>
                <select name="id_categoria" id="id_categoria" class="form-control glass-input" required>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= (int)$cat['id_categoria'] ?>" <?= (int)$producto['id_categoria'] === (int)$cat['id_categoria'] ? 'selected' : '' ?>>
                        <?= e($cat['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="marca">Marca / Fabricante *</label>
                <input type="text" id="marca" name="marca" class="form-control glass-input" required value="<?= e($producto['marca']) ?>">
            </div>

            <div class="form-group">
                <label for="etiqueta">Etiqueta Promocional</label>
                <select name="etiqueta" id="etiqueta" class="form-control glass-input">
                    <option value="" <?= empty($producto['etiqueta']) ? 'selected' : '' ?>>Sin etiqueta</option>
                    <option value="Nuevo" <?= $producto['etiqueta'] === 'Nuevo' ? 'selected' : '' ?>>Nuevo</option>
                    <option value="Hot" <?= $producto['etiqueta'] === 'Hot' ? 'selected' : '' ?>>Hot / Más Vendido</option>
                    <option value="Oferta" <?= $producto['etiqueta'] === 'Oferta' ? 'selected' : '' ?>>Oferta Especial</option>
                    <option value="-10%" <?= $producto['etiqueta'] === '-10%' ? 'selected' : '' ?>>Descuento -10%</option>
                    <option value="-15%" <?= $producto['etiqueta'] === '-15%' ? 'selected' : '' ?>>Descuento -15%</option>
                    <option value="-20%" <?= $producto['etiqueta'] === '-20%' ? 'selected' : '' ?>>Descuento -20%</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="descripcion">Descripción y Especificaciones Técnicas</label>
                <textarea id="descripcion" name="descripcion" class="form-control glass-input" rows="5"><?= e($producto['descripcion']) ?></textarea>
            </div>
        </div>
    </div>

    <!-- Pestaña 2: Precios e Inventario -->
    <div class="glass-tab-content" id="tab-precios">
        <div class="form-grid">
            <div class="form-group">
                <label for="precio">Precio de Venta (S/ PEN) *</label>
                <div style="position:relative;">
                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--color-shadow);font-weight:bold;">S/</span>
                    <input type="number" step="0.01" min="0.01" id="precio" name="precio" class="form-control glass-input" style="padding-left:2rem;font-weight:bold;color:var(--color-blue);" required value="<?= (float)$producto['precio'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="precio_anterior">Precio Normal / Tachado</label>
                <div style="position:relative;">
                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--color-shadow);">S/</span>
                    <input type="number" step="0.01" min="0.00" id="precio_anterior" name="precio_anterior" class="form-control glass-input" style="padding-left:2rem;text-decoration:line-through;" value="<?= !empty($producto['precio_anterior']) ? (float)$producto['precio_anterior'] : '' ?>" placeholder="Ej: 3999.00">
                </div>
                <small style="color:var(--color-shadow);font-size:0.75rem;">Para mostrar ofertas visuales (ahorro).</small>
            </div>

            <div class="form-group">
                <label for="stock">Cantidad en Almacén *</label>
                <div style="position:relative;">
                    <input type="number" min="0" id="stock" name="stock" class="form-control glass-input" style="font-weight:bold;" required value="<?= (int)$producto['stock'] ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Pestaña 3: Multimedia y Visibilidad -->
    <div class="glass-tab-content" id="tab-multimedia">
        <div class="form-group" style="margin-bottom:2rem;">
          <label>Fotografía Principal del Producto</label>
          <div style="display:flex;gap:1.5rem;align-items:center;background:rgba(255,255,255,0.4);padding:1.5rem;border-radius:12px;border:1px dashed rgba(0,0,0,0.1);">
            <div style="width:120px;height:120px;border-radius:12px;overflow:hidden;background:#fff;border:1px solid #cbd5e1;flex-shrink:0;box-shadow:0 4px 10px rgba(0,0,0,0.05);">
              <img src="<?= asset('img/productos/' . ($producto['imagen'] ?: 'prod_1.jpg')) ?>" id="img-preview" alt="Vista previa" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='<?= asset('img/productos/prod_1.jpg') ?>'">
            </div>
            <div style="flex:1;">
              <div style="font-size:0.9rem;font-weight:600;color:var(--color-blue);margin-bottom:0.5rem;">
                Archivo actual: <code><?= e($producto['imagen'] ?: 'Ninguno') ?></code>
              </div>
              <input type="file" id="imagen" name="imagen" class="form-control glass-input" accept="image/jpeg,image/png,image/webp">
              <small style="color:var(--color-shadow);font-size:0.8rem;margin-top:0.5rem;display:block;">
                Formatos recomendados: JPG, PNG, WEBP. Relación de aspecto 1:1 (cuadrado).
              </small>
            </div>
          </div>
        </div>

        <div style="background:rgba(255,255,255,0.4);padding:1.5rem;border-radius:12px;border:1px solid rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;margin-bottom:1rem;color:var(--color-blue);font-size:1rem;">Opciones de Visibilidad</h4>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                    <input type="checkbox" name="destacado" value="1" <?= !empty($producto['destacado']) ? 'checked' : '' ?> style="width:20px;height:20px;accent-color:var(--color-yellow);">
                    <div style="display:flex;flex-direction:column;">
                        <span style="font-weight:600;">Destacar en Portada (Home)</span>
                        <span style="font-size:0.8rem;color:var(--color-shadow);">Aparecerá en la sección "Productos Destacados" de la página principal.</span>
                    </div>
                </label>
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                    <input type="checkbox" name="activo" value="1" <?= !empty($producto['activo']) ? 'checked' : '' ?> style="width:20px;height:20px;accent-color:var(--color-yellow);">
                    <div style="display:flex;flex-direction:column;">
                        <span style="font-weight:600;">Producto Activo para la Venta</span>
                        <span style="font-size:0.8rem;color:var(--color-shadow);">Si se desmarca, el producto se ocultará del catálogo público.</span>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div style="margin-top:2.5rem;display:flex;gap:1rem;justify-content:flex-end;border-top:1px solid rgba(0,0,0,0.05);padding-top:1.5rem;">
      <a href="<?= url('producto') ?>" class="btn btn-outline" style="padding:0.75rem 1.5rem;">Cancelar</a>
      <button type="submit" class="btn btn-yellow" style="padding:0.75rem 2rem;display:inline-flex;align-items:center;gap:0.5rem;font-size:1.05rem;box-shadow:0 4px 15px rgba(253, 224, 71, 0.4);">
        <svg style="width:20px;height:20px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
        <span>Guardar Cambios</span>
      </button>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Lógica de Tabs
    const tabBtns = document.querySelectorAll('.glass-tab-btn');
    const tabContents = document.querySelectorAll('.glass-tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remover active de todos
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Añadir active al clickeado
            btn.classList.add('active');
            document.getElementById(btn.dataset.target).classList.add('active');
        });
    });

    // Lógica Slugifier Automático (Si el usuario borra el slug, se auto-genera)
    const nombreInput = document.getElementById('nombre');
    const slugInput = document.getElementById('slug');
    
    function generarSlug(texto) {
        return texto.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Reemplazar espacios por -
            .replace(/[^\w\-]+/g, '')       // Remover caracteres no-word
            .replace(/\-\-+/g, '-')         // Reemplazar múltiples -
            .replace(/^-+/, '')             // Quitar - al inicio
            .replace(/-+$/, '');            // Quitar - al final
    }

    nombreInput.addEventListener('input', () => {
        // Solo autogenerar si el slug está vacío o el usuario quiere resetearlo
        // Podríamos forzarlo si está vacío
    });
    
    // Si el usuario enfoca fuera del nombre y el slug está vacío, llenarlo
    nombreInput.addEventListener('blur', () => {
        if (!slugInput.value.trim()) {
            slugInput.value = generarSlug(nombreInput.value);
        }
    });

    // Vista previa de imagen
    const imageInput = document.getElementById('imagen');
    const imagePreview = document.getElementById('img-preview');
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});
</script>
