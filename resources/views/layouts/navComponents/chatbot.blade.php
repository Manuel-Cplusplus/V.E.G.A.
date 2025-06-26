{{--
    V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
    Copyright (c) 2025 Manuel Carlucci

    Licensed under CC BY-NC-SA 4.0: http://creativecommons.org/licenses/by-nc-sa/4.0/
--}}

<!-- Chatbot Button -->
<button id="chatbot-trigger" class="flex justify-items-center items-center mr-4" type="button">
    <img src="{{ asset('media/icons/AI.png') }}"
         title="chatbot" alt="chatbot" id="ai-icon" class="w-8 h-8 hover:bg-[#bef6] hover:rounded-md p-1 transition-all duration-200">
</button>

<!-- Chatbot Modal -->
<div id="chatbot-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div id="chatbot-container" class="absolute right-0 top-0 h-full bg-slate-900 text-white shadow-lg flex flex-col" style="min-width: 320px; width: 384px; max-width: 66vw;">
        <!-- Resize Handle -->
        <div id="resize-handle" class="absolute left-0 top-0 bottom-0 w-1 cursor-ew-resize flex items-center bg-white">
            <div class="h-12 w-1 bg-blue-400 rounded opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
        </div>

        <!-- Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-700">
            <div class="flex items-center">
                <span class="font-medium">Powered By Gemini</span>
            </div>
            <button id="close-chatbot" class="text-gray-400 hover:text-white">
                X
            </button>
        </div>


        <!-- Disclaimer -->
        <div class="p-4 text-[12px] text-gray-300 border-b border-slate-700">
            <p>Qui puoi porre domande sui risultati delle ricerche che effettuerai.</p>
            <p class="mt-2">Tieni presente che, come ogni modello linguistico, potrei commettere errori quindi assicurati di verificare le informazioni importanti.</p>
            <p class="mt-2">La generazione e l'invio di immagini non è supportato.</p>

            <!-- Sezione memorizzazione con bottone - gestione resizing -->
            <p class="mt-2">Il sistema memorizzerà i messaggi inviati. Se chiudi il browser la chat verrà resettata automaticamente, oppure resetta manualmente.</p>

            <meta name="csrf-token" content="{{ csrf_token() }}">
            <button id="reset-history" class=" mt-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded transition-all duration-200 border-white border-2">
                Reset Memoria
            </button>


            <div class="mt-4 text-blue-300 text-xs italic flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                </svg>
                <span>Trascina dal bordo sinistro per ridimensionare la finestra</span>
            </div>
        </div>

        <!-- Chat container -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 text-[12px]">
            <!-- Sezione ove compariranno i mex -->
        </div>


        <!-- Input form -->
        <form id="formChat" action="{{ route('gemini.chat') }}" method="POST" class="p-4 border-t border-slate-700">
            @csrf
            <div class="flex items-center relative">
                <input id="chat-input" name="message" type="text" placeholder="Fai una domanda ..."
                       class="flex-1 p-3 pl-4 pr-12 rounded-lg bg-slate-800 border border-slate-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <button id="submit-button" type="submit" class="absolute right-2 bg-blue-600 hover:bg-blue-700 p-2 rounded-full transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    #chatbot-modal {
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
    }

    #chatbot-modal.visible {
        transform: translateX(0);
    }

    /* Resize handle hover effect */
    #resize-handle:hover .resize-indicator {
        opacity: 1;
    }

    .message-container {
        display: flex;
        margin-bottom: 16px;
        align-items: flex-start;
        opacity: 0;
        transform: translateY(10px);
        animation: fadeInUp 0.3s forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }

    .user-icon {
        background-color: #4B5563;
    }

    .bot-icon {
        background-color: #374151;
    }

    .message-content {
        border-radius: 0.75rem;
        padding: 0.85rem;
        max-width: calc(100% - 44px);
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .bot-message {
        background-color: #374151;
    }

    .user-message {
        background-color: #4B5563;
    }

    /* Disabled submit button */
    .submit-disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('chatbot-modal');
        const container = document.getElementById('chatbot-container');
        const trigger = document.getElementById('chatbot-trigger');
        const closeBtn = document.getElementById('close-chatbot');
        const chatForm = document.getElementById('formChat');
        const chatInput = document.getElementById('chat-input');
        const submitBtn = document.getElementById('submit-button');
        const chatMessages = document.getElementById('chat-messages');
        const resizeHandle = document.getElementById('resize-handle');
        const aiIconSrc = document.getElementById('ai-icon').src;
        const resetButton = document.getElementById('reset-history');

        let isWaitingForResponse = false;
        let isResizing = false;
        let startX, startWidth;

        /** Gestione Chat **/
        // Apri chatbot
        trigger.addEventListener('click', function() {
            // Estrai il contenuto HTML del main
            extractMainContent();

            // Mostra il modal
            modal.classList.add('visible');
            modal.classList.remove('hidden');

            setTimeout(() => {
                // Sposta il cursore sull'input di testo
                chatInput.focus();
            }, 300);
        });

        // Chiudi chatbot
        closeBtn.addEventListener('click', function() {
            modal.classList.remove('visible');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        });

        function toggleSubmitButton(enabled) {
            if (enabled) {
                submitBtn.classList.remove('submit-disabled');
                submitBtn.disabled = false;
                chatInput.disabled = false;
            } else {
                submitBtn.classList.add('submit-disabled');
                submitBtn.disabled = true;
                chatInput.disabled = true;
            }
        }

        // Loader di Attesa risposta
        function createLoadingMessage() {
            const messageContainer = document.createElement('div');
            messageContainer.className = 'message-container';

            // Crea icona
            const iconDiv = document.createElement('div');
            iconDiv.className = 'message-icon bot-icon';
            iconDiv.innerHTML = `
                <img src="${aiIconSrc}" alt="AI" class="h-5 w-5">
            `;

            // Crea contenuto del mex
            const messageContent = document.createElement('div');
            messageContent.className = 'message-content bot-message';
            messageContent.innerHTML = '<div class="flex space-x-2">' +
                                        '<div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>' +
                                        '<div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>' +
                                        '<div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div></div>';

            // aggiungi al container dei mex
            messageContainer.appendChild(iconDiv);
            messageContainer.appendChild(messageContent);

            return messageContainer;
        }


        /** Resize **/
        resizeHandle.addEventListener('mousedown', function(e) {
            isResizing = true;
            startX = e.clientX;
            startWidth = parseFloat(getComputedStyle(container).width);

            document.addEventListener('mousemove', handleResize);
            document.addEventListener('mouseup', stopResize);

            // Modalità resizing
            document.body.classList.add('resizing');
        });


        function handleResize(e) {
            if (!isResizing) return;

            const minWidth = 320;
            const maxWidth = window.innerWidth * 0.66; // 2/3 della finestra

            // Calcola la nuova width durante il resizing
            let newWidth = startWidth - (e.clientX - startX);
            newWidth = Math.max(minWidth, Math.min(maxWidth, newWidth));
            container.style.width = `${newWidth}px`;
        }


        function stopResize() {
            isResizing = false;
            document.removeEventListener('mousemove', handleResize);
            document.removeEventListener('mouseup', stopResize);
            document.body.classList.remove('resizing');
        }


        /** Invia Contenuto Pagina **/
        // Funzione per estrarre il contenuto HTML del main
        function extractMainContent() {
            const mainElement = document.querySelector('main');
            if (!mainElement) return;

            // Clona il main per non modificare il DOM originale
            const mainClone = mainElement.cloneNode(true);

            // Rimuovi tutti gli script
            const scripts = mainClone.querySelectorAll('script');
            scripts.forEach(script => script.parentNode.removeChild(script));

            // Rimuovi tutti gli stili inline
            const styles = mainClone.querySelectorAll('style');
            styles.forEach(style => style.parentNode.removeChild(style));

            // Ottieni l'HTML pulito
            const mainContent = mainClone.innerHTML;

            // Invia sempre il contenuto al server, indipendentemente da se ci sono già messaggi
            sendPageContextToBot(mainContent);
        }

        // Funzione per inviare il contesto della pagina
        function sendPageContextToBot(pageContent) {
            // Limita la dimensione del contenuto se necessario
            const maxLength = 50000; // Limite di caratteri
            const trimmedContent = pageContent.length > maxLength
                ? pageContent.substring(0, maxLength) + "... [contenuto troncato per dimensione]"
                : pageContent;

            // Prepara i dati per la richiesta AJAX
            const formData = new FormData();
            formData.append('message', 'Ecco il contenuto HTML della pagina corrente');
            formData.append('page_content', trimmedContent);
            formData.append('is_system_message', 'true');
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            // Effettua la richiesta AJAX
            fetch(chatForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .catch(error => {
                    console.error('Errore nell\'invio del contesto della pagina:', error);
                });
        }


        /** Gestione Messaggi **/
        // Formattazione del mex di Output
        function formatMessage(message) {
            // Converti **testo** in <strong>testo</strong>
            message = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            // Converti elenchi puntati (linee che iniziano con *)
            const lines = message.split('\n');
            let inList = false;
            let formattedLines = [];

            for (let i = 0; i < lines.length; i++) {
                let line = lines[i];

                // Verifica se la linea inizia con * o •
                if (line.trim().startsWith('*') || line.trim().startsWith('•')) {
                    // Se non siamo ancora in una lista, inizia una nuova lista
                    if (!inList) {
                        formattedLines.push('<ul>');
                        inList = true;
                    }

                    // Rimuovi il * o • e aggiungi come elemento lista
                    line = line.trim().replace(/^[\*•]\s+/, '•');
                    formattedLines.push('<li>' + line + '</li>');
                } else {
                    // Se eravamo in una lista e ora non lo siamo più, chiudi la lista
                    if (inList) {
                        formattedLines.push('</ul>');
                        inList = false;
                    }

                    formattedLines.push(line);
                }
            }

            // Se siamo ancora in una lista alla fine, chiudila
            if (inList) {
                formattedLines.push('</ul>');
            }

            return formattedLines.join('\n');
        }


        // Aggiunge il mex nella chat
        function addMessage(message, sender) {
            const messageContainer = document.createElement('div');
            messageContainer.className = 'message-container';

            // Crea icon
            const iconDiv = document.createElement('div');
            iconDiv.className = `message-icon ${sender === 'user' ? 'user-icon' : 'bot-icon'}`;

            if (sender === 'user') {
                iconDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        `;
            } else {
                iconDiv.innerHTML = `
            <img src="${aiIconSrc}" alt="AI" class="h-5 w-5">
        `;
            }

            // Crea contenuto del mex
            const messageContent = document.createElement('div');
            messageContent.className = `message-content ${sender === 'user' ? 'user-message' : 'bot-message'}`;

            // Applica formattazione se è un messaggio del bot
            if (sender === 'bot') {
                messageContent.innerHTML = formatMessage(message);
            } else {
                messageContent.textContent = message;
            }

            // Aggiungi al container
            messageContainer.appendChild(iconDiv);
            messageContainer.appendChild(messageContent);

            chatMessages.appendChild(messageContainer);

            // Fai lo scroll
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }


        /** Invio Mex - Ricezione Mex **/
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Impedisce il submit tradizionale del form

            // Se l’utente non ha scritto nulla o si sta ancora aspettando una risposta, interrompe l'esecuzione.
            const userMessage = chatInput.value.trim();
            if (!userMessage || isWaitingForResponse) {
                return;
            }

            // Aggiungi messaggio utente alla chat
            addMessage(userMessage, 'user');
            chatInput.value = '';

            // Disabilita input durante l'attesa
            toggleSubmitButton(false);
            isWaitingForResponse = true;

            // Aggiungi indicatore di caricamento
            const loadingIndicator = createLoadingMessage();
            chatMessages.appendChild(loadingIndicator);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Prepara i dati per la richiesta AJAX
            const formData = new FormData();
            formData.append('message', userMessage);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            // Effettua la richiesta AJAX con fetch
            fetch(chatForm.action, {
                method: 'POST',
                body: formData,
                // fatta in modo asincrono e non tramite un form HTML tradizionale.
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Rimuovi indicatore di caricamento
                    if (loadingIndicator && loadingIndicator.parentNode) {
                        chatMessages.removeChild(loadingIndicator);
                    }

                    // Aggiungi la risposta alla chat
                    if (data && data.reply) {
                        addMessage(data.reply, 'bot');
                    } else if (data && data.error) {
                        addMessage("Errore: " + data.error, 'bot');
                    } else {
                        addMessage("Risposta non valida dal server.", 'bot');
                    }
                })
                // Messaggio di errore se la richiesta fallisce.
                .catch(error => {
                    console.error('Errore:', error);

                    // Rimuovi indicatore di caricamento
                    if (loadingIndicator && loadingIndicator.parentNode) {
                        chatMessages.removeChild(loadingIndicator);
                    }

                    addMessage("Si è verificato un errore durante la comunicazione con il server.", 'bot');
                })
                .finally(() => {
                    // Ripristina lo stato dell'interfaccia
                    toggleSubmitButton(true);
                    isWaitingForResponse = false;
                    chatInput.focus();
                });
        });



        /** Reset Cronologia **/
        resetButton.addEventListener('click', function() {
            // Invia una richiesta AJAX per resettare la cronologia
            fetch("{{ route('chat.reset') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Resetta la UI della chat
                        chatMessages.innerHTML = '';  // Pulisce i messaggi

                        // Aggiungi un messaggio di conferma nella chat invece di un alert
                        addMessage("Cronologia della chat resettata con successo.", 'bot');
                    } else {
                        // Aggiungi un messaggio di errore nella chat
                        addMessage("Errore nel resettare la cronologia.", 'bot');
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    addMessage("Errore nella richiesta di reset.", 'bot');
                });
        });
    });
</script>
