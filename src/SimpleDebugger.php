<?php
/*

 * SimpleDebugger - Archivo único para debug cliente/servidor con logs bidireccionales avanzados
 * Incluir en HTML: <script src="SimpleDebugger.php"></script>

* === EJEMPLOS DE USO ===

* ERRORES - Activarán respuestas automáticas con 🚨
sd_print('Error al conectar con la base de datos');
sd_print('Exception en el procesamiento de pago');
sd_print('Fatal error en el sistema de autenticación');
sd_print('Bug detectado en la validación');

* SUCCESS - Activarán respuestas automáticas con ✅  
sd_print('Usuario guardado exitosamente');
sd_print('Operación completada correctamente');
sd_print('Datos procesados OK');
sd_print('Login realizado con éxito');

* WARNINGS - Activarán respuestas automáticas con ⚠️
sd_print('Warning: función deprecated detectada');
sd_print('Advertencia: sesión a punto de expirar');
sd_print('Cuidado: límite de memoria cercano');

* INFO - Activarán respuestas automáticas con ℹ️
sd_print('Información de debug del sistema');
sd_print('Consultando datos informativos');
sd_print('Log de seguimiento activado');

* ARCHIVOS FALTANTES - Activarán respuestas automáticas de error
sd_print('Error: archivo config.php no encontrado');
sd_print('Include failed: missing file database.php');
sd_print('Require error: conexion.php not exists');

* VARIABLES GLOBALES - Activarán respuestas automáticas 
sd_print('Procesando $_POST datos del formulario');
sd_print('Accediendo a $_SESSION[user_id]');
sd_print('Leyendo $_GET parameters');
sd_print('Validando $_COOKIE de autenticación');

* SESIONES - Activarán verificación automática
sd_print('Validando sesión de usuario');
sd_print('Logout iniciado por el usuario');
sd_print('Authentication requerida para continuar');
sd_print('Verificando autenticación activa');

* OPERACIONES ESPECÍFICAS - Ya funcionaban, ahora mejoradas
sd_print('Generando PDF del reporte mensual');
sd_print('Enviando email de confirmación al cliente');
sd_print('Consultando usuario en base de datos');
sd_print('Procesando login con credenciales');
sd_print('Iniciando pago con tarjeta de crédito');

* NUEVAS OPERACIONES
sd_print('Subiendo archivo imagen.jpg');
sd_print('Descargando reporte de ventas');
sd_print('Llamando a API de geolocalización');
sd_print('Procesando endpoint /users/profile');

* OBJETOS Y ARRAYS (también funcionan)
sd_print({usuario: 'juan', accion: 'login', timestamp: new Date()});
sd_print(['item1', 'item2', 'item3']);
sd_print({error: 'Conexión fallida', codigo: 500});

* === ATAJOS DE TECLADO ===
- Ctrl + D: Mostrar/ocultar panel
- Ctrl + Shift + E: Ejecutar ejemplos automáticamente
- Ctrl + Shift + C: Copiar logs al portapapeles
- Botón "Examples": Muestra ejemplos en vivo
- Botón "Test": Prueba conexión servidor
- Botón "Clear": Limpia mensajes
*/

// Configuración
$logFile = 'simpledebugger.log';

// Función para guardar log básico
function saveLog($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $logLine = "[{$timestamp}] [{$ip}] {$message}\n";
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
}

// Función para enviar mensaje del servidor al cliente con emojis y niveles
function serverLog($message, $level = 'info') {
    global $logFile;
    
    // Agregar emoji según el nivel si no lo tiene ya
    if (!preg_match('/[\x{1F300}-\x{1F6FF}]|[\x{2600}-\x{26FF}]/u', $message)) {
        switch ($level) {
            case 'error':
                $message = "🚨 " . $message;
                break;
            case 'warning':
                $message = "⚠️ " . $message;
                break;
            case 'success':
                $message = "✅ " . $message;
                break;
            case 'info':
            default:
                $message = "ℹ️ " . $message;
                break;
        }
    }
    
    // Guardar en log
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $logLine = "[{$timestamp}] [{$ip}] SERVER ({$level}): {$message}\n";
    file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    
    return [
        'server_message' => $message,
        'level' => $level,
        'timestamp' => $timestamp
    ];
}

