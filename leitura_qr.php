<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-leitor {
            max-width: 600px;
            margin: 50px auto;
        }
        .card-leitor {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header-leitor {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header-leitor h1 {
            margin: 0;
            font-size: 28px;
        }
        .header-leitor p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .area-scanner {
            padding: 40px 20px;
        }
        #qrcode-reader {
            width: 100%;
            min-height: 300px;
            border: 3px dashed #667eea;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            position: relative;
            overflow: hidden;
        }
        #qrcode-reader video {
            width: 100%;
            height: auto;
            max-height: 400px;
            border-radius: 10px;
        }
        .resultado-box {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 5px solid #28a745;
        }
        .codigo-qr {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }
        .historico-box {
            margin-top: 20px;
            max-height: 200px;
            overflow-y: auto;
        }
        .historico-item {
            padding: 10px;
            margin: 5px 0;
            background: white;
            border-radius: 8px;
            border-left: 3px solid #667eea;
            font-size: 14px;
            word-break: break-all;
        }
        .btn-acoes {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .status-indicator {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
        }
        .status-ativo {
            background: #28a745;
            box-shadow: 0 0 10px #28a745;
        }
        .status-inativo {
            background: #dc3545;
            box-shadow: 0 0 10px #dc3545;
        }
        #loading-camera {
            text-align: center;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="container-leitor">
        <div class="card-leitor">
            <div class="header-leitor">
                <h1><i class="bi bi-qr-code-scan"></i> Leitor QR Code</h1>
                <p>Escaneie um QR Code para ver o código</p>
            </div>
            
            <div class="area-scanner">
                <div class="text-center mb-3">
                    <button id="btnIniciar" class="btn btn-lg btn-primary">
                        <i class="bi bi-play-circle"></i> Iniciar Leitura
                    </button>
                    <button id="btnParar" class="btn btn-lg btn-danger" style="display:none;">
                        <i class="bi bi-stop-circle"></i> Parar Leitura
                    </button>
                    <button id="btnManual" class="btn btn-lg btn-info" style="display:none;">
                        <i class="bi bi-keyboard"></i> Digite Manualmente
                    </button>
                </div>
                
                <div id="qrcode-reader">
                    <div class="text-center text-muted" id="loading-camera">
                        <i class="bi bi-camera-video display-1 d-block mb-3"></i>
                        <p>Clique em "Iniciar Leitura" para começar</p>
                    </div>
                </div>
                
                <div id="resultado" style="display:none;">
                    <div class="resultado-box">
                        <h5 class="mb-3"><i class="bi bi-check-circle text-success"></i> Código Detectado</h5>
                        <div class="codigo-qr" id="codigo-qr">-</div>
                        <div class="mt-3">
                            <strong>Status:</strong> 
                            <span id="status-indicator">
                                <span class="status-indicator"></span>
                                Verificando...
                            </span>
                        </div>
                    </div>
                    
                    <div class="btn-acoes">
                        <button id="btnCopiar" class="btn btn-success flex-fill">
                            <i class="bi bi-clipboard"></i> Copiar Código
                        </button>
                        <button id="btnLimpar" class="btn btn-secondary flex-fill">
                            <i class="bi bi-x-circle"></i> Limpar
                        </button>
                    </div>
                </div>
                
                <div id="historico" class="historico-box"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/dist/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let html5QrCode = null;
        let historico = [];
        let scanning = false;
        
        const btnIniciar = document.getElementById('btnIniciar');
        const btnParar = document.getElementById('btnParar');
        const btnManual = document.getElementById('btnManual');
        const btnCopiar = document.getElementById('btnCopiar');
        const btnLimpar = document.getElementById('btnLimpar');
        const qrcodeReader = document.getElementById('qrcode-reader');
        const resultado = document.getElementById('resultado');
        const codigoQr = document.getElementById('codigo-qr');
        const statusIndicator = document.getElementById('status-indicator');
        const historicoDiv = document.getElementById('historico');
        
        btnIniciar.addEventListener('click', iniciarLeitura);
        btnParar.addEventListener('click', pararLeitura);
        btnManual.addEventListener('click', usarFallbackQR);
        btnCopiar.addEventListener('click', copiarCodigo);
        btnLimpar.addEventListener('click', limparResultado);
        
        async function iniciarLeitura() {
            try {
                btnIniciar.disabled = true;
                qrcodeReader.innerHTML = '<div class="text-center"><i class="bi bi-spinner-border fs-1 text-primary"></i><p class="mt-3">Ligando câmera...</p></div>';
                
                // Criar instância do html5-qrcode
                html5QrCode = new Html5Qrcode("qrcode-reader");
                
                // Verificar se existe câmera
                const devices = await Html5Qrcode.getCameras();
                
                if (!devices || devices.length === 0) {
                    qrcodeReader.innerHTML = '<div class="text-center text-danger"><i class="bi bi-camera-video-off display-1 d-block mb-3"></i><p>Câmera não encontrada. Use "Digite Manualmente".</p></div>';
                    btnIniciar.disabled = false;
                    btnManual.style.display = 'inline-block';
                    return;
                }
                
                const cameraId = devices[0].id;
                
                // Iniciar escaneamento
                await html5QrCode.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        // QR Code detectado
                        mostrarResultado(decodedText);
                        pararLeitura();
                    },
                    (errorMessage) => {
                        // Ignorar erros de decodificação
                    }
                );
                
                scanning = true;
                btnIniciar.style.display = 'none';
                btnParar.style.display = 'inline-block';
                
            } catch (error) {
                console.error('Erro ao iniciar câmera:', error);
                qrcodeReader.innerHTML = `
                    <div class="text-center text-danger">
                        <i class="bi bi-camera-video-off display-1 d-block mb-3"></i>
                        <p>Erro ao acessar câmera: ${error.message}</p>
                        <button class="btn btn-info mt-2" onclick="usarFallbackQR()">
                            <i class="bi bi-keyboard"></i> Digite Manualmente
                        </button>
                    </div>
                `;
                btnIniciar.disabled = false;
            }
        }
        
        function pararLeitura() {
            if (html5QrCode && scanning) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    scanning = false;
                    qrcodeReader.innerHTML = '<div class="text-center text-muted"><i class="bi bi-camera-video display-1 d-block mb-3"></i><p>Clique em "Iniciar Leitura" para começar</p></div>';
                    btnParar.style.display = 'none';
                    btnIniciar.style.display = 'inline-block';
                    btnIniciar.disabled = false;
                }).catch((err) => {
                    console.error('Erro ao parar câmera:', err);
                });
            }
        }
        
        function mostrarResultado(codigo) {
            codigoQr.textContent = codigo;
            resultado.style.display = 'block';
            
            // Adicionar ao histórico
            const agora = new Date().toLocaleString('pt-BR');
            historico.unshift({codigo, data: agora});
            atualizarHistorico();
            
            // Atualizar status
            atualizarStatus(codigo);
        }
        
        function copiarCodigo() {
            const texto = codigoQr.textContent;
            navigator.clipboard.writeText(texto).then(() => {
                alert('Código copiado: ' + texto);
            }).catch(err => {
                console.error('Erro ao copiar:', err);
            });
        }
        
        function limparResultado() {
            resultado.style.display = 'none';
            codigoQr.textContent = '-';
        }
        
        function atualizarStatus(codigo) {
            statusIndicator.innerHTML = '<span class="status-indicator"></span> Verificando...';
            
            fetch('api/verificar_qrcode.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({codigo})
            })
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    if (data.usado) {
                        statusIndicator.innerHTML = `
                            <span class="status-indicator status-inativo"></span>
                            Cartão em Uso${data.pessoa ? ' - ' + data.pessoa : ''}
                            ${data.cartao && data.cartao.saldo ? ' (Saldo: R$ ' + parseFloat(data.cartao.saldo).toFixed(2).replace('.', ',') + ')' : ''}
                        `;
                    } else {
                        statusIndicator.innerHTML = `
                            <span class="status-indicator status-ativo"></span>
                            Cartão Disponível
                        `;
                    }
                } else {
                    statusIndicator.innerHTML = `
                        <span class="status-indicator status-inativo"></span>
                        QR Code não encontrado no sistema
                    `;
                }
            })
            .catch(error => {
                console.error('Erro ao verificar status:', error);
                statusIndicator.innerHTML = `
                    <span class="status-indicator status-inativo"></span>
                    Erro ao verificar no sistema
                `;
            });
        }
        
        function atualizarHistorico() {
            if (historico.length > 0) {
                historicoDiv.innerHTML = '<h6 class="mb-3"><i class="bi bi-clock-history"></i> Histórico de Leituras</h6>';
                historico.slice(0, 5).forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'historico-item';
                    div.innerHTML = `<strong>${item.data}</strong><br><code>${item.codigo}</code>`;
                    historicoDiv.appendChild(div);
                });
            }
        }
        
        function usarFallbackQR() {
            // Parar leitura se estiver ativa
            pararLeitura();
            
            // Fallback usando input manual
            qrcodeReader.innerHTML = `
                <div class="text-center p-4">
                    <p class="mb-3">Digite ou cole o código do QR Code:</p>
                    <input type="text" id="codigo-manual" class="form-control form-control-lg text-center mb-3" 
                           placeholder="Digite o código aqui" style="font-size: 18px;">
                    <button class="btn btn-primary" onclick="processarManual()">
                        <i class="bi bi-check-circle"></i> Verificar Código
                    </button>
                    <button class="btn btn-secondary" onclick="location.reload()">
                        <i class="bi bi-arrow-repeat"></i> Voltar
                    </button>
                </div>
            `;
            btnManual.style.display = 'none';
        }
        
        function processarManual() {
            const codigo = document.getElementById('codigo-manual').value;
            if (codigo.trim()) {
                mostrarResultado(codigo.trim());
                qrcodeReader.innerHTML = '<div class="text-center text-muted"><i class="bi bi-camera-video display-1 d-block mb-3"></i><p>Clique em "Iniciar Leitura" para começar</p></div>';
            }
        }
        
        // Permitir acesso via teclado
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && document.getElementById('codigo-manual')) {
                e.preventDefault();
                processarManual();
            }
        });
        
        // Limpar ao sair da página
        window.addEventListener('beforeunload', () => {
            pararLeitura();
        });
    </script>
</body>
</html>
