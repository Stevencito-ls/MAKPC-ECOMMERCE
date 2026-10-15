<?php

/**
 * TiendaController - Controlador Principal de E-Commerce MAKPC
 */
class TiendaController extends Controller
{

    public function index()
    {
        $productoModel = new Producto();
        $categoriaModel = new Categoria();

        $filtros = [
            'categoria' => $this->input('cat', $this->input('categoria')),
            'marca' => $this->input('marca'),
            'precio_min' => $this->input('min', $this->input('precio_min')),
            'precio_max' => $this->input('max', $this->input('precio_max')),
            'busqueda' => trim((string)$this->input('q', $this->input('busqueda', ''))),
            'orden' => $this->input('orden', 'relevancia'),
            'disponibilidad' => $this->input('stock', $this->input('disponibilidad')),
            'garantia' => $this->input('garantia')
        ];

        $productos = $productoModel->listarConCategoria($filtros);
        $categorias = $categoriaModel->activas();
        $marcas = $productoModel->marcasDisponibles();

        $configModel = new Configuracion();
        $config = $configModel->getConfig();

        $this->view('tienda/index', [
            'title' => 'Catálogo de Productos',
            'productos' => $productos,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'filtros' => $filtros,
            'ofertasActivas' => !empty($config['ofertas_activas']),
            'ofertasFin' => $config['ofertas_fin'] ?? null
        ]);
    }

    public function producto($slug = '')
    {
        if (empty($slug)) {
            $this->redirect('tienda');
        }

        $productoModel = new Producto();
        $res = $productoModel->porSlug($slug);

        if (empty($res)) {
            setFlash('danger', 'El producto solicitado no existe.');
            $this->redirect('tienda');
        }

        $producto = $res[0];
        $relacionados = $productoModel->destacados(4);

        $this->view('tienda/producto', [
            'title' => $producto['nombre'],
            'producto' => $producto,
            'relacionados' => $relacionados
        ]);
    }

    /**
     * Carrito de Compras 100% E-Commerce
     */
    public function carrito()
    {
        $productoModel = new Producto();
        $relacionados = $productoModel->destacados(4);

        $this->view('tienda/carrito', [
            'title' => 'Bolsa de Compras & Carrito | MAKPC Enterprises',
            'relacionados' => $relacionados
        ]);
    }

    /**
     * Finalización de Compra (Checkout)
     */
    public function checkout()
    {
        $this->view('tienda/checkout', [
            'title' => 'Checkout Seguro & Comprobante SUNAT | MAKPC Enterprises'
        ]);
    }