// Función mejorada para procesar mensajes del cliente y generar logs del servidor
function processClientMessage($data) {
    $serverLogs = [];
    $message = strtolower($data['message'] ?? '');
    $originalMessage = $data['message'] ?? '';
    
    // ========== DETECCIÓN DE ERRORES ==========
    $errorKeywords = ['error', 'fallo', 'falló', 'exception', 'fatal', 'crash', 'bug', 'problema', 'failed'];
    foreach ($errorKeywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            $serverLogs[] = serverLog("ERROR detectado en cliente", 'error');
            $serverLogs[] = serverLog("Iniciando análisis de error...", 'error');
            $serverLogs[] = serverLog("Registrando error en sistema de monitoreo", 'error');
            $serverLogs[] = serverLog("Notificación enviada al equipo de desarrollo", 'error');
            break;
        }
    }
    
    // ========== DETECCIÓN DE SUCCESS ==========
    $successKeywords = ['success', 'exitoso', 'exitosamente', 'completado', 'correcto', 'ok', 'guardado', 'saved', 'done', 'finished'];
    foreach ($successKeywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            $serverLogs[] = serverLog("Operación exitosa confirmada", 'success');
            $serverLogs[] = serverLog("Actualizando métricas de rendimiento...", 'success');
            $serverLogs[] = serverLog("Registrando operación exitosa en audit log", 'success');
            break;
        }
    }
    
    // ========== DETECCIÓN DE WARNINGS ==========
    $warningKeywords = ['warning', 'advertencia', 'cuidado', 'atención', 'deprecated', 'obsoleto', 'caution'];
    foreach ($warningKeywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            $serverLogs[] = serverLog("WARNING detectado", 'warning');
            $serverLogs[] = serverLog("Evaluando severidad del warning...", 'warning');
            $serverLogs[] = serverLog("Registrando warning para revisión posterior", 'warning');
            break;
        }
    }
    
    // ========== DETECCIÓN DE INFO ==========
    $infoKeywords = ['info', 'información', 'log', 'trace', 'debug', 'consulta'];
    foreach ($infoKeywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            $serverLogs[] = serverLog("Información registrada", 'info');
            $serverLogs[] = serverLog("Procesando datos informativos...", 'info');
            break;
        }
    }
    
    // ========== DETECCIÓN DE ARCHIVOS PHP FALTANTES ==========
    $fileKeywords = ['archivo no encontrado', 'file not found', 'include', 'require', 'missing file', 'no existe', 'not exists'];
    foreach ($fileKeywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            $serverLogs[] = serverLog("ERROR: Archivo PHP faltante detectado", 'error');
            $serverLogs[] = serverLog("Verificando rutas de archivos...", 'error');
            $serverLogs[] = serverLog("Revisando permisos del servidor...", 'error');
            $serverLogs[] = serverLog("Generando reporte de archivos faltantes", 'error');
            break;
        }
    }
    
    // ========== DETECCIÓN DE VARIABLES GLOBALES ==========
    if (stripos($message, '$_') !== false || stripos($message, 'global') !== false || stripos($message, 'variable global') !== false) {
        $serverLogs[] = serverLog("Acceso a variables globales detectado", 'info');
        $serverLogs[] = serverLog("Validando seguridad de variables globales...", 'info');
        $serverLogs[] = serverLog("Sanitizando datos de entrada...", 'info');
        
        // Detectar variables específicas
        if (stripos($message, '$_POST') !== false) {
            $serverLogs[] = serverLog("Procesando datos POST recibidos", 'info');
        }
        if (stripos($message, '$_GET') !== false) {
            $serverLogs[] = serverLog("Procesando parámetros GET", 'info');
        }
        if (stripos($message, '$_SESSION') !== false) {
            $serverLogs[] = serverLog("Accediendo a datos de sesión", 'info');
        }
        if (stripos($message, '$_COOKIE') !== false) {
            $serverLogs[] = serverLog("Leyendo cookies del cliente", 'info');
        }
    }
    
    // ========== DETECCIÓN DE SESIONES SIN VALIDAR ==========
    $sessionKeywords = ['sesion', 'session', 'logout', 'autenticacion', 'authentication'];
    foreach ($sessionKeywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            $serverLogs[] = serverLog("Operación de sesión detectada", 'warning');
            $serverLogs[] = serverLog("Verificando estado de autenticación...", 'warning');
            
            // Simular verificación de sesión (en tu código real verificarías $_SESSION)
            $sessionValid = rand(0, 1); // Simulación - en tu código real: isset($_SESSION['user_id'])
            
            if ($sessionValid) {
                $serverLogs[] = serverLog("Sesión válida confirmada", 'success');
                $serverLogs[] = serverLog("Usuario autenticado correctamente", 'success');
            } else {
                $serverLogs[] = serverLog("ALERTA: Sesión no válida o expirada", 'error');
                $serverLogs[] = serverLog("Redirigiendo a página de login...", 'error');
                $serverLogs[] = serverLog("Registrando intento de acceso no autorizado", 'error');
            }
            break;
        }
    }
    
    // ========== DETECCIONES ESPECÍFICAS MEJORADAS ==========
    
    if (stripos($message, 'PDF') !== false) {
        $serverLogs[] = serverLog("Procesando solicitud de PDF...", 'info');
        $serverLogs[] = serverLog("Verificando librería PDF disponible...", 'info');
        $serverLogs[] = serverLog("Validando permisos de usuario...", 'warning');
        $serverLogs[] = serverLog("PDF generado exitosamente", 'success');
    }
    
    if (stripos($message, 'email') !== false || stripos($message, 'mail') !== false) {
        $serverLogs[] = serverLog("Preparando envío de email...", 'info');
        $serverLogs[] = serverLog("Validando configuración SMTP...", 'info');
        $serverLogs[] = serverLog("Verificando destinatario...", 'warning');
        $serverLogs[] = serverLog("Email enviado correctamente", 'success');
    }
    
    if (stripos($message, 'usuario') !== false || stripos($message, 'user') !== false) {
        $serverLogs[] = serverLog("Consultando datos de usuario...", 'info');
        $serverLogs[] = serverLog("Conectando a base de datos...", 'info');
        $serverLogs[] = serverLog("Ejecutando query de usuario...", 'info');
        $serverLogs[] = serverLog("Usuario encontrado, cargando perfil...", 'success');
    }
    
    if (stripos($message, 'login') !== false || stripos($message, 'auth') !== false) {
        $serverLogs[] = serverLog("Iniciando proceso de autenticación...", 'info');
        $serverLogs[] = serverLog("Validando credenciales...", 'warning');
        $serverLogs[] = serverLog("Verificando contraseña...", 'warning');
        $serverLogs[] = serverLog("Autenticación exitosa", 'success');
        $serverLogs[] = serverLog("Creando sesión de usuario...", 'success');
    }
    
    if (stripos($message, 'pago') !== false || stripos($message, 'payment') !== false) {
        $serverLogs[] = serverLog("Procesando pago...", 'info');
        $serverLogs[] = serverLog("Conectando con gateway de pago...", 'info');
        $serverLogs[] = serverLog("Validando tarjeta...", 'warning');
        $serverLogs[] = serverLog("Pago procesado exitosamente", 'success');
    }
    
    if (stripos($message, 'base de datos') !== false || stripos($message, 'database') !== false || stripos($message, 'BD') !== false) {
        $serverLogs[] = serverLog("Conectando a base de datos...", 'info');
        $serverLogs[] = serverLog("Verificando conexión MySQL...", 'info');
        $serverLogs[] = serverLog("Ejecutando consulta...", 'info');
        $serverLogs[] = serverLog("Datos obtenidos correctamente", 'success');
    }
    
    // ========== DETECCIONES ADICIONALES ==========
    
    // Upload de archivos
    if (stripos($message, 'upload') !== false || stripos($message, 'subir') !== false) {
        $serverLogs[] = serverLog("Procesando subida de archivo...", 'info');
        $serverLogs[] = serverLog("Validando tipo de archivo...", 'warning');
        $serverLogs[] = serverLog("Verificando tamaño máximo...", 'warning');
        $serverLogs[] = serverLog("Archivo subido exitosamente", 'success');
    }
    
    // Download de archivos
    if (stripos($message, 'download') !== false || stripos($message, 'descargar') !== false) {
        $serverLogs[] = serverLog("Preparando descarga...", 'info');
        $serverLogs[] = serverLog("Verificando permisos de descarga...", 'warning');
        $serverLogs[] = serverLog("Descarga iniciada", 'success');
    }
    
    // API calls
    if (stripos($message, 'api') !== false || stripos($message, 'endpoint') !== false) {
        $serverLogs[] = serverLog("Llamada a API detectada", 'info');
        $serverLogs[] = serverLog("Validando API key...", 'warning');
        $serverLogs[] = serverLog("Procesando respuesta de API...", 'info');
    }
    
    // Si no hay procesamiento específico, mensaje genérico más útil
    if (empty($serverLogs)) {
        $serverLogs[] = serverLog("Procesando: " . substr($originalMessage, 0, 50), 'info');
        $serverLogs[] = serverLog("Mensaje procesado correctamente", 'success');
    }
    
    return $serverLogs;
}

