document.addEventListener('DOMContentLoaded', () => {
    // Verifica se estamos na página de criação de deck
    if (!document.getElementById('deck-form')) {
        return;
    }

    const cardImage = document.getElementById('card-image');
    const cardCounter = document.getElementById('card-counter');
    const cardIdentifierInput = document.getElementById('card-identifier');
    const prevButton = document.getElementById('prev-card');
    const nextButton = document.getElementById('next-card');
    const form = document.getElementById('deck-form');
    
    // cardList é passado do PHP
    const totalCards = cardList.length;
    let currentIndex = 0;
    
    // Array para armazenar os identificadores digitados pelo usuário
    const identifiers = new Array(totalCards).fill('');

    function updateCardView() {
        const cardName = cardList[currentIndex];
        // O nome do arquivo é o nome da carta + .png (ex: ace_of_spades.png)
        cardImage.src = `assets/images/cards/${cardName}.png`;
        cardImage.alt = cardName.replace(/_/g, ' ');
        
        cardCounter.textContent = `Carta ${currentIndex + 1} de ${totalCards}`;
        cardIdentifierInput.value = identifiers[currentIndex];
        
        // Desabilita/Habilita botões de navegação
        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === totalCards - 1;
    }

    nextButton.addEventListener('click', () => {
        if (currentIndex < totalCards - 1) {
            // Salva o valor atual antes de avançar
            identifiers[currentIndex] = cardIdentifierInput.value;
            currentIndex++;
            updateCardView();
        }
    });

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            // Salva o valor atual antes de voltar
            identifiers[currentIndex] = cardIdentifierInput.value;
            currentIndex--;
            updateCardView();
        }
    });
    
    // Salva o último identificador antes de submeter o formulário
    form.addEventListener('submit', (e) => {
        // Salva o valor do campo atual
        identifiers[currentIndex] = cardIdentifierInput.value;

        // Preenche o formulário com os identificadores antes de enviar
        // Remove o input visível para não ser enviado duplicado
        cardIdentifierInput.name = '';

        // Cria inputs hidden para cada identificador
        for (let i = 0; i < totalCards; i++) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            // O name="identifiers[]" cria um array no lado do PHP
            hiddenInput.name = 'identifiers[]';
            hiddenInput.value = identifiers[i];
            form.appendChild(hiddenInput);

            const cardInput = document.createElement('input');
            cardInput.type = 'hidden';
            cardInput.name = 'cards[]';
            cardInput.value = cardList[i];
            form.appendChild(cardInput);
        }
    });

    // Inicia a visualização com a primeira carta
    updateCardView();
});

// Lógica para a página de visualização em grade (view_deck.php)
document.addEventListener('DOMContentLoaded', () => {
    const deckGrid = document.querySelector('.deck-grid');
    if (!deckGrid) {
        return;
    }

    const saveStatus = document.getElementById('save-status');
    let saveTimeout;

    deckGrid.addEventListener('blur', (e) => {
        if (e.target && e.target.classList.contains('identifier-input')) {
            const input = e.target;
            const deckId = input.dataset.deckId;
            const cardKey = input.dataset.cardKey;
            const identifier = input.value;

            // Mostra o status de "Salvando..."
            showStatus('Salvando...');

            fetch('update_identifier.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    deck_id: deckId,
                    card_key: cardKey,
                    identifier: identifier,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' || data.status === 'no_change') {
                    // Mostra o status de "Salvo!" e esconde depois de um tempo
                    showStatus('Salvo!', true);
                } else {
                    // Mostra uma mensagem de erro
                    showStatus(`Erro: ${data.message}`, true, true);
                    alert(`Falha ao salvar: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                showStatus('Erro de conexão.', true, true);
                alert('Ocorreu um erro de conexão. Verifique o console para mais detalhes.');
            });
        }
    }, true); // Usa capturing para garantir que o evento seja pego

    function showStatus(message, autoHide = false, isError = false) {
        clearTimeout(saveTimeout);
        saveStatus.textContent = message;
        saveStatus.classList.add('visible');
        saveStatus.style.backgroundColor = isError ? '#e74c3c' : '#2c3e50';

        if (autoHide) {
            saveTimeout = setTimeout(() => {
                saveStatus.classList.remove('visible');
            }, 2000);
        }
    }
});
