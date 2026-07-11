# Guion del Pitch (5 minutos) — VigIA · Equipo 80

**Concurso Datos al Ecosistema 2026: IA para Colombia**

Reparto por perfil: **Andrés** abre (problema, solución, datos) · **Néstor** lleva lo técnico
(arquitectura, IA, rigor, visión) · **Ángela Mariana** cierra (regulación, impacto, equipo).

> Tagline para memorizar (cualquiera puede rematar con ella):
> **“VigIA convierte los datos abiertos del Estado en decisiones que el ciudadano entiende, verifica y usa a tiempo.”**

Ritmo objetivo: ~150 palabras/min. Total ≈ 5:00. Practicar los **traspasos de palabra**.

---

## 🟦 ANDRÉS MAURICIO NOVA — Apertura (0:00 – 1:45) · Slides 1–4

> *Rol en escena: líder que enmarca el problema y la propuesta de valor.*

**[Slide 1 · Portada] (0:00–0:20)**
“Buenos días. Somos el **Equipo 80**. Yo soy Andrés Nova, ingeniero de sistemas y gerente de
tecnología e innovación, y les presento **VigIA**: *inteligencia artificial que vigila, protege y
cuida el entorno de los colombianos.*”

**[Slide 2 · El problema] (0:20–0:55)**
“Colombia **ya publica** miles de datos abiertos: calidad del aire, incendios, seguridad. Pero para el
ciudadano están **fragmentados, en lenguaje técnico y llegan tarde**. Cuando hay un pico de
contaminación o de hurtos, la gente se entera **cuando ya pasó**. El dato existe; el problema es que
**no llega a quien lo necesita, cuando lo necesita**.”

**[Slide 3 · La solución] (0:55–1:20)**
“VigIA resuelve eso en **un solo lugar**. Une tres cosas: **datos abiertos oficiales en vivo**, un
**sensor IoT real en campo**, y un **sistema de IA** que traduce todo a lenguaje ciudadano. El
resultado: interpretación, predicción, recomendaciones y alertas — **en segundos y verificables**.”

**[Slide 4 · Fuentes de datos] (1:20–1:45)**
“Integramos **11 datasets de datos.gov.co** consultados en vivo por la API Socrata: aire, incendios,
clima y cinco de seguridad del SIEDCO. Y sumamos un **sensor propio, un ESP8266 con MQ2, ya
desplegado**, midiendo en campo. Para el detalle técnico, les paso la palabra a Néstor.”

---

## 🟩 NÉSTOR ANDRÉS SANTOS — Núcleo técnico (1:45 – 3:40) · Slides 5–8

> *Rol en escena: AI Cloud Engineer (Oracle · MIT). Aquí se gana el criterio técnico.*

**[Slide 5 · Arquitectura] (1:45–2:10)**
“Gracias, Andrés. Soy Néstor Santos, AI Cloud Engineer. La arquitectura es una **tubería verificable**:
las fuentes alimentan un backend en PHP que consulta, normaliza y analiza; pasa a un sistema
multi-agente y a la base de datos; y llega al frontend. **Cada paso deja trazabilidad**: de qué
dataset, qué municipio y qué fecha salió cada dato.”

**[Slide 6 · IA multi-agente] (2:10–2:35)**
“La IA **no es solo llamar a un modelo**. Un **Agente Clasificador** entiende la pregunta y decide qué
datos traer; luego enruta a un **especialista**: ambiental, seguridad, predictor o simulador. Y es
**multi-proveedor**: Kimi, OpenAI, Gemini, Claude o cualquier modelo compatible.”

**[Slide 7 · Rigor técnico] (2:35–3:10)** — *punto fuerte, decir despacio*
“Y aquí está nuestro **diferenciador**. La predicción a 7 días **no la inventa un modelo de lenguaje**:
la calcula un **módulo estadístico** —regresión lineal con banda de confianza y **backtesting**— que
reporta **R², MAE, RMSE y MAPE reales**. Las alertas son **reglas determinísticas auditables**. El
modelo de lenguaje **solo explica** el resultado. Y todo esto **funciona incluso sin API key**. Eso nos
blinda ante la pregunta ‘¿y esto no es una alucinación?’.”