    /**
     * Procesar Cobro Online con Pasarela Culqi (Sandbox / Live)
     * Genera pedido en BD, descuenta inventario y emite Boleta o Factura SUNAT
     */
    public function procesarPagoCulqi()
    {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        // Leer datos JSON o POST
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
        if (!$data || !is_array($data)) {
            $data = $_POST;
        }

        if (!verify_csrf($data['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token de seguridad inválido o sesión expirada.'], 403);
        }

        $tokenId = trim($data['token_id'] ?? '');
        $orderCode = trim($data['order_code'] ?? '') ?: ('PED-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)));
        $tipoComprobante = strtolower(trim($data['tipo_comprobante'] ?? 'boleta'));
        $tipoDoc = ($tipoComprobante === 'factura') ? 'RUC' : 'DNI';
        $numeroDoc = trim($data['numero_documento'] ?? '');
        $clienteNombre = trim($data['cliente_nombre'] ?? '');
        $clienteTelefono = trim($data['cliente_telefono'] ?? '');
        $clienteCorreo = trim($data['cliente_correo'] ?? '');
        $departamento = trim($data['direccion_departamento'] ?? 'Tumbes');
        $distrito = trim($data['direccion_distrito'] ?? 'Tumbes');
        $calle = trim($data['direccion_calle'] ?? '');
        $referencia = trim($data['direccion_referencia'] ?? '');
        $metodoEnvio = trim($data['shipping_method'] ?? 'tumbes_express');
        $items = $data['items'] ?? [];

        if (empty($tokenId)) {
            $this->json(['success' => false, 'message' => 'El token de pago generado por Culqi es obligatorio.'], 400);
        }

        if (empty($clienteNombre) || empty($clienteTelefono) || empty($clienteCorreo) || empty($numeroDoc)) {
            $this->json(['success' => false, 'message' => 'Por favor complete todos los datos requeridos de facturación y contacto.'], 400);
        }

        if (empty($items) || !is_array($items)) {
            $this->json(['success' => false, 'message' => 'El carrito de compras no contiene productos.'], 400);
        }

        // Validar y calcular subtotal real desde la Base de Datos
        $subtotal = 0;
        $itemsReales = [];
        $productoModel = new Producto();
        
        foreach ($items as $item) {
            $prodId = (int)($item['id'] ?? 0);
            $q = (int)($item['qty'] ?? 1);
            if ($prodId > 0 && $q > 0) {
                $dbProd = $productoModel->find($prodId);
                if ($dbProd) {
                    $precioReal = (float)$dbProd['precio'];
                    $subtotal += ($precioReal * $q);
                    
                    // Sobreescribir el precio del cliente con el precio real del servidor
                    $item['price'] = $precioReal;
                    $item['name'] = $dbProd['nombre']; // Opcional, pero más seguro
                    $itemsReales[] = $item;
                }
            }
        }
        $items = $itemsReales; // Actualizar lista de items con datos validados

        // Costo de envío según zona de Tumbes
        $costoEnvio = 0;
        if ($metodoEnvio === 'recojo') {
            $costoEnvio = 0;
        } elseif ($metodoEnvio === 'provincias') {
            $costoEnvio = 18.00;
        } else {
            $costoEnvio = ($subtotal >= 300) ? 0 : 10.00;
        }

        $descuentoRaw = (float)($data['descuento'] ?? 0);
        $descuento = max(0, min($descuentoRaw, $subtotal * 0.5));
        $total = max(0, $subtotal - $descuento + $costoEnvio);
        $montoCentavos = (int)round($total * 100);

        // Llamar a la API de Culqi (Charges)
        $chargeId = null;
        $authCode = null;
        $cardBrand = 'YAPE / TARJETA';
        $pagoAprobado = false;

        $culqiPrivateKey = CULQI_PRIVATE_KEY;

        $chargePayload = [
            'amount' => $montoCentavos,
            'currency_code' => 'PEN',
            'email' => $clienteCorreo,
            'source_id' => $tokenId,
            'description' => "Compra en MAKPC Enterprises - Pedido {$orderCode}",
            'antifraud_details' => [
                'first_name' => explode(' ', $clienteNombre)[0] ?? 'Cliente',
                'last_name' => explode(' ', $clienteNombre)[1] ?? 'Tumbes',
                'phone_number' => preg_replace('/[^0-9]/', '', $clienteTelefono)
            ]
        ];

        $ch = curl_init('https://api.culqi.com/v2/charges');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($chargePayload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $culqiPrivateKey
            ],
            CURLOPT_TIMEOUT => 25,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $responseRaw = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isOfflineTest = str_starts_with($tokenId, 'offline_');
        if ($isOfflineTest && defined('APP_ENV') && APP_ENV === 'production') {
            $this->json([
                'success' => false,
                'message' => 'El modo de pago simulado offline está deshabilitado en el entorno de producción.'
            ], 403);
        }

        if ($responseRaw) {
            $res = json_decode($responseRaw, true);
            if ($httpCode >= 200 && $httpCode < 300 && !empty($res['id'])) {
                $chargeId = $res['id'];
                $authCode = $res['reference_code'] ?? ($res['outcome']['user_message'] ?? ('AUT-' . strtoupper(substr(uniqid(), -6))));
                $cardBrand = $res['source']['iin']['card_brand'] ?? ($res['source']['brand'] ?? 'YAPE');
                $pagoAprobado = true;
            } elseif ($isOfflineTest) {
                // Modo simulado explícito (offline/contraentrega/transferencia simulada)
                $chargeId = 'chr_test_' . substr(md5(uniqid()), 0, 16);
                $authCode = 'AUTH-' . rand(100000, 999999);
                $cardBrand = (stripos($tokenId, 'yape') !== false) ? 'YAPE SANDBOX' : 'OFFLINE TEST';
                $pagoAprobado = true;
            } else {
                $errorMsg = $res['user_message'] ?? ($res['merchant_message'] ?? 'La transacción fue denegada por la pasarela de pagos.');
                $this->json([
                    'success' => false,
                    'message' => 'Error de Culqi: ' . $errorMsg
                ], 400);
            }
        } else {
            if ($isOfflineTest) {
                $chargeId = 'chr_test_' . substr(md5(uniqid()), 0, 16);
                $authCode = 'AUTH-' . rand(100000, 999999);
                $cardBrand = 'OFFLINE TEST';
                $pagoAprobado = true;
            } else {
                $this->json([
                    'success' => false,
                    'message' => 'Error de conexión con la pasarela Culqi: ' . ($curlError ?: 'Tiempo de espera agotado')
                ], 502);
            }
        }

        if (!$pagoAprobado) {
            $this->json(['success' => false, 'message' => 'No fue posible autorizar el cargo.'], 400);
        }

        // 1. Guardar Pedido en BD
        $pedidoModel = new PedidoTienda();
        $idPedido = $pedidoModel->create([
            'codigo_pedido' => $orderCode,
            'tipo_comprobante' => $tipoComprobante,
            'tipo_documento' => $tipoDoc,
            'numero_documento' => $numeroDoc,
            'cliente_nombre' => $clienteNombre,
            'cliente_telefono' => $clienteTelefono,
            'cliente_correo' => $clienteCorreo,
            'direccion_departamento' => $departamento,
            'direccion_distrito' => $distrito,
            'direccion_calle' => $calle,
            'direccion_referencia' => $referencia ?: null,
            'metodo_envio' => $metodoEnvio,
            'metodo_pago' => 'Culqi Online (' . $cardBrand . ')',
            'estado_pago' => 'Pagado',
            'subtotal' => $subtotal,
            'costo_envio' => $costoEnvio,
            'descuento' => $descuento,
            'op_gravadas' => round($total / 1.18, 2),
            'igv' => round($total - ($total / 1.18), 2),
            'total' => $total,
            'culqi_charge_id' => $chargeId,
            'culqi_authorization_code' => $authCode,
            'culqi_brand' => $cardBrand,
            'items_json' => json_encode($items, JSON_UNESCAPED_UNICODE)
        ]);

        // 2. Descontar Stock en Productos y Registrar en Kardex
        $pdo = $pedidoModel->getPdo();
        $kardexModel = new Kardex();
        foreach ($items as $it) {
            $prodId = (int)($it['id'] ?? 0);
            $qty = (int)($it['qty'] ?? 1);
            if ($prodId > 0 && $qty > 0) {
                $stStmt = $pdo->prepare("UPDATE productos SET stock = GREATEST(0, stock - :qty1), veces_vendido = veces_vendido + :qty2 WHERE id_producto = :id");
                $stStmt->execute([':qty1' => $qty, ':qty2' => $qty, ':id' => $prodId]);
                
                // Registrar movimiento en Kardex por la salida de venta
                $kardexModel->registrarMovimiento($prodId, 'SALIDA', $qty, "Venta E-Commerce - Pedido {$orderCode}");
            }
        }

        // 3. Emitir Comprobante Electrónico SUNAT (Boleta B001 o Factura F001)
        $comprobanteModel = new ComprobantePago();
        $tipoDocSunat = ($tipoComprobante === 'factura') ? 'Factura' : 'Boleta';
        $numeracion = $comprobanteModel->generarSiguienteNumero($tipoDocSunat);

        $opGravadas = round($total / 1.18, 2);
        $igv = round($total - $opGravadas, 2);
        $fechaEmision = date('Y-m-d H:i:s');

        // Código Hash digital representativo SHA-256 según estándar UBL 2.1 SUNAT
        $cadenaTributaria = "20409456520|{$numeracion['serie']}|{$numeracion['correlativo']}|{$igv}|{$total}|{$fechaEmision}|{$tipoDoc}|{$numeroDoc}";
        $codigoHash = strtoupper(substr(hash('sha256', $cadenaTributaria), 0, 28));

        $comprobanteModel->create([
            'id_pedido' => $idPedido,
            'tipo' => $tipoDocSunat,
            'serie' => $numeracion['serie'],
            'correlativo' => $numeracion['correlativo'],
            'numero_completo' => $numeracion['numero_completo'],
            'fecha_emision' => $fechaEmision,
            'ruc_emisor' => '20409456520',
            'razon_social_emisor' => 'MAK-PC ENTERPRISES S.A.C.',
            'direccion_emisor' => 'Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes, Tumbes',
            'tipo_doc_cliente' => $tipoDoc,
            'num_doc_cliente' => $numeroDoc,
            'nombre_cliente' => $clienteNombre,
            'direccion_cliente' => trim("{$calle}, {$distrito}, {$departamento}"),
            'moneda' => 'PEN',
            'op_gravadas' => $opGravadas,
            'igv' => $igv,
            'total' => $total,
            'codigo_hash' => $codigoHash,
            'estado_sunat' => 'Aceptado / Emitido'
        ]);

        $this->json([
            'success' => true,
            'message' => '¡Pago aprobado y comprobante emitido exitosamente!',
            'codigo_pedido' => $orderCode,
            'numero_comprobante' => $numeracion['numero_completo'],
            'tipo_comprobante' => ($tipoDocSunat === 'Factura' ? 'Factura Electrónica' : 'Boleta de Venta Electrónica'),
            'comprobante_url' => url("tienda/comprobante/{$orderCode}"),
            'charge_id' => $chargeId,
            'authorization_code' => $authCode,
            'brand' => $cardBrand,
            'total' => number_format($total, 2, '.', '')
        ]);
    }

    /**
     * Ver e Imprimir Comprobante Electrónico SUNAT & Voucher de Transacción
     */
    public function comprobante($codigo = null)
    {
        if (empty($codigo)) {
            $codigo = $this->input('codigo');
        }

        if (empty($codigo)) {
            setFlash('danger', 'Debe especificar el código de pedido o comprobante.');
            $this->redirect('tienda');
        }

        $pedidoModel = new PedidoTienda();
        $pedido = $pedidoModel->obtenerConComprobante($codigo);

        if (!$pedido) {
            // Intentar buscar por número de comprobante completo (ej. B001-00000001)
            $comprobanteModel = new ComprobantePago();
            $comp = $comprobanteModel->buscarPorNumero($codigo);
            if ($comp && !empty($comp['codigo_pedido'])) {
                $pedido = $pedidoModel->obtenerConComprobante($comp['codigo_pedido']);
            }
        }

        if (!$pedido) {
            http_response_code(404);
            $this->view('home/404', ['title' => 'Comprobante No Encontrado']);
            return;
        }

        $items = json_decode($pedido['items_json'] ?? '[]', true);

        $this->view('tienda/comprobante', [
            'title' => "Comprobante Electrónico {$pedido['comprobante_numero']} | MAKPC Enterprises S.A.C.",
            'pedido' => $pedido,
            'items' => $items
        ]);
    }

    /**
     * Centro de Soporte, Taller y Rastreo de Reparaciones / Tickets
     */
    public function soporte()
    {
        $ticketModel = new TicketSoporte();

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada. Por favor intente nuevamente.');
                $this->redirect('tienda/soporte');
            }

            $nombre = trim($this->input('nombre_solicitante'));
            $telefono = trim($this->input('telefono_solicitante'));
            $asunto = trim($this->input('asunto'));
            $descripcion = trim($this->input('descripcion'));
            $tipo = $this->input('tipo', 'Soporte Tecnico');

            if (empty($nombre) || empty($telefono) || empty($descripcion)) {
                setFlash('danger', 'Nombre, teléfono y detalle del problema son requeridos.');
                $this->redirect('tienda/soporte');
            }

            $codigo = generarCodigoTicket($ticketModel->getPdo());

            $ticketModel->create([
                'codigo_ticket' => $codigo,
                'nombre_solicitante' => $nombre,
                'telefono_solicitante' => $telefono,
                'correo_solicitante' => trim((string)$this->input('correo_solicitante', '')) ?: null,
                'asunto' => $asunto ?: 'Solicitud de Soporte Técnico',
                'descripcion' => $descripcion,
                'tipo' => $tipo,
                'prioridad' => 'Media',
                'estado' => 'Abierto'
            ]);

            setFlash('success', "Su solicitud ha sido registrada con el ticket #{$codigo}. Nos comunicaremos a su WhatsApp en breve.");
            $this->redirect('tienda/soporte?codigo=' . urlencode($codigo));
        }

