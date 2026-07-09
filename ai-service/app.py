"""
Microservicio de detección de hurto en video para VigIA (camino "pull").

Envuelve el modelo de visión del equipo (repo NestorAndresSantosVidales/vigia:
MMAction2 TSN + ResNet-50) y expone una API HTTP que VigIA (PHP, AiClient::detectar)
consume subiendo un video MP4. Devuelve scores agregados de hurto/riesgo y el nivel
de alerta con los mismos umbrales del modelo.

Flujo:
    VigIA PHP  --POST /detectar (video.mp4)-->  este servicio
               <--{tipo, nivel_alerta, theft_score, risk_score, top5}--

Puesta en marcha:
    pip install -r requirements.txt          # fastapi, uvicorn, python-multipart
    # además, en el MISMO entorno, el stack del modelo de Néstor:
    #   torch, mmaction2, mmcv==2.1.0, mmengine, decord
    # y su repo clonado con el checkpoint entrenado.
    set VIGIA_MODEL_REPO=C:\ruta\a\vigia          # repo de Néstor (con demo/ y configs/)
    set VIGIA_TSN_CONFIG=configs/tsn_hurto.py     # config del modelo
    set VIGIA_TSN_CHECKPOINT=work_dirs/tsn_hurto/best_acc_top1_epoch_13.pth
    uvicorn app:app --host 0.0.0.0 --port 8001

    # en src/Config.php:  AI_SERVICE_URL = 'http://127.0.0.1:8001'

Degradación elegante: si el stack del modelo o el checkpoint no están disponibles,
/detectar responde {"ok": false, "error": "modelo no disponible"} (HTTP 200) para que
VigIA siga operando por el camino webhook (push) sin romperse.
"""
import os
import subprocess
import sys
import tempfile
from typing import Optional

from fastapi import FastAPI, UploadFile, File
from fastapi.responses import JSONResponse

app = FastAPI(title="VigIA — Detección de hurto (TSN)")

# Taxonomía del modelo (coincide con VigIA Config::CV_THEFT_CLASSES / CV_RISK_CLASSES).
THEFT_CLASSES = ["stealing", "shoplifting", "robbery", "burglary"]
RISK_CLASSES = ["fighting", "assault", "abuse", "vandalism", "shooting", "explosion", "arson"]

MODEL_REPO = os.environ.get("VIGIA_MODEL_REPO", "")
TSN_CONFIG = os.environ.get("VIGIA_TSN_CONFIG", "configs/tsn_hurto.py")
TSN_CHECKPOINT = os.environ.get(
    "VIGIA_TSN_CHECKPOINT", "work_dirs/tsn_hurto/best_acc_top1_epoch_13.pth"
)


def modelo_disponible() -> Optional[str]:
    """Devuelve None si el modelo está listo, o un mensaje de error si falta algo."""
    if not MODEL_REPO or not os.path.isdir(MODEL_REPO):
        return "VIGIA_MODEL_REPO no configurado o inexistente"
    ckpt = os.path.join(MODEL_REPO, TSN_CHECKPOINT)
    if not os.path.isfile(ckpt):
        return f"Checkpoint no encontrado: {ckpt}"
    try:
        import mmaction  # noqa: F401
    except Exception:
        return "MMAction2 no instalado en el entorno"
    return None


def nivel_alerta(theft: float, risk: float, normal: float) -> str:
    """Mismos umbrales que el modelo de Néstor y que evento.php."""
    if theft >= 0.50:
        return "alta"
    if risk >= 0.40:
        return "riesgo"
    if theft >= 0.25:
        return "media"
    if normal >= 0.35:
        return "normal"
    return "baja"


def agregar_scores(scores: dict) -> dict:
    """Agrupa scores por clase individual en theft/risk/normal y arma el top5."""
    theft = sum(v for k, v in scores.items() if k.lower() in THEFT_CLASSES)
    risk = sum(v for k, v in scores.items() if k.lower() in RISK_CLASSES)
    normal = scores.get("normal", scores.get("Normal", 0.0))
    top5 = sorted(scores.items(), key=lambda kv: kv[1], reverse=True)[:5]
    tipo = top5[0][0] if top5 else "desconocido"
    return {
        "tipo": tipo,
        "theft_score": round(float(theft), 4),
        "risk_score": round(float(risk), 4),
        "normal_score": round(float(normal), 4),
        "nivel_alerta": nivel_alerta(theft, risk, normal),
        "top5": [{"clase": k, "score": round(float(v), 4)} for k, v in top5],
    }


def inferir_video(ruta_video: str) -> dict:
    """
    Ejecuta la inferencia del TSN sobre el video (subprocess a demo_inferencer,
    igual que run_inference() del repo de Néstor) y devuelve {clase: score}.
    Adaptar el parseo a la salida real del inferencer si cambia el formato.
    """
    import json

    cmd = [
        sys.executable,
        os.path.join(MODEL_REPO, "demo", "demo_inferencer.py"),
        ruta_video,
        "--rec", os.path.join(MODEL_REPO, TSN_CONFIG),
        "--rec-weights", os.path.join(MODEL_REPO, TSN_CHECKPOINT),
        "--device", "cpu",
        "--print-result",
    ]
    out = subprocess.run(cmd, cwd=MODEL_REPO, capture_output=True, text=True, timeout=300)
    if out.returncode != 0:
        raise RuntimeError(out.stderr[-400:] or "fallo del inferencer")

    # Se espera una línea JSON con predictions[0].rec_scores[0] + labels.
    scores: dict = {}
    for linea in reversed(out.stdout.splitlines()):
        linea = linea.strip()
        if linea.startswith("{") and "rec_scores" in linea:
            data = json.loads(linea)
            rec = data["predictions"][0]["rec_scores"][0]
            labels = data["predictions"][0].get("rec_labels") or list(range(len(rec)))
            scores = {str(labels[i]): float(rec[i]) for i in range(len(rec))}
            break
    if not scores:
        raise RuntimeError("no se pudo parsear la salida del inferencer")
    return scores


@app.get("/health")
def health():
    err = modelo_disponible()
    return {"ok": err is None, "modelo": "no disponible" if err else "listo", "detalle": err}


@app.post("/detectar")
async def detectar(video: UploadFile = File(...)):
    err = modelo_disponible()
    if err is not None:
        return JSONResponse({"ok": False, "error": f"modelo no disponible: {err}"})

    contenido = await video.read()
    with tempfile.NamedTemporaryFile(suffix=".mp4", delete=False) as tmp:
        tmp.write(contenido)
        ruta = tmp.name
    try:
        scores = inferir_video(ruta)
        resultado = agregar_scores(scores)
        resultado["ok"] = True
        return resultado
    except Exception as e:  # noqa: BLE001
        return JSONResponse({"ok": False, "error": str(e)})
    finally:
        try:
            os.unlink(ruta)
        except OSError:
            pass