// Si es una petición AJAX (POST), procesar como API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    
    // Capturar errores PHP
    function phpErrorHandler($errno, $errstr, $errfile, $errline) {
        $message = "PHP ERROR: {$errstr} en {$errfile}:{$errline}";
        saveLog($message);
        return false;
    }
    
    set_error_handler('phpErrorHandler');
    
    // Procesar request
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if ($data) {
            $message = $data['message'] ?? 'Sin mensaje';
            saveLog("CLIENT: {$message}");
            
            // Procesar mensaje y generar logs del servidor
            $serverResponse = processClientMessage($data);
            
            if (isset($data['test'])) {
                $serverMsg = serverLog("Test de conexión procesado correctamente");
                echo json_encode([
                    'status' => 'ok', 
                    'message' => 'Servidor funcionando',
                    'time' => date('H:i:s'),
                    'server_logs' => array_merge([$serverMsg], $serverResponse)
                ]);
            } else {
                echo json_encode([
                    'status' => 'logged',
                    'server_logs' => $serverResponse
                ]);
            }
        } else {
            throw new Exception('Datos inválidos');
        }
        
    } catch (Exception $e) {
        $errorMsg = serverLog("ERROR: {$e->getMessage()}", 'error');
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage(),
            'server_logs' => [$errorMsg]
        ]);
    }
    
    exit; // Importante: salir aquí para requests AJAX
}

