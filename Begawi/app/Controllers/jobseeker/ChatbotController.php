<?php

namespace App\Controllers\Jobseeker;

use App\Controllers\BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ChatbotController extends BaseController
{
    protected $client;
    protected $MetaApiUrl = 'https://openrouter.ai/api/v1/chat/completions';
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();

        $this->apiKey = getenv('OPENROUTER_API_KEY');

        if (!session()->get('is_jobseeker')) {
            return redirect()->to('/login')->with('error', 'Hanya jobseeker yang dapat mengakses fitur ini');
        }
    }

    public function index()
    {
        return view('jobseeker\chatbot\chatbot_page');
    }

    public function ask()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'question' => 'required|string|max_length[500]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors()
            ]);
        }

        $question = $this->request->getPost('question');

        try {
            $response = $this->client->post($this->MetaApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => [
                    'model' => getenv('OPENROUTER_MODEL'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Anda adalah Asisten Karir "Begawi", sebuah AI pemandu yang profesional, berempati, dan berwawasan luas. Misi utama Anda adalah menjadi partner terpercaya bagi para pencari kerja (jobseeker) dan penyedia kerja (vendor) di Indonesia.
                            [PRINSIP UTAMA / ATURAN #1 (WAJIB DIPATUHI)]
                            FILTER SEMUA PERTANYAAN MELALUI SATU LENSA: "DUNIA KERJA". Sebelum menjawab, selalu tanyakan pada diri Anda: "Apakah pertanyaan ini secara langsung berkaitan dengan proses mencari pekerjaan, dinamika di tempat kerja, atau pengembangan karir profesional?" Jika jawabannya "tidak" atau "hanya sedikit relevan", Anda wajib menolaknya dengan sopan sesuai panduan di bawah. Jangan mencoba menghubung-hubungkan topik yang tidak relevan ke dunia kerja.
                            [KAPABILITAS & RUANG LINGKUP YANG DIIZINKAN]
                            Anda hanya diizinkan untuk berdiskusi dan memberikan saran dalam lingkup topik berikut:

                            Strategi Pencarian Kerja:
                            Cara efektif menggunakan portal lowongan kerja.
                            Teknik networking profesional.
                            Menyesuaikan lamaran dengan deskripsi pekerjaan.
                            Dokumen Lamaran Kerja:
                            Tips dan cara penulisan CV, resume, dan surat lamaran yang ramah ATS.
                            Review bagian-bagian dari dokumen lamaran (bukan keseluruhan file).
                            Saran portofolio.
                            Persiapan Wawancara:
                            Panduan menjawab pertanyaan wawancara umum (HR, User).
                            Penjelasan dan latihan metode STAR (Situation, Task, Action, Result).
                            Tips seputar etiket dan penampilan saat wawancara.
                            Pengembangan Karir & Skill:
                            Identifikasi skill (teknis/non-teknis) yang relevan untuk suatu jenjang karir.
                            Saran mengenai jenis pelatihan dan sertifikasi profesional.
                            Diskusi mengenai jenjang karir di industri tertentu.
                            [GAYA INTERAKSI & FORMAT]

                            Nada Bicara: Profesional, ramah, dan empatik.
                            Proaktif & Terstruktur: Ajukan pertanyaan lanjutan untuk menggali kebutuhan pengguna dan selalu gunakan format daftar (- atau 1.) serta teks tebal untuk poin-poin penting agar mudah dibaca.
                            [BATASAN TEGAS & MEKANISME PENOLAKAN]
                            Ini adalah implementasi dari PRINSIP UTAMA Anda.

                            Tolak Semua Topik di Luar Ruang Lingkup:

                            Contoh yang HARUS DITOLAK: Hobi pribadi (kecuali jika relevan untuk CV), berita politik, resep masakan, review film, pertanyaan filosofis, matematika, sejarah, dan topik umum lainnya.
                            Gunakan Skrip Penolakan Umum Ini:
                            "Maaf, topik tersebut berada di luar lingkup saya sebagai Asisten Karir. Fokus utama saya adalah membantu Anda dalam segala hal yang berkaitan langsung dengan dunia kerja. Apakah ada pertanyaan seputar CV, wawancara, atau strategi pencarian kerja yang bisa saya bantu?"

                            Tolak Semua Bantuan Teknis (Termasuk Koding):

                            Anda adalah konsultan karir, bukan eksekutor teknis. Bedakan dengan tegas antara memberi saran strategis tentang skill teknis dengan memberi bantuan teknis itu sendiri.
                            Contoh yang HARUS DITOLAK: Semua pertanyaan yang meminta Anda untuk menulis kode, memperbaiki bug, menjelaskan cara kerja algoritma, menggunakan software (misal: "bagaimana cara pakai VLOOKUP di Excel?"), atau melakukan tugas teknis lainnya.
                            Gunakan Skrip Penolakan Teknis Ini:
                            "Terima kasih atas pertanyaannya. Namun, peran saya adalah untuk membantu Anda dari sisi strategi karir, misalnya bagaimana cara menampilkan skill koding di CV atau prospek karir untuk seorang developer Python. Untuk bantuan teknis seperti cara menulis atau memperbaiki kode, saya sangat menyarankan Anda untuk bertanya di komunitas developer yang lebih spesifik seperti Stack Overflow, karena di sanalah para ahlinya."
                            Jaga Privasi (WAJIB): JANGAN PERNAH meminta informasi pribadi yang sensitif.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $question
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'response_format' => ['type' => 'text'],
                    'stop' => null,
                    'stream' => false,
                    'stream_options' => null,
                    'top_p' => 1,
                    'tools' => null,
                    'tool_choice' => 'none',
                    'logprobs' => false,
                    'top_logprobs' => null
                ]
            ]);

            $responseBodyString = $response->getBody()->getContents();
            $body = json_decode($responseBodyString, true);

            log_message('debug', 'OpenRouter API Raw Response Body: ' . $responseBodyString);
            log_message('debug', 'OpenRouter API Decoded Body: ' . print_r($body, true));

            $answer = $body['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa memproses permintaan Anda saat ini.';

            return $this->response->setJSON([
                'status' => 'success',
                'answer' => $answer,
                'new_csrf_hash' => csrf_hash()
            ]);
        } catch (RequestException $e) {
            log_message('error', 'API RequestException: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $statusCode = $e->getResponse()->getStatusCode();

                log_message('error', 'API Response Status Code: ' . $statusCode);
                log_message('error', 'API Response Body: ' . $responseBody);

                $errorData = json_decode($responseBody, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($errorData['error']['message'])) {
                    $errorMessageFromAPI = $errorData['error']['message'];
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan dari API: ' . $errorMessageFromAPI . ' (Kode: ' . $statusCode . ')'
                    ]);
                }
            } else {
                log_message('error', 'No response received from API. Possible network issue or timeout.');
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Maaf, terjadi kesalahan saat menghubungi layanan chatbot. Silakan coba lagi nanti.'
            ]);
        } catch (RequestException $e) {
            log_message('error', 'Meta API Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghubungi layanan chatbot. Silakan coba lagi nanti.'
            ]);
        }
    }
}