**[Slide 8 · Visión × SIEDCO] (3:10–3:40)**
“Como innovación, un **modelo de visión por computador** —una TSN entrenada por nosotros— detecta
eventos de hurto en video y los **cruza con la estadística oficial del SIEDCO** por municipio. Detección
en vivo **más** evidencia oficial: una alerta accionable y defendible. Mariana nos cuenta cómo lo
hacemos de forma responsable.”

---

## 🟪 ÁNGELA MARIANA MARTÍNEZ — Cierre (3:40 – 5:00) · Slides 9–11, 13–14

> *Rol en escena: abogada y especialista en gestión pública. Dueña del marco legal y del impacto.*

**[Slide 9 · Gobernanza de IA] (3:40–4:00)**
“Gracias, Néstor. Soy Ángela Mariana Martínez, abogada y especialista en gestión pública. Para
nosotros la IA **no es una caja negra**: **cada respuesta muestra su procedencia** —dataset, municipio,
registros y fecha—, la analítica es auditable y **toda alerta pasa por revisión humana**.”

**[Slide 10 · Regulación] (4:00–4:25)** — *autoridad: es su terreno*
“Y está construido **dentro del marco normativo colombiano**: la **Ley 1712 de 2014** de transparencia,
que sustenta los datos abiertos; la **Ley 1581 de 2012** de protección de datos; el **CONPES 3975** de
inteligencia artificial; y la reciente **Circular 002 de 2024 de la SIC** sobre datos personales en
sistemas de IA. Nuestro compromiso: **sin reconocimiento facial, solo análisis agregado, con revisión
humana**.”

**[Slide 11 · Impacto] (4:25–4:45)**
“El impacto es **medible y replicable**: en Pereira traducimos y predecimos el aire; en Bogotá cruzamos
hurtos con visión; en Boyacá anticipamos incendios. **Once datasets**, consulta ciudadana en
**segundos**, y **funciona sin API key**. Añadir un municipio nuevo es **configuración, no reingeniería**.”

**[Slide 13 · Equipo + Slide 14 · Cierre] (4:45–5:00)**
“Somos un equipo hecho para esto: **tecnología, IA/cloud y gestión pública**. **VigIA: datos abiertos más
IA responsable, igual a decisiones que protegen a Colombia.** Muchas gracias — quedamos atentos a sus
preguntas.”

---

## 🎯 Preparación de preguntas del jurado (Q&A)

| Pregunta probable | Responde | Respuesta clave |
|---|---|---|
| “¿La predicción es confiable o la inventa la IA?” | **Néstor** | Es **estadística** (regresión + backtesting con MAE/RMSE/MAPE); el LLM solo narra; funciona sin API key. |
| “¿El dron/sensor es real o simulado?” | **Andrés** | Es un **piloto real desplegado** (ESP8266 + MQ2) enviando por API; la arquitectura acepta cualquier sensor. |
| “¿No hay riesgo de vigilancia con la visión?” | **Mariana** | **Sin reconocimiento facial ni identificación individual**, solo acciones agregadas; alertas de apoyo con revisión humana; alineado a la Circular SIC 002/2024. |
| “¿Qué tan escalable es?” | **Néstor** | N datasets = N entradas de configuración; multi-dron por `device_id`; multi-proveedor de LLM. |
| “¿Cómo se financia / sostiene?” | **Andrés** | Datos abiertos gratuitos + LLM con opciones **gratis** (OpenRouter/Gemini); replicable por cualquier alcaldía. |
| “¿Por qué les creo el impacto?” | **Mariana** | Casos territoriales concretos + indicadores medibles + repositorio y demo verificables. |

## 🗣️ Tips de entrega
- **Contacto visual** y ritmo pausado en el Slide 7 (rigor) y el Slide 10 (regulación): son los que ganan puntos.
- **Traspasos con nombre**: “…les paso la palabra a Néstor / Mariana”. Que se note equipo.
- **No leer**: usar las notas del orador del `.pptx` como apoyo, no como libreto.
- Cerrar **mirando al jurado** con la frase-tagline.
- Tener a mano la **demo** (o el video) por si piden verlo en vivo.

---

*Guion alineado con la presentación `docs/presentacion/VigIA-Equipo80.pptx` (las mismas notas del orador están en cada diapositiva).*