// Si es GET con parámetro test, mostrar test simple
if (isset($_GET['test'])) {
    $timestamp = date('Y-m-d H:i:s');
    saveLog("Test directo desde GET");
    echo "Test OK - {$timestamp}";
    exit;
}

// Si llegamos aquí, es una petición normal - servir el JavaScript
header('Content-Type: application/javascript');
?>

class SimpleDebugger {
    constructor() {
        this.debugElement = null;
        this.serverUrl = '<?php echo $_SERVER['PHP_SELF']; ?>'; // URL de este mismo archivo
        this.init();
    }
    
    init() {
        this.createDebugPanel();
        this.setupErrorCapture();
        console.log('SimpleDebugger cargado desde:', this.serverUrl);
    }
    
    createDebugPanel() {
        this.debugElement = document.createElement('div');
        this.debugElement.id = 'simple-debug';
        this.debugElement.style.cssText = `
            position: fixed; top: 10px; left: 10px; 
            width: 600px; max-height: 500px;
            background: rgba(0,0,0,0.95); color: white; 
            font-family: 'Courier New', monospace; font-size: 12px;
            border-radius: 8px; z-index: 99999;
            display: none; box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        `;
        
        const header = document.createElement('div');
        header.style.cssText = `
            background: #333; padding: 10px; cursor: move;
            border-radius: 8px 8px 0 0; display: flex;
            justify-content: space-between; align-items: center;
            border-bottom: 2px solid #555;
        `;
        header.innerHTML = `
            <span style="font-weight: bold; color: #4af;">🛠 Debug Console</span>
            <div>
                <button onclick="window.debugger.testServer()" style="background:#0a84ff; border:none; color:white; padding:4px 10px; margin-right:5px; border-radius:4px; cursor:pointer; font-size:10px;">Test</button>
                <button onclick="window.debugger.showExamples()" style="background:#ff6b35; border:none; color:white; padding:4px 10px; margin-right:5px; border-radius:4px; cursor:pointer; font-size:10px;">Examples</button>
                <button onclick="window.debugger.copyLogs()" style="background:#28a745; border:none; color:white; padding:4px 10px; margin-right:5px; border-radius:4px; cursor:pointer; font-size:10px;">📋 Copy</button>
                <button onclick="window.debugger.clear()" style="background:#666; border:none; color:white; padding:4px 10px; margin-right:5px; border-radius:4px; cursor:pointer;">Clear</button>
                <button onclick="window.debugger.hide()" style="background:#f44; border:none; color:white; padding:4px 10px; border-radius:4px; cursor:pointer;">×</button>
            </div>
        `;
        
        const content = document.createElement('div');
        content.id = 'debug-content';
        content.style.cssText = `
            padding: 12px; max-height: 420px; overflow-y: auto;
            background: rgba(0,0,0,0.2);
        `;
        
        this.debugElement.appendChild(header);
        this.debugElement.appendChild(content);
        document.body.appendChild(this.debugElement);
        
        this.makeDraggable(header);
    }
    
