# MAKPC Enterprises S.A.C. &bull; Plataforma Web Empresarial

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4-D22128?style=for-the-badge&logo=apache&logoColor=white)
![Culqi](https://img.shields.io/badge/Culqi-Pasarela%20de%20Pagos-00D68F?style=for-the-badge)
![SUNAT](https://img.shields.io/badge/SUNAT-Facturación%20Electrónica-0A4E9B?style=for-the-badge)

Plataforma web de última generación para **MAK-PC ENTERPRISES S.A.C.** (RUC: 20409456520), empresa líder en tecnología y soporte técnico especializado en Tumbes, Perú.

---

## 🏛️ Arquitectura Modular Desacoplada

El repositorio está organizado en dos módulos independientes contenidos dentro de la carpeta principal:

```
MAKPC/
├── landing/                                  # 1. LANDING PAGE INSTITUCIONAL
│   ├── index.php                             # Front-controller de la landing
│   ├── layouts/                              # head.php, navbar.php, floating_menu.php, footer.php
│   ├── sections/                             # hero.php, tienda.php, conocenos.php, servicios.php, convenios.php, datos.php
│   ├── css/                                  # Estilos modulares responsivos y menú flotante
│   ├── js/                                   # Scripts interactivos (BotonFlotante.js)
│   ├── imagenes/                             # Fotografías y badges oficiales
│   └── Iconos/                               # Recursos vectoriales SVG
│
├── ecommerce/                                # 2. ECOMMERCE MAKPC (MVC Completo Autónomo)
│   ├── index.php                             # Front-controller de Ecommerce MAKPC
│   ├── config/                               # app.php, database.php
│   ├── core/                                 # Controller.php, Database.php, Model.php, Router.php
│   ├── controllers/                          # TiendaController, AuthController, PanelController, etc.
│   ├── models/                               # Producto, Categoria, PedidoTienda, ComprobantePago, etc.
│   ├── views/                                # Catálogo, producto, carrito, checkout, comprobante, crear_pc, soporte, panel
│   ├── layouts/                              # header.php, footer.php, admin_header.php, sidebar.php, admin_footer.php
│   ├── components/                           # ofertas_flash.php, spotlight_pc.php, marcas.php, garantias.php
│   ├── assets/                               # css/ (coolbox.css, app.css), js/, img/
│   └── sql/                                  # Scripts de creación y datos de MySQL
│
├── .gitignore                                # Reglas de exclusión para Git
├── .env.example                              # Plantilla de variables de entorno
├── .htaccess                                 # Reglas Apache mod_rewrite
└── index.php                                 # Enrutador inteligente raíz (redirige a landing o ecommerce)
```

---

## 🚀 Características Principales

### 🛒 Ecommerce MAKPC
- **Explorar Catálogo:** Prioridad visual #1 con filtros laterales interactivos (categoría, marca, rango de precio en Soles, disponibilidad en stock).
- **Ventana Flotante de Ofertas Relámpago 24H:** Modal interactivo con cuenta regresiva en vivo, cerrable (`✕`) y reactivable desde un botón flotante permanente.
- **Bolsa de Compras & Checkout:** Cálculo automático de subtotal, IGV (18%) y total en Soles.
- **Pasarela de Pagos Culqi:** Integración para tarjetas de débito/crédito, Yape y transferencias.
- **Comprobantes Electrónicos SUNAT:** Emisión de Boletas (`B001`) y Facturas (`F001`) con código HASH, RUC/DNI y formato imprimible oficial.
- **Armador de PC Inteligente ("Crea tu PC"):** Estudio interactivo para ensamblar computadoras personalizadas con cálculo de consumo eléctrico (Watts) y prevención de cuello de botella.
- **Taller & Rastreo de Órdenes:** Consulta en tiempo real del estado de reparación de equipos técnicos.
- **Panel Administrativo Multirrol:** Control de inventario, ventas y órdenes para roles `admin`, `vendedor` y `tecnico`.

### 🌐 Landing Page Institucional
- Diseño corporativo premium con colores oficiales (`#161D45` navy, `#FCC827` oro, `#05A9E9` cian).
- 100% responsiva para computadoras, tablets y teléfonos celulares.
- Menú lateral flotante de redes sociales (TikTok, Facebook, WhatsApp).
- Secciones completas: Conócenos, Servicios Técnicos, Convenios Institucionales y Ficha Legal RUC.

---

## 💻 Instalación y Puesta en Marcha

### 1. Clonar el repositorio
```bash
git clone https://github.com/Stevencito-ls/MAKPC-ECOMMERCE.git
```

### 2. Configurar en XAMPP
Copiar la carpeta dentro de `C:\xampp\htdocs\MAKPC`.

### 3. Base de Datos
1. Iniciar **Apache** y **MySQL** en el panel de XAMPP.
2. Ingresar a `http://localhost/phpmyadmin/`.
3. Crear la base de datos `taller_servicios`.
4. Importar el archivo SQL ubicado en `ecommerce/sql/`.

### 4. Variables de Entorno
Copiar el archivo `.env.example` a `.env` tanto en la raíz como en `ecommerce/`:
```bash
cp .env.example .env
cp .env.example ecommerce/.env
```

### 5. Acceso al Sistema
- **Página Principal / Landing Page:** `http://localhost/MAKPC/` o `http://localhost/MAKPC/landing/`
- **Ecommerce MAKPC:** `http://localhost/MAKPC/ecommerce/`
- **Panel Administrativo:** `http://localhost/MAKPC/ecommerce/?action=login`
  - **Usuario:** `admin` | **Contraseña:** `admin123`

---

## 📄 Licencia y Derechos

&copy; 2026 **MAK-PC ENTERPRISES S.A.C.** &bull; RUC 20409456520. Todos los derechos reservados.  
Jr. Simón Bolívar N° 461 Int. 001, Tumbes, Perú.
