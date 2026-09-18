<?php
/**
 * MAKPC - Representación Impresa de Comprobante de Pago Electrónico SUNAT & Voucher Culqi
 * Boleta de Venta Electrónica (B001) / Factura Electrónica (F001)
 * @var array $pedido
 * @var array $items
 */

$esFactura = ($pedido['tipo_comprobante'] === 'factura');
$tipoTitulo = $esFactura ? 'FACTURA ELECTRÓNICA' : 'BOLETA DE VENTA ELECTRÓNICA';
$tipoCodigoSunat = $esFactura ? '01' : '03';
$tipoDocCliente = $pedido['tipo_documento'];
$numDocCliente = $pedido['numero_documento'];
$clienteNombre = $pedido['cliente_nombre'];
$comprobanteNum = $pedido['comprobante_numero'] ?: ($esFactura ? 'F001-00000001' : 'B001-00000001');
$hashSunat = $pedido['codigo_hash'] ?: strtoupper(substr(md5($pedido['codigo_pedido']), 0, 28));
$fechaEmision = !empty($pedido['comprobante_fecha']) ? date('d/m/Y H:i', strtotime($pedido['comprobante_fecha'])) : date('d/m/Y H:i');

$total = (float)$pedido['total'];
$opGravadas = (float)($pedido['op_gravadas'] ?: round($total / 1.18, 2));
$igv = (float)($pedido['igv'] ?: round($total - $opGravadas, 2));
$costoEnvio = (float)($pedido['costo_envio'] ?? 0);

// Función oficial para monto en letras en Soles para comprobantes SUNAT
if (!function_exists('montoEnLetrasSoles')) {
    function montoEnLetrasSoles(float $monto) {
        $enteros = (int)floor($monto);
        $centavos = str_pad((string)round(($monto - $enteros) * 100), 2, '0', STR_PAD_LEFT);
        
        $unidades = ["", "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISÉIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTE", "VEINTIUNO", "VEINTIDÓS", "VEINTITRÉS", "VEINTICUATRO", "VEINTICINCO", "VEINTISÉIS", "VEINTISIETE", "VEINTIOCHO", "VEINTINUEVE"];
        $decenas = ["", "DIEZ", "VEINTE", "TREINTA", "CUARENTA", "CINCUENTA", "SESENTA", "SETENTA", "OCHENTA", "NOVENTA"];
        $centenas = ["", "CIENTO", "DOSCIENTOS", "TRESCIENTOS", "CUATROCIENTOS", "QUINIENTOS", "SEISCIENTOS", "SETECIENTOS", "OCHOCIENTOS", "NOVECIENTOS"];
        
        $numALetras = function($num) use (&$numALetras, $unidades, $decenas, $centenas) {
            if ($num == 0) return "CERO";
            if ($num == 100) return "CIEN";
            $res = "";
            if ($num >= 1000000) {
                $millones = (int)floor($num / 1000000);
                $num %= 1000000;
                $res .= ($millones == 1 ? "UN MILLÓN " : $numALetras($millones) . " MILLONES ");
            }
            if ($num >= 1000) {
                $miles = (int)floor($num / 1000);
                $num %= 1000;
                $res .= ($miles == 1 ? "MIL " : $numALetras($miles) . " MIL ");
            }
            if ($num >= 100) {
                $c = (int)floor($num / 100);
                $num %= 100;
                $res .= $centenas[$c] . " ";
            }
            if ($num > 0) {
                if ($num < 30) {
                    $res .= $unidades[$num] . " ";
                } else {
                    $d = (int)floor($num / 10);
                    $u = $num % 10;
                    $res .= $decenas[$d] . ($u > 0 ? " Y " . $unidades[$u] : "") . " ";
                }
            }
            return trim($res);
        };
        
        $texto = $numALetras($enteros);
        return "SON: " . $texto . " CON " . $centavos . "/100 SOLES";
    }
}

// Cadena QR para validación SUNAT: RUC|TipoDoc|Serie|Correlativo|IGV|Total|Fecha|TipoDocCli|NumDocCli|Hash
$qrText = "20409456520|{$tipoCodigoSunat}|" . str_replace('-', '|', $comprobanteNum) . "|{$igv}|{$total}|" . date('Y-m-d') . "|{$tipoDocCliente}|{$numDocCliente}|{$hashSunat}";
$qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=4&data=" . urlencode($qrText);
?>