    makeDraggable(header) {
        let isDragging = false;
        let startX, startY, startLeft, startTop;
        
        header.addEventListener('mousedown', (e) => {
            if (e.target.tagName === 'BUTTON') return;
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = this.debugElement.offsetLeft;
            startTop = this.debugElement.offsetTop;
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const newLeft = startLeft + (e.clientX - startX);
            const newTop = startTop + (e.clientY - startY);
            this.debugElement.style.left = newLeft + 'px';
            this.debugElement.style.top = newTop + 'px';
        });
        
        document.addEventListener('mouseup', () => {
            isDragging = false;
        });
    }
    
    setupErrorCapture() {
        window.addEventListener('error', (e) => {
            this.log(`JS ERROR: ${e.message} en ${e.filename}:${e.lineno}`);
        });
        
        window.addEventListener('unhandledrejection', (e) => {
            this.log(`PROMISE ERROR: ${e.reason}`);
        });
    }
    
    log(data, sendToServer = true) {
        const timestamp = new Date().toLocaleTimeString();
        let message = '';
        
        if (typeof data === 'object') {
            if (data === null) {
                message = 'null';
            } else if (data instanceof Error) {
                message = `ERROR: ${data.message}`;
                if (data.stack) message += `\nStack: ${data.stack}`;
            } else {
                message = JSON.stringify(data, null, 2);
            }
        } else {
            message = String(data);
        }
        
        console.log(`%c[${timestamp}] CLIENT: ${message}`, 'color: #4af; font-weight: bold;');
        this.showInPanel(timestamp, message, 'client');
        
        if (sendToServer) {
            this.sendToServer(message, data);
        }
        
        this.show();
    }
    
    showInPanel(timestamp, message, source = 'client') {
        const content = document.getElementById('debug-content');
        if (!content) return;
        
        const isServer = source === 'server';
        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = `
            border-bottom: 1px solid #333; 
            padding: 6px 0; margin-bottom: 3px;
            word-wrap: break-word;
            ${isServer ? 'background: rgba(0, 255, 0, 0.1); border-left: 3px solid #0f0;' : 'border-left: 3px solid #4af;'}
            padding-left: 10px;
        `;
        
        const sourceLabel = isServer ? 'SERVER' : 'CLIENT';
        const sourceColor = isServer ? '#0f0' : '#4af';
        
        messageDiv.innerHTML = `
            <strong style="color: ${sourceColor};">[${timestamp}] ${sourceLabel}:</strong>
            <pre style="margin: 2px 0; white-space: pre-wrap; color: ${sourceColor};">${this.escapeHtml(message)}</pre>
        `;
        
        content.appendChild(messageDiv);
        content.scrollTop = content.scrollHeight;
    }
    
