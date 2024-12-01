<?php

include('connect.php');

// Check if user is logged in
$is_logged_in = isset($_SESSION['uid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Help Chat</title>
    <style>
    #chat-icon {
        width: 60px;
        height: 60px;
        background-color: #28a745; /* Vibrant green color */
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 7;
        font-size: 28px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    #chat-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    #chat-box {
        display: none;
        position: fixed;
        margin: 5px;
        bottom: 80px;
        right: 20px;
        width: 350px;
        height: 400px; /* Adjusted height to prevent overflow */
        border: 1px solid #ddd;
        border-radius: 15px;
        background-color: #ffffff;
        flex-direction: column;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        z-index: 5;
        overflow: hidden;
        transition: all 0.3s;
    }

    #chat-box-header {
        background-color: #28a745; /* Vibrant green color */
        color: white;
        padding: 15px;
        text-align: center;
        font-size: 18px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        border-bottom: 1px solid #ddd;
    }

    #chat-box-body {
        padding: 10px;
        height: 280px; /* Reduced height to make space for the footer */
        overflow-y: auto;
        background-color: #f9f9f9;
        font-family: 'Arial', sans-serif;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #chat-box-footer {
        padding: 10px 15px;
        border-top: 1px solid #ddd;
        background-color: #f1f1f1;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #chat-input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        margin-right: 10px;
    }

    #send-btn {
        background-color: #28a745; /* Vibrant green color */
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #send-btn:hover {
        background-color: #218838; /* Darker green on hover */
    }

    .chat-message {
        margin-bottom: 10px;
        padding: 8px;
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.5;
        max-width: 80%;
        word-wrap: break-word;
    }

    .user-message {
        background-color: #007bff;
        color: white;
        align-self: flex-end;
        text-align: right;
    }

    .bot-message {
        background-color: #e0e0e0;
        color: black;
        align-self: flex-start;
        text-align: left;
    }
</style>

</head>
<body>

<!-- Chat Icon -->
<div id="chat-icon" <?= $is_logged_in ? '' : 'style="display: none;"' ?>>💬</div>

<!-- Chat Box -->
<div id="chat-box">
    <div id="chat-box-header">Chat with Us</div>
    <div id="chat-box-body">
        <!-- Chat messages will appear here -->
    </div>
    <div id="chat-box-footer">
        <input type="text" id="chat-input" placeholder="Type a message...">
        <button id="send-btn">Send</button>
    </div>
</div>

<!-- Chat Box JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatIcon = document.getElementById('chat-icon');
    const chatBox = document.getElementById('chat-box');
    const sendBtn = document.getElementById('send-btn');
    const chatInput = document.getElementById('chat-input');
    const chatBoxBody = document.getElementById('chat-box-body');

    chatIcon.addEventListener('click', function() {
        const currentDisplay = window.getComputedStyle(chatBox).display;
        chatBox.style.display = currentDisplay === 'none' ? 'flex' : 'none';
    });

    function addChatMessage(message, isUser = true) {
        const chatMessage = document.createElement('div');
        chatMessage.classList.add('chat-message');
        chatMessage.classList.add(isUser ? 'user-message' : 'bot-message');
        chatMessage.textContent = message;
        chatBoxBody.appendChild(chatMessage);
        chatBoxBody.scrollTop = chatBoxBody.scrollHeight;
    }

    function handleCommand(command) {
        command = command.trim().toLowerCase();
        switch (true) {
            case /hi|hello|hey/.test(command):
                return "Hello! How can I assist you today? Press 1 for general information, 2 for contact details, or 3 to end the chat.";
            case '1' === command:
                return "For more information, visit our FAQ page or contact support.";
            case '2' === command:
                return "You can contact us at support@example.com or call us at (123) 456-7890.";
            case '3' === command:
                return "Thank you for chatting with us. Have a great day!";
            default:
                return "I did not understand that command. Please try again.";
        }
    }

    sendBtn.addEventListener('click', function() {
        const message = chatInput.value.trim();
        if (message !== '') {
            addChatMessage(message);
            chatInput.value = '';
            
            // Simulate bot response
            setTimeout(() => {
                const response = handleCommand(message);
                addChatMessage(response, false);
            }, 500);
        }
    });

    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendBtn.click();
        }
    });
});

</script>

</body>
</html>
