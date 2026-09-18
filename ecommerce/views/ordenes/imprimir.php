<?php
/**
 * @var array $orden
 * @var array $componentes
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprobante de Servicio - <?= e($orden['codigo_orden']) ?> - MAKPC</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #161D45; background: #fff; padding: 25px; line-height: 1.4; font-size: 13px; }
    .sheet { max-width: 800px; margin: 0 auto; border: 2px solid #161D45; padding: 25px; border-radius: 8px; }
    .header-row { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #FCC827; padding-bottom: 15px; margin-bottom: 20px; }
    .company-info h1 { font-size: 20px; color: #161D45; margin-bottom: 3px; }
    .company-info h1 span { color: #FCC827; }
    .company-info p { font-size: 11px; color: #555; }
    .ticket-box { text-align: right; border: 2px solid #161D45; padding: 8px 15px; border-radius: 6px; background: #E7E9F7; }
    .ticket-box .ord-num { font-size: 16px; font-weight: 800; color: #161D45; }
    .section-title { background: #161D45; color: #fff; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 4px 8px; margin: 15px 0 8px 0; border-radius: 4px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .info-group { margin-bottom: 4px; }
    .info-group strong { font-size: 11px; color: #555; text-transform: uppercase; }
    .info-group div { font-size: 13px; font-weight: 600; color: #161D45; }
    .box-bordered { border: 1px solid #ccc; padding: 8px 12px; border-radius: 4px; min-height: 40px; margin-bottom: 10px; background: #fafafa; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th { background: #E7E9F7; border: 1px solid #bbb; padding: 6px; font-size: 11px; text-align: left; }
    td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
    .totals-table { width: 280px; margin-left: auto; margin-top: 10px; }
    .totals-table td { padding: 4px 8px; }
    .signatures { display: flex; justify-content: space-between; margin-top: 40px; padding-top: 20px; }
    .sig-box { width: 45%; text-align: center; border-top: 1px solid #000; padding-top: 6px; font-size: 11px; font-weight: 600; }
    .terms { font-size: 9px; color: #666; margin-top: 20px; line-height: 1.3; border-top: 1px dotted #ccc; padding-top: 8px; }
    .no-print { margin-bottom: 15px; text-align: right; }
    .btn-print { background: #FCC827; color: #161D45; font-weight: 700; border: none; padding: 8px 18px; border-radius: 5px; cursor: pointer; }
    @media print { .no-print { display: none; } body { padding: 0; } .sheet { border: none; padding: 0; } }
  </style>
</head>
<body>

<div class="no-print">
  <button onclick="window.print()" class="btn-print" style="display:inline-flex;align-items:center;gap:6px;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
    Imprimir Hoja de Taller
  </button>
</div>

<div class="sheet">
  <div class="header-row">
    <div class="company-info">
      <h1>MAK<span>PC</span> ENTERPRISES S.A.C.</h1>
      <p>RUC: 20409456520 | Servicio Técnico Especializado en Cómputo</p>
      <p>Dirección: Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes, Perú | WhatsApp: +51 975 513 327 | Email: soporte@makpc.pe</p>
    </div>
    <div class="ticket-box">
      <div>ORDEN DE SERVICIO</div>
      <div class="ord-num"><?= e($orden['codigo_orden']) ?></div>
      <div style="font-size:10px;"><?= date('d/m/Y H:i', strtotime($orden['fecha_recepcion'])) ?></div>
    </div>
  </div>

  <div class="section-title">1. Información del Cliente y Equipo</div>
  <div class="grid-2">
    <div>
      <div class="info-group"><strong>Cliente:</strong> <div><?= e($orden['cliente_nombre']) ?></div></div>
      <div class="info-group"><strong>Teléfono:</strong> <div><?= e($orden['telefono']) ?></div></div>
      <div class="info-group"><strong>DNI / RUC:</strong> <div><?= e($orden['dni'] ?: 'No registrado') ?></div></div>
    </div>
    <div>
      <div class="info-group"><strong>Equipo:</strong> <div><?= e($orden['tipo_equipo']) ?> - <?= e($orden['equipo_marca']) ?> <?= e($orden['equipo_modelo']) ?></div></div>
      <div class="info-group"><strong>N° de Serie:</strong> <div><?= e($orden['numero_serie'] ?: 'S/N') ?></div></div>
      <div class="info-group"><strong>Accesorios:</strong> <div><?= e($orden['accesorios_entregados'] ?: 'Solo equipo') ?></div></div>
    </div>
  </div>

  <div class="section-title">2. Recepción Física & Falla Reportada</div>
  <div class="info-group"><strong>Estado Físico en Ingreso:</strong></div>
  <div class="box-bordered"><?= e($orden['estado_recepcion_fisico']) ?></div>

  <div class="info-group"><strong>Falla Manifestada:</strong></div>
  <div class="box-bordered"><?= e($orden['falla_reportada']) ?></div>

  <?php if (!empty($componentes)): ?>
    <div class="section-title">3. Trazabilidad de Componentes & Repuestos</div>
    <table>
      <thead>
        <tr>
          <th>Componente</th>
          <th>Serie Retirada</th>
          <th>Repuesto Instalado</th>
          <th>Serie Nueva</th>
          <th>Costo (S/)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($componentes as $c): ?>
          <tr>
            <td><?= e($c['tipo_componente']) ?></td>
            <td><?= e($c['serie_retirada'] ?: '-') ?></td>
            <td><?= e($c['pieza_instalada'] ?: '-') ?></td>
            <td><?= e($c['serie_instalada'] ?: '-') ?></td>
            <td>S/ <?= number_format($c['precio'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <div class="section-title">4. Liquidación y Presupuesto</div>
  <table class="totals-table">
    <tr>
      <td>Mano de Obra:</td>
      <td style="text-align:right;">S/ <?= number_format($orden['costo_mano_obra'], 2) ?></td>
    </tr>
    <tr>
      <td>Repuestos:</td>
      <td style="text-align:right;">S/ <?= number_format($orden['costo_repuestos'], 2) ?></td>
    </tr>
    <tr style="font-weight:bold;background:#E7E9F7;">
      <td>TOTAL:</td>
      <td style="text-align:right;">S/ <?= number_format($orden['costo_total'], 2) ?></td>
    </tr>
    <tr>
      <td>Abono / Adelanto:</td>
      <td style="text-align:right;">- S/ <?= number_format($orden['adelanto'], 2) ?></td>
    </tr>
    <tr style="font-weight:bold;color:#b91c1c;">
      <td>SALDO POR CANCELAR:</td>
      <td style="text-align:right;">S/ <?= number_format($orden['costo_total'] - $orden['adelanto'], 2) ?></td>
    </tr>
  </table>

  <div class="signatures">
    <div class="sig-box">
      Firma del Técnico Responsable<br>
      <?= e($orden['tecnico_responsable']) ?>
    </div>
    <div class="sig-box">
      Firma del Cliente Conforme<br>
      DNI: <?= e($orden['dni'] ?: '____________________') ?>
    </div>
  </div>

  <div class="terms">
    <strong>TÉRMINOS Y CONDICIONES:</strong> El taller no se responsabiliza por pérdida de información de almacenamiento (se recomienda realizar copias de seguridad previas). Pasados los 30 días posteriores al aviso de equipo listo, la empresa cobrará S/ 2.00 diarios por concepto de almacenaje. Garantía otorgada de <?= (int)$orden['garantia_meses'] ?> meses exclusivamente sobre las piezas y trabajos especificados en esta orden.
  </div>
</div>

</body>
</html>