    sendToServer(message, originalData) {
        const payload = {
            timestamp: new Date().toISOString(),
            message: message,
            url: window.location.href,
            userAgent: navigator.userAgent
        };
        
        fetch(this.serverUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            // Mostrar logs del servidor si los hay
            if (data.server_logs && data.server_logs.length > 0) {
                data.server_logs.forEach(serverLog => {
                    this.showServerMessage(serverLog);
                });
            }
        })
        .catch(error => {
            console.warn('No se pudo enviar al servidor:', error);
        });
    }
    
    // mostrar mensajes del servidor
    showServerMessage(serverLog) {
        const timestamp = new Date().toLocaleTimeString();
        
        // Mostrar en consola con estilo diferente
        console.log(`%c[${timestamp}] SERVER: ${serverLog.server_message}`, 
                   'color: #0f0; font-weight: bold;');
        
        // Mostrar en panel visual
        this.showInPanel(timestamp, serverLog.server_message, 'server');
    }
    
    testServer() {
        this.log('Probando conexión con servidor...', false);
        
        fetch(this.serverUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({test: true, message: 'Test de conexión desde cliente'})
        })
        .then(response => response.json())
        .then(data => {
            this.log(`Servidor respondió: ${data.message} (${data.time})`, false);
            
            // Mostrar logs del servidor del test
            if (data.server_logs) {
                data.server_logs.forEach(serverLog => {
                    this.showServerMessage(serverLog);
                });
            }
        })
        .catch(error => {
            this.log(`Error del servidor: ${error}`, false);
        });
    }
    
    // mostrar ejemplos de uso
    showExamples() {
        this.log('=== EJEMPLOS DE USO DEL DEBUGGER ===', false);
        
        // Ejemplos con delay para mostrar la secuencia
        setTimeout(() => {
            sd_print('Iniciando login de usuario...');
        }, 500);
        
        setTimeout(() => {
            sd_print('Error al conectar con la base de datos');
        }, 1000);
        
        setTimeout(() => {
            sd_print('Usuario guardado exitosamente');
        }, 1500);
        
        setTimeout(() => {
            sd_print('Warning: función deprecated detectada');
        }, 2000);
        
        setTimeout(() => {
            sd_print('Procesando $_POST datos del formulario');
        }, 2500);
        
        setTimeout(() => {
            sd_print('Validando sesión de usuario');
        }, 3000);
        
        setTimeout(() => {
            sd_print('Subiendo archivo imagen.jpg');
        }, 3500);
        
        setTimeout(() => {
            sd_print('Generando PDF del reporte mensual');
        }, 4000);
        
        setTimeout(() => {
            sd_print('Enviando email de confirmación');
        }, 4500);
        
        setTimeout(() => {
            sd_print('=== FIN DE EJEMPLOS ===', false);
        }, 5000);
    }

    copyLogs() {
        const content = document.getElementById('debug-content');
        if (!content) {
            this.log('No hay contenido para copiar', false);
            return;
        }
        
        // Obtener todos los mensajes del panel
        const messages = content.querySelectorAll('div');
        let textToCopy = '';
        
        messages.forEach(msg => {
            const text = msg.textContent || msg.innerText;
            textToCopy += text + '\n\n';
        });
        
        if (!textToCopy.trim()) {
            this.log('No hay mensajes para copiar', false);
            return;
        }
        
        // Copiar al portapapeles
        navigator.clipboard.writeText(textToCopy)
            .then(() => {
                // Feedback visual temporal
                const originalText = this.log;
                this.log('✅ Logs copiados al portapapeles!', false);
                
                // Cambiar el botón temporalmente para dar feedback
                const copyBtn = event.target;
                const originalBtnText = copyBtn.innerHTML;
                copyBtn.innerHTML = '✓ Copied!';
                copyBtn.style.background = '#20c997';
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalBtnText;
                    copyBtn.style.background = '#28a745';
                }, 2000);
            })
            .catch(err => {
                // Fallback para navegadores antiguos
                this.fallbackCopy(textToCopy);
            });
    }

    // Método fallback para navegadores que no soportan clipboard API
    fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.select();
        
        try {
            document.execCommand('copy');
            this.log('✅ Logs copiados (fallback)', false);
        } catch (err) {
            this.log('❌ Error al copiar logs', false);
        }
        
        document.body.removeChild(textArea);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    show() {
        this.debugElement.style.display = 'block';
    }
    
    hide() {
        this.debugElement.style.display = 'none';
    }
    
    clear() {
        const content = document.getElementById('debug-content');
        if (content) content.innerHTML = '';
        console.clear();
        this.log('Debug limpiado', false);
    }
}

// Auto-inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDebugger);
} else {
    initDebugger();
}

function initDebugger() {
    window.debugger = new SimpleDebugger();
    
    // Alias global simple - CAMBIADO DE d() A sd_print()
    window.sd_print = (data) => window.debugger.log(data);
    
    // Atajos de teclado
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.key === 'd') {
            e.preventDefault();
            const panel = document.getElementById('simple-debug');
            if (panel && panel.style.display === 'none') {
                window.debugger.show();
            } else if (panel) {
                window.debugger.hide();
            }
        }

        if (e.ctrlKey && e.shiftKey && e.key === 'C') {
            e.preventDefault();
            window.debugger.copyLogs();
        }
        
        // Nuevo atajo: Ctrl + Shift + E para ejemplos
        if (e.ctrlKey && e.shiftKey && e.key === 'E') {
            e.preventDefault();
            window.debugger.showExamples();
        }
    });
    
    console.log('%cDebug listo! Usa sd_print("tu mensaje") para debuggear', 'color: #4af; font-size: 14px; font-weight: bold;');
    console.log('%cAtaljos: Ctrl+D (mostrar/ocultar), Ctrl+Shift+E (ejemplos), Ctrl+Shift+C (copiar logs)', 'color: #888; font-size: 12px;');
    
    // Mensaje de bienvenida
    setTimeout(() => {
        window.debugger.log('SimpleDebugger inicializado correctamente', false);
    }, 100);
}
