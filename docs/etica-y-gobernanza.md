# Ética, Privacidad y Gobernanza de IA — VigIA

**Concurso Datos al Ecosistema 2026 · IA para Colombia**

VigIA integra datos abiertos, sensores IoT y modelos de IA (LLM + visión por computador) para
seguridad ciudadana y monitoreo ambiental. Por su naturaleza sensible, adoptamos principios
explícitos de uso responsable. Este documento es parte del compromiso ético del proyecto.

---

## 1. Privacidad y minimización de datos

- **Sin reconocimiento facial.** El modelo de visión (TSN) clasifica *acciones* (hurto/riesgo/normal),
  **no identifica personas** ni realiza biometría facial.
- **Sin identificación individual.** No se almacenan ni procesan datos que permitan identificar a una
  persona concreta. Los eventos de seguridad se registran de forma **agregada** (tipo, municipio, hora).
- **Minimización.** Solo se guarda lo necesario: tipo de evento, confianza, municipio y marca temporal.
  Los videos de ejemplo del dashboard provienen de datasets públicos de investigación (UCF-Crime), no de
  cámaras de ciudadanos.
- **Datos abiertos oficiales.** Las estadísticas provienen de datos.gov.co (SIEDCO, IDEAM, etc.),
  ya anonimizados y publicados por las entidades responsables.

## 2. La IA es de apoyo, no de decisión automática

- **Ninguna alerta dispara una acción policial o de emergencia automática.** VigIA **notifica y
  contextualiza**; la decisión y la actuación son siempre de un humano responsable.
- **Revisión humana obligatoria** para cualquier uso operativo de las detecciones de visión.
- Las alertas ambientales críticas (PM2.5, PM10, ruido) se deciden con **reglas determinísticas
  auditables** (`src/Alertas.php`), no con el LLM.

## 3. Rigor y no-alucinación

- **La analítica es estadística, no generada por el LLM.** La predicción a 7 días usa regresión lineal
  con intervalo de confianza y **backtesting (MAE/RMSE/MAPE)** en `src/Analitica.php`. El LLM **solo
  explica** los resultados ya calculados; se le instruye explícitamente "no inventes cifras".
- **Prompts restringidos a los datos entregados.** Interpretación, recomendaciones y chat responden
  únicamente con los datos consultados; si son insuficientes, lo declaran.

## 4. Trazabilidad y explicabilidad

Cada respuesta de IA muestra su **procedencia**: fuente, ID del dataset (recurso datos.gov.co),
municipio, número de registros, última fecha disponible y momento de consulta. Así el ciudadano —y el
jurado— puede **verificar** de dónde salió cada afirmación. Las respuestas llevan el distintivo
**"IA de apoyo — verificable"**.

## 5. Límites de uso declarados

VigIA **no debe usarse** para:
- Vigilancia individual dirigida ni perfilamiento de personas.
- Decisiones automáticas de detención, sanción o despliegue de fuerza.
- Sustituir el criterio profesional de autoridades ambientales o de seguridad.

VigIA **sí es apropiado** para: información ciudadana comprensible, priorización de recursos por
municipio, alertas tempranas de apoyo y análisis agregado de tendencias.

---

*VigIA — Inteligencia Artificial que VIGILA, PROTEGE y CUIDA tu entorno, con responsabilidad.*
