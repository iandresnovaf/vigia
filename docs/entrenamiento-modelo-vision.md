# Entrenamiento del modelo de visión (seguridad) — VigIA

> **Evidencia de entrenamiento** del modelo de visión por computador que detecta eventos de
> seguridad (hurto/riesgo) para VigIA — Concurso Datos al Ecosistema 2026 · IA para Colombia.
>
> Este es el **registro real de la terminal** del proceso de entorno, instalación y ejecución del
> modelo **TSN (Temporal Segment Network)** sobre **MMAction2** (PyTorch). Documenta, entre otros:
> creación del entorno conda `mmaction2`, instalación editable de MMAction2 1.2.0, el ajuste de
> compatibilidad **MMCV 2.1.0** (MMAction2 no soporta 2.2.0), PyTorch 2.5.1 + CUDA 12.1, y la
> inferencia con `demo_inferencer.py` sobre el modelo TSN.
>
> Entorno del equipo: Windows · conda · Python 3.10 · torch 2.5.1+cu121 · mmaction2 1.2.0 · mmcv 2.1.0 · decord 0.6.0.

---

## Registro completo (log de terminal)

```text
(base) C:\Windows\System32>conda activate mmaction2

EnvironmentNameNotFound: Could not find conda environment: mmaction2
You can list all discoverable environments with `conda info --envs`.

ERROR: 'conda activate mmaction2' exited with code 1.

(base) C:\Windows\System32>conda activate mmaction2

EnvironmentNameNotFound: Could not find conda environment: mmaction2
You can list all discoverable environments with `conda info --envs`.

ERROR: 'conda activate mmaction2' exited with code 1.

(base) C:\Windows\System32>conda info --envs

# conda environments:
#
# * -> active
# + -> frozen
base                 *   C:\ProgramData\miniconda3
                         C:\Users\agente\.conda\envs\mmaction2
                         C:\Users\agente\miniconda3

(base) C:\Windows\System32>conda activate C:\Users\agente\.conda\envs\mmaction2

(mmaction2) C:\Windows\System32>cd C:\Users\nesto\Documents\ai-projects\mmaction2

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -m pip install -v -e .
Using pip 26.1.2 from C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\pip (python 3.10)
Obtaining file:///C:/Users/nesto/Documents/ai-projects/mmaction2
  Running command installing build dependencies
  Using pip 26.1.2 from C:\Users\agente\.conda\envs\mmaction2\Lib\site-packages\pip (python 3.10)
  Collecting setuptools>=40.8.0
    Obtaining dependency information for setuptools>=40.8.0 from https://files.pythonhosted.org/packages/5d/40/e1e72872c6354b306daef1703549e8e83b4d43cfea356311bf722a043752/setuptools-83.0.0-py3-none-any.whl.metadata
    Downloading setuptools-83.0.0-py3-none-any.whl.metadata (6.6 kB)
  Downloading setuptools-83.0.0-py3-none-any.whl (1.0 MB)
     ---------------------------------------- 1.0/1.0 MB 6.0 MB/s  0:00:00
  Installing collected packages: setuptools
  ERROR: pip's dependency resolver does not currently take into account all the packages that are installed. This behaviour is the source of the following dependency conflicts.
  openxlab 0.1.3 requires setuptools~=60.2.0, but you have setuptools 83.0.0 which is incompatible.
  Successfully installed setuptools-83.0.0
  Installing build dependencies ... done
  Running command Checking if build backend supports build_editable
  Checking if build backend supports build_editable ... done
  Running command Getting requirements to build editable
  C:\Users\nesto\AppData\Local\Temp\pip-build-env-__t9p6i8\overlay\Lib\site-packages\setuptools\dist.py:765: SetuptoolsDeprecationWarning: License classifiers are deprecated.
  !!

          ********************************************************************************
          Please consider removing the following classifiers in favor of a SPDX license expression:

          License :: OSI Approved :: Apache Software License

          See https://packaging.python.org/en/latest/guides/writing-pyproject-toml/#license for details.
          ********************************************************************************

  !!
    self._finalize_license_expression()
  running egg_info
  creating mmaction2.egg-info
  writing mmaction2.egg-info\PKG-INFO
  writing dependency_links to mmaction2.egg-info\dependency_links.txt
  writing requirements to mmaction2.egg-info\requires.txt
  writing top-level names to mmaction2.egg-info\top_level.txt
  writing manifest file 'mmaction2.egg-info\SOURCES.txt'
  reading manifest file 'mmaction2.egg-info\SOURCES.txt'
  reading manifest template 'MANIFEST.in'
  warning: no files found matching 'mmaction\.mim\model-index.yml'
  warning: no files found matching 'mmaction\.mim\dataset-index.yml'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.yml' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.sh' under directory 'mmaction\.mim\tools'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\tools'
  adding license file 'LICENSE'
  writing manifest file 'mmaction2.egg-info\SOURCES.txt'
  Getting requirements to build editable ... done
  Running command Preparing editable metadata (pyproject.toml)
  C:\Users\nesto\AppData\Local\Temp\pip-build-env-__t9p6i8\overlay\Lib\site-packages\setuptools\dist.py:765: SetuptoolsDeprecationWarning: License classifiers are deprecated.
  !!

          ********************************************************************************
          Please consider removing the following classifiers in favor of a SPDX license expression:

          License :: OSI Approved :: Apache Software License

          See https://packaging.python.org/en/latest/guides/writing-pyproject-toml/#license for details.
          ********************************************************************************

  !!
    self._finalize_license_expression()
  running dist_info
  creating C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info
  writing C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info\PKG-INFO
  writing dependency_links to C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info\dependency_links.txt
  writing requirements to C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info\requires.txt
  writing top-level names to C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info\top_level.txt
  writing manifest file 'C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info\SOURCES.txt'
  reading manifest file 'C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info\SOURCES.txt'
  reading manifest template 'MANIFEST.in'
  warning: no files found matching 'mmaction\.mim\model-index.yml'
  warning: no files found matching 'mmaction\.mim\dataset-index.yml'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.yml' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.sh' under directory 'mmaction\.mim\tools'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\tools'
  adding license file 'LICENSE'
  writing manifest file 'C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2.egg-info\SOURCES.txt'
  creating 'C:\Users\nesto\AppData\Local\Temp\pip-modern-metadata-odac9x32\mmaction2-1.2.0.dist-info'
  C:\Users\nesto\AppData\Local\Temp\pip-build-env-__t9p6i8\overlay\Lib\site-packages\setuptools\_distutils\cmd.py:119: SetuptoolsDeprecationWarning: bdist_wheel.universal is deprecated
  !!

          ********************************************************************************
          With Python 2.7 end-of-life, support for building universal wheels
          (i.e., wheels that support both Python 2 and Python 3)
          is being obviated.
          Please discontinue using this option, or if you still need it,
          file an issue with pypa/setuptools describing your use case.

          This deprecation is overdue, please update your project and remove deprecated
          calls to avoid build errors in the future.
          ********************************************************************************

  !!
    self.finalize_options()
  Preparing editable metadata (pyproject.toml) ... done
Collecting decord>=0.4.1 (from mmaction2==1.2.0)
  Obtaining dependency information for decord>=0.4.1 from https://files.pythonhosted.org/packages/6c/be/e15b5b866da452e62635a7b27513f31cb581fa2ea9cc9b768b535d62a955/decord-0.6.0-py3-none-win_amd64.whl.metadata
  Downloading decord-0.6.0-py3-none-win_amd64.whl.metadata (422 bytes)
Collecting einops (from mmaction2==1.2.0)
  Obtaining dependency information for einops from https://files.pythonhosted.org/packages/2a/09/f8d8f8f31e4483c10a906437b4ce31bdf3d6d417b73fe33f1a8b59e34228/einops-0.8.2-py3-none-any.whl.metadata
  Downloading einops-0.8.2-py3-none-any.whl.metadata (13 kB)
Requirement already satisfied: matplotlib in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmaction2==1.2.0) (3.10.9)
Requirement already satisfied: numpy in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmaction2==1.2.0) (2.2.6)
Collecting opencv-contrib-python (from mmaction2==1.2.0)
  Obtaining dependency information for opencv-contrib-python from https://files.pythonhosted.org/packages/09/29/6985260569ee3c7f6fcae252bc06e2a843e5b90eed665a2936bdd26fa283/opencv_contrib_python-5.0.0.93-cp37-abi3-win_amd64.whl.metadata
  Downloading opencv_contrib_python-5.0.0.93-cp37-abi3-win_amd64.whl.metadata (20 kB)
Requirement already satisfied: Pillow in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmaction2==1.2.0) (12.2.0)
Requirement already satisfied: scipy in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmaction2==1.2.0) (1.15.3)
Requirement already satisfied: torch>=1.3 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmaction2==1.2.0) (2.5.1+cu121)
Requirement already satisfied: filelock in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from torch>=1.3->mmaction2==1.2.0) (3.14.0)
Requirement already satisfied: typing-extensions>=4.8.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from torch>=1.3->mmaction2==1.2.0) (4.15.0)
Requirement already satisfied: networkx in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from torch>=1.3->mmaction2==1.2.0) (3.4.2)
Requirement already satisfied: jinja2 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from torch>=1.3->mmaction2==1.2.0) (3.1.6)
Requirement already satisfied: fsspec in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from torch>=1.3->mmaction2==1.2.0) (2026.4.0)
Requirement already satisfied: sympy==1.13.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from torch>=1.3->mmaction2==1.2.0) (1.13.1)
Requirement already satisfied: mpmath<1.4,>=1.1.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from sympy==1.13.1->torch>=1.3->mmaction2==1.2.0) (1.3.0)
Requirement already satisfied: MarkupSafe>=2.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from jinja2->torch>=1.3->mmaction2==1.2.0) (3.0.3)
Requirement already satisfied: contourpy>=1.0.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmaction2==1.2.0) (1.3.2)
Requirement already satisfied: cycler>=0.10 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmaction2==1.2.0) (0.12.1)
Requirement already satisfied: fonttools>=4.22.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmaction2==1.2.0) (4.63.0)
Requirement already satisfied: kiwisolver>=1.3.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmaction2==1.2.0) (1.5.0)
Requirement already satisfied: packaging>=20.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmaction2==1.2.0) (24.2)
Requirement already satisfied: pyparsing>=3 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmaction2==1.2.0) (3.3.2)
Requirement already satisfied: python-dateutil>=2.7 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmaction2==1.2.0) (2.9.0.post0)
Requirement already satisfied: six>=1.5 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from python-dateutil>=2.7->matplotlib->mmaction2==1.2.0) (1.17.0)
Downloading decord-0.6.0-py3-none-win_amd64.whl (24.7 MB)
   ---------------------------------------- 24.7/24.7 MB 8.7 MB/s  0:00:02
Downloading einops-0.8.2-py3-none-any.whl (65 kB)
Downloading opencv_contrib_python-5.0.0.93-cp37-abi3-win_amd64.whl (53.8 MB)
   ---------------------------------------- 53.8/53.8 MB 19.6 MB/s  0:00:02
Building wheels for collected packages: mmaction2
  Running command Building editable for mmaction2 (pyproject.toml)
  C:\Users\nesto\AppData\Local\Temp\pip-build-env-__t9p6i8\overlay\Lib\site-packages\setuptools\dist.py:765: SetuptoolsDeprecationWarning: License classifiers are deprecated.
  !!

          ********************************************************************************
          Please consider removing the following classifiers in favor of a SPDX license expression:

          License :: OSI Approved :: Apache Software License

          See https://packaging.python.org/en/latest/guides/writing-pyproject-toml/#license for details.
          ********************************************************************************

  !!
    self._finalize_license_expression()
  running editable_wheel
  creating C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info
  writing C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info\PKG-INFO
  writing dependency_links to C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info\dependency_links.txt
  writing requirements to C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info\requires.txt
  writing top-level names to C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info\top_level.txt
  writing manifest file 'C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info\SOURCES.txt'
  reading manifest file 'C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info\SOURCES.txt'
  reading manifest template 'MANIFEST.in'
  warning: no files found matching 'mmaction\.mim\model-index.yml'
  warning: no files found matching 'mmaction\.mim\dataset-index.yml'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.yml' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.sh' under directory 'mmaction\.mim\tools'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\tools'
  adding license file 'LICENSE'
  writing manifest file 'C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2.egg-info\SOURCES.txt'
  creating 'C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2-1.2.0.dist-info'
  C:\Users\nesto\AppData\Local\Temp\pip-build-env-__t9p6i8\overlay\Lib\site-packages\setuptools\_distutils\cmd.py:119: SetuptoolsDeprecationWarning: bdist_wheel.universal is deprecated
  !!

          ********************************************************************************
          With Python 2.7 end-of-life, support for building universal wheels
          (i.e., wheels that support both Python 2 and Python 3)
          is being obviated.
          Please discontinue using this option, or if you still need it,
          file an issue with pypa/setuptools describing your use case.

          This deprecation is overdue, please update your project and remove deprecated
          calls to avoid build errors in the future.
          ********************************************************************************

  !!
    self.finalize_options()
  creating C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\tmpcb63cq2j\.tmp-x6s_vu_i\mmaction2-1.2.0.dist-info\WHEEL
  running build_py
  running egg_info
  creating C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info
  writing C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info\PKG-INFO
  writing dependency_links to C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info\dependency_links.txt
  writing requirements to C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info\requires.txt
  writing top-level names to C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info\top_level.txt
  writing manifest file 'C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info\SOURCES.txt'
  reading manifest file 'C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info\SOURCES.txt'
  reading manifest template 'MANIFEST.in'
  warning: no files found matching 'mmaction\.mim\model-index.yml'
  warning: no files found matching 'mmaction\.mim\dataset-index.yml'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.yml' under directory 'mmaction\.mim\configs'
  warning: no files found matching '*.sh' under directory 'mmaction\.mim\tools'
  warning: no files found matching '*.py' under directory 'mmaction\.mim\tools'
  adding license file 'LICENSE'
  writing manifest file 'C:\Users\nesto\AppData\Local\Temp\tmpy72qm3pp.build-temp\mmaction2.egg-info\SOURCES.txt'
  Editable install will be performed using a meta path finder.

  Options like `package-data`, `include/exclude-package-data` or
  `packages.find.exclude/include` may have no effect.

  adding '__editable___mmaction2_1_2_0_finder.py'
  adding '__editable__.mmaction2-1.2.0.pth'
  creating 'C:\\Users\\nesto\\AppData\\Local\\Temp\\pip-ephem-wheel-cache-c7ty40ty\\wheels\\84\\1c\\51\\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d\\tmpcb63cq2j\\.tmp-x6s_vu_i\\mmaction2-1.2.0-0.editable-py2.py3-none-any.whl' and adding 'C:\\Users\\nesto\\AppData\\Local\\Temp\\tmpw156obicmmaction2-1.2.0-0.editable-py2.py3-none-any.whl' to it
  adding 'mmaction2-1.2.0.dist-info/licenses/LICENSE'
  adding 'mmaction2-1.2.0.dist-info/METADATA'
  adding 'mmaction2-1.2.0.dist-info/WHEEL'
  adding 'mmaction2-1.2.0.dist-info/top_level.txt'
  adding 'mmaction2-1.2.0.dist-info/RECORD'
  C:\Users\nesto\AppData\Local\Temp\pip-build-env-__t9p6i8\overlay\Lib\site-packages\setuptools\command\editable_wheel.py:352: InformationOnly: Editable installation.
  !!

          ********************************************************************************
          Please be careful with folders in your working directory with the same
          name as your package as they may take precedence during imports.
          ********************************************************************************

  !!
    with strategy, WheelFile(wheel_path, "w") as wheel_obj:
  Building editable for mmaction2 (pyproject.toml) ... done
  Created wheel for mmaction2: filename=mmaction2-1.2.0-0.editable-py2.py3-none-any.whl size=13612 sha256=99e6b5cfd848d931b043ddaeca8e5b707ec99b07c9373cfa69d0003fab57020d
  Stored in directory: C:\Users\nesto\AppData\Local\Temp\pip-ephem-wheel-cache-c7ty40ty\wheels\84\1c\51\c8f24b0655411f06f7a414cb62ec279593df0c76a84874174d
Successfully built mmaction2
Installing collected packages: opencv-contrib-python, einops, decord, mmaction2
Successfully installed decord-0.6.0 einops-0.8.2 mmaction2-1.2.0 opencv-contrib-python-5.0.0.93

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -c "import mmaction; print('MMAction2 OK')"
Traceback (most recent call last):
  File "<string>", line 1, in <module>
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\__init__.py", line 16, in <module>
    assert (digit_version(mmcv_minimum_version) <= mmcv_version
AssertionError: MMCV==2.2.0 is used but incompatible. Please install mmcv>=2.0.0rc4, <2.2.0.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>clear
"clear" no se reconoce como un comando interno o externo,
programa o archivo por lotes ejecutable.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>clear
"clear" no se reconoce como un comando interno o externo,
programa o archivo por lotes ejecutable.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -m pip uninstall mmcv mmcv-lite -y
WARNING: Skipping mmcv as it is not installed.
Found existing installation: mmcv-lite 2.2.0
Uninstalling mmcv-lite-2.2.0:
  Successfully uninstalled mmcv-lite-2.2.0

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -m pip install mmcv-lite==2.1.0
Collecting mmcv-lite==2.1.0
  Downloading mmcv_lite-2.1.0-py2.py3-none-any.whl.metadata (2.4 kB)
Requirement already satisfied: addict in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (2.4.0)
Requirement already satisfied: mmengine>=0.3.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (0.10.7)
Requirement already satisfied: numpy in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (2.2.6)
Requirement already satisfied: packaging in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (24.2)
Requirement already satisfied: Pillow in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (12.2.0)
Requirement already satisfied: pyyaml in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (6.0.3)
Requirement already satisfied: yapf in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (0.43.0)
Requirement already satisfied: opencv-python>=3 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (5.0.0.93)
Requirement already satisfied: regex in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmcv-lite==2.1.0) (2026.6.28)
Requirement already satisfied: matplotlib in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmengine>=0.3.0->mmcv-lite==2.1.0) (3.10.9)
Requirement already satisfied: rich in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmengine>=0.3.0->mmcv-lite==2.1.0) (13.4.2)
Requirement already satisfied: termcolor in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from mmengine>=0.3.0->mmcv-lite==2.1.0) (3.3.0)
Requirement already satisfied: contourpy>=1.0.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmengine>=0.3.0->mmcv-lite==2.1.0) (1.3.2)
Requirement already satisfied: cycler>=0.10 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmengine>=0.3.0->mmcv-lite==2.1.0) (0.12.1)
Requirement already satisfied: fonttools>=4.22.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmengine>=0.3.0->mmcv-lite==2.1.0) (4.63.0)
Requirement already satisfied: kiwisolver>=1.3.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmengine>=0.3.0->mmcv-lite==2.1.0) (1.5.0)
Requirement already satisfied: pyparsing>=3 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmengine>=0.3.0->mmcv-lite==2.1.0) (3.3.2)
Requirement already satisfied: python-dateutil>=2.7 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from matplotlib->mmengine>=0.3.0->mmcv-lite==2.1.0) (2.9.0.post0)
Requirement already satisfied: six>=1.5 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from python-dateutil>=2.7->matplotlib->mmengine>=0.3.0->mmcv-lite==2.1.0) (1.17.0)
Requirement already satisfied: markdown-it-py>=2.2.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from rich->mmengine>=0.3.0->mmcv-lite==2.1.0) (4.2.0)
Requirement already satisfied: pygments<3.0.0,>=2.13.0 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from rich->mmengine>=0.3.0->mmcv-lite==2.1.0) (2.20.0)
Requirement already satisfied: mdurl~=0.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from markdown-it-py>=2.2.0->rich->mmengine>=0.3.0->mmcv-lite==2.1.0) (0.1.2)
Requirement already satisfied: platformdirs>=3.5.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from yapf->mmcv-lite==2.1.0) (4.10.0)
Requirement already satisfied: tomli>=2.0.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from yapf->mmcv-lite==2.1.0) (2.4.1)
Downloading mmcv_lite-2.1.0-py2.py3-none-any.whl (719 kB)
   ---------------------------------------- 720.0/720.0 kB 2.4 MB/s  0:00:00
Installing collected packages: mmcv-lite
Successfully installed mmcv-lite-2.1.0

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -c "import mmcv; print(mmcv.__version__)"
2.1.0

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -c "import mmaction; print('MMAction2 OK')"
MMAction2 OK

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py demo/demo.mp4 --rec tsn --print-result --vid-out-dir outputs
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
Loads checkpoint by http backend from path: https://download.openmmlab.com/mmaction/v1.0/recognition/tsn/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb_20220906-2692d16c.pth
Downloading: "https://download.openmmlab.com/mmaction/v1.0/recognition/tsn/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb_20220906-2692d16c.pth" to C:\Users\nesto/.cache\torch\hub\checkpoints\tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb_20220906-2692d16c.pth
100%|█████████████████████████████████████████████████████████████████████████████| 93.1M/93.1M [00:12<00:00, 7.60MB/s]
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
Traceback (most recent call last):
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\demo\demo_inferencer.py", line 70, in <module>
    main()
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\demo\demo_inferencer.py", line 65, in main
    mmaction2 = MMAction2Inferencer(**init_args)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\mmaction2_inferencer.py", line 73, in __init__
    self.actionrecog_inferencer = ActionRecogInferencer(
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\actionrecog_inferencer.py", line 74, in __init__
    super().__init__(
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\infer\infer.py", line 180, in __init__
    self.model = self._init_model(cfg, weights, device)  # type: ignore
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\infer\infer.py", line 483, in _init_model
    model = MODELS.build(cfg.model)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\registry\registry.py", line 570, in build
    return self.build_func(cfg, *args, **kwargs, registry=self)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\registry\build_functions.py", line 232, in build_model_from_cfg
    return build_from_cfg(cfg, registry, default_args)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\registry\build_functions.py", line 98, in build_from_cfg
    obj_cls = registry.get(obj_type)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\registry\registry.py", line 451, in get
    self.import_from_location()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\registry\registry.py", line 376, in import_from_location
    import_module(loc)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\importlib\__init__.py", line 126, in import_module
    return _bootstrap._gcd_import(name[level:], package, level)
  File "<frozen importlib._bootstrap>", line 1050, in _gcd_import
  File "<frozen importlib._bootstrap>", line 1027, in _find_and_load
  File "<frozen importlib._bootstrap>", line 1006, in _find_and_load_unlocked
  File "<frozen importlib._bootstrap>", line 688, in _load_unlocked
  File "<frozen importlib._bootstrap_external>", line 883, in exec_module
  File "<frozen importlib._bootstrap>", line 241, in _call_with_frames_removed
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\models\__init__.py", line 8, in <module>
    from .multimodal import *  # noqa: F401,F403
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\models\multimodal\__init__.py", line 2, in <module>
    from mmaction.utils.dependency import WITH_MULTIMODAL
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\utils\dependency.py", line 6, in <module>
    from importlib_metadata import PackageNotFoundError, distribution
ModuleNotFoundError: No module named 'importlib_metadata'

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>clear
"clear" no se reconoce como un comando interno o externo,
programa o archivo por lotes ejecutable.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -m pip install importlib-metadata
Collecting importlib-metadata
  Downloading importlib_metadata-9.0.0-py3-none-any.whl.metadata (4.5 kB)
Collecting zipp>=3.20 (from importlib-metadata)
  Downloading zipp-4.1.0-py3-none-any.whl.metadata (3.6 kB)
Downloading importlib_metadata-9.0.0-py3-none-any.whl (27 kB)
Downloading zipp-4.1.0-py3-none-any.whl (10 kB)
Installing collected packages: zipp, importlib-metadata
Successfully installed importlib-metadata-9.0.0 zipp-4.1.0

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py demo/demo.mp4 --rec tsn --device cpu --print-result --vid-out-dir outputs
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
Loads checkpoint by http backend from path: https://download.openmmlab.com/mmaction/v1.0/recognition/tsn/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb_20220906-2692d16c.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\visualization\visualizer.py:196: UserWarning: Failed to add <class 'mmaction.visualization.video_backend.LocalVisBackend'>, please provide the `save_dir` argument.
  warnings.warn(f'Failed to add {vis_backend.__class__}, '
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━   07/05 14:51:45 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/05 14:51:45 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Traceback (most recent call last):
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\visualization\video_backend.py", line 57, in add_video
    from moviepy.editor import ImageSequenceClip
ModuleNotFoundError: No module named 'moviepy'

During handling of the above exception, another exception occurred:

Traceback (most recent call last):
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\demo\demo_inferencer.py", line 70, in <module>
    main()
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\demo\demo_inferencer.py", line 66, in main
    mmaction2(**call_args)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\mmaction2_inferencer.py", line 163, in __call__
    visualization = self.visualize(
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\mmaction2_inferencer.py", line 126, in visualize
    return self.actionrecog_inferencer.visualize(
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\actionrecog_inferencer.py", line 274, in visualize
    visualization = self.visualizer.add_datasample(
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dist\utils.py", line 427, in wrapper
    return func(*args, **kwargs)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\visualization\action_visualizer.py", line 281, in add_datasample
    tmp_local_vis_backend.add_video(
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\visualization\vis_backend.py", line 60, in wrapper
    return old_func(obj, *args, **kwargs)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\visualization\video_backend.py", line 59, in add_video
    raise ImportError('Please install moviepy to enable '
ImportError: Please install moviepy to enable output file.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python -m pip install moviepy==1.0.3 imageio-ffmpeg
Collecting moviepy==1.0.3
  Downloading moviepy-1.0.3.tar.gz (388 kB)
  Installing build dependencies ... done
  Getting requirements to build wheel ... done
  Preparing metadata (pyproject.toml) ... done
Collecting imageio-ffmpeg
  Downloading imageio_ffmpeg-0.6.0-py3-none-win_amd64.whl.metadata (1.5 kB)
Collecting decorator<5.0,>=4.0.2 (from moviepy==1.0.3)
  Downloading decorator-4.4.2-py2.py3-none-any.whl.metadata (4.2 kB)
Collecting imageio<3.0,>=2.5 (from moviepy==1.0.3)
  Downloading imageio-2.37.3-py3-none-any.whl.metadata (9.7 kB)
Requirement already satisfied: tqdm<5.0,>=4.11.2 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from moviepy==1.0.3) (4.65.2)
Requirement already satisfied: numpy>=1.17.3 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from moviepy==1.0.3) (2.2.6)
Requirement already satisfied: requests<3.0,>=2.8.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from moviepy==1.0.3) (2.28.2)
Collecting proglog<=1.0.0 (from moviepy==1.0.3)
  Downloading proglog-0.1.12-py3-none-any.whl.metadata (794 bytes)
Requirement already satisfied: pillow>=8.3.2 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from imageio<3.0,>=2.5->moviepy==1.0.3) (12.2.0)
Requirement already satisfied: charset-normalizer<4,>=2 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from requests<3.0,>=2.8.1->moviepy==1.0.3) (3.4.7)
Requirement already satisfied: idna<4,>=2.5 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from requests<3.0,>=2.8.1->moviepy==1.0.3) (3.18)
Requirement already satisfied: urllib3<1.27,>=1.21.1 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from requests<3.0,>=2.8.1->moviepy==1.0.3) (1.26.20)
Requirement already satisfied: certifi>=2017.4.17 in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from requests<3.0,>=2.8.1->moviepy==1.0.3) (2026.6.17)
Requirement already satisfied: colorama in c:\users\agente\.conda\envs\mmaction2\lib\site-packages (from tqdm<5.0,>=4.11.2->moviepy==1.0.3) (0.4.6)
Downloading decorator-4.4.2-py2.py3-none-any.whl (9.2 kB)
Downloading imageio-2.37.3-py3-none-any.whl (317 kB)
Downloading proglog-0.1.12-py3-none-any.whl (6.3 kB)
Downloading imageio_ffmpeg-0.6.0-py3-none-win_amd64.whl (31.2 MB)
   ---------------------------------------- 31.2/31.2 MB 7.9 MB/s  0:00:04
Building wheels for collected packages: moviepy
  Building wheel for moviepy (pyproject.toml) ... done
  Created wheel for moviepy: filename=moviepy-1.0.3-py3-none-any.whl size=110838 sha256=d3c89874ff2605548957852abc5882ec71b307acc383417f4cba6cd47edc3c9e
  Stored in directory: c:\users\nesto\appdata\local\pip\cache\wheels\96\32\2d\e10123bd88fbfc02fed53cc18c80a171d3c87479ed845fa7c1
Successfully built moviepy
Installing collected packages: imageio-ffmpeg, imageio, decorator, proglog, moviepy
Successfully installed decorator-4.4.2 imageio-2.37.3 imageio-ffmpeg-0.6.0 moviepy-1.0.3 proglog-0.1.12

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py demo/demo.mp4 --rec tsn --device cpu --print-result --vid-out-dir outputs
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
Loads checkpoint by http backend from path: https://download.openmmlab.com/mmaction/v1.0/recognition/tsn/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb/tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb_20220906-2692d16c.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\visualization\visualizer.py:196: UserWarning: Failed to add <class 'mmaction.visualization.video_backend.LocalVisBackend'>, please provide the `save_dir` argument.
  warnings.warn(f'Failed to add {vis_backend.__class__}, '
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━   07/05 14:53:03 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/05 14:53:03 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{'predictions': [{'rec_labels': [[6]], 'rec_scores': [[9.720660880672844e-23, 1.9259295315921014e-19, 4.603698740764451e-21, 1.5097833664697694e-19, 1.396565702001216e-20, 3.437963362471769e-21, 1.0, 6.62445008623678e-20, 1.0834397446052778e-19, 4.828650993024387e-20, 5.835426006715245e-21, 1.0373294660873043e-19, 1.1990395535286647e-18, 1.6898664639747915e-17, 1.6023864945390223e-20, 1.3419029510984638e-18, 6.977067875586244e-19, 2.1200067585785485e-22, 6.967668345212611e-22, 1.9250299681264566e-15, 3.1481822029520534e-20, 2.4645727237009252e-17, 5.4582461434990586e-24, 1.4556745523315117e-22, 1.491181754704071e-18, 2.1960387187911238e-23, 3.6561786971627296e-20, 8.898723003061766e-18, 2.869906770323781e-22, 8.429794391353637e-20, 8.204972819897994e-22, 6.3486680700116e-20, 2.4012392811173415e-21, 1.422079478111271e-21, 8.083446760294295e-20, 5.7106521928807995e-21, 4.0254320418270445e-21, 2.655682226659219e-19, 1.4516295967269652e-18, 6.326808852972606e-21, 2.557450451580518e-22, 5.124681162798643e-21, 1.2332913814883428e-22, 1.0300424572551096e-19, 2.4982167163647724e-22, 1.5287611899139177e-22, 1.9160313746449576e-17, 1.2186314782021737e-20, 6.698549959485046e-20, 2.261345894116166e-22, 1.0074523485967425e-20, 2.9736089658470047e-20, 2.8043175354470485e-19, 1.7705578706907934e-19, 1.0588622699017667e-20, 1.3534636951941645e-20, 1.629770825705473e-19, 2.408335360831405e-17, 3.820830794624303e-18, 1.1500897295654778e-21, 1.9900270623039324e-23, 3.468260064096874e-21, 5.281164509206782e-22, 2.2177017164618194e-21, 4.328733484614811e-22, 2.9388069167712916e-21, 7.039780379856496e-21, 3.105208231425363e-20, 4.31274805771851e-21, 3.152568522020572e-20, 1.4871306376540926e-19, 1.2823426011136915e-21, 4.586537974228243e-21, 1.9513096299837304e-19, 1.2313135948651608e-20, 6.340768030680418e-23, 7.753964695690056e-19, 2.4219945270780684e-20, 1.0833048158165649e-24, 1.2402799418995515e-20, 2.5874403431114773e-21, 4.816167464978133e-17, 1.5822824642869862e-18, 1.1255214984437533e-18, 6.377400459199475e-22, 1.414817237239581e-22, 2.484892767323168e-21, 2.438075223128171e-22, 5.036519380695689e-21, 4.159602918225776e-21, 3.602611078461035e-21, 9.099881127120627e-19, 3.1307892563498663e-22, 1.0346563317714369e-22, 2.605606004844966e-21, 7.460740994637828e-23, 1.1998424169295235e-22, 2.1826982327510783e-18, 1.5446076056347665e-20, 4.0761780375993955e-21, 7.851811456079132e-17, 7.116856849614337e-17, 1.334202093983039e-16, 8.837473668204021e-22, 7.237600643543392e-23, 5.344011252611989e-21, 5.139719370958342e-20, 2.073725993208787e-23, 3.853907175305581e-21, 8.408609887445727e-17, 3.268029920363024e-18, 2.052165831445696e-21, 3.210727397018719e-20, 6.304460849739776e-18, 2.7701495208924687e-16, 1.3493576309917866e-16, 6.66000962951273e-17, 4.2638940424344484e-17, 2.5772322431842022e-23, 7.845100870641735e-18, 1.687133862481465e-19, 7.656165443579357e-22, 5.881466120283993e-20, 3.8255959494886734e-20, 3.9299676604760538e-19, 1.198206789446977e-19, 1.282938752766098e-22, 3.302541860072691e-19, 6.531229900817608e-21, 5.874755456005944e-22, 3.13612220850465e-21, 6.202713343396326e-20, 1.6088813461027873e-17, 3.699419499060454e-19, 3.263104328002735e-21, 1.0566863138461285e-22, 6.0691932766133126e-24, 2.3407710454803297e-20, 1.1843912091625935e-20, 1.1049873683943899e-16, 2.5821467103085647e-20, 2.4729857891721835e-22, 4.239051359288217e-23, 9.23442468917889e-23, 9.162934416878554e-18, 1.2533696550405484e-19, 2.506905343970847e-21, 3.0081184825798678e-22, 5.515470113563791e-24, 2.8470205649334495e-21, 4.7195924741013417e-17, 1.0120427188446265e-22, 2.384417747489573e-20, 8.423457250820943e-21, 3.4754631593333345e-22, 7.394377930359348e-17, 3.335071666623941e-23, 4.646997687643124e-21, 6.124488553077805e-20, 1.0120278125289614e-21, 2.8285958034133627e-21, 7.193403329244079e-21, 2.2321365204974942e-23, 1.1804933628640074e-21, 1.0387618416012456e-21, 3.184219499658082e-22, 1.4303203104022188e-23, 1.040831850481869e-22, 5.506154455134036e-25, 1.5215992164187248e-22, 1.7816530829499662e-22, 5.086968285140246e-23, 1.0664822623494182e-22, 2.325520138963566e-24, 1.384022929728527e-20, 1.185004637499913e-22, 2.4087646779090777e-19, 7.59507390937738e-23, 1.0605151165139347e-21, 5.649373982634211e-22, 1.5917682096602263e-19, 1.0839027718778515e-19, 3.6932801508215946e-22, 1.0001657921289453e-19, 2.809573557381484e-20, 2.707419175111886e-20, 3.0513892071707955e-22, 4.70404017577588e-19, 1.845400035141249e-18, 3.209693900880458e-23, 1.821564205405074e-18, 1.9350011502841365e-20, 8.721021390286571e-24, 1.9772656528768494e-19, 1.340286575165139e-14, 3.8160964263883554e-18, 3.6768927860412206e-20, 4.407624449363295e-18, 1.0203270836357679e-21, 5.928331985680424e-23, 4.846807849807996e-21, 3.98490798575769e-24, 4.4050078144519063e-23, 2.4102402982251105e-15, 1.2005715566465983e-20, 1.5277053469239337e-23, 1.6686972889999454e-22, 3.2482984816625676e-22, 6.120021777770019e-22, 1.7469279055295676e-20, 1.1576452229683726e-17, 1.5204592954975026e-18, 6.244327475232419e-21, 2.946475179933278e-20, 8.161519079577055e-22, 3.857143196334768e-21, 3.986177878896785e-20, 3.138122264986731e-20, 1.1833868100010495e-23, 9.006425227538621e-20, 2.7129163819910886e-22, 4.024111703241871e-21, 4.7031740756692715e-21, 8.782526540038374e-22, 3.4242945372112436e-16, 6.781227570477484e-22, 1.452880061168598e-20, 6.441750430702122e-23, 4.467899756431539e-21, 7.452568101218983e-18, 1.1310283762275394e-22, 2.063178481143875e-21, 5.409934477816911e-21, 2.508304490896412e-19, 2.062117323124656e-20, 6.933970731696466e-21, 4.323754164288797e-20, 1.0378028139324376e-22, 8.187819892998354e-19, 1.4810665636587693e-20, 1.0268792992056612e-21, 5.366380268171083e-21, 2.944041679194632e-17, 1.5456265564401126e-21, 6.639189062415733e-23, 2.46720047261991e-22, 3.99689125622454e-23, 2.0207430655882678e-21, 2.1112686536164984e-20, 4.7940176516351305e-20, 6.529610678612664e-21, 1.894404025500119e-23, 7.112910577707e-20, 4.9866747184609815e-23, 1.566732731513262e-25, 2.238578987718769e-19, 1.3569848835083549e-19, 1.2393374639014463e-22, 4.612067966794155e-20, 2.927098704143831e-17, 1.637079871146174e-19, 4.0886697573186534e-20, 2.853098201782762e-21, 3.448035538509631e-22, 7.71595206300458e-21, 9.966005305977424e-21, 6.104702982514841e-22, 4.357893822639614e-22, 1.3489748024985152e-19, 8.866248486749e-21, 2.5950105805241147e-22, 5.116044940800224e-22, 6.1199221604149076e-24, 7.503263910645043e-24, 6.572021052567696e-20, 8.420566157644843e-21, 5.577714025621099e-18, 4.856952848435918e-20, 5.5940059940201846e-21, 4.3650745182050726e-14, 6.004813047561785e-21, 1.0603081951430516e-22, 1.479309077487423e-22, 3.297190389250385e-20, 2.589820102959701e-21, 1.8657973542348106e-21, 8.96346122342724e-24, 9.431629666603262e-21, 8.030813075937812e-16, 3.0691383745801346e-19, 1.6822688772569167e-19, 4.11089739284766e-18, 7.75047166705961e-20, 8.12326930293413e-18, 2.083156306575773e-20, 1.4185205617985365e-20, 2.9981876199291236e-21, 1.3805774238460595e-23, 3.319300668529986e-20, 3.169044568966189e-24, 3.45389355648293e-22, 5.178117030369568e-19, 4.013579690716025e-21, 9.742159044279672e-22, 4.713265436804348e-22, 1.2491848345843084e-17, 4.190584354145839e-19, 7.770649734419334e-23, 2.6462417951729363e-23, 6.502613005914968e-22, 3.562781021195327e-24, 1.097292897387944e-22, 5.837939254450407e-22, 2.689741289426378e-22, 1.0905314276605152e-17, 4.630659583124204e-23, 1.7676415097319184e-20, 7.212510361288744e-18, 1.7856897182807732e-20, 1.845201001268702e-20, 1.1453722615018621e-17, 3.513077183253976e-23, 5.470404288651378e-22, 3.637406710626601e-23, 2.641591065706206e-24, 6.317592938115708e-22, 4.8464146184239614e-23, 1.617328478329027e-19, 2.6695936556892887e-22, 2.224741990807442e-22, 1.2455114909200829e-17, 1.356860806416472e-19, 2.2974336637837414e-19, 2.5757181371083683e-16, 3.619883391517767e-15, 1.0351029406397623e-21, 1.720514477607101e-19, 2.316729073035783e-23, 4.433026702301291e-22, 2.660619170134711e-20, 5.1176872860960215e-21, 2.0722719647882836e-22, 2.079924486073134e-20, 7.877964756378466e-19, 6.893983435519685e-22, 6.766063164767788e-21, 2.0583135847766193e-20, 2.2995105656678458e-20, 3.438975285473083e-20, 4.752404486626963e-23, 5.751929636358189e-21, 2.3172222435393016e-17, 5.6367494923487024e-18, 1.2749597411124301e-18, 7.276943724350533e-22, 2.442759264613207e-21, 1.3466406022074671e-19, 6.88164121819483e-20, 1.8707700683240597e-23, 1.319538630177087e-16, 5.842592271613762e-23, 2.0252925037042295e-17, 6.142173496859604e-21, 1.1244469286279422e-21, 1.1426904414664454e-21, 4.734843347811589e-19, 1.3975929326906233e-22, 3.5708666088155705e-22, 1.565571787401584e-20, 1.103739847681762e-17, 1.4606203189322385e-21, 3.2859257079593187e-21, 3.827754454678911e-21, 1.3881895289468362e-20, 7.809757040479122e-23, 2.927850560187309e-22, 5.095066731193245e-23, 1.4364904143713975e-20, 4.607170145265981e-24, 1.9712105615459905e-19, 3.845777321127983e-18, 3.430718665866611e-21, 1.0576770545008278e-17, 9.26505142496319e-23, 1.414947468708702e-21, 7.054670236727626e-22, 7.26156423956467e-19, 1.425400774837705e-17, 3.3167438403318876e-20, 4.6497198660144974e-17, 2.7786834506021002e-21, 9.812357314989266e-21, 5.616775978111071e-22, 5.615659077287023e-23, 4.074332132363695e-19, 2.7752462107803264e-17, 1.0438962254956943e-17, 1.2254094316843502e-20, 7.730930920955328e-18, 2.089194356491595e-22]]}]}

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>
(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>
(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>mkdir data\hurto

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>mkdir data\hurto\videos

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>mkdir data\hurto\videos\normal

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>mkdir data\hurto\videos\sospechoso

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>mkdir data\hurto\videos\hurto

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>cd C:\Users\nesto\Documents\ai-projects\mmaction2

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>copy configs\recognition\tsn\tsn_imagenet-pretrained-r50_8xb32-1x1x8-100e_kinetics400-rgb.py configs\recognition\tsn\tsn_hurto.py
        1 archivo(s) copiado(s).

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>notepad configs\recognition\tsn\tsn_hurto.py

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python tools/train.py configs/recognition/tsn/tsn_hurto.py --work-dir work_dirs/tsn_hurto
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
07/05 15:14:29 - mmengine - INFO -
------------------------------------------------------------
System environment:
    sys.platform: win32
    Python: 3.10.20 | packaged by Anaconda, Inc. | (main, Jun 11 2026, 15:13:20) [MSC v.1942 64 bit (AMD64)]
    CUDA available: True
    MUSA available: False
    numpy_random_seed: 574829700
    GPU 0: NVIDIA GeForce RTX 4050 Laptop GPU
    CUDA_HOME: None
    MSVC: Compilador de optimización de C/C++ de Microsoft (R) versión 19.44.35223 para x64
    GCC: n/a
    PyTorch: 2.5.1+cu121
    PyTorch compiling details: PyTorch built with:
  - C++ Version: 201703
  - MSVC 192930154
  - Intel(R) oneAPI Math Kernel Library Version 2024.2.2-Product Build 20240823 for Intel(R) 64 architecture applications
  - Intel(R) MKL-DNN v3.5.3 (Git Hash 66f0cb9eb66affd2da3bf5f8d897376f04aae6af)
  - OpenMP 2019
  - LAPACK is enabled (usually provided by MKL)
  - CPU capability usage: AVX2
  - CUDA Runtime 12.1
  - NVCC architecture flags: -gencode;arch=compute_50,code=sm_50;-gencode;arch=compute_60,code=sm_60;-gencode;arch=compute_61,code=sm_61;-gencode;arch=compute_70,code=sm_70;-gencode;arch=compute_75,code=sm_75;-gencode;arch=compute_80,code=sm_80;-gencode;arch=compute_86,code=sm_86;-gencode;arch=compute_90,code=sm_90
  - CuDNN 90.1  (built against CUDA 12.4)
  - Magma 2.5.4
  - Build settings: BLAS_INFO=mkl, BUILD_TYPE=Release, CUDA_VERSION=12.1, CUDNN_VERSION=9.1.0, CXX_COMPILER=C:/actions-runner/_work/pytorch/pytorch/builder/windows/tmp_bin/sccache-cl.exe, CXX_FLAGS=/DWIN32 /D_WINDOWS /GR /EHsc /Zc:__cplusplus /bigobj /FS /utf-8 -DUSE_PTHREADPOOL -DNDEBUG -DUSE_KINETO -DLIBKINETO_NOCUPTI -DLIBKINETO_NOROCTRACER -DLIBKINETO_NOXPUPTI=ON -DUSE_FBGEMM -DUSE_XNNPACK -DSYMBOLICATE_MOBILE_DEBUG_HANDLE /wd4624 /wd4068 /wd4067 /wd4267 /wd4661 /wd4717 /wd4244 /wd4804 /wd4273, LAPACK_INFO=mkl, PERF_WITH_AVX=1, PERF_WITH_AVX2=1, TORCH_VERSION=2.5.1, USE_CUDA=ON, USE_CUDNN=ON, USE_CUSPARSELT=OFF, USE_EXCEPTION_PTR=1, USE_GFLAGS=OFF, USE_GLOG=OFF, USE_GLOO=ON, USE_MKL=ON, USE_MKLDNN=ON, USE_MPI=OFF, USE_NCCL=OFF, USE_NNPACK=OFF, USE_OPENMP=ON, USE_ROCM=OFF, USE_ROCM_KERNEL_ASSERT=OFF,

    TorchVision: 0.20.1+cu121
    OpenCV: 5.0.0
    MMEngine: 0.10.7

Runtime environment:
    cudnn_benchmark: False
    mp_cfg: {'mp_start_method': 'fork', 'opencv_num_threads': 0}
    dist_cfg: {'backend': 'nccl'}
    seed: 574829700
    diff_rank_seed: False
    deterministic: False
    Distributed launcher: none
    Distributed training: False
    GPU number: 1
------------------------------------------------------------

07/05 15:14:29 - mmengine - INFO - Config:
ann_file_test = 'D:/archive/dataset-video-split/test.txt'
ann_file_train = 'D:/archive/dataset-video-split/train.txt'
ann_file_val = 'D:/archive/dataset-video-split/valid.txt'
auto_scale_lr = dict(base_batch_size=256, enable=False)
data_root = 'D:/archive/dataset-video-split/train'
data_root_test = 'D:/archive/dataset-video-split/test'
data_root_val = 'D:/archive/dataset-video-split/valid'
dataset_type = 'VideoDataset'
default_hooks = dict(
    checkpoint=dict(
        interval=3, max_keep_ckpts=3, save_best='auto', type='CheckpointHook'),
    logger=dict(ignore_last=False, interval=20, type='LoggerHook'),
    param_scheduler=dict(type='ParamSchedulerHook'),
    runtime_info=dict(type='RuntimeInfoHook'),
    sampler_seed=dict(type='DistSamplerSeedHook'),
    sync_buffers=dict(type='SyncBuffersHook'),
    timer=dict(type='IterTimerHook'))
default_scope = 'mmaction'
env_cfg = dict(
    cudnn_benchmark=False,
    dist_cfg=dict(backend='nccl'),
    mp_cfg=dict(mp_start_method='fork', opencv_num_threads=0))
file_client_args = dict(io_backend='disk')
launcher = 'none'
load_from = None
log_level = 'INFO'
log_processor = dict(by_epoch=True, type='LogProcessor', window_size=20)
model = dict(
    backbone=dict(
        depth=50,
        norm_eval=False,
        pretrained='https://download.pytorch.org/models/resnet50-11ad3fa6.pth',
        type='ResNet'),
    cls_head=dict(
        average_clips='prob',
        consensus=dict(dim=1, type='AvgConsensus'),
        dropout_ratio=0.4,
        in_channels=2048,
        init_std=0.01,
        num_classes=21,
        spatial_type='avg',
        type='TSNHead'),
    data_preprocessor=dict(
        format_shape='NCHW',
        mean=[
            123.675,
            116.28,
            103.53,
        ],
        std=[
            58.395,
            57.12,
            57.375,
        ],
        type='ActionDataPreprocessor'),
    test_cfg=None,
    train_cfg=None,
    type='Recognizer2D')
optim_wrapper = dict(
    clip_grad=dict(max_norm=40, norm_type=2),
    optimizer=dict(lr=0.01, momentum=0.9, type='SGD', weight_decay=0.0001))
param_scheduler = [
    dict(
        begin=0,
        by_epoch=True,
        end=100,
        gamma=0.1,
        milestones=[
            40,
            80,
        ],
        type='MultiStepLR'),
]
randomness = dict(deterministic=False, diff_rank_seed=False, seed=None)
resume = False
test_cfg = dict(type='TestLoop')
test_dataloader = dict(
    batch_size=1,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/test.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/test'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1,
                frame_interval=1,
                num_clips=8,
                test_mode=True,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(crop_size=224, type='CenterCrop'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        test_mode=True,
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=False, type='DefaultSampler'))
test_evaluator = dict(type='AccMetric')
test_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(
        clip_len=1,
        frame_interval=1,
        num_clips=25,
        test_mode=True,
        type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(crop_size=224, type='TenCrop'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
train_cfg = dict(
    max_epochs=10, type='EpochBasedTrainLoop', val_begin=1, val_interval=1)
train_dataloader = dict(
    batch_size=2,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/train.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/train'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1, frame_interval=1, num_clips=8,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(
                input_size=224,
                max_wh_scale_gap=1,
                random_crop=False,
                scales=(
                    1,
                    0.875,
                    0.75,
                    0.66,
                ),
                type='MultiScaleCrop'),
            dict(keep_ratio=False, scale=(
                224,
                224,
            ), type='Resize'),
            dict(flip_ratio=0.5, type='Flip'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=True, type='DefaultSampler'))
train_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(clip_len=1, frame_interval=1, num_clips=8, type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(
        input_size=224,
        max_wh_scale_gap=1,
        random_crop=False,
        scales=(
            1,
            0.875,
            0.75,
            0.66,
        ),
        type='MultiScaleCrop'),
    dict(keep_ratio=False, scale=(
        224,
        224,
    ), type='Resize'),
    dict(flip_ratio=0.5, type='Flip'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
val_cfg = dict(type='ValLoop')
val_dataloader = dict(
    batch_size=1,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/valid.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/valid'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1,
                frame_interval=1,
                num_clips=8,
                test_mode=True,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(crop_size=224, type='CenterCrop'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        test_mode=True,
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=False, type='DefaultSampler'))
val_evaluator = dict(type='AccMetric')
val_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(
        clip_len=1,
        frame_interval=1,
        num_clips=8,
        test_mode=True,
        type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(crop_size=224, type='CenterCrop'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
vis_backends = [
    dict(type='LocalVisBackend'),
]
visualizer = dict(
    type='ActionVisualizer', vis_backends=[
        dict(type='LocalVisBackend'),
    ])
work_dir = 'work_dirs/tsn_hurto'

C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
07/05 15:14:30 - mmengine - INFO - Distributed training is not used, all SyncBatchNorm (SyncBN) layers in the model will be automatically reverted to BatchNormXd layers if they are used.
07/05 15:14:30 - mmengine - INFO - Hooks will be executed in the following order:
before_run:
(VERY_HIGH   ) RuntimeInfoHook
(BELOW_NORMAL) LoggerHook
 --------------------
before_train:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_train_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(NORMAL      ) DistSamplerSeedHook
 --------------------
before_train_iter:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
 --------------------
after_train_iter:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
after_train_epoch:
(NORMAL      ) IterTimerHook
(NORMAL      ) SyncBuffersHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_val:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
before_val_epoch:
(NORMAL      ) IterTimerHook
(NORMAL      ) SyncBuffersHook
 --------------------
before_val_iter:
(NORMAL      ) IterTimerHook
 --------------------
after_val_iter:
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_val_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
after_val:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
after_train:
(VERY_HIGH   ) RuntimeInfoHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_test:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
before_test_epoch:
(NORMAL      ) IterTimerHook
 --------------------
before_test_iter:
(NORMAL      ) IterTimerHook
 --------------------
after_test_iter:
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_test_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_test:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
after_run:
(BELOW_NORMAL) LoggerHook
 --------------------
Loads checkpoint by http backend from path: https://download.pytorch.org/models/resnet50-11ad3fa6.pth
Downloading: "https://download.pytorch.org/models/resnet50-11ad3fa6.pth" to C:\Users\nesto/.cache\torch\hub\checkpoints\resnet50-11ad3fa6.pth
100%|█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████| 97.8M/97.8M [05:37<00:00, 304kB/s]
07/05 15:20:09 - mmengine - INFO - These parameters in pretrained checkpoint are not loaded: {'fc.bias', 'fc.weight'}
07/05 15:20:09 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/05 15:20:09 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
07/05 15:20:09 - mmengine - INFO - Checkpoints will be saved to C:\Users\nesto\Documents\ai-projects\mmaction2\work_dirs\tsn_hurto.
07/05 15:20:37 - mmengine - INFO - Epoch(train)  [1][ 20/470]  lr: 1.0000e-02  eta: 1:48:37  time: 1.3926  data_time: 0.9880  memory: 1561  grad_norm: 10.7779  loss: 3.1889  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 3.1889
07/05 15:21:06 - mmengine - INFO - Epoch(train)  [1][ 40/470]  lr: 1.0000e-02  eta: 1:49:18  time: 1.4224  data_time: 1.0353  memory: 1561  grad_norm: 8.5622  loss: 3.0067  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 3.0067
07/05 15:21:38 - mmengine - INFO - Epoch(train)  [1][ 60/470]  lr: 1.0000e-02  eta: 1:54:13  time: 1.6162  data_time: 1.2216  memory: 1561  grad_norm: 8.0874  loss: 2.9102  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.9102
07/05 15:22:06 - mmengine - INFO - Epoch(train)  [1][ 80/470]  lr: 1.0000e-02  eta: 1:52:27  time: 1.4110  data_time: 1.0126  memory: 1561  grad_norm: 7.3743  loss: 3.0701  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 3.0701
07/05 15:22:36 - mmengine - INFO - Epoch(train)  [1][100/470]  lr: 1.0000e-02  eta: 1:52:27  time: 1.4920  data_time: 1.0944  memory: 1561  grad_norm: 5.8753  loss: 3.0402  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 3.0402
07/05 15:29:36 - mmengine - INFO - Epoch(train)  [1][120/470]  lr: 1.0000e-02  eta: 6:00:34  time: 21.0075  data_time: 20.5281  memory: 1561  grad_norm: 7.3528  loss: 2.9068  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.9068
07/05 15:30:13 - mmengine - INFO - Epoch(train)  [1][140/470]  lr: 1.0000e-02  eta: 5:27:54  time: 1.8601  data_time: 1.3734  memory: 1561  grad_norm: 6.8797  loss: 2.9840  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.9840
07/05 15:30:54 - mmengine - INFO - Epoch(train)  [1][160/470]  lr: 1.0000e-02  eta: 5:04:50  time: 2.0281  data_time: 1.5150  memory: 1561  grad_norm: 7.3766  loss: 2.7173  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.7173
07/05 15:31:30 - mmengine - INFO - Epoch(train)  [1][180/470]  lr: 1.0000e-02  eta: 4:44:48  time: 1.7954  data_time: 1.3191  memory: 1561  grad_norm: 7.5434  loss: 2.8442  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.8442
07/05 15:32:09 - mmengine - INFO - Epoch(train)  [1][200/470]  lr: 1.0000e-02  eta: 4:29:42  time: 1.9350  data_time: 1.4437  memory: 1561  grad_norm: 7.7328  loss: 2.4805  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.4805
07/05 15:32:45 - mmengine - INFO - Epoch(train)  [1][220/470]  lr: 1.0000e-02  eta: 4:16:30  time: 1.8297  data_time: 1.3455  memory: 1561  grad_norm: 9.9630  loss: 2.7212  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.7212
07/05 15:33:25 - mmengine - INFO - Epoch(train)  [1][240/470]  lr: 1.0000e-02  eta: 4:06:18  time: 1.9736  data_time: 1.4889  memory: 1561  grad_norm: 8.9804  loss: 2.7485  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.7485
07/05 15:34:01 - mmengine - INFO - Epoch(train)  [1][260/470]  lr: 1.0000e-02  eta: 3:56:39  time: 1.8125  data_time: 1.3605  memory: 1561  grad_norm: 8.8554  loss: 2.7133  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.7133
07/05 15:34:38 - mmengine - INFO - Epoch(train)  [1][280/470]  lr: 1.0000e-02  eta: 3:48:34  time: 1.8639  data_time: 1.3929  memory: 1561  grad_norm: 9.0456  loss: 2.7133  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.7133
07/05 15:35:16 - mmengine - INFO - Epoch(train)  [1][300/470]  lr: 1.0000e-02  eta: 3:41:39  time: 1.8990  data_time: 1.4127  memory: 1561  grad_norm: 8.9738  loss: 2.4980  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.4980
07/05 15:36:28 - mmengine - INFO - Epoch(train)  [1][320/470]  lr: 1.0000e-02  eta: 3:43:09  time: 3.5741  data_time: 2.9304  memory: 1561  grad_norm: 10.2614  loss: 2.7555  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.7555
07/05 15:37:43 - mmengine - INFO - Epoch(train)  [1][340/470]  lr: 1.0000e-02  eta: 3:45:16  time: 3.7876  data_time: 3.1151  memory: 1561  grad_norm: 9.8269  loss: 2.7268  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.7268
07/05 15:38:55 - mmengine - INFO - Epoch(train)  [1][360/470]  lr: 1.0000e-02  eta: 3:46:09  time: 3.5783  data_time: 2.8756  memory: 1561  grad_norm: 9.4060  loss: 2.6237  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.6237
07/05 15:40:16 - mmengine - INFO - Epoch(train)  [1][380/470]  lr: 1.0000e-02  eta: 3:48:35  time: 4.0446  data_time: 3.3215  memory: 1561  grad_norm: 9.8386  loss: 2.9136  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.9136
07/05 15:41:28 - mmengine - INFO - Epoch(train)  [1][400/470]  lr: 1.0000e-02  eta: 3:49:08  time: 3.6247  data_time: 2.9245  memory: 1561  grad_norm: 8.7907  loss: 2.7507  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.7507
07/05 15:42:42 - mmengine - INFO - Epoch(train)  [1][420/470]  lr: 1.0000e-02  eta: 3:49:38  time: 3.6559  data_time: 2.9547  memory: 1561  grad_norm: 9.9872  loss: 2.5544  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.5544
07/05 15:43:51 - mmengine - INFO - Epoch(train)  [1][440/470]  lr: 1.0000e-02  eta: 3:49:23  time: 3.4761  data_time: 2.8366  memory: 1561  grad_norm: 9.8601  loss: 2.8329  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.8329
07/05 15:45:01 - mmengine - INFO - Epoch(train)  [1][460/470]  lr: 1.0000e-02  eta: 3:49:12  time: 3.5180  data_time: 2.8572  memory: 1561  grad_norm: 9.0303  loss: 2.6310  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.6310
07/05 15:45:30 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 15:45:30 - mmengine - INFO - Epoch(train)  [1][470/470]  lr: 1.0000e-02  eta: 3:48:04  time: 3.2412  data_time: 2.6112  memory: 1561  grad_norm: 9.0268  loss: 2.7513  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.7513
07/05 15:45:41 - mmengine - INFO - Epoch(val)  [1][ 20/194]    eta: 0:01:35  time: 0.5470  data_time: 0.4742  memory: 292
07/05 15:45:54 - mmengine - INFO - Epoch(val)  [1][ 40/194]    eta: 0:01:33  time: 0.6644  data_time: 0.5912  memory: 292
07/05 15:46:11 - mmengine - INFO - Epoch(val)  [1][ 60/194]    eta: 0:01:32  time: 0.8528  data_time: 0.7670  memory: 292
07/05 15:46:28 - mmengine - INFO - Epoch(val)  [1][ 80/194]    eta: 0:01:22  time: 0.8251  data_time: 0.7348  memory: 292
07/05 15:46:40 - mmengine - INFO - Epoch(val)  [1][100/194]    eta: 0:01:06  time: 0.6220  data_time: 0.5355  memory: 292
07/05 15:46:52 - mmengine - INFO - Epoch(val)  [1][120/194]    eta: 0:00:50  time: 0.5907  data_time: 0.5257  memory: 292
07/05 15:47:12 - mmengine - INFO - Epoch(val)  [1][140/194]    eta: 0:00:39  time: 1.0046  data_time: 0.8952  memory: 292
07/05 15:47:28 - mmengine - INFO - Epoch(val)  [1][160/194]    eta: 0:00:25  time: 0.8015  data_time: 0.7073  memory: 292
07/05 15:47:52 - mmengine - INFO - Epoch(val)  [1][180/194]    eta: 0:00:11  time: 1.1965  data_time: 1.0948  memory: 292
07/05 15:48:08 - mmengine - INFO - Epoch(val) [1][194/194]    acc/top1: 0.1701  acc/top5: 0.5258  acc/mean1: 0.1505  data_time: 0.7275  time: 0.8157
07/05 15:48:09 - mmengine - INFO - The best checkpoint with 0.1701 acc/top1 at 1 epoch is saved to best_acc_top1_epoch_1.pth.
07/05 15:48:40 - mmengine - INFO - Epoch(train)  [2][ 20/470]  lr: 1.0000e-02  eta: 3:42:14  time: 1.5749  data_time: 1.1009  memory: 1561  grad_norm: 10.4325  loss: 2.6033  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.6033
07/05 15:49:06 - mmengine - INFO - Epoch(train)  [2][ 40/470]  lr: 1.0000e-02  eta: 3:36:00  time: 1.2776  data_time: 0.9340  memory: 1561  grad_norm: 10.2457  loss: 2.8907  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.8907
07/05 15:49:29 - mmengine - INFO - Epoch(train)  [2][ 60/470]  lr: 1.0000e-02  eta: 3:29:50  time: 1.1316  data_time: 0.7876  memory: 1561  grad_norm: 9.6467  loss: 2.5995  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.5995
07/05 15:49:51 - mmengine - INFO - Epoch(train)  [2][ 80/470]  lr: 1.0000e-02  eta: 3:24:05  time: 1.1358  data_time: 0.7919  memory: 1561  grad_norm: 10.1387  loss: 2.6900  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.6900
07/05 15:50:17 - mmengine - INFO - Epoch(train)  [2][100/470]  lr: 1.0000e-02  eta: 3:19:05  time: 1.2864  data_time: 0.9347  memory: 1561  grad_norm: 8.3073  loss: 2.8462  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.8462
07/05 15:50:40 - mmengine - INFO - Epoch(train)  [2][120/470]  lr: 1.0000e-02  eta: 3:14:06  time: 1.1649  data_time: 0.8108  memory: 1561  grad_norm: 11.1809  loss: 2.6751  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.6751
07/05 15:51:05 - mmengine - INFO - Epoch(train)  [2][140/470]  lr: 1.0000e-02  eta: 3:09:36  time: 1.2428  data_time: 0.8966  memory: 1561  grad_norm: 10.4007  loss: 2.5179  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.5179
07/05 15:51:29 - mmengine - INFO - Epoch(train)  [2][160/470]  lr: 1.0000e-02  eta: 3:05:13  time: 1.1766  data_time: 0.8340  memory: 1561  grad_norm: 11.2070  loss: 2.5323  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.5323
07/05 15:51:54 - mmengine - INFO - Epoch(train)  [2][180/470]  lr: 1.0000e-02  eta: 3:01:15  time: 1.2577  data_time: 0.9129  memory: 1561  grad_norm: 11.5300  loss: 2.7668  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.7668
07/05 15:52:18 - mmengine - INFO - Epoch(train)  [2][200/470]  lr: 1.0000e-02  eta: 2:57:23  time: 1.2005  data_time: 0.8543  memory: 1561  grad_norm: 9.4354  loss: 2.5961  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.5961
07/05 15:52:42 - mmengine - INFO - Epoch(train)  [2][220/470]  lr: 1.0000e-02  eta: 2:53:46  time: 1.2329  data_time: 0.8860  memory: 1561  grad_norm: 8.8137  loss: 2.8116  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.8116
07/05 15:53:07 - mmengine - INFO - Epoch(train)  [2][240/470]  lr: 1.0000e-02  eta: 2:50:20  time: 1.2322  data_time: 0.8853  memory: 1561  grad_norm: 8.7579  loss: 2.5608  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.5608
07/05 15:53:30 - mmengine - INFO - Epoch(train)  [2][260/470]  lr: 1.0000e-02  eta: 2:46:56  time: 1.1554  data_time: 0.8110  memory: 1561  grad_norm: 8.4967  loss: 2.6059  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.6059
07/05 15:53:56 - mmengine - INFO - Epoch(train)  [2][280/470]  lr: 1.0000e-02  eta: 2:43:54  time: 1.2747  data_time: 0.9282  memory: 1561  grad_norm: 8.5364  loss: 2.5904  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.5904
07/05 15:54:22 - mmengine - INFO - Epoch(train)  [2][300/470]  lr: 1.0000e-02  eta: 2:41:03  time: 1.2995  data_time: 0.9477  memory: 1561  grad_norm: 8.2523  loss: 2.3486  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.3486
07/05 15:54:48 - mmengine - INFO - Epoch(train)  [2][320/470]  lr: 1.0000e-02  eta: 2:38:20  time: 1.3066  data_time: 0.9577  memory: 1561  grad_norm: 8.8374  loss: 2.4562  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.4562
07/05 15:55:14 - mmengine - INFO - Epoch(train)  [2][340/470]  lr: 1.0000e-02  eta: 2:35:41  time: 1.2834  data_time: 0.9340  memory: 1561  grad_norm: 8.3580  loss: 2.4731  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.4731
07/05 15:55:39 - mmengine - INFO - Epoch(train)  [2][360/470]  lr: 1.0000e-02  eta: 2:33:07  time: 1.2654  data_time: 0.9204  memory: 1561  grad_norm: 8.1396  loss: 2.5158  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.5158
07/05 15:56:08 - mmengine - INFO - Epoch(train)  [2][380/470]  lr: 1.0000e-02  eta: 2:30:59  time: 1.4800  data_time: 1.1087  memory: 1561  grad_norm: 8.4092  loss: 2.8913  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.8913
07/05 15:56:36 - mmengine - INFO - Epoch(train)  [2][400/470]  lr: 1.0000e-02  eta: 2:28:46  time: 1.3783  data_time: 1.0330  memory: 1561  grad_norm: 7.8479  loss: 2.4404  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.4404
07/05 15:57:05 - mmengine - INFO - Epoch(train)  [2][420/470]  lr: 1.0000e-02  eta: 2:26:43  time: 1.4391  data_time: 1.0915  memory: 1561  grad_norm: 9.0070  loss: 2.2513  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.2513
07/05 15:57:30 - mmengine - INFO - Epoch(train)  [2][440/470]  lr: 1.0000e-02  eta: 2:24:27  time: 1.2382  data_time: 0.8938  memory: 1561  grad_norm: 9.2959  loss: 2.3920  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.3920
07/05 15:57:56 - mmengine - INFO - Epoch(train)  [2][460/470]  lr: 1.0000e-02  eta: 2:22:22  time: 1.3047  data_time: 0.9595  memory: 1561  grad_norm: 9.3259  loss: 2.4919  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.4919
07/05 15:58:07 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 15:58:07 - mmengine - INFO - Epoch(train)  [2][470/470]  lr: 1.0000e-02  eta: 2:21:15  time: 1.2014  data_time: 0.8576  memory: 1561  grad_norm: 9.1779  loss: 2.5621  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.5621
07/05 15:58:15 - mmengine - INFO - Epoch(val)  [2][ 20/194]    eta: 0:01:03  time: 0.3665  data_time: 0.3090  memory: 292
07/05 15:58:24 - mmengine - INFO - Epoch(val)  [2][ 40/194]    eta: 0:01:04  time: 0.4669  data_time: 0.3944  memory: 292
07/05 15:58:36 - mmengine - INFO - Epoch(val)  [2][ 60/194]    eta: 0:01:03  time: 0.5986  data_time: 0.5493  memory: 292
07/05 15:58:49 - mmengine - INFO - Epoch(val)  [2][ 80/194]    eta: 0:00:59  time: 0.6432  data_time: 0.5530  memory: 292
07/05 15:58:58 - mmengine - INFO - Epoch(val)  [2][100/194]    eta: 0:00:47  time: 0.4623  data_time: 0.4004  memory: 292
07/05 15:59:07 - mmengine - INFO - Epoch(val)  [2][120/194]    eta: 0:00:36  time: 0.4593  data_time: 0.3882  memory: 292
07/05 15:59:23 - mmengine - INFO - Epoch(val)  [2][140/194]    eta: 0:00:29  time: 0.7867  data_time: 0.6885  memory: 292
07/05 15:59:34 - mmengine - INFO - Epoch(val)  [2][160/194]    eta: 0:00:18  time: 0.5524  data_time: 0.4775  memory: 292
07/05 15:59:51 - mmengine - INFO - Epoch(val)  [2][180/194]    eta: 0:00:08  time: 0.8437  data_time: 0.7649  memory: 292
07/05 16:00:03 - mmengine - INFO - Epoch(val) [2][194/194]    acc/top1: 0.2732  acc/top5: 0.6237  acc/mean1: 0.2281  data_time: 0.5234  time: 0.5972
07/05 16:00:03 - mmengine - INFO - The previous best checkpoint C:\Users\nesto\Documents\ai-projects\mmaction2\work_dirs\tsn_hurto\best_acc_top1_epoch_1.pth is removed
07/05 16:00:03 - mmengine - INFO - The best checkpoint with 0.2732 acc/top1 at 2 epoch is saved to best_acc_top1_epoch_2.pth.
07/05 16:00:28 - mmengine - INFO - Epoch(train)  [3][ 20/470]  lr: 1.0000e-02  eta: 2:19:11  time: 1.2502  data_time: 0.8956  memory: 1561  grad_norm: 9.9866  loss: 2.5231  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.5231
07/05 16:00:53 - mmengine - INFO - Epoch(train)  [3][ 40/470]  lr: 1.0000e-02  eta: 2:17:10  time: 1.2273  data_time: 0.8808  memory: 1561  grad_norm: 8.6370  loss: 2.5141  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.5141
07/05 16:01:19 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:01:19 - mmengine - INFO - Epoch(train)  [3][ 60/470]  lr: 1.0000e-02  eta: 2:15:19  time: 1.3001  data_time: 0.9538  memory: 1561  grad_norm: 9.4767  loss: 2.6751  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.6751
07/05 16:01:47 - mmengine - INFO - Epoch(train)  [3][ 80/470]  lr: 1.0000e-02  eta: 2:13:37  time: 1.4012  data_time: 1.0451  memory: 1561  grad_norm: 10.4886  loss: 2.3283  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3283
07/05 16:02:12 - mmengine - INFO - Epoch(train)  [3][100/470]  lr: 1.0000e-02  eta: 2:11:48  time: 1.2457  data_time: 0.9009  memory: 1561  grad_norm: 9.2547  loss: 2.3847  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.3847
07/05 16:02:39 - mmengine - INFO - Epoch(train)  [3][120/470]  lr: 1.0000e-02  eta: 2:10:09  time: 1.3444  data_time: 0.9975  memory: 1561  grad_norm: 8.8014  loss: 2.4287  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.4287
07/05 16:03:05 - mmengine - INFO - Epoch(train)  [3][140/470]  lr: 1.0000e-02  eta: 2:08:30  time: 1.3130  data_time: 0.9635  memory: 1561  grad_norm: 8.5170  loss: 2.1731  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.1731
07/05 16:03:31 - mmengine - INFO - Epoch(train)  [3][160/470]  lr: 1.0000e-02  eta: 2:06:54  time: 1.3099  data_time: 0.9646  memory: 1561  grad_norm: 9.3841  loss: 2.2104  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.2104
07/05 16:03:58 - mmengine - INFO - Epoch(train)  [3][180/470]  lr: 1.0000e-02  eta: 2:05:23  time: 1.3552  data_time: 1.0091  memory: 1561  grad_norm: 9.7499  loss: 2.0667  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.0667
07/05 16:04:23 - mmengine - INFO - Epoch(train)  [3][200/470]  lr: 1.0000e-02  eta: 2:03:47  time: 1.2374  data_time: 0.8913  memory: 1561  grad_norm: 8.7135  loss: 2.4899  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.4899
07/05 16:04:50 - mmengine - INFO - Epoch(train)  [3][220/470]  lr: 1.0000e-02  eta: 2:02:19  time: 1.3334  data_time: 0.9884  memory: 1561  grad_norm: 10.5770  loss: 2.3685  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.3685
07/05 16:05:14 - mmengine - INFO - Epoch(train)  [3][240/470]  lr: 1.0000e-02  eta: 2:00:46  time: 1.2110  data_time: 0.8650  memory: 1561  grad_norm: 8.9560  loss: 2.3680  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.3680
07/05 16:05:38 - mmengine - INFO - Epoch(train)  [3][260/470]  lr: 1.0000e-02  eta: 1:59:17  time: 1.2282  data_time: 0.8835  memory: 1561  grad_norm: 8.9765  loss: 2.1999  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.1999
07/05 16:06:05 - mmengine - INFO - Epoch(train)  [3][280/470]  lr: 1.0000e-02  eta: 1:57:55  time: 1.3289  data_time: 0.9811  memory: 1561  grad_norm: 10.3896  loss: 2.4756  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.4756
07/05 16:06:32 - mmengine - INFO - Epoch(train)  [3][300/470]  lr: 1.0000e-02  eta: 1:56:36  time: 1.3540  data_time: 1.0066  memory: 1561  grad_norm: 9.7136  loss: 2.1509  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.1509
07/05 16:06:57 - mmengine - INFO - Epoch(train)  [3][320/470]  lr: 1.0000e-02  eta: 1:55:15  time: 1.2632  data_time: 0.9058  memory: 1561  grad_norm: 10.2522  loss: 2.1538  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.1538
07/05 16:07:20 - mmengine - INFO - Epoch(train)  [3][340/470]  lr: 1.0000e-02  eta: 1:53:49  time: 1.1534  data_time: 0.8094  memory: 1561  grad_norm: 9.0913  loss: 2.3021  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.3021
07/05 16:07:45 - mmengine - INFO - Epoch(train)  [3][360/470]  lr: 1.0000e-02  eta: 1:52:28  time: 1.2161  data_time: 0.8698  memory: 1561  grad_norm: 9.2099  loss: 2.0650  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.0650
07/05 16:08:08 - mmengine - INFO - Epoch(train)  [3][380/470]  lr: 1.0000e-02  eta: 1:51:05  time: 1.1448  data_time: 0.7978  memory: 1561  grad_norm: 9.0404  loss: 2.4423  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.4423
07/05 16:08:31 - mmengine - INFO - Epoch(train)  [3][400/470]  lr: 1.0000e-02  eta: 1:49:46  time: 1.1834  data_time: 0.8363  memory: 1561  grad_norm: 9.0738  loss: 2.2668  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.2668
07/05 16:08:55 - mmengine - INFO - Epoch(train)  [3][420/470]  lr: 1.0000e-02  eta: 1:48:29  time: 1.1965  data_time: 0.8469  memory: 1561  grad_norm: 7.9076  loss: 2.5065  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.5065
07/05 16:09:20 - mmengine - INFO - Epoch(train)  [3][440/470]  lr: 1.0000e-02  eta: 1:47:17  time: 1.2516  data_time: 0.9083  memory: 1561  grad_norm: 9.1978  loss: 2.2299  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.2299
07/05 16:09:46 - mmengine - INFO - Epoch(train)  [3][460/470]  lr: 1.0000e-02  eta: 1:46:08  time: 1.3005  data_time: 0.9552  memory: 1561  grad_norm: 9.9027  loss: 2.5967  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.5967
07/05 16:09:58 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:09:58 - mmengine - INFO - Epoch(train)  [3][470/470]  lr: 1.0000e-02  eta: 1:45:31  time: 1.2548  data_time: 0.9112  memory: 1561  grad_norm: 9.1324  loss: 2.2638  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.2638
07/05 16:09:58 - mmengine - INFO - Saving checkpoint at 3 epochs
07/05 16:10:06 - mmengine - INFO - Epoch(val)  [3][ 20/194]    eta: 0:01:00  time: 0.3500  data_time: 0.2876  memory: 292
07/05 16:10:15 - mmengine - INFO - Epoch(val)  [3][ 40/194]    eta: 0:01:02  time: 0.4618  data_time: 0.3714  memory: 292
07/05 16:10:27 - mmengine - INFO - Epoch(val)  [3][ 60/194]    eta: 0:01:02  time: 0.5917  data_time: 0.5193  memory: 292
07/05 16:10:38 - mmengine - INFO - Epoch(val)  [3][ 80/194]    eta: 0:00:55  time: 0.5609  data_time: 0.4929  memory: 292
07/05 16:10:47 - mmengine - INFO - Epoch(val)  [3][100/194]    eta: 0:00:44  time: 0.4225  data_time: 0.3545  memory: 292
07/05 16:10:55 - mmengine - INFO - Epoch(val)  [3][120/194]    eta: 0:00:34  time: 0.4052  data_time: 0.3415  memory: 292
07/05 16:11:09 - mmengine - INFO - Epoch(val)  [3][140/194]    eta: 0:00:26  time: 0.6981  data_time: 0.6117  memory: 292
07/05 16:11:19 - mmengine - INFO - Epoch(val)  [3][160/194]    eta: 0:00:17  time: 0.5173  data_time: 0.4426  memory: 292
07/05 16:11:36 - mmengine - INFO - Epoch(val)  [3][180/194]    eta: 0:00:07  time: 0.8643  data_time: 0.7438  memory: 292
07/05 16:11:47 - mmengine - INFO - Epoch(val) [3][194/194]    acc/top1: 0.2113  acc/top5: 0.5722  acc/mean1: 0.1959  data_time: 0.4824  time: 0.5606
07/05 16:12:11 - mmengine - INFO - Epoch(train)  [4][ 20/470]  lr: 1.0000e-02  eta: 1:44:20  time: 1.1966  data_time: 0.8386  memory: 1561  grad_norm: 8.1499  loss: 2.3003  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.3003
07/05 16:12:35 - mmengine - INFO - Epoch(train)  [4][ 40/470]  lr: 1.0000e-02  eta: 1:43:08  time: 1.1649  data_time: 0.8185  memory: 1561  grad_norm: 8.5202  loss: 2.1939  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.1939
07/05 16:12:58 - mmengine - INFO - Epoch(train)  [4][ 60/470]  lr: 1.0000e-02  eta: 1:41:58  time: 1.1788  data_time: 0.8304  memory: 1561  grad_norm: 9.1617  loss: 2.1460  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.1460
07/05 16:13:25 - mmengine - INFO - Epoch(train)  [4][ 80/470]  lr: 1.0000e-02  eta: 1:40:57  time: 1.3578  data_time: 1.0072  memory: 1561  grad_norm: 11.1556  loss: 2.3022  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.3022
07/05 16:13:49 - mmengine - INFO - Epoch(train)  [4][100/470]  lr: 1.0000e-02  eta: 1:39:48  time: 1.1617  data_time: 0.8150  memory: 1561  grad_norm: 9.7682  loss: 2.6190  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.6190
07/05 16:14:12 - mmengine - INFO - Epoch(train)  [4][120/470]  lr: 1.0000e-02  eta: 1:38:41  time: 1.1522  data_time: 0.8056  memory: 1561  grad_norm: 8.8417  loss: 2.5748  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.5748
07/05 16:14:34 - mmengine - INFO - Epoch(train)  [4][140/470]  lr: 1.0000e-02  eta: 1:37:34  time: 1.1375  data_time: 0.7889  memory: 1561  grad_norm: 8.0456  loss: 2.4084  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.4084
07/05 16:14:58 - mmengine - INFO - Epoch(train)  [4][160/470]  lr: 1.0000e-02  eta: 1:36:30  time: 1.2012  data_time: 0.8540  memory: 1561  grad_norm: 8.3722  loss: 2.4732  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.4732
07/05 16:15:24 - mmengine - INFO - Epoch(train)  [4][180/470]  lr: 1.0000e-02  eta: 1:35:32  time: 1.2984  data_time: 0.9539  memory: 1561  grad_norm: 9.2478  loss: 2.5552  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.5552
07/05 16:15:50 - mmengine - INFO - Epoch(train)  [4][200/470]  lr: 1.0000e-02  eta: 1:34:32  time: 1.2530  data_time: 0.9057  memory: 1561  grad_norm: 8.2377  loss: 2.3523  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.3523
07/05 16:16:15 - mmengine - INFO - Epoch(train)  [4][220/470]  lr: 1.0000e-02  eta: 1:33:35  time: 1.2881  data_time: 0.9409  memory: 1561  grad_norm: 9.4112  loss: 2.4001  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.4001
07/05 16:16:40 - mmengine - INFO - Epoch(train)  [4][240/470]  lr: 1.0000e-02  eta: 1:32:37  time: 1.2377  data_time: 0.8905  memory: 1561  grad_norm: 8.4158  loss: 2.2722  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.2722
07/05 16:17:03 - mmengine - INFO - Epoch(train)  [4][260/470]  lr: 1.0000e-02  eta: 1:31:35  time: 1.1241  data_time: 0.7783  memory: 1561  grad_norm: 7.9321  loss: 2.3105  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.3105
07/05 16:17:28 - mmengine - INFO - Epoch(train)  [4][280/470]  lr: 1.0000e-02  eta: 1:30:39  time: 1.2654  data_time: 0.9217  memory: 1561  grad_norm: 7.5375  loss: 2.2862  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.2862
07/05 16:17:54 - mmengine - INFO - Epoch(train)  [4][300/470]  lr: 1.0000e-02  eta: 1:29:46  time: 1.3227  data_time: 0.9776  memory: 1561  grad_norm: 8.5748  loss: 2.0866  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.0866
07/05 16:18:19 - mmengine - INFO - Epoch(train)  [4][320/470]  lr: 1.0000e-02  eta: 1:28:50  time: 1.2365  data_time: 0.8904  memory: 1561  grad_norm: 9.0441  loss: 2.2523  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.2523
07/05 16:18:44 - mmengine - INFO - Epoch(train)  [4][340/470]  lr: 1.0000e-02  eta: 1:27:57  time: 1.2711  data_time: 0.9157  memory: 1561  grad_norm: 8.0535  loss: 1.9561  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 1.9561
07/05 16:19:10 - mmengine - INFO - Epoch(train)  [4][360/470]  lr: 1.0000e-02  eta: 1:27:04  time: 1.2769  data_time: 0.9310  memory: 1561  grad_norm: 8.9827  loss: 2.2023  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.2023
07/05 16:19:34 - mmengine - INFO - Epoch(train)  [4][380/470]  lr: 1.0000e-02  eta: 1:26:09  time: 1.1782  data_time: 0.8333  memory: 1561  grad_norm: 10.5557  loss: 2.5836  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.5836
07/05 16:19:58 - mmengine - INFO - Epoch(train)  [4][400/470]  lr: 1.0000e-02  eta: 1:25:16  time: 1.2370  data_time: 0.8939  memory: 1561  grad_norm: 8.5420  loss: 2.2944  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.2944
07/05 16:20:23 - mmengine - INFO - Epoch(train)  [4][420/470]  lr: 1.0000e-02  eta: 1:24:24  time: 1.2476  data_time: 0.8997  memory: 1561  grad_norm: 8.4389  loss: 2.2952  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.2952
07/05 16:20:47 - mmengine - INFO - Epoch(train)  [4][440/470]  lr: 1.0000e-02  eta: 1:23:32  time: 1.1999  data_time: 0.8522  memory: 1561  grad_norm: 7.7780  loss: 2.3439  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.3439
07/05 16:21:12 - mmengine - INFO - Epoch(train)  [4][460/470]  lr: 1.0000e-02  eta: 1:22:40  time: 1.2184  data_time: 0.8725  memory: 1561  grad_norm: 8.9119  loss: 2.2833  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.2833
07/05 16:21:23 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:21:23 - mmengine - INFO - Epoch(train)  [4][470/470]  lr: 1.0000e-02  eta: 1:22:13  time: 1.1110  data_time: 0.7685  memory: 1561  grad_norm: 7.7907  loss: 2.4876  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.4876
07/05 16:21:30 - mmengine - INFO - Epoch(val)  [4][ 20/194]    eta: 0:00:58  time: 0.3380  data_time: 0.2814  memory: 292
07/05 16:21:39 - mmengine - INFO - Epoch(val)  [4][ 40/194]    eta: 0:01:01  time: 0.4604  data_time: 0.3835  memory: 292
07/05 16:21:58 - mmengine - INFO - Epoch(val)  [4][ 60/194]    eta: 0:01:18  time: 0.9517  data_time: 0.9106  memory: 292
07/05 16:22:18 - mmengine - INFO - Epoch(val)  [4][ 80/194]    eta: 0:01:18  time: 0.9959  data_time: 0.9537  memory: 292
07/05 16:22:30 - mmengine - INFO - Epoch(val)  [4][100/194]    eta: 0:01:03  time: 0.6112  data_time: 0.5677  memory: 292
07/05 16:22:43 - mmengine - INFO - Epoch(val)  [4][120/194]    eta: 0:00:49  time: 0.6236  data_time: 0.5832  memory: 292
07/05 16:23:07 - mmengine - INFO - Epoch(val)  [4][140/194]    eta: 0:00:40  time: 1.2229  data_time: 1.1784  memory: 292
07/05 16:23:25 - mmengine - INFO - Epoch(val)  [4][160/194]    eta: 0:00:25  time: 0.8780  data_time: 0.8408  memory: 292
07/05 16:23:54 - mmengine - INFO - Epoch(val)  [4][180/194]    eta: 0:00:11  time: 1.4535  data_time: 1.4150  memory: 292
07/05 16:24:05 - mmengine - INFO - Epoch(val) [4][194/194]    acc/top1: 0.2216  acc/top5: 0.6907  acc/mean1: 0.1927  data_time: 0.7866  time: 0.8328
07/05 16:24:31 - mmengine - INFO - Epoch(train)  [5][ 20/470]  lr: 1.0000e-02  eta: 1:21:26  time: 1.3427  data_time: 0.9472  memory: 1561  grad_norm: 7.4743  loss: 1.8773  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.8773
07/05 16:24:55 - mmengine - INFO - Epoch(train)  [5][ 40/470]  lr: 1.0000e-02  eta: 1:20:35  time: 1.1947  data_time: 0.8042  memory: 1561  grad_norm: 7.8612  loss: 2.1000  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.1000
07/05 16:25:23 - mmengine - INFO - Epoch(train)  [5][ 60/470]  lr: 1.0000e-02  eta: 1:19:50  time: 1.3823  data_time: 0.9928  memory: 1561  grad_norm: 8.4868  loss: 2.1485  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.1485
07/05 16:25:49 - mmengine - INFO - Epoch(train)  [5][ 80/470]  lr: 1.0000e-02  eta: 1:19:03  time: 1.2997  data_time: 0.9097  memory: 1561  grad_norm: 8.4000  loss: 2.3013  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3013
07/05 16:26:16 - mmengine - INFO - Epoch(train)  [5][100/470]  lr: 1.0000e-02  eta: 1:18:19  time: 1.3692  data_time: 0.9790  memory: 1561  grad_norm: 8.5181  loss: 2.0274  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.0274
07/05 16:26:43 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:26:43 - mmengine - INFO - Epoch(train)  [5][120/470]  lr: 1.0000e-02  eta: 1:17:34  time: 1.3511  data_time: 0.9592  memory: 1561  grad_norm: 8.3099  loss: 2.0193  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.0193
07/05 16:27:10 - mmengine - INFO - Epoch(train)  [5][140/470]  lr: 1.0000e-02  eta: 1:16:49  time: 1.3327  data_time: 0.9301  memory: 1561  grad_norm: 7.3245  loss: 1.8669  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.8669
07/05 16:27:36 - mmengine - INFO - Epoch(train)  [5][160/470]  lr: 1.0000e-02  eta: 1:16:03  time: 1.2822  data_time: 0.8944  memory: 1561  grad_norm: 9.1090  loss: 2.3756  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.3756
07/05 16:28:02 - mmengine - INFO - Epoch(train)  [5][180/470]  lr: 1.0000e-02  eta: 1:15:19  time: 1.3272  data_time: 0.9379  memory: 1561  grad_norm: 8.1682  loss: 2.1073  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.1073
07/05 16:28:27 - mmengine - INFO - Epoch(train)  [5][200/470]  lr: 1.0000e-02  eta: 1:14:33  time: 1.2598  data_time: 0.8698  memory: 1561  grad_norm: 9.3159  loss: 2.3224  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3224
07/05 16:28:53 - mmengine - INFO - Epoch(train)  [5][220/470]  lr: 1.0000e-02  eta: 1:13:49  time: 1.2956  data_time: 0.9062  memory: 1561  grad_norm: 7.4367  loss: 2.0858  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.0858
07/05 16:29:19 - mmengine - INFO - Epoch(train)  [5][240/470]  lr: 1.0000e-02  eta: 1:13:05  time: 1.2800  data_time: 0.8873  memory: 1561  grad_norm: 9.7331  loss: 2.4726  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.4726
07/05 16:29:47 - mmengine - INFO - Epoch(train)  [5][260/470]  lr: 1.0000e-02  eta: 1:12:23  time: 1.3924  data_time: 1.0047  memory: 1561  grad_norm: 10.3334  loss: 2.0507  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.0507
07/05 16:30:14 - mmengine - INFO - Epoch(train)  [5][280/470]  lr: 1.0000e-02  eta: 1:11:41  time: 1.3412  data_time: 0.9532  memory: 1561  grad_norm: 8.3186  loss: 2.3545  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3545
07/05 16:30:42 - mmengine - INFO - Epoch(train)  [5][300/470]  lr: 1.0000e-02  eta: 1:11:01  time: 1.4037  data_time: 1.0142  memory: 1561  grad_norm: 8.0135  loss: 1.9713  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.9713
07/05 16:31:09 - mmengine - INFO - Epoch(train)  [5][320/470]  lr: 1.0000e-02  eta: 1:10:19  time: 1.3596  data_time: 0.9591  memory: 1561  grad_norm: 8.9178  loss: 2.0997  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.0997
07/05 16:31:34 - mmengine - INFO - Epoch(train)  [5][340/470]  lr: 1.0000e-02  eta: 1:09:36  time: 1.2537  data_time: 0.8649  memory: 1561  grad_norm: 8.2344  loss: 2.0310  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.0310
07/05 16:32:00 - mmengine - INFO - Epoch(train)  [5][360/470]  lr: 1.0000e-02  eta: 1:08:53  time: 1.2934  data_time: 0.9036  memory: 1561  grad_norm: 9.4733  loss: 2.3579  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3579
07/05 16:32:26 - mmengine - INFO - Epoch(train)  [5][380/470]  lr: 1.0000e-02  eta: 1:08:12  time: 1.3231  data_time: 0.9341  memory: 1561  grad_norm: 8.4352  loss: 2.3003  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3003
07/05 16:32:55 - mmengine - INFO - Epoch(train)  [5][400/470]  lr: 1.0000e-02  eta: 1:07:33  time: 1.4318  data_time: 1.0420  memory: 1561  grad_norm: 9.1305  loss: 2.3517  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3517
07/05 16:33:19 - mmengine - INFO - Epoch(train)  [5][420/470]  lr: 1.0000e-02  eta: 1:06:50  time: 1.1958  data_time: 0.8058  memory: 1561  grad_norm: 7.7731  loss: 2.2929  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.2929
07/05 16:33:48 - mmengine - INFO - Epoch(train)  [5][440/470]  lr: 1.0000e-02  eta: 1:06:12  time: 1.4531  data_time: 1.0621  memory: 1561  grad_norm: 8.4425  loss: 2.0181  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.0181
07/05 16:34:13 - mmengine - INFO - Epoch(train)  [5][460/470]  lr: 1.0000e-02  eta: 1:05:30  time: 1.2564  data_time: 0.8636  memory: 1561  grad_norm: 8.1909  loss: 2.2209  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.2209
07/05 16:34:26 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:34:26 - mmengine - INFO - Epoch(train)  [5][470/470]  lr: 1.0000e-02  eta: 1:05:10  time: 1.2115  data_time: 0.8204  memory: 1561  grad_norm: 8.0294  loss: 2.4603  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.4603
07/05 16:34:32 - mmengine - INFO - Epoch(val)  [5][ 20/194]    eta: 0:00:56  time: 0.3223  data_time: 0.2965  memory: 292
07/05 16:34:40 - mmengine - INFO - Epoch(val)  [5][ 40/194]    eta: 0:00:56  time: 0.4108  data_time: 0.3754  memory: 292
07/05 16:34:52 - mmengine - INFO - Epoch(val)  [5][ 60/194]    eta: 0:00:58  time: 0.5729  data_time: 0.5343  memory: 292
07/05 16:35:03 - mmengine - INFO - Epoch(val)  [5][ 80/194]    eta: 0:00:52  time: 0.5417  data_time: 0.5084  memory: 292
07/05 16:35:11 - mmengine - INFO - Epoch(val)  [5][100/194]    eta: 0:00:42  time: 0.3962  data_time: 0.3596  memory: 292
07/05 16:35:18 - mmengine - INFO - Epoch(val)  [5][120/194]    eta: 0:00:32  time: 0.3804  data_time: 0.3463  memory: 292
07/05 16:35:31 - mmengine - INFO - Epoch(val)  [5][140/194]    eta: 0:00:25  time: 0.6569  data_time: 0.6221  memory: 292
07/05 16:35:41 - mmengine - INFO - Epoch(val)  [5][160/194]    eta: 0:00:16  time: 0.4903  data_time: 0.4507  memory: 292
07/05 16:35:57 - mmengine - INFO - Epoch(val)  [5][180/194]    eta: 0:00:07  time: 0.7828  data_time: 0.7443  memory: 292
07/05 16:36:08 - mmengine - INFO - Epoch(val) [5][194/194]    acc/top1: 0.2680  acc/top5: 0.6289  acc/mean1: 0.2323  data_time: 0.4901  time: 0.5268
07/05 16:36:35 - mmengine - INFO - Epoch(train)  [6][ 20/470]  lr: 1.0000e-02  eta: 1:04:31  time: 1.3753  data_time: 0.9742  memory: 1561  grad_norm: 7.5732  loss: 2.0372  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.0372
07/05 16:37:04 - mmengine - INFO - Epoch(train)  [6][ 40/470]  lr: 1.0000e-02  eta: 1:03:53  time: 1.4326  data_time: 1.0384  memory: 1561  grad_norm: 7.7408  loss: 2.2294  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.2294
07/05 16:37:28 - mmengine - INFO - Epoch(train)  [6][ 60/470]  lr: 1.0000e-02  eta: 1:03:11  time: 1.1952  data_time: 0.8062  memory: 1561  grad_norm: 6.8566  loss: 1.9246  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.9246
07/05 16:37:55 - mmengine - INFO - Epoch(train)  [6][ 80/470]  lr: 1.0000e-02  eta: 1:02:32  time: 1.3477  data_time: 0.9592  memory: 1561  grad_norm: 8.6638  loss: 1.9786  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 1.9786
07/05 16:38:22 - mmengine - INFO - Epoch(train)  [6][100/470]  lr: 1.0000e-02  eta: 1:01:53  time: 1.3517  data_time: 0.9618  memory: 1561  grad_norm: 8.8726  loss: 2.3217  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3217
07/05 16:38:47 - mmengine - INFO - Epoch(train)  [6][120/470]  lr: 1.0000e-02  eta: 1:01:13  time: 1.2589  data_time: 0.8686  memory: 1561  grad_norm: 7.3422  loss: 2.0488  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.0488
07/05 16:39:14 - mmengine - INFO - Epoch(train)  [6][140/470]  lr: 1.0000e-02  eta: 1:00:35  time: 1.3366  data_time: 0.9454  memory: 1561  grad_norm: 7.9868  loss: 1.9735  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.9735
07/05 16:39:45 - mmengine - INFO - Epoch(train)  [6][160/470]  lr: 1.0000e-02  eta: 1:00:00  time: 1.5509  data_time: 1.1544  memory: 1561  grad_norm: 8.5904  loss: 1.9553  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.9553
07/05 16:40:10 - mmengine - INFO - Epoch(train)  [6][180/470]  lr: 1.0000e-02  eta: 0:59:21  time: 1.2639  data_time: 0.8758  memory: 1561  grad_norm: 7.5610  loss: 2.2560  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.2560
07/05 16:40:34 - mmengine - INFO - Epoch(train)  [6][200/470]  lr: 1.0000e-02  eta: 0:58:41  time: 1.2085  data_time: 0.8137  memory: 1561  grad_norm: 7.0104  loss: 2.1419  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.1419
07/05 16:40:59 - mmengine - INFO - Epoch(train)  [6][220/470]  lr: 1.0000e-02  eta: 0:58:01  time: 1.2403  data_time: 0.8497  memory: 1561  grad_norm: 7.4264  loss: 2.4906  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.4906
07/05 16:41:22 - mmengine - INFO - Epoch(train)  [6][240/470]  lr: 1.0000e-02  eta: 0:57:21  time: 1.1322  data_time: 0.7419  memory: 1561  grad_norm: 7.2903  loss: 2.3006  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.3006
07/05 16:41:46 - mmengine - INFO - Epoch(train)  [6][260/470]  lr: 1.0000e-02  eta: 0:56:41  time: 1.2244  data_time: 0.8361  memory: 1561  grad_norm: 7.4999  loss: 2.4964  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.4964
07/05 16:42:12 - mmengine - INFO - Epoch(train)  [6][280/470]  lr: 1.0000e-02  eta: 0:56:04  time: 1.3059  data_time: 0.9027  memory: 1561  grad_norm: 6.4786  loss: 1.9823  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 1.9823
07/05 16:42:40 - mmengine - INFO - Epoch(train)  [6][300/470]  lr: 1.0000e-02  eta: 0:55:27  time: 1.3745  data_time: 0.9788  memory: 1561  grad_norm: 7.5927  loss: 1.7451  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.7451
07/05 16:43:07 - mmengine - INFO - Epoch(train)  [6][320/470]  lr: 1.0000e-02  eta: 0:54:51  time: 1.3660  data_time: 0.9709  memory: 1561  grad_norm: 7.7138  loss: 2.0128  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.0128
07/05 16:43:34 - mmengine - INFO - Epoch(train)  [6][340/470]  lr: 1.0000e-02  eta: 0:54:15  time: 1.3712  data_time: 0.9838  memory: 1561  grad_norm: 9.0748  loss: 2.2945  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.2945
07/05 16:44:00 - mmengine - INFO - Epoch(train)  [6][360/470]  lr: 1.0000e-02  eta: 0:53:37  time: 1.2620  data_time: 0.8727  memory: 1561  grad_norm: 8.3636  loss: 2.4442  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.4442
07/05 16:44:26 - mmengine - INFO - Epoch(train)  [6][380/470]  lr: 1.0000e-02  eta: 0:53:00  time: 1.3081  data_time: 0.9192  memory: 1561  grad_norm: 7.5410  loss: 2.0401  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.0401
07/05 16:44:52 - mmengine - INFO - Epoch(train)  [6][400/470]  lr: 1.0000e-02  eta: 0:52:24  time: 1.3149  data_time: 0.9230  memory: 1561  grad_norm: 8.1199  loss: 2.2145  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.2145
07/05 16:45:21 - mmengine - INFO - Epoch(train)  [6][420/470]  lr: 1.0000e-02  eta: 0:51:49  time: 1.4226  data_time: 1.0335  memory: 1561  grad_norm: 7.2290  loss: 1.8113  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 1.8113
07/05 16:45:48 - mmengine - INFO - Epoch(train)  [6][440/470]  lr: 1.0000e-02  eta: 0:51:13  time: 1.3603  data_time: 0.9722  memory: 1561  grad_norm: 6.6837  loss: 2.0275  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.0275
07/05 16:46:14 - mmengine - INFO - Epoch(train)  [6][460/470]  lr: 1.0000e-02  eta: 0:50:37  time: 1.2953  data_time: 0.9070  memory: 1561  grad_norm: 7.0857  loss: 1.9898  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.9898
07/05 16:46:26 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:46:26 - mmengine - INFO - Epoch(train)  [6][470/470]  lr: 1.0000e-02  eta: 0:50:19  time: 1.2805  data_time: 0.8888  memory: 1561  grad_norm: 7.2169  loss: 1.9995  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 1.9995
07/05 16:46:27 - mmengine - INFO - Saving checkpoint at 6 epochs
07/05 16:46:34 - mmengine - INFO - Epoch(val)  [6][ 20/194]    eta: 0:00:58  time: 0.3359  data_time: 0.2982  memory: 292
07/05 16:46:42 - mmengine - INFO - Epoch(val)  [6][ 40/194]    eta: 0:00:57  time: 0.4075  data_time: 0.3769  memory: 292
07/05 16:46:53 - mmengine - INFO - Epoch(val)  [6][ 60/194]    eta: 0:00:58  time: 0.5725  data_time: 0.5343  memory: 292
07/05 16:47:04 - mmengine - INFO - Epoch(val)  [6][ 80/194]    eta: 0:00:53  time: 0.5502  data_time: 0.5112  memory: 292
07/05 16:47:12 - mmengine - INFO - Epoch(val)  [6][100/194]    eta: 0:00:42  time: 0.3957  data_time: 0.3612  memory: 292
07/05 16:47:20 - mmengine - INFO - Epoch(val)  [6][120/194]    eta: 0:00:32  time: 0.3890  data_time: 0.3480  memory: 292
07/05 16:47:33 - mmengine - INFO - Epoch(val)  [6][140/194]    eta: 0:00:25  time: 0.6641  data_time: 0.6219  memory: 292
07/05 16:47:43 - mmengine - INFO - Epoch(val)  [6][160/194]    eta: 0:00:16  time: 0.4906  data_time: 0.4479  memory: 292
07/05 16:47:59 - mmengine - INFO - Epoch(val)  [6][180/194]    eta: 0:00:07  time: 0.8005  data_time: 0.7440  memory: 292
07/05 16:48:10 - mmengine - INFO - Epoch(val) [6][194/194]    acc/top1: 0.3041  acc/top5: 0.6907  acc/mean1: 0.2625  data_time: 0.4914  time: 0.5313
07/05 16:48:10 - mmengine - INFO - The previous best checkpoint C:\Users\nesto\Documents\ai-projects\mmaction2\work_dirs\tsn_hurto\best_acc_top1_epoch_2.pth is removed
07/05 16:48:11 - mmengine - INFO - The best checkpoint with 0.3041 acc/top1 at 6 epoch is saved to best_acc_top1_epoch_6.pth.
07/05 16:48:37 - mmengine - INFO - Epoch(train)  [7][ 20/470]  lr: 1.0000e-02  eta: 0:49:43  time: 1.3001  data_time: 0.9116  memory: 1561  grad_norm: 7.2314  loss: 1.9231  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.9231
07/05 16:49:04 - mmengine - INFO - Epoch(train)  [7][ 40/470]  lr: 1.0000e-02  eta: 0:49:07  time: 1.3544  data_time: 0.9637  memory: 1561  grad_norm: 8.4848  loss: 2.2096  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.2096
07/05 16:49:32 - mmengine - INFO - Epoch(train)  [7][ 60/470]  lr: 1.0000e-02  eta: 0:48:32  time: 1.3564  data_time: 0.9647  memory: 1561  grad_norm: 7.7050  loss: 1.8953  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.8953
07/05 16:49:59 - mmengine - INFO - Epoch(train)  [7][ 80/470]  lr: 1.0000e-02  eta: 0:47:58  time: 1.3897  data_time: 1.0015  memory: 1561  grad_norm: 7.6393  loss: 1.9048  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.9048
07/05 16:50:26 - mmengine - INFO - Epoch(train)  [7][100/470]  lr: 1.0000e-02  eta: 0:47:22  time: 1.3376  data_time: 0.9388  memory: 1561  grad_norm: 8.9897  loss: 2.0285  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.0285
07/05 16:50:50 - mmengine - INFO - Epoch(train)  [7][120/470]  lr: 1.0000e-02  eta: 0:46:45  time: 1.1778  data_time: 0.7879  memory: 1561  grad_norm: 7.5192  loss: 2.1996  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.1996
07/05 16:51:17 - mmengine - INFO - Epoch(train)  [7][140/470]  lr: 1.0000e-02  eta: 0:46:11  time: 1.3535  data_time: 0.9618  memory: 1561  grad_norm: 9.7817  loss: 2.6005  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.6005
07/05 16:51:42 - mmengine - INFO - Epoch(train)  [7][160/470]  lr: 1.0000e-02  eta: 0:45:35  time: 1.2812  data_time: 0.8881  memory: 1561  grad_norm: 8.2581  loss: 2.1615  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.1615
07/05 16:52:11 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:52:11 - mmengine - INFO - Epoch(train)  [7][180/470]  lr: 1.0000e-02  eta: 0:45:02  time: 1.4306  data_time: 1.0303  memory: 1561  grad_norm: 9.7578  loss: 2.1084  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.1084
07/05 16:52:35 - mmengine - INFO - Epoch(train)  [7][200/470]  lr: 1.0000e-02  eta: 0:44:26  time: 1.2037  data_time: 0.8151  memory: 1561  grad_norm: 7.9412  loss: 2.3629  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.3629
07/05 16:53:00 - mmengine - INFO - Epoch(train)  [7][220/470]  lr: 1.0000e-02  eta: 0:43:50  time: 1.2327  data_time: 0.8427  memory: 1561  grad_norm: 7.8870  loss: 2.2989  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.2989
07/05 16:53:26 - mmengine - INFO - Epoch(train)  [7][240/470]  lr: 1.0000e-02  eta: 0:43:15  time: 1.3205  data_time: 0.9265  memory: 1561  grad_norm: 7.6801  loss: 2.0449  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.0449
07/05 16:53:50 - mmengine - INFO - Epoch(train)  [7][260/470]  lr: 1.0000e-02  eta: 0:42:39  time: 1.1790  data_time: 0.7910  memory: 1561  grad_norm: 7.1400  loss: 2.4301  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.4301
07/05 16:54:16 - mmengine - INFO - Epoch(train)  [7][280/470]  lr: 1.0000e-02  eta: 0:42:05  time: 1.3218  data_time: 0.9305  memory: 1561  grad_norm: 8.1782  loss: 1.9548  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.9548
07/05 16:54:43 - mmengine - INFO - Epoch(train)  [7][300/470]  lr: 1.0000e-02  eta: 0:41:31  time: 1.3228  data_time: 0.9296  memory: 1561  grad_norm: 8.0501  loss: 2.0199  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.0199
07/05 16:55:07 - mmengine - INFO - Epoch(train)  [7][320/470]  lr: 1.0000e-02  eta: 0:40:56  time: 1.2116  data_time: 0.8210  memory: 1561  grad_norm: 8.2108  loss: 1.9236  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 1.9236
07/05 16:55:34 - mmengine - INFO - Epoch(train)  [7][340/470]  lr: 1.0000e-02  eta: 0:40:22  time: 1.3457  data_time: 0.9492  memory: 1561  grad_norm: 8.5835  loss: 2.1329  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.1329
07/05 16:55:59 - mmengine - INFO - Epoch(train)  [7][360/470]  lr: 1.0000e-02  eta: 0:39:48  time: 1.2732  data_time: 0.8756  memory: 1561  grad_norm: 8.2660  loss: 2.1014  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.1014
07/05 16:56:26 - mmengine - INFO - Epoch(train)  [7][380/470]  lr: 1.0000e-02  eta: 0:39:14  time: 1.3658  data_time: 0.9725  memory: 1561  grad_norm: 7.4382  loss: 1.7966  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 1.7966
07/05 16:56:54 - mmengine - INFO - Epoch(train)  [7][400/470]  lr: 1.0000e-02  eta: 0:38:41  time: 1.3581  data_time: 0.9655  memory: 1561  grad_norm: 8.5783  loss: 2.1781  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.1781
07/05 16:57:21 - mmengine - INFO - Epoch(train)  [7][420/470]  lr: 1.0000e-02  eta: 0:38:08  time: 1.3471  data_time: 0.9582  memory: 1561  grad_norm: 7.8264  loss: 1.9638  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.9638
07/05 16:57:50 - mmengine - INFO - Epoch(train)  [7][440/470]  lr: 1.0000e-02  eta: 0:37:35  time: 1.4531  data_time: 1.0668  memory: 1561  grad_norm: 7.9957  loss: 1.7658  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.7658
07/05 16:58:17 - mmengine - INFO - Epoch(train)  [7][460/470]  lr: 1.0000e-02  eta: 0:37:02  time: 1.3650  data_time: 0.9748  memory: 1561  grad_norm: 8.0481  loss: 1.7050  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.7050
07/05 16:58:33 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 16:58:33 - mmengine - INFO - Epoch(train)  [7][470/470]  lr: 1.0000e-02  eta: 0:36:47  time: 1.4755  data_time: 1.0846  memory: 1561  grad_norm: 7.3233  loss: 1.4799  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.4799
07/05 16:58:39 - mmengine - INFO - Epoch(val)  [7][ 20/194]    eta: 0:00:56  time: 0.3265  data_time: 0.2921  memory: 292
07/05 16:58:47 - mmengine - INFO - Epoch(val)  [7][ 40/194]    eta: 0:00:56  time: 0.4076  data_time: 0.3753  memory: 292
07/05 16:58:59 - mmengine - INFO - Epoch(val)  [7][ 60/194]    eta: 0:00:58  time: 0.5850  data_time: 0.5377  memory: 292
07/05 16:59:10 - mmengine - INFO - Epoch(val)  [7][ 80/194]    eta: 0:00:53  time: 0.5408  data_time: 0.5046  memory: 292
07/05 16:59:18 - mmengine - INFO - Epoch(val)  [7][100/194]    eta: 0:00:42  time: 0.3989  data_time: 0.3611  memory: 292
07/05 16:59:26 - mmengine - INFO - Epoch(val)  [7][120/194]    eta: 0:00:32  time: 0.3925  data_time: 0.3475  memory: 292
07/05 16:59:39 - mmengine - INFO - Epoch(val)  [7][140/194]    eta: 0:00:25  time: 0.6636  data_time: 0.6196  memory: 292
07/05 16:59:49 - mmengine - INFO - Epoch(val)  [7][160/194]    eta: 0:00:16  time: 0.5006  data_time: 0.4576  memory: 292
07/05 17:00:05 - mmengine - INFO - Epoch(val)  [7][180/194]    eta: 0:00:07  time: 0.7970  data_time: 0.7461  memory: 292
07/05 17:00:16 - mmengine - INFO - Epoch(val) [7][194/194]    acc/top1: 0.2423  acc/top5: 0.6340  acc/mean1: 0.2000  data_time: 0.4915  time: 0.5322
07/05 17:00:40 - mmengine - INFO - Epoch(train)  [8][ 20/470]  lr: 1.0000e-02  eta: 0:36:12  time: 1.2135  data_time: 0.8200  memory: 1561  grad_norm: 8.5287  loss: 2.0468  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.0468
07/05 17:01:07 - mmengine - INFO - Epoch(train)  [8][ 40/470]  lr: 1.0000e-02  eta: 0:35:39  time: 1.3218  data_time: 0.9318  memory: 1561  grad_norm: 8.0674  loss: 2.1277  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.1277
07/05 17:01:35 - mmengine - INFO - Epoch(train)  [8][ 60/470]  lr: 1.0000e-02  eta: 0:35:07  time: 1.4028  data_time: 1.0111  memory: 1561  grad_norm: 7.7233  loss: 1.7141  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.7141
07/05 17:02:01 - mmengine - INFO - Epoch(train)  [8][ 80/470]  lr: 1.0000e-02  eta: 0:34:34  time: 1.3178  data_time: 0.9296  memory: 1561  grad_norm: 8.0983  loss: 2.1432  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.1432
07/05 17:02:25 - mmengine - INFO - Epoch(train)  [8][100/470]  lr: 1.0000e-02  eta: 0:33:59  time: 1.1802  data_time: 0.7890  memory: 1561  grad_norm: 7.5486  loss: 2.3237  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3237
07/05 17:02:55 - mmengine - INFO - Epoch(train)  [8][120/470]  lr: 1.0000e-02  eta: 0:33:28  time: 1.4963  data_time: 1.0998  memory: 1561  grad_norm: 8.0200  loss: 1.7529  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.7529
07/05 17:03:21 - mmengine - INFO - Epoch(train)  [8][140/470]  lr: 1.0000e-02  eta: 0:32:55  time: 1.3164  data_time: 0.9256  memory: 1561  grad_norm: 7.0571  loss: 1.7014  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.7014
07/05 17:03:46 - mmengine - INFO - Epoch(train)  [8][160/470]  lr: 1.0000e-02  eta: 0:32:22  time: 1.2677  data_time: 0.8762  memory: 1561  grad_norm: 8.0980  loss: 2.3097  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.3097
07/05 17:04:14 - mmengine - INFO - Epoch(train)  [8][180/470]  lr: 1.0000e-02  eta: 0:31:49  time: 1.3736  data_time: 0.9819  memory: 1561  grad_norm: 5.6393  loss: 1.5657  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 1.5657
07/05 17:04:41 - mmengine - INFO - Epoch(train)  [8][200/470]  lr: 1.0000e-02  eta: 0:31:17  time: 1.3889  data_time: 1.0004  memory: 1561  grad_norm: 7.3349  loss: 1.7309  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.7309
07/05 17:05:09 - mmengine - INFO - Epoch(train)  [8][220/470]  lr: 1.0000e-02  eta: 0:30:45  time: 1.3816  data_time: 0.9939  memory: 1561  grad_norm: 8.1792  loss: 1.8804  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.8804
07/05 17:05:34 - mmengine - INFO - Epoch(train)  [8][240/470]  lr: 1.0000e-02  eta: 0:30:12  time: 1.2370  data_time: 0.8480  memory: 1561  grad_norm: 7.8550  loss: 2.2038  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.2038
07/05 17:06:00 - mmengine - INFO - Epoch(train)  [8][260/470]  lr: 1.0000e-02  eta: 0:29:39  time: 1.3256  data_time: 0.9376  memory: 1561  grad_norm: 7.9857  loss: 2.0245  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 2.0245
07/05 17:06:25 - mmengine - INFO - Epoch(train)  [8][280/470]  lr: 1.0000e-02  eta: 0:29:07  time: 1.2426  data_time: 0.8559  memory: 1561  grad_norm: 7.1898  loss: 2.1820  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.1820
07/05 17:06:53 - mmengine - INFO - Epoch(train)  [8][300/470]  lr: 1.0000e-02  eta: 0:28:35  time: 1.3684  data_time: 0.9768  memory: 1561  grad_norm: 8.0298  loss: 2.0072  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.0072
07/05 17:07:20 - mmengine - INFO - Epoch(train)  [8][320/470]  lr: 1.0000e-02  eta: 0:28:02  time: 1.3498  data_time: 0.9606  memory: 1561  grad_norm: 6.9077  loss: 1.8897  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.8897
07/05 17:07:48 - mmengine - INFO - Epoch(train)  [8][340/470]  lr: 1.0000e-02  eta: 0:27:31  time: 1.4135  data_time: 1.0230  memory: 1561  grad_norm: 7.7176  loss: 1.8220  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.8220
07/05 17:08:15 - mmengine - INFO - Epoch(train)  [8][360/470]  lr: 1.0000e-02  eta: 0:26:59  time: 1.3760  data_time: 0.9868  memory: 1561  grad_norm: 7.2731  loss: 1.5660  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.5660
07/05 17:08:43 - mmengine - INFO - Epoch(train)  [8][380/470]  lr: 1.0000e-02  eta: 0:26:27  time: 1.3659  data_time: 0.9762  memory: 1561  grad_norm: 8.0641  loss: 2.0504  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 2.0504
07/05 17:09:07 - mmengine - INFO - Epoch(train)  [8][400/470]  lr: 1.0000e-02  eta: 0:25:55  time: 1.2238  data_time: 0.8322  memory: 1561  grad_norm: 8.0999  loss: 2.3496  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.3496
07/05 17:09:35 - mmengine - INFO - Epoch(train)  [8][420/470]  lr: 1.0000e-02  eta: 0:25:23  time: 1.3752  data_time: 0.9884  memory: 1561  grad_norm: 6.8085  loss: 1.7754  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.7754
07/05 17:10:00 - mmengine - INFO - Epoch(train)  [8][440/470]  lr: 1.0000e-02  eta: 0:24:51  time: 1.2459  data_time: 0.8516  memory: 1561  grad_norm: 7.3412  loss: 2.0836  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.0836
07/05 17:10:24 - mmengine - INFO - Epoch(train)  [8][460/470]  lr: 1.0000e-02  eta: 0:24:18  time: 1.2462  data_time: 0.8546  memory: 1561  grad_norm: 7.4732  loss: 2.0718  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.0718
07/05 17:10:36 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 17:10:36 - mmengine - INFO - Epoch(train)  [8][470/470]  lr: 1.0000e-02  eta: 0:24:02  time: 1.2020  data_time: 0.8117  memory: 1561  grad_norm: 6.8206  loss: 2.0442  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.0442
07/05 17:10:43 - mmengine - INFO - Epoch(val)  [8][ 20/194]    eta: 0:00:57  time: 0.3298  data_time: 0.2947  memory: 292
07/05 17:10:51 - mmengine - INFO - Epoch(val)  [8][ 40/194]    eta: 0:00:57  time: 0.4173  data_time: 0.3808  memory: 292
07/05 17:11:03 - mmengine - INFO - Epoch(val)  [8][ 60/194]    eta: 0:00:59  time: 0.5889  data_time: 0.5438  memory: 292
07/05 17:11:14 - mmengine - INFO - Epoch(val)  [8][ 80/194]    eta: 0:00:54  time: 0.5609  data_time: 0.5199  memory: 292
07/05 17:11:22 - mmengine - INFO - Epoch(val)  [8][100/194]    eta: 0:00:43  time: 0.4131  data_time: 0.3655  memory: 292
07/05 17:11:30 - mmengine - INFO - Epoch(val)  [8][120/194]    eta: 0:00:33  time: 0.3878  data_time: 0.3525  memory: 292
07/05 17:11:44 - mmengine - INFO - Epoch(val)  [8][140/194]    eta: 0:00:26  time: 0.6806  data_time: 0.6386  memory: 292
07/05 17:11:54 - mmengine - INFO - Epoch(val)  [8][160/194]    eta: 0:00:16  time: 0.4999  data_time: 0.4643  memory: 292
07/05 17:12:10 - mmengine - INFO - Epoch(val)  [8][180/194]    eta: 0:00:07  time: 0.8060  data_time: 0.7607  memory: 292
07/05 17:12:42 - mmengine - INFO - Epoch(val) [8][194/194]    acc/top1: 0.3144  acc/top5: 0.6959  acc/mean1: 0.2885  data_time: 0.6060  time: 0.6465
07/05 17:12:42 - mmengine - INFO - The previous best checkpoint C:\Users\nesto\Documents\ai-projects\mmaction2\work_dirs\tsn_hurto\best_acc_top1_epoch_6.pth is removed
07/05 17:12:42 - mmengine - INFO - The best checkpoint with 0.3144 acc/top1 at 8 epoch is saved to best_acc_top1_epoch_8.pth.
07/05 17:13:08 - mmengine - INFO - Epoch(train)  [9][ 20/470]  lr: 1.0000e-02  eta: 0:23:30  time: 1.2456  data_time: 0.8539  memory: 1561  grad_norm: 7.1898  loss: 1.7781  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.7781
07/05 17:13:33 - mmengine - INFO - Epoch(train)  [9][ 40/470]  lr: 1.0000e-02  eta: 0:22:58  time: 1.2845  data_time: 0.8953  memory: 1561  grad_norm: 7.6363  loss: 1.9034  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 1.9034
07/05 17:14:02 - mmengine - INFO - Epoch(train)  [9][ 60/470]  lr: 1.0000e-02  eta: 0:22:27  time: 1.4134  data_time: 1.0228  memory: 1561  grad_norm: 7.4032  loss: 1.5872  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.5872
07/05 17:14:31 - mmengine - INFO - Epoch(train)  [9][ 80/470]  lr: 1.0000e-02  eta: 0:21:56  time: 1.4670  data_time: 1.0697  memory: 1561  grad_norm: 6.8258  loss: 1.5186  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.5186
07/05 17:14:56 - mmengine - INFO - Epoch(train)  [9][100/470]  lr: 1.0000e-02  eta: 0:21:24  time: 1.2311  data_time: 0.8376  memory: 1561  grad_norm: 7.0260  loss: 1.8089  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 1.8089
07/05 17:15:21 - mmengine - INFO - Epoch(train)  [9][120/470]  lr: 1.0000e-02  eta: 0:20:53  time: 1.2958  data_time: 0.9049  memory: 1561  grad_norm: 6.4872  loss: 2.0365  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.0365
07/05 17:15:49 - mmengine - INFO - Epoch(train)  [9][140/470]  lr: 1.0000e-02  eta: 0:20:21  time: 1.3831  data_time: 0.9970  memory: 1561  grad_norm: 6.5081  loss: 1.5864  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.5864
07/05 17:16:18 - mmengine - INFO - Epoch(train)  [9][160/470]  lr: 1.0000e-02  eta: 0:19:51  time: 1.4306  data_time: 1.0362  memory: 1561  grad_norm: 6.3267  loss: 1.4429  top1_acc: 1.0000  top5_acc: 1.0000  loss_cls: 1.4429
07/05 17:16:47 - mmengine - INFO - Epoch(train)  [9][180/470]  lr: 1.0000e-02  eta: 0:19:20  time: 1.4399  data_time: 1.0479  memory: 1561  grad_norm: 7.9109  loss: 1.9064  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 1.9064
07/05 17:17:12 - mmengine - INFO - Epoch(train)  [9][200/470]  lr: 1.0000e-02  eta: 0:18:48  time: 1.2810  data_time: 0.8915  memory: 1561  grad_norm: 6.9229  loss: 1.8163  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.8163
07/05 17:17:39 - mmengine - INFO - Epoch(train)  [9][220/470]  lr: 1.0000e-02  eta: 0:18:17  time: 1.3431  data_time: 0.9520  memory: 1561  grad_norm: 8.7531  loss: 2.1085  top1_acc: 0.0000  top5_acc: 0.0000  loss_cls: 2.1085
07/05 17:18:04 - mmengine - INFO - Exp name: tsn_hurto_20260705_151426
07/05 17:18:04 - mmengine - INFO - Epoch(train)  [9][240/470]  lr: 1.0000e-02  eta: 0:17:46  time: 1.2724  data_time: 0.8861  memory: 1561  grad_norm: 8.1120  loss: 2.2961  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 2.2961
07/05 17:18:31 - mmengine - INFO - Epoch(train)  [9][260/470]  lr: 1.0000e-02  eta: 0:17:14  time: 1.3097  data_time: 0.9207  memory: 1561  grad_norm: 7.3764  loss: 2.0269  top1_acc: 0.0000  top5_acc: 0.5000  loss_cls: 2.0269
07/05 17:18:57 - mmengine - INFO - Epoch(train)  [9][280/470]  lr: 1.0000e-02  eta: 0:16:43  time: 1.3003  data_time: 0.9066  memory: 1561  grad_norm: 6.6467  loss: 2.1466  top1_acc: 0.5000  top5_acc: 1.0000  loss_cls: 2.1466
07/05 17:19:24 - mmengine - INFO - Epoch(train)  [9][300/470]  lr: 1.0000e-02  eta: 0:16:12  time: 1.3477  data_time: 0.9611  memory: 1561  grad_norm: 6.8706  loss: 1.9502  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.9502
07/05 17:19:50 - mmengine - INFO - Epoch(train)  [9][320/470]  lr: 1.0000e-02  eta: 0:15:41  time: 1.3069  data_time: 0.9174  memory: 1561  grad_norm: 7.2738  loss: 1.7890  top1_acc: 0.5000  top5_acc: 0.5000  loss_cls: 1.7890
07/05 17:20:16 - mmengine - INFO - Epoch(train)  [9][340/470]  lr: 1.0000e-02  eta: 0:15:10  time: 1.3158  data_time: 0.9271  memory: 1561  grad_norm: 8.6069  loss: 1.9617  top1_acc: 0.0000  top5_acc: 1.0000  loss_cls: 1.9617
Traceback (most recent call last):
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\tools\train.py", line 143, in <module>
    main()
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\tools\train.py", line 139, in main
    runner.train()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\runner.py", line 1777, in train
    model = self.train_loop.run()  # type: ignore
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\loops.py", line 98, in run
    self.run_epoch()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\loops.py", line 114, in run_epoch
    for idx, data_batch in enumerate(self.dataloader):
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\dataloader.py", line 701, in __next__
    data = self._next_data()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\dataloader.py", line 757, in _next_data
    data = self._dataset_fetcher.fetch(index)  # may raise StopIteration
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\_utils\fetch.py", line 52, in fetch
    data = [self.dataset[idx] for idx in possibly_batched_index]
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\_utils\fetch.py", line 52, in <listcomp>
    data = [self.dataset[idx] for idx in possibly_batched_index]
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dataset\base_dataset.py", line 410, in __getitem__
    data = self.prepare_data(idx)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dataset\base_dataset.py", line 793, in prepare_data
    return self.pipeline(data_info)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dataset\base_dataset.py", line 60, in __call__
    data = t(data)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\transforms\base.py", line 12, in __call__
    return self.transform(results)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\datasets\transforms\loading.py", line 1229, in transform
    imgs = self._decord_load_frames(container, frame_inds)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\datasets\transforms\loading.py", line 1202, in _decord_load_frames
    imgs = container.get_batch(frame_inds).asnumpy()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\decord\video_reader.py", line 175, in get_batch
    arr = _CAPI_VideoReaderGetBatch(self._handle, indices)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\decord\_ffi\_ctypes\function.py", line 173, in __call__
    check_call(_LIB.DECORDFuncCall(
KeyboardInterrupt

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>pñ0
"pñ0" no se reconoce como un comando interno o externo,
programa o archivo por lotes ejecutable.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>
(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>
(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python tools/test.py configs/recognition/tsn/tsn_hurto.py work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
07/05 18:06:19 - mmengine - INFO -
------------------------------------------------------------
System environment:
    sys.platform: win32
    Python: 3.10.20 | packaged by Anaconda, Inc. | (main, Jun 11 2026, 15:13:20) [MSC v.1942 64 bit (AMD64)]
    CUDA available: True
    MUSA available: False
    numpy_random_seed: 1666131197
    GPU 0: NVIDIA GeForce RTX 4050 Laptop GPU
    CUDA_HOME: None
    MSVC: Compilador de optimización de C/C++ de Microsoft (R) versión 19.44.35223 para x64
    GCC: n/a
    PyTorch: 2.5.1+cu121
    PyTorch compiling details: PyTorch built with:
  - C++ Version: 201703
  - MSVC 192930154
  - Intel(R) oneAPI Math Kernel Library Version 2024.2.2-Product Build 20240823 for Intel(R) 64 architecture applications
  - Intel(R) MKL-DNN v3.5.3 (Git Hash 66f0cb9eb66affd2da3bf5f8d897376f04aae6af)
  - OpenMP 2019
  - LAPACK is enabled (usually provided by MKL)
  - CPU capability usage: AVX2
  - CUDA Runtime 12.1
  - NVCC architecture flags: -gencode;arch=compute_50,code=sm_50;-gencode;arch=compute_60,code=sm_60;-gencode;arch=compute_61,code=sm_61;-gencode;arch=compute_70,code=sm_70;-gencode;arch=compute_75,code=sm_75;-gencode;arch=compute_80,code=sm_80;-gencode;arch=compute_86,code=sm_86;-gencode;arch=compute_90,code=sm_90
  - CuDNN 90.1  (built against CUDA 12.4)
  - Magma 2.5.4
  - Build settings: BLAS_INFO=mkl, BUILD_TYPE=Release, CUDA_VERSION=12.1, CUDNN_VERSION=9.1.0, CXX_COMPILER=C:/actions-runner/_work/pytorch/pytorch/builder/windows/tmp_bin/sccache-cl.exe, CXX_FLAGS=/DWIN32 /D_WINDOWS /GR /EHsc /Zc:__cplusplus /bigobj /FS /utf-8 -DUSE_PTHREADPOOL -DNDEBUG -DUSE_KINETO -DLIBKINETO_NOCUPTI -DLIBKINETO_NOROCTRACER -DLIBKINETO_NOXPUPTI=ON -DUSE_FBGEMM -DUSE_XNNPACK -DSYMBOLICATE_MOBILE_DEBUG_HANDLE /wd4624 /wd4068 /wd4067 /wd4267 /wd4661 /wd4717 /wd4244 /wd4804 /wd4273, LAPACK_INFO=mkl, PERF_WITH_AVX=1, PERF_WITH_AVX2=1, TORCH_VERSION=2.5.1, USE_CUDA=ON, USE_CUDNN=ON, USE_CUSPARSELT=OFF, USE_EXCEPTION_PTR=1, USE_GFLAGS=OFF, USE_GLOG=OFF, USE_GLOO=ON, USE_MKL=ON, USE_MKLDNN=ON, USE_MPI=OFF, USE_NCCL=OFF, USE_NNPACK=OFF, USE_OPENMP=ON, USE_ROCM=OFF, USE_ROCM_KERNEL_ASSERT=OFF,

    TorchVision: 0.20.1+cu121
    OpenCV: 5.0.0
    MMEngine: 0.10.7

Runtime environment:
    cudnn_benchmark: False
    mp_cfg: {'mp_start_method': 'fork', 'opencv_num_threads': 0}
    dist_cfg: {'backend': 'nccl'}
    seed: 1666131197
    Distributed launcher: none
    Distributed training: False
    GPU number: 1
------------------------------------------------------------

07/05 18:06:19 - mmengine - INFO - Config:
ann_file_test = 'D:/archive/dataset-video-split/test.txt'
ann_file_train = 'D:/archive/dataset-video-split/train.txt'
ann_file_val = 'D:/archive/dataset-video-split/valid.txt'
auto_scale_lr = dict(base_batch_size=256, enable=False)
data_root = 'D:/archive/dataset-video-split/train'
data_root_test = 'D:/archive/dataset-video-split/test'
data_root_val = 'D:/archive/dataset-video-split/valid'
dataset_type = 'VideoDataset'
default_hooks = dict(
    checkpoint=dict(
        interval=3, max_keep_ckpts=3, save_best='auto', type='CheckpointHook'),
    logger=dict(ignore_last=False, interval=20, type='LoggerHook'),
    param_scheduler=dict(type='ParamSchedulerHook'),
    runtime_info=dict(type='RuntimeInfoHook'),
    sampler_seed=dict(type='DistSamplerSeedHook'),
    sync_buffers=dict(type='SyncBuffersHook'),
    timer=dict(type='IterTimerHook'))
default_scope = 'mmaction'
env_cfg = dict(
    cudnn_benchmark=False,
    dist_cfg=dict(backend='nccl'),
    mp_cfg=dict(mp_start_method='fork', opencv_num_threads=0))
file_client_args = dict(io_backend='disk')
launcher = 'none'
load_from = 'work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth'
log_level = 'INFO'
log_processor = dict(by_epoch=True, type='LogProcessor', window_size=20)
model = dict(
    backbone=dict(
        depth=50,
        norm_eval=False,
        pretrained='https://download.pytorch.org/models/resnet50-11ad3fa6.pth',
        type='ResNet'),
    cls_head=dict(
        average_clips='prob',
        consensus=dict(dim=1, type='AvgConsensus'),
        dropout_ratio=0.4,
        in_channels=2048,
        init_std=0.01,
        num_classes=21,
        spatial_type='avg',
        type='TSNHead'),
    data_preprocessor=dict(
        format_shape='NCHW',
        mean=[
            123.675,
            116.28,
            103.53,
        ],
        std=[
            58.395,
            57.12,
            57.375,
        ],
        type='ActionDataPreprocessor'),
    test_cfg=None,
    train_cfg=None,
    type='Recognizer2D')
optim_wrapper = dict(
    clip_grad=dict(max_norm=40, norm_type=2),
    optimizer=dict(lr=0.01, momentum=0.9, type='SGD', weight_decay=0.0001))
param_scheduler = [
    dict(
        begin=0,
        by_epoch=True,
        end=100,
        gamma=0.1,
        milestones=[
            40,
            80,
        ],
        type='MultiStepLR'),
]
resume = False
test_cfg = dict(type='TestLoop')
test_dataloader = dict(
    batch_size=1,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/test.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/test'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1,
                frame_interval=1,
                num_clips=8,
                test_mode=True,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(crop_size=224, type='CenterCrop'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        test_mode=True,
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=False, type='DefaultSampler'))
test_evaluator = dict(type='AccMetric')
test_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(
        clip_len=1,
        frame_interval=1,
        num_clips=25,
        test_mode=True,
        type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(crop_size=224, type='TenCrop'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
train_cfg = dict(
    max_epochs=10, type='EpochBasedTrainLoop', val_begin=1, val_interval=1)
train_dataloader = dict(
    batch_size=2,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/train.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/train'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1, frame_interval=1, num_clips=8,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(
                input_size=224,
                max_wh_scale_gap=1,
                random_crop=False,
                scales=(
                    1,
                    0.875,
                    0.75,
                    0.66,
                ),
                type='MultiScaleCrop'),
            dict(keep_ratio=False, scale=(
                224,
                224,
            ), type='Resize'),
            dict(flip_ratio=0.5, type='Flip'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=True, type='DefaultSampler'))
train_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(clip_len=1, frame_interval=1, num_clips=8, type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(
        input_size=224,
        max_wh_scale_gap=1,
        random_crop=False,
        scales=(
            1,
            0.875,
            0.75,
            0.66,
        ),
        type='MultiScaleCrop'),
    dict(keep_ratio=False, scale=(
        224,
        224,
    ), type='Resize'),
    dict(flip_ratio=0.5, type='Flip'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
val_cfg = dict(type='ValLoop')
val_dataloader = dict(
    batch_size=1,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/valid.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/valid'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1,
                frame_interval=1,
                num_clips=8,
                test_mode=True,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(crop_size=224, type='CenterCrop'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        test_mode=True,
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=False, type='DefaultSampler'))
val_evaluator = dict(type='AccMetric')
val_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(
        clip_len=1,
        frame_interval=1,
        num_clips=8,
        test_mode=True,
        type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(crop_size=224, type='CenterCrop'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
vis_backends = [
    dict(type='LocalVisBackend'),
]
visualizer = dict(
    type='ActionVisualizer', vis_backends=[
        dict(type='LocalVisBackend'),
    ])
work_dir = './work_dirs\\tsn_hurto'

C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
07/05 18:06:21 - mmengine - INFO - Distributed training is not used, all SyncBatchNorm (SyncBN) layers in the model will be automatically reverted to BatchNormXd layers if they are used.
07/05 18:06:21 - mmengine - INFO - Hooks will be executed in the following order:
before_run:
(VERY_HIGH   ) RuntimeInfoHook
(BELOW_NORMAL) LoggerHook
 --------------------
before_train:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_train_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(NORMAL      ) DistSamplerSeedHook
 --------------------
before_train_iter:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
 --------------------
after_train_iter:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
after_train_epoch:
(NORMAL      ) IterTimerHook
(NORMAL      ) SyncBuffersHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_val:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
before_val_epoch:
(NORMAL      ) IterTimerHook
(NORMAL      ) SyncBuffersHook
 --------------------
before_val_iter:
(NORMAL      ) IterTimerHook
 --------------------
after_val_iter:
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_val_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
after_val:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
after_train:
(VERY_HIGH   ) RuntimeInfoHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_test:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
before_test_epoch:
(NORMAL      ) IterTimerHook
 --------------------
before_test_iter:
(NORMAL      ) IterTimerHook
 --------------------
after_test_iter:
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_test_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_test:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
after_run:
(BELOW_NORMAL) LoggerHook
 --------------------
Loads checkpoint by local backend from path: work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\checkpoint.py:347: FutureWarning: You are using `torch.load` with `weights_only=False` (the current default value), which uses the default pickle module implicitly. It is possible to construct malicious pickle data which will execute arbitrary code during unpickling (See https://github.com/pytorch/pytorch/blob/main/SECURITY.md#untrusted-models for more details). In a future release, the default value for `weights_only` will be flipped to `True`. This limits the functions that could be executed during unpickling. Arbitrary objects will no longer be allowed to be loaded via this mode unless they are explicitly allowlisted by the user via `torch.serialization.add_safe_globals`. We recommend you start setting `weights_only=True` for any use case where you don't have full control of the loaded file. Please open an issue on GitHub for any issues related to this experimental feature.
  checkpoint = torch.load(filename, map_location=map_location)
07/05 18:06:24 - mmengine - INFO - Load checkpoint from work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
07/05 18:06:24 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/05 18:06:24 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
07/05 18:06:44 - mmengine - INFO - Epoch(test) [ 20/200]    eta: 0:02:56  time: 0.9790  data_time: 0.9201  memory: 189
07/05 18:07:10 - mmengine - INFO - Epoch(test) [ 40/200]    eta: 0:03:03  time: 1.3144  data_time: 1.2690  memory: 189
07/05 18:07:25 - mmengine - INFO - Epoch(test) [ 60/200]    eta: 0:02:22  time: 0.7513  data_time: 0.7033  memory: 189
07/05 18:07:39 - mmengine - INFO - Epoch(test) [ 80/200]    eta: 0:01:52  time: 0.7020  data_time: 0.6624  memory: 189
Traceback (most recent call last):
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\tools\test.py", line 126, in <module>
    main()
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\tools\test.py", line 122, in main
    runner.test()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\runner.py", line 1823, in test
    metrics = self.test_loop.run()  # type: ignore
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\loops.py", line 462, in run
    for idx, data_batch in enumerate(self.dataloader):
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\dataloader.py", line 701, in __next__
    data = self._next_data()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\dataloader.py", line 757, in _next_data
    data = self._dataset_fetcher.fetch(index)  # may raise StopIteration
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\_utils\fetch.py", line 52, in fetch
    data = [self.dataset[idx] for idx in possibly_batched_index]
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\torch\utils\data\_utils\fetch.py", line 52, in <listcomp>
    data = [self.dataset[idx] for idx in possibly_batched_index]
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dataset\base_dataset.py", line 403, in __getitem__
    data = self.prepare_data(idx)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dataset\base_dataset.py", line 793, in prepare_data
    return self.pipeline(data_info)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dataset\base_dataset.py", line 60, in __call__
    data = t(data)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\transforms\base.py", line 12, in __call__
    return self.transform(results)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\datasets\transforms\loading.py", line 1229, in transform
    imgs = self._decord_load_frames(container, frame_inds)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\datasets\transforms\loading.py", line 1202, in _decord_load_frames
    imgs = container.get_batch(frame_inds).asnumpy()
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\decord\video_reader.py", line 175, in get_batch
    arr = _CAPI_VideoReaderGetBatch(self._handle, indices)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\decord\_ffi\_ctypes\function.py", line 173, in __call__
    check_call(_LIB.DECORDFuncCall(
KeyboardInterrupt

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python tools/test.py configs/recognition/tsn/tsn_hurto.py work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
07/06 07:12:43 - mmengine - INFO -
------------------------------------------------------------
System environment:
    sys.platform: win32
    Python: 3.10.20 | packaged by Anaconda, Inc. | (main, Jun 11 2026, 15:13:20) [MSC v.1942 64 bit (AMD64)]
    CUDA available: True
    MUSA available: False
    numpy_random_seed: 1803905211
    GPU 0: NVIDIA GeForce RTX 4050 Laptop GPU
    CUDA_HOME: None
    MSVC: Compilador de optimización de C/C++ de Microsoft (R) versión 19.44.35223 para x64
    GCC: n/a
    PyTorch: 2.5.1+cu121
    PyTorch compiling details: PyTorch built with:
  - C++ Version: 201703
  - MSVC 192930154
  - Intel(R) oneAPI Math Kernel Library Version 2024.2.2-Product Build 20240823 for Intel(R) 64 architecture applications
  - Intel(R) MKL-DNN v3.5.3 (Git Hash 66f0cb9eb66affd2da3bf5f8d897376f04aae6af)
  - OpenMP 2019
  - LAPACK is enabled (usually provided by MKL)
  - CPU capability usage: AVX2
  - CUDA Runtime 12.1
  - NVCC architecture flags: -gencode;arch=compute_50,code=sm_50;-gencode;arch=compute_60,code=sm_60;-gencode;arch=compute_61,code=sm_61;-gencode;arch=compute_70,code=sm_70;-gencode;arch=compute_75,code=sm_75;-gencode;arch=compute_80,code=sm_80;-gencode;arch=compute_86,code=sm_86;-gencode;arch=compute_90,code=sm_90
  - CuDNN 90.1  (built against CUDA 12.4)
  - Magma 2.5.4
  - Build settings: BLAS_INFO=mkl, BUILD_TYPE=Release, CUDA_VERSION=12.1, CUDNN_VERSION=9.1.0, CXX_COMPILER=C:/actions-runner/_work/pytorch/pytorch/builder/windows/tmp_bin/sccache-cl.exe, CXX_FLAGS=/DWIN32 /D_WINDOWS /GR /EHsc /Zc:__cplusplus /bigobj /FS /utf-8 -DUSE_PTHREADPOOL -DNDEBUG -DUSE_KINETO -DLIBKINETO_NOCUPTI -DLIBKINETO_NOROCTRACER -DLIBKINETO_NOXPUPTI=ON -DUSE_FBGEMM -DUSE_XNNPACK -DSYMBOLICATE_MOBILE_DEBUG_HANDLE /wd4624 /wd4068 /wd4067 /wd4267 /wd4661 /wd4717 /wd4244 /wd4804 /wd4273, LAPACK_INFO=mkl, PERF_WITH_AVX=1, PERF_WITH_AVX2=1, TORCH_VERSION=2.5.1, USE_CUDA=ON, USE_CUDNN=ON, USE_CUSPARSELT=OFF, USE_EXCEPTION_PTR=1, USE_GFLAGS=OFF, USE_GLOG=OFF, USE_GLOO=ON, USE_MKL=ON, USE_MKLDNN=ON, USE_MPI=OFF, USE_NCCL=OFF, USE_NNPACK=OFF, USE_OPENMP=ON, USE_ROCM=OFF, USE_ROCM_KERNEL_ASSERT=OFF,

    TorchVision: 0.20.1+cu121
    OpenCV: 5.0.0
    MMEngine: 0.10.7

Runtime environment:
    cudnn_benchmark: False
    mp_cfg: {'mp_start_method': 'fork', 'opencv_num_threads': 0}
    dist_cfg: {'backend': 'nccl'}
    seed: 1803905211
    Distributed launcher: none
    Distributed training: False
    GPU number: 1
------------------------------------------------------------

07/06 07:12:43 - mmengine - INFO - Config:
ann_file_test = 'D:/archive/dataset-video-split/test.txt'
ann_file_train = 'D:/archive/dataset-video-split/train.txt'
ann_file_val = 'D:/archive/dataset-video-split/valid.txt'
auto_scale_lr = dict(base_batch_size=256, enable=False)
data_root = 'D:/archive/dataset-video-split/train'
data_root_test = 'D:/archive/dataset-video-split/test'
data_root_val = 'D:/archive/dataset-video-split/valid'
dataset_type = 'VideoDataset'
default_hooks = dict(
    checkpoint=dict(
        interval=3, max_keep_ckpts=3, save_best='auto', type='CheckpointHook'),
    logger=dict(ignore_last=False, interval=20, type='LoggerHook'),
    param_scheduler=dict(type='ParamSchedulerHook'),
    runtime_info=dict(type='RuntimeInfoHook'),
    sampler_seed=dict(type='DistSamplerSeedHook'),
    sync_buffers=dict(type='SyncBuffersHook'),
    timer=dict(type='IterTimerHook'))
default_scope = 'mmaction'
env_cfg = dict(
    cudnn_benchmark=False,
    dist_cfg=dict(backend='nccl'),
    mp_cfg=dict(mp_start_method='fork', opencv_num_threads=0))
file_client_args = dict(io_backend='disk')
launcher = 'none'
load_from = 'work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth'
log_level = 'INFO'
log_processor = dict(by_epoch=True, type='LogProcessor', window_size=20)
model = dict(
    backbone=dict(
        depth=50,
        norm_eval=False,
        pretrained='https://download.pytorch.org/models/resnet50-11ad3fa6.pth',
        type='ResNet'),
    cls_head=dict(
        average_clips='prob',
        consensus=dict(dim=1, type='AvgConsensus'),
        dropout_ratio=0.4,
        in_channels=2048,
        init_std=0.01,
        num_classes=21,
        spatial_type='avg',
        type='TSNHead'),
    data_preprocessor=dict(
        format_shape='NCHW',
        mean=[
            123.675,
            116.28,
            103.53,
        ],
        std=[
            58.395,
            57.12,
            57.375,
        ],
        type='ActionDataPreprocessor'),
    test_cfg=None,
    train_cfg=None,
    type='Recognizer2D')
optim_wrapper = dict(
    clip_grad=dict(max_norm=40, norm_type=2),
    optimizer=dict(lr=0.01, momentum=0.9, type='SGD', weight_decay=0.0001))
param_scheduler = [
    dict(
        begin=0,
        by_epoch=True,
        end=100,
        gamma=0.1,
        milestones=[
            40,
            80,
        ],
        type='MultiStepLR'),
]
resume = False
test_cfg = dict(type='TestLoop')
test_dataloader = dict(
    batch_size=1,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/test.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/test'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1,
                frame_interval=1,
                num_clips=8,
                test_mode=True,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(crop_size=224, type='CenterCrop'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        test_mode=True,
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=False, type='DefaultSampler'))
test_evaluator = dict(type='AccMetric')
test_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(
        clip_len=1,
        frame_interval=1,
        num_clips=25,
        test_mode=True,
        type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(crop_size=224, type='TenCrop'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
train_cfg = dict(
    max_epochs=10, type='EpochBasedTrainLoop', val_begin=1, val_interval=1)
train_dataloader = dict(
    batch_size=2,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/train.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/train'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1, frame_interval=1, num_clips=8,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(
                input_size=224,
                max_wh_scale_gap=1,
                random_crop=False,
                scales=(
                    1,
                    0.875,
                    0.75,
                    0.66,
                ),
                type='MultiScaleCrop'),
            dict(keep_ratio=False, scale=(
                224,
                224,
            ), type='Resize'),
            dict(flip_ratio=0.5, type='Flip'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=True, type='DefaultSampler'))
train_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(clip_len=1, frame_interval=1, num_clips=8, type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(
        input_size=224,
        max_wh_scale_gap=1,
        random_crop=False,
        scales=(
            1,
            0.875,
            0.75,
            0.66,
        ),
        type='MultiScaleCrop'),
    dict(keep_ratio=False, scale=(
        224,
        224,
    ), type='Resize'),
    dict(flip_ratio=0.5, type='Flip'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
val_cfg = dict(type='ValLoop')
val_dataloader = dict(
    batch_size=1,
    dataset=dict(
        ann_file='D:/archive/dataset-video-split/valid.txt',
        data_prefix=dict(video='D:/archive/dataset-video-split/valid'),
        pipeline=[
            dict(io_backend='disk', type='DecordInit'),
            dict(
                clip_len=1,
                frame_interval=1,
                num_clips=8,
                test_mode=True,
                type='SampleFrames'),
            dict(type='DecordDecode'),
            dict(scale=(
                -1,
                256,
            ), type='Resize'),
            dict(crop_size=224, type='CenterCrop'),
            dict(input_format='NCHW', type='FormatShape'),
            dict(type='PackActionInputs'),
        ],
        test_mode=True,
        type='VideoDataset'),
    num_workers=0,
    persistent_workers=False,
    sampler=dict(shuffle=False, type='DefaultSampler'))
val_evaluator = dict(type='AccMetric')
val_pipeline = [
    dict(io_backend='disk', type='DecordInit'),
    dict(
        clip_len=1,
        frame_interval=1,
        num_clips=8,
        test_mode=True,
        type='SampleFrames'),
    dict(type='DecordDecode'),
    dict(scale=(
        -1,
        256,
    ), type='Resize'),
    dict(crop_size=224, type='CenterCrop'),
    dict(input_format='NCHW', type='FormatShape'),
    dict(type='PackActionInputs'),
]
vis_backends = [
    dict(type='LocalVisBackend'),
]
visualizer = dict(
    type='ActionVisualizer', vis_backends=[
        dict(type='LocalVisBackend'),
    ])
work_dir = './work_dirs\\tsn_hurto'

C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
07/06 07:12:44 - mmengine - INFO - Distributed training is not used, all SyncBatchNorm (SyncBN) layers in the model will be automatically reverted to BatchNormXd layers if they are used.
07/06 07:12:44 - mmengine - INFO - Hooks will be executed in the following order:
before_run:
(VERY_HIGH   ) RuntimeInfoHook
(BELOW_NORMAL) LoggerHook
 --------------------
before_train:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_train_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(NORMAL      ) DistSamplerSeedHook
 --------------------
before_train_iter:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
 --------------------
after_train_iter:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
after_train_epoch:
(NORMAL      ) IterTimerHook
(NORMAL      ) SyncBuffersHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_val:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
before_val_epoch:
(NORMAL      ) IterTimerHook
(NORMAL      ) SyncBuffersHook
 --------------------
before_val_iter:
(NORMAL      ) IterTimerHook
 --------------------
after_val_iter:
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_val_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
(LOW         ) ParamSchedulerHook
(VERY_LOW    ) CheckpointHook
 --------------------
after_val:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
after_train:
(VERY_HIGH   ) RuntimeInfoHook
(VERY_LOW    ) CheckpointHook
 --------------------
before_test:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
before_test_epoch:
(NORMAL      ) IterTimerHook
 --------------------
before_test_iter:
(NORMAL      ) IterTimerHook
 --------------------
after_test_iter:
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_test_epoch:
(VERY_HIGH   ) RuntimeInfoHook
(NORMAL      ) IterTimerHook
(BELOW_NORMAL) LoggerHook
 --------------------
after_test:
(VERY_HIGH   ) RuntimeInfoHook
 --------------------
after_run:
(BELOW_NORMAL) LoggerHook
 --------------------
Loads checkpoint by local backend from path: work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\checkpoint.py:347: FutureWarning: You are using `torch.load` with `weights_only=False` (the current default value), which uses the default pickle module implicitly. It is possible to construct malicious pickle data which will execute arbitrary code during unpickling (See https://github.com/pytorch/pytorch/blob/main/SECURITY.md#untrusted-models for more details). In a future release, the default value for `weights_only` will be flipped to `True`. This limits the functions that could be executed during unpickling. Arbitrary objects will no longer be allowed to be loaded via this mode unless they are explicitly allowlisted by the user via `torch.serialization.add_safe_globals`. We recommend you start setting `weights_only=True` for any use case where you don't have full control of the loaded file. Please open an issue on GitHub for any issues related to this experimental feature.
  checkpoint = torch.load(filename, map_location=map_location)
07/06 07:12:46 - mmengine - INFO - Load checkpoint from work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
07/06 07:12:46 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/06 07:12:46 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
07/06 07:13:18 - mmengine - INFO - Epoch(test) [ 20/200]    eta: 0:04:51  time: 1.6201  data_time: 1.5482  memory: 189
07/06 07:13:50 - mmengine - INFO - Epoch(test) [ 40/200]    eta: 0:04:14  time: 1.5568  data_time: 1.4632  memory: 189
07/06 07:14:09 - mmengine - INFO - Epoch(test) [ 60/200]    eta: 0:03:14  time: 0.9839  data_time: 0.9165  memory: 189
07/06 07:14:39 - mmengine - INFO - Epoch(test) [ 80/200]    eta: 0:02:48  time: 1.4655  data_time: 1.3833  memory: 189
07/06 07:15:01 - mmengine - INFO - Epoch(test) [100/200]    eta: 0:02:15  time: 1.1317  data_time: 1.0778  memory: 189
07/06 07:15:30 - mmengine - INFO - Epoch(test) [120/200]    eta: 0:01:49  time: 1.4420  data_time: 1.3689  memory: 189
07/06 07:16:09 - mmengine - INFO - Epoch(test) [140/200]    eta: 0:01:27  time: 1.9722  data_time: 1.8996  memory: 189
07/06 07:16:34 - mmengine - INFO - Epoch(test) [160/200]    eta: 0:00:57  time: 1.2338  data_time: 1.1576  memory: 189
07/06 07:16:56 - mmengine - INFO - Epoch(test) [180/200]    eta: 0:00:27  time: 1.0828  data_time: 1.0083  memory: 189
07/06 07:17:21 - mmengine - INFO - Epoch(test) [200/200]    eta: 0:00:00  time: 1.2566  data_time: 1.1795  memory: 189
07/06 07:17:21 - mmengine - INFO - Epoch(test) [200/200]    acc/top1: 0.4300  acc/top5: 0.7700  acc/mean1: 0.2632  data_time: 1.3003  time: 1.3745

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2> .circlecipython demo/demo_inferencer.py D:\archive\dataset-video-split\test\Robbery018_x264.mp4 --rec configs/recognition/tsn/tsn_hurto.py --print-result
".circlecipython" no se reconoce como un comando interno o externo,
programa o archivo por lotes ejecutable.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py D:\archive\dataset-video-split\test\Robbery018_x264.mp4 --rec configs/recognition/tsn/tsn_hurto.py --print-result
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\infer\infer.py:506: UserWarning: Checkpoint is not loaded, and the inference result is calculated by the randomly initialized model!
  warnings.warn('Checkpoint is not loaded, and the inference '
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\visualization\visualizer.py:196: UserWarning: Failed to add <class 'mmaction.visualization.video_backend.LocalVisBackend'>, please provide the `save_dir` argument.
  warnings.warn(f'Failed to add {vis_backend.__class__}, '
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━   07/06 07:19:21 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/06 07:19:21 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Traceback (most recent call last):
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\demo\demo_inferencer.py", line 70, in <module>
    main()
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\demo\demo_inferencer.py", line 66, in main
    mmaction2(**call_args)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\mmaction2_inferencer.py", line 161, in __call__
    preds = self.forward(ori_inputs, batch_size, **forward_kwargs)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\mmaction2_inferencer.py", line 93, in forward
    predictions = self.actionrecog_inferencer(
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\apis\inferencers\actionrecog_inferencer.py", line 126, in __call__
    return super().__call__(
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\infer\infer.py", line 221, in __call__
    for data in (track(inputs, description='Inference')
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\rich\progress.py", line 168, in track
    yield from progress.track(
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\rich\progress.py", line 1210, in track
    for value in sequence:
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\infer\infer.py", line 291, in preprocess
    yield from map(self.collate_fn, chunked_data)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\infer\infer.py", line 588, in _get_chunk_data
    processed_data = next(inputs_iter)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\dataset\base_dataset.py", line 60, in __call__
    data = t(data)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\transforms\base.py", line 12, in __call__
    return self.transform(results)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\datasets\transforms\loading.py", line 1155, in transform
    container = self._get_video_reader(results['filename'])
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\mmaction\datasets\transforms\loading.py", line 1142, in _get_video_reader
    file_obj = io.BytesIO(self.file_client.get(filename))
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\fileio\file_client.py", line 301, in get
    return self.client.get(filepath)
  File "C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\fileio\backends\local_backend.py", line 33, in get
    with open(filepath, 'rb') as f:
FileNotFoundError: [Errno 2] No such file or directory: 'D:\\archive\\dataset-video-split\\test\\Robbery018_x264.mp4'

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>.circlecipython demo/demo_inferencer.py D:\archive\dataset-video-split\test\Robbery020_x264.mp4 ^
¿Más? --rec configs/recognition/tsn/tsn_hurto.py ^
¿Más? --rec-weights work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth ^
¿Más? --device cpu ^
¿Más? --print-result
".circlecipython" no se reconoce como un comando interno o externo,
programa o archivo por lotes ejecutable.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py D:\archive\dataset-video-split\test\Robbery020_x264.mp4 ^
¿Más? --rec configs/recognition/tsn/tsn_hurto.py ^
¿Más? --rec-weights work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth ^
¿Más? --device cpu ^
¿Más? --print-result
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
Loads checkpoint by local backend from path: work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\checkpoint.py:347: FutureWarning: You are using `torch.load` with `weights_only=False` (the current default value), which uses the default pickle module implicitly. It is possible to construct malicious pickle data which will execute arbitrary code during unpickling (See https://github.com/pytorch/pytorch/blob/main/SECURITY.md#untrusted-models for more details). In a future release, the default value for `weights_only` will be flipped to `True`. This limits the functions that could be executed during unpickling. Arbitrary objects will no longer be allowed to be loaded via this mode unless they are explicitly allowlisted by the user via `torch.serialization.add_safe_globals`. We recommend you start setting `weights_only=True` for any use case where you don't have full control of the loaded file. Please open an issue on GitHub for any issues related to this experimental feature.
  checkpoint = torch.load(filename, map_location=map_location)
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\visualization\visualizer.py:196: UserWarning: Failed to add <class 'mmaction.visualization.video_backend.LocalVisBackend'>, please provide the `save_dir` argument.
  warnings.warn(f'Failed to add {vis_backend.__class__}, '
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━   07/06 07:22:06 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/06 07:22:06 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{'predictions': [{'rec_labels': [[20]], 'rec_scores': [[0.060470208525657654, 0.020945711061358452, 0.0004759439907502383, 0.02033214084804058, 0.0031251118052750826, 0.006341783329844475, 0.22687913477420807, 0.0003591404529288411, 7.192867997218855e-06, 0.0014518240932375193, 3.372237551957369e-05, 2.4648416001582518e-05, 0.023848112672567368, 0.03164644166827202, 0.00010197270603384823, 0.057067181915044785, 8.5594663687516e-05, 0.07964663952589035, 0.10773872584104538, 0.05984015390276909, 0.29957857728004456]]}]}

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py D:\archive\dataset-video-split\test\Robbery020_x264.mp4 ^

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py D:\archive\dataset-video-split\test\Robbery030_x264.mp4 --rec configs/recognition/tsn/tsn_hurto.py --rec-weights work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth --device cpu --print-result
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
Loads checkpoint by local backend from path: work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\checkpoint.py:347: FutureWarning: You are using `torch.load` with `weights_only=False` (the current default value), which uses the default pickle module implicitly. It is possible to construct malicious pickle data which will execute arbitrary code during unpickling (See https://github.com/pytorch/pytorch/blob/main/SECURITY.md#untrusted-models for more details). In a future release, the default value for `weights_only` will be flipped to `True`. This limits the functions that could be executed during unpickling. Arbitrary objects will no longer be allowed to be loaded via this mode unless they are explicitly allowlisted by the user via `torch.serialization.add_safe_globals`. We recommend you start setting `weights_only=True` for any use case where you don't have full control of the loaded file. Please open an issue on GitHub for any issues related to this experimental feature.
  checkpoint = torch.load(filename, map_location=map_location)
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\visualization\visualizer.py:196: UserWarning: Failed to add <class 'mmaction.visualization.video_backend.LocalVisBackend'>, please provide the `save_dir` argument.
  warnings.warn(f'Failed to add {vis_backend.__class__}, '
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━   07/06 07:24:11 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/06 07:24:11 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{'predictions': [{'rec_labels': [[20]], 'rec_scores': [[0.02456267550587654, 0.007114848122000694, 0.019085805863142014, 0.035807687789201736, 0.0493028461933136, 0.04479099437594414, 0.050674039870500565, 0.0002535213134251535, 0.00011477581574581563, 0.002522155875340104, 0.00014805124374106526, 0.00018206480308435857, 0.04769996553659439, 0.02240731008350849, 0.00016217604570556432, 0.017326466739177704, 0.0010241302661597729, 0.12527813017368317, 0.018912099301815033, 0.23176059126853943, 0.30086958408355713]]}]}

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python demo/demo_inferencer.py D:\archive\dataset-video-split\test\Robbery065_x264.mp4 --rec configs/recognition/tsn/tsn_hurto.py --rec-weights work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth --device cpu --print-result
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
  from torch.distributed.optim import \
Loads checkpoint by local backend from path: work_dirs/tsn_hurto/best_acc_top1_epoch_8.pth
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\runner\checkpoint.py:347: FutureWarning: You are using `torch.load` with `weights_only=False` (the current default value), which uses the default pickle module implicitly. It is possible to construct malicious pickle data which will execute arbitrary code during unpickling (See https://github.com/pytorch/pytorch/blob/main/SECURITY.md#untrusted-models for more details). In a future release, the default value for `weights_only` will be flipped to `True`. This limits the functions that could be executed during unpickling. Arbitrary objects will no longer be allowed to be loaded via this mode unless they are explicitly allowlisted by the user via `torch.serialization.add_safe_globals`. We recommend you start setting `weights_only=True` for any use case where you don't have full control of the loaded file. Please open an issue on GitHub for any issues related to this experimental feature.
  checkpoint = torch.load(filename, map_location=map_location)
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmcv\cnn\bricks\transformer.py:33: UserWarning: Fail to import ``MultiScaleDeformableAttention`` from ``mmcv.ops.multi_scale_deform_attn``, You should install ``mmcv`` rather than ``mmcv-lite`` if you need this module.
  warnings.warn('Fail to import ``MultiScaleDeformableAttention`` from '
C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\visualization\visualizer.py:196: UserWarning: Failed to add <class 'mmaction.visualization.video_backend.LocalVisBackend'>, please provide the `save_dir` argument.
  warnings.warn(f'Failed to add {vis_backend.__class__}, '
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━   07/06 07:24:27 - mmengine - WARNING - "FileClient" will be deprecated in future. Please use io functions in https://mmengine.readthedocs.io/en/latest/api/fileio.html#file-io
07/06 07:24:27 - mmengine - WARNING - "HardDiskBackend" is the alias of "LocalBackend" and the former will be deprecated in future.
Inference ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{'predictions': [{'rec_labels': [[6]], 'rec_scores': [[0.0156454648822546, 0.03599141538143158, 0.013446799479424953, 0.020192500203847885, 0.034272871911525726, 0.019586144015192986, 0.3782007396221161, 0.0006342870183289051, 0.000492169288918376, 0.0012344635324552655, 7.370983803411946e-05, 8.398466161452234e-05, 0.06015888601541519, 0.016734449192881584, 0.002120753051713109, 0.015557982958853245, 0.0020826924592256546, 0.05820309743285179, 0.013328169472515583, 0.05994009971618652, 0.25201937556266785]]}]}

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>notepad detectar_evento.py

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python detectar_evento.py D:\archive\dataset-video-split\test\Robbery030_x264.mp4

Analizando video...
Archivo: D:\archive\dataset-video-split\test\Robbery030_x264.mp4
Esto puede tardar unos segundos.

Traceback (most recent call last):
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\detectar_evento.py", line 136, in <module>
    main()
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\detectar_evento.py", line 131, in main
    prediction = run_inference(video, args.config, args.checkpoint, args.device)
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\detectar_evento.py", line 62, in run_inference
    return ast.literal_eval(match.group(0))
  File "C:\Users\agente\.conda\envs\mmaction2\lib\ast.py", line 64, in literal_eval
    node_or_string = parse(node_or_string.lstrip(" \t"), mode='eval')
  File "C:\Users\agente\.conda\envs\mmaction2\lib\ast.py", line 50, in parse
    return compile(source, filename, mode, flags,
  File "<unknown>", line 3
    C:\Users\agente\.conda\envs\mmaction2\lib\site-packages\mmengine\optim\optimizer\zero_optimizer.py:11: DeprecationWarning: `TorchScript` support for functional optimizers is deprecated and will be removed in a future PyTorch release. Consider using the `torch.compile` optimizer instead.
    ^
SyntaxError: invalid syntax

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python detectar_evento.py D:\archive\dataset-video-split\test\Robbery030_x264.mp4
  File "C:\Users\nesto\Documents\ai-projects\mmaction2\detectar_evento.py", line 47
    output = result.stdout
                          ^
IndentationError: unindent does not match any outer indentation level

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python detectar_evento.py D:\archive\dataset-video-split\test\Robbery030_x264.mp4

Analizando video...
Archivo: D:\archive\dataset-video-split\test\Robbery030_x264.mp4
Esto puede tardar unos segundos.

==============================
 Resultado del analisis
==============================
Evento mas probable: Abuse
Confianza del modelo: 30.1%

Top 5 posibilidades:
 - Abuse: 30.1%
 - Arrest: 23.2%
 - Assault: 12.5%
 - Normal: 5.1%
 - Robbery: 4.9%

Lectura para una persona no tecnica:
ALERTA DE RIESGO: no parece hurto directo, pero se ve comportamiento sospechoso o violento.

Resumen de riesgo:
 - Probabilidad agrupada de hurto: 9.3%
 - Probabilidad agrupada de riesgo/sospecha: 57.6%
 - Probabilidad de normalidad: 5.1%

Nota:
Este resultado no confirma un delito. Solo genera una alerta visual para revision humana.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python detectar_evento.py D:\archive\dataset-video-split\test\Robbery0312_x264.mp4
No encontre el video: D:\archive\dataset-video-split\test\Robbery0312_x264.mp4

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python detectar_evento.py D:\archive\dataset-video-split\test\Robbery020_x264.mp4

Analizando video...
Archivo: D:\archive\dataset-video-split\test\Robbery020_x264.mp4
Esto puede tardar unos segundos.

==============================
 Resultado del analisis
==============================
Evento mas probable: Abuse
Confianza del modelo: 30.0%

Top 5 posibilidades:
 - Abuse: 30.0%
 - Normal: 22.7%
 - Arson: 10.8%
 - Assault: 8.0%
 - Vandalism: 6.0%

Lectura para una persona no tecnica:
ALERTA DE RIESGO: no parece hurto directo, pero se ve comportamiento sospechoso o violento.

Resumen de riesgo:
 - Probabilidad agrupada de hurto: 8.2%
 - Probabilidad agrupada de riesgo/sospecha: 62.3%
 - Probabilidad de normalidad: 22.7%

Nota:
Este resultado no confirma un delito. Solo genera una alerta visual para revision humana.

(mmaction2) C:\Users\nesto\Documents\ai-projects\mmaction2>python detectar_evento.py D:\archive\dataset-video-split\test\Robbery031_x264.mp4

Analizando video...
Archivo: D:\archive\dataset-video-split\test\Robbery031_x264.mp4
Esto puede tardar unos segundos.

==============================
 Resultado del analisis
==============================
Evento mas probable: Stealing
Confianza del modelo: 22.1%

Top 5 posibilidades:
 - Stealing: 22.1%
 - Roadaccidents: 14.7%
 - Assault: 13.8%
 - Arson: 8.5%
 - Vandalism: 8.0%

Lectura para una persona no tecnica:
ALERTA MEDIA: el video podria estar relacionado con hurto, requiere revision humana.

Resumen de riesgo:
 - Probabilidad agrupada de hurto: 25.8%
 - Probabilidad agrupada de riesgo/sospecha: 49.6%
 - Probabilidad de normalidad: 3.0%

Nota:
Este resultado no confirma un delito. Solo genera una alerta visual para revision humana
.
```
