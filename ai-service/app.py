"""
(Fase 2) Microservicio de inferencia de comportamientos inseguros.

PHP no puede correr visión por computador; este servicio recibe un frame/imagen
y devuelve la clasificación {tipo_comportamiento, confianza}. El dashboard (PHP,
AiClient.php) lo llama y guarda el resultado en dron_eventos_seguridad.

Este archivo es una PLANTILLA de ejemplo. Sustituye `clasificar_frame()` por tu
modelo real (YOLOv8, MediaPipe, un clasificador entrenado, o una API de visión).

Ejecutar:
    pip install fastapi uvicorn python-multipart
    uvicorn app:app --host 0.0.0.0 --port 8001
Luego en src/Config.php: AI_SERVICE_URL = 'http://127.0.0.1:8001'
"""
import random
from fastapi import FastAPI, UploadFile, File

app = FastAPI(title="Inferencia de comportamientos - dashboard-entorno")

CLASES = ["normal", "merodeo", "forcejeo", "aglomeracion", "caida", "intrusion"]


def clasificar_frame(contenido: bytes) -> dict:
    """Reemplazar por inferencia real (YOLO/MediaPipe/etc.)."""
    # --- DEMO: resultado simulado ---
    etiqueta = random.choice(CLASES)
    return {
        "tipo_comportamiento": etiqueta,
        "confianza": round(random.uniform(0.60, 0.98), 3),
        "modelo": "demo-stub",
        "bytes_recibidos": len(contenido),
    }


@app.get("/health")
def health():
    return {"ok": True}


@app.post("/clasificar")
async def clasificar(imagen: UploadFile = File(...)):
    contenido = await imagen.read()
    return clasificar_frame(contenido)
