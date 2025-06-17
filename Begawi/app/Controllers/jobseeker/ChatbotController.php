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
        // Validasi input
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
                    'model' => 'meta-llama/llama-3.3-8b-instruct:free',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Anda adalah Asisten Karir Profesional yang berdedikasi untuk membantu para pencari kerja (jobseeker) menemukan pekerjaan yang paling cocok dan sesuai dengan aspirasi mereka. Anda berfungsi sebagai pemandu utama di platform ini, yang melayani baik jobseeker maupun vendor jasa pekerjaan.
                            Tugas utama Anda adalah:
                            - Memberikan saran strategis tentang pencarian kerja yang efektif.
                            - Membantu dalam pengembangan karir, termasuk identifikasi skill dan peluang.
                            - Memberikan panduan praktis untuk penulisan CV dan resume yang menarik.
                            - Menyiapkan pengguna untuk wawancara kerja yang sukses, termasuk tips dan simulasi.
                            - Jangan menjawab diluar konteks pekerjaan, dunia kerja, dan pelatihan yang berkaitan dengan pekerjaan. Mohon tolak secara halus untuk pertanyaan diluar konteks tersebut seperti Maaf, saya tidak dapat membantu dengan pertanyaan tersebut karena topiknya tidak terkait dengan dunia karir atau pekerjaan. Jika Anda memiliki pertanyaan tentang strategi pencarian kerja, pengembangan karir, atau tips wawancara, saya dengan senang hati membantu.

                            Saat memberikan informasi yang berbentuk daftar, langkah-langkah, atau poin-poin penting, mohon **gunakan format daftar berpoin (bullet points)** atau **daftar bernomor (numbered lists) dengan jelas (Markdown)** agar mudah dibaca dan dipahami. Pastikan setiap poin disajikan secara terpisah dan rapi. Hindari paragraf panjang untuk daftar.

                            Contoh format daftar yang diharapkan:
                            - Poin satu
                            - Poin dua
                                - Sub-poin A
                                - Sub-poin B
                            - Poin tiga

                            Fokuslah pada informasi yang relevan dengan dunia karir di Indonesia (misalnya, saran CV yang umum di Indonesia, persiapan interview umum, dll.).'
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
            log_message('error', 'DeepSeek API Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghubungi layanan chatbot. Silakan coba lagi nanti.'
            ]);
        }
    }
}