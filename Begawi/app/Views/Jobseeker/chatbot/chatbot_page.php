<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="chatbot-container-wrapper"> <div class="chatbot-header">
            <h2 class="text-white">ChatBot Begawi</h2>
        </div>
        <div class="chatbot-body">
            <div id="chat-container" class="chat-messages-area">
                <div class="chat-message bot-message initial-message">
                    <strong>Career Assistant:</strong> Halo! Saya asisten karir Anda. Ada yang bisa saya bantu terkait pencarian kerja, pengembangan karir, atau persiapan interview?
                </div>
                </div>
            
            <form id="chatbot-form" class="chatbot-input-form">
                <?= csrf_field() ?> <div class="input-group">
                    <input type="text" id="question" class="form-control chat-input" placeholder="Tanyakan sesuatu tentang karir Anda..." required>
                    <div class="input-group-append">
                        <button class="btn btn-primary chat-send-button" type="submit">
                            <i class="fas fa-arrow-right"></i> Kirim
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Styling tambahan untuk pesan loading
    const loadingMessageHtml = `
        <div class="chat-message bot-message loading">
            <strong>Career Assistant:</strong> <i>Mengetik...</i>
        </div>
    `;

    $('#chatbot-form').on('submit', function(e) {
        e.preventDefault();
        
        const question = $('#question').val().trim();
        if (!question) return;
        
        addMessage('user', question);
        $('#question').val('');
        
        // =================== PERUBAHAN DIMULAI DI SINI ===================

        // Ambil nama dan hash token CSRF dari hidden input
        const csrfName = $('input[name=csrf_test_name]').attr('name');
        const csrfHash = $('input[name=csrf_test_name]').val();

        $.ajax({
            url: '<?= site_url('jobseeker/chatbot/ask') ?>',
            type: 'POST',
            dataType: 'json',
            // Kirim data pertanyaan BERSAMA DENGAN token CSRF
            data: {
                question: question,
                [csrfName]: csrfHash 
            },
            beforeSend: function() {
                $('#chat-container').append(loadingMessageHtml);
                scrollToBottom();
            },
            success: function(response) {
                // Perbarui nilai token di form agar request berikutnya valid
                if(response.new_csrf_hash) {
                    $('input[name=csrf_test_name]').val(response.new_csrf_hash);
                }

                $('.chat-message.loading').remove();
                
                if (response.status === 'success') {
                    addMessage('bot', response.answer);
                } else {
                    addMessage('bot', 'Maaf, terjadi kesalahan: ' + (response.message || 'Silakan coba lagi nanti.'));
                }
            },
            error: function(xhr) {
                $('.chat-message.loading').remove();
                addMessage('bot', 'Maaf, terjadi kesalahan saat menghubungi server.');
                console.error('Error:', xhr.responseText);
            }
        });
    });
    
    function addMessage(sender, message) {
        const senderClass = sender === 'user' ? 'user-message' : 'bot-message';
        const senderName = sender === 'user' ? 'Anda' : 'Career Assistant';
        
        $('#chat-container').append(`
            <div class="chat-message ${senderClass}">
                ${message}
            </div>
        `);
        
        scrollToBottom();
    }
    
    function scrollToBottom() {
        const container = $('#chat-container');
        container.scrollTop(container[0].scrollHeight);
    }
});
</script>

<style>

body {
    background-color: #f0f2f5; 
}

.container.mt-4 {
    max-width: 700px; 
}

.chatbot-container-wrapper {
    background-color: #2F4F4F; 
    border-radius: 15px; 
    overflow: hidden; 
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); 
}

.chatbot-header {
    background-color: #366767; 
    color: white;
    padding: 15px 20px;
    font-size: 1.2em;
    text-align: center; 
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.chatbot-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: 600px; 
}

.chat-messages-area {
    flex-grow: 1; 
    overflow-y: auto; 
    margin-bottom: 20px;
    padding-right: 10px; 
}

.chat-message {
    padding: 12px 18px; 
    border-radius: 20px; 
    margin-bottom: 10px;
    max-width: 75%; 
    word-wrap: break-word; 
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); 
}

.user-message {
    background-color: #4CAF50; 
    color: white;
    margin-left: auto; 
    margin-right: 0;
    text-align: left; 
}

.bot-message {
    background-color: #f0f0f0; 
    color: #333;
    margin-right: auto; 
    margin-left: 0;
    text-align: left;
}

.chat-message.initial-message {
    margin-right: auto;
    margin-left: 0;
}

.loading {
    color: #666;
    font-style: italic;
    background-color: #e0e0e0;
    text-align: left;
}

.chatbot-input-form .input-group {
    background-color: white; 
    border-radius: 25px; 
    overflow: hidden; 
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); 
    padding: 5px; 
}

.chatbot-input-form .form-control.chat-input {
    border: none; 
    box-shadow: none; 
    padding-left: 15px; 
    background-color: transparent; 
    height: 45px; 
}

.chatbot-input-form .form-control.chat-input:focus {
    box-shadow: none; 
}

.chatbot-input-form .input-group-append {
    margin-left: 0; 
}

.chatbot-input-form .btn.chat-send-button {
    background-color: #4CAF50; 
    border-color: #4CAF50;
    border-radius: 20px; 
    padding: 10px 20px; 
    font-size: 1.1em;
    min-width: 60px; 
    transition: background-color 0.3s ease; 
}

.chatbot-input-form .btn.chat-send-button:hover {
    background-color: #45a049; 
    border-color: #45a049;
}

.card, .card-header, .card-body {
    border: none;
    background: none;
}
</style>

<?= $this->endSection() ?>