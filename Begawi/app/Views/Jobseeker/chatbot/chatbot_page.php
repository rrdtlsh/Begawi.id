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
                <div class="input-group">
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
        
        $.ajax({
            url: '<?= site_url('jobseeker/chatbot/ask') ?>',
            type: 'POST',
            dataType: 'json',
            data: { question: question },
            beforeSend: function() {
                $('#chat-container').append(loadingMessageHtml);
                scrollToBottom();
            },
            success: function(response) {
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
/* Reset atau styling dasar */
body {
    background-color: #f0f2f5; /* Latar belakang halaman umum */
}

.container.mt-4 {
    max-width: 700px; /* Lebar maksimal container */
}

/* Styling utama wrapper chatbot (sesuai gambar) */
.chatbot-container-wrapper {
    background-color: #2F4F4F; /* Hijau tua */
    border-radius: 15px; /* Sudut membulat */
    overflow: hidden; /* Pastikan konten tidak meluber */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Sedikit bayangan */
}

.chatbot-header {
    background-color: #366767; /* Sedikit lebih terang dari background utama */
    color: white;
    padding: 15px 20px;
    font-size: 1.2em;
    text-align: center; /* Sesuaikan jika ada logo di kiri */
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.chatbot-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: 600px; /* Tinggi total body, sesuaikan */
}

.chat-messages-area {
    flex-grow: 1; /* Agar area pesan mengisi ruang yang tersedia */
    overflow-y: auto; /* Scroll jika pesan banyak */
    margin-bottom: 20px;
    padding-right: 10px; /* Untuk estetika scrollbar */
}

/* Styling Gelembung Chat */
.chat-message {
    padding: 12px 18px; /* Padding lebih besar */
    border-radius: 20px; /* Lebih membulat */
    margin-bottom: 10px;
    max-width: 75%; /* Batasi lebar gelembung */
    word-wrap: break-word; /* Pastikan teks panjang tidak meluber */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Sedikit bayangan */
}

.user-message {
    background-color: #4CAF50; /* Hijau terang */
    color: white;
    margin-left: auto; /* Dorong ke kanan */
    margin-right: 0;
    text-align: left; /* Teks tetap kiri di gelembung */
}

.bot-message {
    background-color: #f0f0f0; /* Abu-abu terang */
    color: #333;
    margin-right: auto; /* Dorong ke kiri */
    margin-left: 0;
    text-align: left;
}

/* Untuk pesan awal bot */
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

/* Input Area Styling */
.chatbot-input-form .input-group {
    background-color: white; /* Latar belakang putih untuk input */
    border-radius: 25px; /* Sudut membulat */
    overflow: hidden; /* Untuk menjaga border-radius */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); /* Bayangan lebih jelas */
    padding: 5px; /* Padding di sekitar input dan tombol */
}

.chatbot-input-form .form-control.chat-input {
    border: none; /* Hapus border bawaan */
    box-shadow: none; /* Hapus shadow bawaan */
    padding-left: 15px; /* Sedikit padding di kiri */
    background-color: transparent; /* Pastikan background transparan */
    height: 45px; /* Tinggi input */
}

.chatbot-input-form .form-control.chat-input:focus {
    box-shadow: none; /* Pastikan tidak ada shadow fokus */
}

.chatbot-input-form .input-group-append {
    margin-left: 0; /* Pastikan tidak ada margin ekstra */
}

.chatbot-input-form .btn.chat-send-button {
    background-color: #4CAF50; /* Warna tombol hijau */
    border-color: #4CAF50;
    border-radius: 20px; /* Lebih membulat */
    padding: 10px 20px; /* Padding tombol */
    font-size: 1.1em;
    min-width: 60px; /* Lebar minimum tombol */
    transition: background-color 0.3s ease; /* Transisi hover */
}

.chatbot-input-form .btn.chat-send-button:hover {
    background-color: #45a049; /* Warna hover */
    border-color: #45a049;
}

/* Hapus styling card bawaan Bootstrap jika tidak digunakan */
.card, .card-header, .card-body {
    border: none;
    background: none;
}
</style>

<?= $this->endSection() ?>