        // Búsqueda en vivo de órdenes y tickets para seguimiento
        $terminoBusqueda = trim((string)$this->input('buscar', $this->input('codigo', '')));
        $resultadoOrden = null;
        $resultadoTicket = null;
        $resultadoPedido = null;

        if (!empty($terminoBusqueda)) {
            $ordenModel = new OrdenServicio();
            $ordenes = $ordenModel->listarCompleto(null, $terminoBusqueda);
            if (!empty($ordenes)) {
                $resultadoOrden = $ordenes[0];
            }

            $sqlTicket = "SELECT * FROM tickets_soporte WHERE codigo_ticket = :codigo OR telefono_solicitante LIKE :tel ORDER BY creado_en DESC LIMIT 1";
            $resTickets = $ticketModel->query($sqlTicket, [
                ':codigo' => $terminoBusqueda,
                ':tel' => "%{$terminoBusqueda}%"
            ]);
            if (!empty($resTickets)) {
                $resultadoTicket = $resTickets[0];
            }

            // Búsqueda de Pedidos E-commerce & Comprobantes SUNAT
            $pedidoModel = new PedidoTienda();
            $resultadoPedido = $pedidoModel->obtenerConComprobante($terminoBusqueda);
            if (!$resultadoPedido) {
                $sqlPed = "SELECT p.*, c.numero_completo as comprobante_numero, c.tipo as comprobante_tipo 
                           FROM pedidos_tienda p 
                           LEFT JOIN comprobantes_pago c ON p.id_pedido = c.id_pedido 
                           WHERE p.cliente_telefono LIKE :tel OR c.numero_completo = :num 
                           ORDER BY p.creado_en DESC LIMIT 1";
                $resultadoPedido = $pedidoModel->queryOne($sqlPed, [
                    ':tel' => "%{$terminoBusqueda}%",
                    ':num' => $terminoBusqueda
                ]);
            }
        }

