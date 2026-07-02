/* Chat conversacional VigIA — panel flotante multi-agente */
(function ($) {
    'use strict';

    const MAX_HISTORY = 10;
    let history = [];
    let isOpen  = false;

    function currentState() {
        return {
            municipio: $('#municipio').val() || '',
            tema:      $('.tema-btn.active').data('tema') || 'aire',
        };
    }

    function escHtml(str) {
        return $('<span>').text(str).html();
    }

    function appendMsg(content, role, agente) {
        const $msgs = $('#chat-messages');
        if (role === 'user') {
            $msgs.append($('<div class="msg-user">').text(content));
        } else {
            const $wrap = $('<div class="msg-wrap">');
            if (agente) {
                $wrap.append($('<div class="msg-agent">').text(agente));
            }
            $wrap.append($('<div class="msg-bot">').text(content));
            $msgs.append($wrap);
        }
        $msgs.scrollTop($msgs[0].scrollHeight);
    }

    function showThinking(label) {
        const $msgs = $('#chat-messages');
        const $t = $('<div class="msg-bot msg-thinking">').text(label || 'Analizando…');
        $msgs.append($t);
        $msgs.scrollTop($msgs[0].scrollHeight);
        return $t;
    }

    function sendMessage() {
        const $input = $('#chat-input');
        const msg    = $input.val().trim();
        if (!msg) return;

        $input.val('');
        appendMsg(msg, 'user');
        history.push({ role: 'user', content: msg });
        if (history.length > MAX_HISTORY) history = history.slice(-MAX_HISTORY);

        const { municipio, tema } = currentState();
        const $send    = $('#chat-send').prop('disabled', true).text('…');
        const $thinking = showThinking('🤔 VigIA está analizando…');

        $.ajax({
            url:         'api/chat.php',
            method:      'POST',
            contentType: 'application/json',
            data:        JSON.stringify({ mensaje: msg, municipio, tema, contexto: history.slice(-6) }),
        }).done(function (res) {
            $thinking.remove();
            if (res.ok) {
                appendMsg(res.respuesta, 'bot', res.agente);
                history.push({ role: 'assistant', content: res.respuesta });
                if (history.length > MAX_HISTORY) history = history.slice(-MAX_HISTORY);
            } else {
                appendMsg('⚠️ ' + (res.error || 'Error al conectar con el asistente.'), 'bot', 'VigIA');
            }
        }).fail(function () {
            $thinking.remove();
            appendMsg('⚠️ Error de red. Verifica la conexión y la API key.', 'bot', 'VigIA');
        }).always(function () {
            $send.prop('disabled', false).text('Enviar');
        });
    }

    function openChat() {
        isOpen = true;
        $('#chat-panel').show();
        if (!$('#chat-messages').children().length) {
            appendMsg(
                '¡Hola! Soy VigIA 👋 Pregúntame sobre calidad del aire, seguridad, incendios o clima en tu municipio.',
                'bot',
                'Agente VigIA'
            );
        }
        setTimeout(function () { $('#chat-input').focus(); }, 100);
    }

    function closeChat() {
        isOpen = false;
        $('#chat-panel').hide();
    }

    $(function () {
        $('#btn-chat').on('click', function () {
            isOpen ? closeChat() : openChat();
        });

        $('#chat-close').on('click', closeChat);

        $('#chat-send').on('click', sendMessage);

        $('#chat-input').on('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    });

})(jQuery);
