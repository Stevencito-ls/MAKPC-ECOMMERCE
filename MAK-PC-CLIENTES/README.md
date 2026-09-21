# Sistema de Atención al Cliente, Control de Taller y Emisión de Recibos A5
### Empresa: MAK-PC Enterprises S.A.C. (RUC: 20409456520 - Tumbes, Perú)

---

## 🚀 Arquitectura del Proyecto

El sistema ha sido construido bajo una arquitectura modular y desacoplada en 5 Fases:

```
MAK-PC/
├── database/
│   └── schema.sql              # FASE 1: Script SQL completo (MySQL 8.0+ / InnoDB)
├── backend/
│   ├── config/
│   │   ├── Database.php        # Conexión Singleton PDO optimizada
│   │   └── Response.php        # Formato estándar de respuestas HTTP JSON
│   ├── services/
│   │   ├── ClienteService.php  # Búsqueda ágil y registro rápido de clientes
│   │   ├── OrdenService.php    # Control de taller, código ORD-2026-XXXX y estados
│   │   ├── ReciboService.php   # Correlativo atómico, garantía y liquidaciones
│   │   └── DashboardService.php# KPIs y métricas del laboratorio
│   ├── controllers/
│   │   ├── ClienteController.php
│   │   ├── OrdenController.php
│   │   ├── ReciboController.php
│   │   └── DashboardController.php
│   ├── utils/
│   │   ├── NumeroALetrasHelper.php # FASE 3: Conversor formal a Soles (XX/100 SOLES)
│   │   └── GarantiaHelper.php      # FASE 3: Cálculo y validación legal 90 días (Ley 29571)
│   └── index.php               # Front Controller, autoloader PSR-4 y Router REST
├── frontend/                   # FASE 4: Interfaz de mostrador y laboratorio
│   ├── index.html              # Dashboard, Recepción rápida y Control de Taller
│   ├── recibo.html             # FASE 5: Plantilla del Recibo Físico A5
│   ├── css/
│   │   ├── styles.css          # Identidad visual MAK-PC, badges y modales
│   │   └── recibo-print.css    # @media print exacto para media hoja A5 (148mm x 210mm)
│   └── js/
│       ├── api.js              # Consumo de la API REST
│       ├── utils.js            # Notificaciones Toast, formateo y modales
│       └── app.js              # Lógica reactiva de mostrador y taller
└── tests/
    └── test_fase3.php          # Suite de pruebas unitarias
```

---

## 🛠️ Guía Rápida de Puesta en Marcha

### 1. Importar la Base de Datos en MySQL
Ejecutar el script en MySQL (XAMPP / Laragon / MySQL Server):
```bash
mysql -u root -p < database/schema.sql
```

### 2. Iniciar el Servidor Backend (PHP Built-in Server)
Desde la raíz del proyecto:
```bash
php -S localhost:8000 -t backend
```

### 3. Abrir el Frontend
Abrir `frontend/index.html` en cualquier navegador web moderno o servirlo localmente:
```
http://localhost:8000/frontend/index.html
```

---

## 🖨️ Impresión del Recibo A5 Oficial

1. En el **Tablero de Taller**, hacer clic en `Recibo` para generar el comprobante o en el icono de impresora `🖨️` en órdenes con recibo emitido.
2. Se abrirá la vista oficial [frontend/recibo.html](file:///d:/PRACTICAS/MAK-PC/frontend/recibo.html).
3. Al pulsar `Imprimir Recibo A5` o `Ctrl + P`, el navegador aplicará automáticamente el tamaño **A5 (148 mm × 210 mm)** con cero márgenes y colores exactos.
