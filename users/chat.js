document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById('chat-box');
    const chatContainer = document.querySelector('.chat-container');
    const receiverId = chatContainer.querySelector('input[name="receiver_id"]').value;
    const userId = "<?php echo $organizer_id; ?>"; // Идентификатор организатора из PHP

    function loadMessages() {
        fetch(`load_messages.php?receiver_id=${receiverId}`)
            .then(response => response.json())
            .then(data => {
                chatBox.innerHTML = '';
                data.messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    if (message.sender_id == userId) {
                        messageElement.classList.add('sent');
                    } else {
                        messageElement.classList.add('received');
                    }
                    messageElement.textContent = message.message;
                    chatBox.appendChild(messageElement);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    loadMessages();
    setInterval(loadMessages, 3000);
});
