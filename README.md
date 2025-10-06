# 🛠️ SimpleDebugger

**SimpleDebugger** es una librería PHP + JavaScript de un solo archivo que proporciona un **sistema de depuración bidireccional cliente-servidor**, con panel visual, logs en tiempo real y respuestas automáticas inteligentes (con emojis 🚨⚠️✅ℹ️).

Ideal para desarrolladores que quieren **ver en vivo qué está ocurriendo tanto en el cliente como en el servidor**, sin depender de herramientas externas.

---

## 🚀 Características principales

✅ **Archivo único:** solo necesitas incluir `SimpleDebugger.php`  
✅ **Panel visual en el navegador** para ver los logs  
✅ **Logs bidireccionales** (cliente ↔ servidor)  
✅ **Respuestas automáticas inteligentes** según el tipo de mensaje  
✅ **Captura de errores JS y Promesas no manejadas**  
✅ **Atajos de teclado**  
✅ **Soporte para objetos, arrays y errores PHP**  
✅ **Modo "Examples" para ver casos en vivo**

---

## ⚙️ Instalación

1. Descarga la libreria

2. Copia el archivo `SimpleDebugger.php` en tu proyecto.

3. Inclúyelo en tu HTML:

   ```html
   <script src="SimpleDebugger.php"></script>
   ```

¡Listo! 🎉  
El debugger se activará automáticamente cuando cargues la página.

---

## 💡 Uso básico

Para registrar mensajes desde el cliente, usa la función global `sd_print()`:

```js
sd_print('Conectando a la base de datos...');
sd_print('Usuario guardado exitosamente');
sd_print('Warning: función deprecated detectada');
sd_print('Error al procesar el pago');
```

### Ejemplo con objetos y arrays

```js
sd_print({ usuario: 'juan', accion: 'login', timestamp: new Date() });
sd_print(['item1', 'item2', 'item3']);
sd_print({ error: 'Conexión fallida', codigo: 500 });
```

---

## 🧠 Clasificación automática de mensajes

| Tipo de mensaje | Ejemplo detectado | Emoji | Acción del servidor |
|------------------|------------------|--------|----------------------|
| **Error** | `"Error al conectar con la base de datos"` | 🚨 | Inicia análisis, registra y notifica |
| **Success** | `"Usuario guardado exitosamente"` | ✅ | Confirma operación y registra métricas |
| **Warning** | `"Advertencia: función deprecated"` | ⚠️ | Registra para revisión futura |
| **Info** | `"Información de debug del sistema"` | ℹ️ | Guarda datos informativos |

Además detecta:
- **Archivos PHP faltantes**
- **Variables globales (`$_POST`, `$_GET`, `$_SESSION`, `$_COOKIE`)**
- **Sesiones de usuario**
- **Operaciones comunes (PDF, Email, Login, API, Upload/Download, etc.)**

---

## 🎮 Atajos de teclado

| Atajo | Acción |
|-------|--------|
| `Ctrl + D` | Mostrar / ocultar panel |
| `Ctrl + Shift + E` | Ejecutar ejemplos automáticos |
| `Ctrl + Shift + C` | Copiar logs al portapapeles |

---

## 🧰 Panel de depuración

El panel visual aparece en la esquina superior izquierda del navegador.

Contiene botones para:
- **Test:** Verificar conexión con el servidor
- **Examples:** Ejecutar ejemplos automáticos
- **📋 Copy:** Copiar todos los logs
- **Clear:** Limpiar consola
- **×:** Cerrar panel

---

## 🔁 Comunicación bidireccional

Cada mensaje enviado con `sd_print()` se registra en el cliente **y se envía al servidor**, que:
- Lo analiza semánticamente (detecta errores, sesiones, operaciones, etc.)
- Genera respuestas automáticas con emojis y niveles
- Guarda todo en `simpledebugger.log`

El resultado se devuelve al navegador en formato JSON y se muestra en el panel.

---

## 🧪 Probar conexión

Desde el panel o el código:

```js
window.debugger.testServer();
```

Esto genera un test de comunicación cliente-servidor y muestra los logs en tiempo real.

---

## 🗂️ Estructura del proyecto

```
SimpleDebugger/
│
├── SimpleDebugger.php   # Archivo único con el servidor PHP y cliente JS
├── simpledebugger.log   # Archivo de logs generado automáticamente
└── README.md            # Este archivo :)
```

---

## 📋 Requisitos

- PHP ≥ 7.4  
- Un servidor web con soporte para PHP (Apache, Nginx, etc.)

---

## ⚖️ Licencia

Este proyecto está disponible bajo la licencia **MIT**, lo que significa que puedes usarlo, modificarlo y distribuirlo libremente.

---

## 💬 Autor

Desarrollado con 💻 y ☕ por **[Gustavo Fernández]**  
📧 Contacto: [gustavoo.fernandez@gmail.com]  
🐙 GitHub: [https://github.com/gustavofernandez](https://github.com/gustavofernandez)

---

## 🌟 ¿Te gustó?

Si este proyecto te fue útil:
- ⭐ ¡Dale una estrella en GitHub!
- 🐛 Reporta bugs o mejoras en *Issues*
- 💬 Comparte con otros desarrolladores

---

> _SimpleDebugger: porque debuggear debería ser tan simple como escribir `sd_print()`._
