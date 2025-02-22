<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class SupabaseStorage
{
    protected $client;
    protected $url;
    protected $apiKey;
    protected $bucket;

    public function __construct()
    {
        $this->client = new Client();
        $this->url = env('SUPABASE_URL') . "/storage/v1/object";
        $this->apiKey = env('SUPABASE_KEY');
        $this->bucket = env('SUPABASE_BUCKET');
    }

    public function uploadImage($file, $path)
    {
        // Generate unique filename
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        // Convert file ke binary untuk dikirim ke Supabase
        $fileContents = file_get_contents($file->getRealPath());

        // Endpoint upload ke Supabase
        $endpoint = "{$this->url}/{$this->bucket}/{$path}/{$filename}";

        // Kirim request ke Supabase
        $response = $this->client->request('POST', $endpoint, [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => $file->getMimeType(),
                'x-upsert' => 'true'
            ],
            'body' => $fileContents
        ]);

        if ($response->getStatusCode() == 200) {
            return "{$this->url}/public/{$this->bucket}/{$path}/{$filename}";
        }

        return null;
    }
}