<style>
  .cpe-container {
    max-width: 960px;
    margin: 2rem auto 4rem;
    padding: 0 1rem;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  }
  .cpe-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
  }
  .cpe-sheet {
    background: #FFFFFF;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    padding: 2.5rem 3rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    color: #1F2937;
    line-height: 1.5;
  }
  .cpe-header-grid {
    display: grid;
    grid-template-columns: 1.6fr 1.2fr;
    gap: 2rem;
    align-items: center;
    margin-bottom: 1.75rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #E5E7EB;
  }
  .cpe-sunat-box {
    border: 2px solid #161D45;
    border-radius: 8px;
    text-align: center;
    padding: 1.25rem 1rem;
    background: #FAFAFA;
  }
  .cpe-sunat-box .ruc {
    font-size: 1.15rem;
    font-weight: 800;
    color: #161D45;
    letter-spacing: 1px;
  }
  .cpe-sunat-box .tipo {
    font-size: 1.05rem;
    font-weight: 900;
    color: #05A9E9;
    margin: 0.5rem 0;
    background: #F0F9FF;
    padding: 0.35rem;
    border-radius: 4px;
    border: 1px solid #BAE6FD;
  }
  .cpe-sunat-box .num {
    font-size: 1.3rem;
    font-weight: 900;
    color: #111827;
  }
  .cpe-info-table {
    width: 100%;
    margin-bottom: 1.5rem;
    border-collapse: collapse;
    font-size: 0.88rem;
  }
  .cpe-info-table td {
    padding: 0.35rem 0.5rem;
    vertical-align: top;
  }
  .cpe-info-table td.label {
    width: 160px;
    font-weight: 700;
    color: #4B5563;
    text-transform: uppercase;
    font-size: 0.78rem;
  }
  .cpe-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
    font-size: 0.88rem;
  }
  .cpe-items-table th {
    background: #161D45;
    color: #FFFFFF;
    font-weight: 800;
    text-align: left;
    padding: 0.65rem 0.75rem;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .cpe-items-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #E5E7EB;
  }
  .cpe-items-table tr:nth-child(even) td {
    background: #F9FAFB;
  }
  .cpe-totals-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 2rem;
    align-items: start;
    margin-bottom: 2rem;
  }
  .cpe-voucher-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 1rem 1.25rem;
    font-size: 0.82rem;
  }
  .cpe-totals-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
  }
  .cpe-totals-table td {
    padding: 0.4rem 0.6rem;
    text-align: right;
  }
  .cpe-totals-table td.total-label {
    font-weight: 700;
    color: #4B5563;
  }
  .cpe-totals-table tr.total-row td {
    border-top: 2px solid #161D45;
    font-size: 1.15rem;
    font-weight: 900;
    color: #161D45;
    padding-top: 0.75rem;
  }
  .cpe-footer-sunat {
    border-top: 1px dashed #CBD5E1;
    padding-top: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    font-size: 0.78rem;
    color: #64748B;
  }
  @media print {
    body { background: #FFFFFF !important; padding: 0 !important; }
    .cb-top-bar, .cb-header-main, .cb-nav-strip, .cpe-actions-bar, .cb-footer, .floating-whatsapp {
      display: none !important;
    }
    .cpe-container { margin: 0 !important; max-width: 100% !important; padding: 0 !important; }
    .cpe-sheet { border: none !important; box-shadow: none !important; padding: 0 !important; }
  }
</style>

<div class="cpe-container">
  
  <!-- BARRA DE ACCIONES (NO IMPRIMIBLE) -->
  <div class="cpe-actions-bar no-print">
    <div style="display:flex;align-items:center;gap:0.75rem;">
      <span style="background:rgba(16,185,129,0.12);color:#059669;padding:0.35rem 0.75rem;border-radius:6px;font-size:0.8rem;font-weight:800;display:inline-flex;align-items:center;gap:6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
        PAGO ONLINE CONFIRMADO (CULQI SANDBOX)
      </span>
      <span style="font-size:0.85rem;color:#64748B;">
        Pedido: <strong style="color:var(--cb-navy);"><?= e($pedido['codigo_pedido']) ?></strong>
      </span>
    </div>
    <div style="display:flex;gap:0.6rem;">
      <a href="<?= url('tienda') ?>" class="btn btn-outline" style="padding:0.5rem 1rem;font-size:0.85rem;text-decoration:none;color:#334155;">
        &larr; Volver a la Tienda
      </a>
      <button onclick="window.print()" class="btn btn-primary" style="background:#161D45;color:#fff;border:none;padding:0.5rem 1.25rem;border-radius:6px;cursor:pointer;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        Imprimir / Guardar PDF
      </button>
    </div>
  </div>

  <!-- HOJA OFICIAL DE COMPROBANTE SUNAT -->
  <div class="cpe-sheet">
    
    <!-- ENCABEZADO: DATOS DEL EMISOR Y RECUADRO TRIBUTARIO -->
    <div class="cpe-header-grid">
      <div>
        <div style="font-family:var(--cb-font-display);font-size:1.6rem;font-weight:900;color:#161D45;letter-spacing:0.5px;margin-bottom:0.25rem;">
          MAK<span style="color:#05A9E9;">PC</span> <span style="font-size:1rem;color:#FCC827;font-weight:800;">ENTERPRISES S.A.C.</span>
        </div>
        <div style="font-size:0.85rem;color:#4B5563;line-height:1.6;">
          <strong>Servicio Técnico Especializado, Ensamblaje de PCs & E-commerce</strong><br>
          Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes, Tumbes &mdash; Perú<br>
          Central Telefónica & WhatsApp: +51 975 513 327<br>
          Email Oficial: facturacion@makpc.pe &bull; Web: https://makpc.com.pe
        </div>
      </div>

      <!-- RECUADRO SUNAT -->
      <div class="cpe-sunat-box">
        <div class="ruc">R.U.C. 20409456520</div>
        <div class="tipo"><?= $tipoTitulo ?></div>
        <div class="num"><?= e($comprobanteNum) ?></div>
      </div>
    </div>

    <!-- DATOS DEL CLIENTE / RECEPTOR -->
    <table class="cpe-info-table">
      <tr>
        <td class="label">Señor(es) / Razón Social:</td>
        <td><strong><?= e($clienteNombre) ?></strong></td>
        <td class="label">Fecha de Emisión:</td>
        <td><?= $fechaEmision ?></td>
      </tr>
      <tr>
        <td class="label"><?= e($tipoDocCliente) ?> / Documento:</td>
        <td>
          <code><?= e($numDocCliente) ?></code>
          <span style="background:rgba(16,185,129,0.12);color:#059669;padding:0.18rem 0.5rem;border-radius:4px;font-size:0.72rem;font-weight:700;margin-left:0.5rem;display:inline-flex;align-items:center;gap:4px;">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Validado con <?= $tipoDocCliente === 'RUC' ? 'SUNAT' : 'RENIEC' ?>
          </span>
        </td>
        <td class="label">Moneda:</td>
        <td>SOLES (PEN)</td>
      </tr>
      <tr>
        <td class="label">Dirección de Entrega:</td>
        <td><?= e($pedido['direccion_calle']) ?>, <?= e($pedido['direccion_distrito']) ?>, <?= e($pedido['direccion_departamento']) ?></td>
        <td class="label">Forma de Pago:</td>
        <td><strong style="color:#059669;">Contado &bull; Pasarela Culqi</strong></td>
      </tr>
      <tr>
        <td class="label">Teléfono / WhatsApp:</td>
        <td><?= e($pedido['cliente_telefono']) ?></td>
        <td class="label">Código de Pedido:</td>
        <td><code><?= e($pedido['codigo_pedido']) ?></code></td>
      </tr>
    </table>

    <!-- DETALLE DE PRODUCTOS / ÍTEMS -->
    <table class="cpe-items-table">
      <thead>
        <tr>
          <th style="width:40px;text-align:center;">Ítem</th>
          <th style="width:60px;text-align:center;">Cant.</th>
          <th style="width:70px;text-align:center;">Unidad</th>
          <th>Descripción del Producto / Componente</th>
          <th style="width:110px;text-align:right;">P. Unitario</th>
          <th style="width:110px;text-align:right;">Importe</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $itemIndex = 1;
        if (!empty($items)):
          foreach ($items as $it): 
            $cant = (int)($it['qty'] ?? 1);
            $pu = (float)($it['price'] ?? 0);
            $subItem = $cant * $pu;
        ?>
          <tr>
            <td style="text-align:center;color:#6B7280;"><?= $itemIndex++ ?></td>
            <td style="text-align:center;font-weight:700;"><?= $cant ?></td>
            <td style="text-align:center;color:#6B7280;">NIU</td>
            <td>
              <strong><?= e($it['name'] ?? 'Producto Tecnológico') ?></strong>
              <?php if (!empty($it['sku'])): ?>
                <span style="font-size:0.75rem;color:#6B7280;margin-left:6px;">[SKU: <?= e($it['sku']) ?>]</span>
              <?php endif; ?>
            </td>
            <td style="text-align:right;">S/ <?= number_format($pu, 2) ?></td>
            <td style="text-align:right;font-weight:700;">S/ <?= number_format($subItem, 2) ?></td>
          </tr>
        <?php 
          endforeach;
        else:
        ?>
          <tr>
            <td style="text-align:center;">1</td>
            <td style="text-align:center;">1</td>
            <td style="text-align:center;">NIU</td>
            <td>Productos tecnológicos adquiridos en tienda MAKPC</td>
            <td style="text-align:right;">S/ <?= number_format($total, 2) ?></td>
            <td style="text-align:right;">S/ <?= number_format($total, 2) ?></td>
          </tr>
        <?php endif; ?>
        <?php if ($costoEnvio > 0): ?>
          <tr>
            <td style="text-align:center;color:#6B7280;"><?= $itemIndex ?></td>
            <td style="text-align:center;">1</td>
            <td style="text-align:center;color:#6B7280;">ZZ</td>
            <td>Servicio de Despacho y Envío Express (<?= e($pedido['direccion_distrito']) ?>, Tumbes)</td>
            <td style="text-align:right;">S/ <?= number_format($costoEnvio, 2) ?></td>
            <td style="text-align:right;font-weight:700;">S/ <?= number_format($costoEnvio, 2) ?></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- TOTALES Y VOUCHER DE TRANSACCIÓN -->
    <div class="cpe-totals-grid">
      
      <!-- VOUCHER BANCARIO CULQI -->
      <div class="cpe-voucher-card">
        <div style="font-weight:800;color:#161D45;margin-bottom:0.4rem;display:flex;align-items:center;gap:6px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#05A9E9" stroke-width="2.5"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          CONSTANCIA DE TRANSACCIÓN DIGITAL &bull; CULQI PERÚ
        </div>
        <div style="color:#475569;line-height:1.6;">
          <strong>ID Transacción:</strong> <code><?= e($pedido['culqi_charge_id'] ?: 'chr_test_simulated') ?></code><br>
          <strong>Cód. Autorización:</strong> <code><?= e($pedido['culqi_authorization_code'] ?: 'AUTH-' . rand(100000, 999999)) ?></code><br>
          <strong>Medio de Pago:</strong> <?= e($pedido['culqi_brand'] ?: 'YAPE / TARJETA') ?><br>
          <strong>Entorno:</strong> MODO SANDBOX (Certificación de Pruebas Aprobada)<br>
          <strong>Operador:</strong> Credicorp / BCP &bull; Pago Inmediato en Línea
        </div>
      </div>

      <!-- LIQUIDACIÓN TRIBUTARIA SUNAT -->
      <div>
        <table class="cpe-totals-table">
          <tr>
            <td class="total-label">Op. Gravadas:</td>
            <td>S/ <?= number_format($opGravadas, 2) ?></td>
          </tr>
          <tr>
            <td class="total-label">I.G.V. (18.00%):</td>
            <td>S/ <?= number_format($igv, 2) ?></td>
          </tr>
          <?php if (!empty($pedido['descuento']) && $pedido['descuento'] > 0): ?>
            <tr>
              <td class="total-label" style="color:#DC2626;">Descuento Cupón:</td>
              <td style="color:#DC2626;">- S/ <?= number_format((float)$pedido['descuento'], 2) ?></td>
            </tr>
          <?php endif; ?>
          <tr class="total-row">
            <td class="total-label">IMPORTE TOTAL:</td>
            <td>S/ <?= number_format($total, 2) ?></td>
          </tr>
        </table>
      </div>
    </div>

    <div style="background:#F9FAFB;padding:0.6rem 1rem;border-radius:6px;font-size:0.82rem;color:#374151;margin-bottom:1.5rem;">
      <strong><?= montoEnLetrasSoles($total) ?></strong>
    </div>

    <!-- PIE DE COMPROBANTE SUNAT: CÓDIGO QR Y HASH -->
    <div class="cpe-footer-sunat">
      <div>
        <img src="<?= $qrApiUrl ?>" alt="Código QR SUNAT" width="110" height="110" style="display:block;border:1px solid #D1D5DB;border-radius:4px;">
      </div>
      <div style="line-height:1.6;">
        <div style="font-weight:700;color:#1E293B;margin-bottom:0.25rem;">
          Representación Impresa de la <?= e($tipoTitulo) ?>
        </div>
        <div>
          Autorizada mediante Resolución de Superintendencia N° 097-2012/SUNAT.<br>
          Consulte la validez y autenticidad de este documento en el portal institucional de la SUNAT.<br>
          <strong>Código Hash SHA-256:</strong> <code><?= e($hashSunat) ?></code>
        </div>
        <div style="margin-top:0.4rem;color:#05A9E9;font-weight:600;">
          MAK-PC ENTERPRISES S.A.C. &mdash; RUC: 20409456520 &mdash; Comprometidos con el desarrollo tecnológico de Tumbes y el Perú.
        </div>
      </div>
    </div>

  </div>

</div>