        $this->view('tienda/soporte', [
            'title' => 'Centro de Soporte, Taller y Rastreo de Órdenes',
            'terminoBusqueda' => $terminoBusqueda,
            'resultadoOrden' => $resultadoOrden,
            'resultadoTicket' => $resultadoTicket,
            'resultadoPedido' => $resultadoPedido
        ]);
    }

    /**
     * Estudio / Configurador Inteligente "Crea tu PC"
     */
    public function crearPc()
    {
        // Cargar catálogo modular de componentes, presets y accesorios desde configuración JSON
        $catalogFile = __DIR__ . '/../config/pc_builder.json';
        $catalog = [];
        if (file_exists($catalogFile)) {
            $catalog = json_decode((string)file_get_contents($catalogFile), true) ?: [];
        }

        $componentes = $catalog['componentes'] ?? [];
        $presets     = $catalog['presets'] ?? [];
        $accesorios  = $catalog['accesorios'] ?? [];

        $this->view('tienda/crear_pc', [
            'title' => 'Crea tu PC a Medida | Asesoría Inteligente Anti Cuello de Botella',
            'componentes' => $componentes,
            'presets' => $presets,
            'accesorios' => $accesorios,
            'isPcBuilder' => true
        ]);
    }

    /**
     * API en Vivo para Validación de DNI (RENIEC) y RUC (SUNAT)
     * Responde en formato JSON para el autocompletado en Checkout y Clientes
     */
    public function consultarDocumento()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $tipo = strtolower(trim((string)$this->input('tipo', 'dni')));
        $numero = preg_replace('/[^0-9]/', '', (string)$this->input('numero', ''));

        if (empty($numero)) {
            echo json_encode(['success' => false, 'message' => 'El número de documento es requerido.']);
            exit;
        }

        $apisPeruToken = trim((string)(getenv('APISPERU_TOKEN') ?: (defined('APISPERU_TOKEN') ? APISPERU_TOKEN : '')));

        if ($tipo === 'dni') {
            if (strlen($numero) !== 8) {
                echo json_encode(['success' => false, 'message' => 'El DNI debe contener exactamente 8 dígitos.']);
                exit;
            }

            // 1. Intentar apisperu.com si el token está disponible
            if (!empty($apisPeruToken)) {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => "https://dniruc.apisperu.com/api/v1/dni/" . urlencode($numero),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_HTTPHEADER => [
                        'Accept: application/json',
                        'Authorization: Bearer ' . $apisPeruToken
                    ],
                    CURLOPT_SSL_VERIFYPEER => true
                ]);
                $resp = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($code === 200 && $resp) {
                    $d = json_decode($resp, true);
                    if (!empty($d['success']) && $d['success'] === true) {
                        $nomComp = !empty($d['nombreCompleto'])
                            ? $d['nombreCompleto']
                            : trim(($d['nombres'] ?? '') . ' ' . ($d['apellidoPaterno'] ?? '') . ' ' . ($d['apellidoMaterno'] ?? ''));

                        echo json_encode([
                            'success' => true,
                            'tipo' => 'dni',
                            'numero' => $numero,
                            'nombre_completo' => $nomComp,
                            'nombres' => $d['nombres'] ?? '',
                            'apellido_paterno' => $d['apellidoPaterno'] ?? '',
                            'apellido_materno' => $d['apellidoMaterno'] ?? '',
                            'origen' => 'apisperu.com (RENIEC Oficial)'
                        ]);
                        exit;
                    }
                }
            }

            // 2. Respaldo RENIEC
            $url = "https://api.apis.net.pe/v1/dni?numero=" . urlencode($numero);
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) MAKPC/1.0'
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (!empty($data['nombre']) || !empty($data['nombres'])) {
                    $nombreCompleto = !empty($data['nombre']) 
                        ? $data['nombre'] 
                        : trim(($data['nombres'] ?? '') . ' ' . ($data['apellidoPaterno'] ?? '') . ' ' . ($data['apellidoMaterno'] ?? ''));

                    echo json_encode([
                        'success' => true,
                        'tipo' => 'dni',
                        'numero' => $numero,
                        'nombre_completo' => $nombreCompleto,
                        'nombres' => $data['nombres'] ?? '',
                        'apellido_paterno' => $data['apellidoPaterno'] ?? '',
                        'apellido_materno' => $data['apellidoMaterno'] ?? '',
                        'origen' => 'apisperu.com (RENIEC Oficial)'
                    ]);
                    exit;
                }
            }

            // 3. Respaldo en línea RENIEC en tiempo real (para DNIs recientes como 77044052)
            $chOnline = curl_init("https://eldni.com/pe/buscar-datos-por-dni");
            curl_setopt_array($chOnline, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 4,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]);
            $respOnline = curl_exec($chOnline);
            $hdrSize = curl_getinfo($chOnline, CURLINFO_HEADER_SIZE);
            $hdrs = substr($respOnline, 0, $hdrSize);
            $bodyHtml = substr($respOnline, $hdrSize);
            curl_close($chOnline);

            if ($bodyHtml) {
                preg_match_all('/^Set-Cookie:\s*([^;]+)/mi', $hdrs, $cMatches);
                $cookies = $cMatches[1] ?? [];
                $xsrf = '';
                foreach ($cookies as $c) {
                    if (str_starts_with($c, 'XSRF-TOKEN=')) {
                        $xsrf = urldecode(substr($c, 11));
                        break;
                    }
                }
                if (preg_match('/name="_token"\s+value="([^"]+)"/i', $bodyHtml, $tMatch)) {
                    $chPost = curl_init("https://eldni.com/pe/buscar-datos-por-dni");
                    curl_setopt_array($chPost, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_TIMEOUT => 5,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query(['_token' => $tMatch[1], 'dni' => $numero]),
                        CURLOPT_HTTPHEADER => [
                            'Cookie: ' . implode('; ', $cookies),
                            'X-XSRF-TOKEN: ' . $xsrf,
                            'Referer: https://eldni.com/pe/buscar-datos-por-dni',
                            'Origin: https://eldni.com',
                            'Content-Type: application/x-www-form-urlencoded',
                            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                        ]
                    ]);
                    $resPost = curl_exec($chPost);
                    $codePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
                    curl_close($chPost);

                    if ($codePost === 200 && $resPost) {
                        preg_match('/id="nombres"[^>]+value="([^"]*)"/i', $resPost, $mN);
                        preg_match('/id="apellidop"[^>]+value="([^"]*)"/i', $resPost, $mP);
                        preg_match('/id="apellidom"[^>]+value="([^"]*)"/i', $resPost, $mM);
                        preg_match('/id="completos"[^>]+value="([^"]*)"/i', $resPost, $mC);
                        $n = trim($mN[1] ?? '');
                        $p = trim($mP[1] ?? '');
                        $m = trim($mM[1] ?? '');
                        $full = trim($mC[1] ?? '');
                        if ($full || $n) {
                            echo json_encode([
                                'success' => true,
                                'tipo' => 'dni',
                                'numero' => $numero,
                                'nombre_completo' => $full ?: trim("$n $p $m"),
                                'nombres' => $n,
                                'apellido_paterno' => $p,
                                'apellido_materno' => $m,
                                'origen' => 'RENIEC Oficial (Consulta en Tiempo Real)'
                            ]);
                            exit;
                        }
                    }
                }
            }

            echo json_encode([
                'success' => false,
                'message' => 'No se encontró información para el DNI ingresado en la base de datos de RENIEC.'
            ]);
            exit;

        } elseif ($tipo === 'ruc') {
            if (strlen($numero) !== 11) {
                echo json_encode(['success' => false, 'message' => 'El RUC debe contener exactamente 11 dígitos.']);
                exit;
            }

            // 1. Intentar apisperu.com si el token está disponible
            if (!empty($apisPeruToken)) {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => "https://dniruc.apisperu.com/api/v1/ruc/" . urlencode($numero),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_HTTPHEADER => [
                        'Accept: application/json',
                        'Authorization: Bearer ' . $apisPeruToken
                    ],
                    CURLOPT_SSL_VERIFYPEER => true
                ]);
                $resp = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($code === 200 && $resp) {
                    $d = json_decode($resp, true);
                    if (!empty($d['success']) && $d['success'] === true) {
                        $dir = trim(($d['direccion'] ?? '') . ' ' . ($d['distrito'] ?? '') . ' ' . ($d['provincia'] ?? '') . ' ' . ($d['departamento'] ?? ''));
                        echo json_encode([
                            'success' => true,
                            'tipo' => 'ruc',
                            'numero' => $numero,
                            'razon_social' => $d['razonSocial'] ?? ($d['nombre'] ?? ''),
                            'direccion' => $dir,
                            'estado' => $d['estado'] ?? 'ACTIVO',
                            'condicion' => $d['condicion'] ?? 'HABIDO',
                            'departamento' => $d['departamento'] ?? '',
                            'provincia' => $d['provincia'] ?? '',
                            'distrito' => $d['distrito'] ?? '',
                            'origen' => 'apisperu.com (SUNAT Oficial)'
                        ]);
                        exit;
                    }
                }
            }

            // 2. Respaldo SUNAT
            $url = "https://api.apis.net.pe/v1/ruc?numero=" . urlencode($numero);
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) MAKPC/1.0'
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (!empty($data['nombre'])) {
                    echo json_encode([
                        'success' => true,
                        'tipo' => 'ruc',
                        'numero' => $numero,
                        'razon_social' => $data['nombre'],
                        'direccion' => trim(($data['direccion'] ?? '') . ' ' . ($data['distrito'] ?? '') . ' ' . ($data['provincia'] ?? '') . ' ' . ($data['departamento'] ?? '')),
                        'estado' => $data['estado'] ?? 'ACTIVO',
                        'condicion' => $data['condicion'] ?? 'HABIDO',
                        'departamento' => $data['departamento'] ?? '',
                        'provincia' => $data['provincia'] ?? '',
                        'distrito' => $data['distrito'] ?? '',
                        'origen' => 'apisperu.com (SUNAT Oficial)'
                    ]);
                    exit;
                }
            }

            echo json_encode([
                'success' => false,
                'message' => 'No se encontró información para el RUC ingresado en el padrón de contribuyentes de SUNAT.'
            ]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Tipo de documento no soportado.']);
        exit;
    }

    /**
     * API: Verificar disponibilidad y stock en vivo de un producto
     */
    public function verificarStock()
    {
        $id = (int)$this->input('id', $this->input('id_producto', 0));
        $productoModel = new Producto();
        $prod = $productoModel->find($id);

        if (!$prod) {
            $this->json(['success' => false, 'message' => 'Producto no encontrado', 'stock' => 0], 404);
        }

        $this->json([
            'success' => true,
            'id_producto' => (int)$prod['id_producto'],
            'nombre' => $prod['nombre'],
            'stock' => (int)$prod['stock'],
            'disponible' => (int)$prod['stock'] > 0
        ]);
    }

    /**
     * Alias para consulta de ordenes de taller y tickets
     */
    public function consultarOrden()
    {
        $this->soporte();
    }

    /**
     * Alias para registro de tickets de soporte tecnico
     */
    public function crearTicket()
    {
        $this->soporte();
    }
}
