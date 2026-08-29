<?php

namespace App\Services;

use ImageKit\ImageKit;

class ImageKitService
{
    protected ImageKit $client;

    public function __construct()
    {
        $this->client = new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.url_endpoint'),
        );
    }

    public function upload($file, string $folder): string
    {
        $result = $this->client->uploadFile([
            'file' => base64_encode(file_get_contents($file->getRealPath())),
            'fileName' => uniqid() . '_' . $file->getClientOriginalName(),
            'folder' => $folder,
        ]);

        return $result->result->url;
    }
}
