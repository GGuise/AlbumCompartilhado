<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class GoogleDriveService
{
    protected $client;
    protected $driveService;
    protected $rootFolderId;

    public function __construct()
    {
        $this->rootFolderId = env('GOOGLE_DRIVE_FOLDER_ID');

        // Usar OAuth com Refresh Token (conta pessoal do dono)
        $clientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            \Log::warning('GoogleDriveService: Credenciais OAuth não configuradas.');
            return;
        }

        try {
            $this->client = new Google_Client();
            $this->client->setClientId($clientId);
            $this->client->setClientSecret($clientSecret);
            $this->client->addScope(Google_Service_Drive::DRIVE);
            $this->client->setAccessType('offline');

            // Usar o refresh token para obter access token
            $this->client->refreshToken($refreshToken);

            $this->driveService = new Google_Service_Drive($this->client);
        } catch (\Exception $e) {
            \Log::error('GoogleDriveService: Erro ao inicializar - ' . $e->getMessage());
        }
    }

    /**
     * Verifica se o serviço está configurado e pronto para uso.
     */
    public function isConfigured()
    {
        return $this->driveService !== null && $this->rootFolderId !== null;
    }

    /**
     * Cria uma subpasta dentro da pasta raiz do Drive.
     *
     * @param string $folderName Nome da pasta
     * @return string|null ID da pasta criada, ou null se falhou
     */
    public function createFolder($folderName)
    {
        if (!$this->isConfigured()) {
            \Log::warning('GoogleDriveService: Serviço não configurado. Pasta não criada.');
            return null;
        }

        try {
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$this->rootFolderId]
            ]);

            $folder = $this->driveService->files->create($fileMetadata, [
                'fields' => 'id'
            ]);

            \Log::info('GoogleDriveService: Pasta criada com sucesso. ID: ' . $folder->id);

            return $folder->id;
        } catch (\Exception $e) {
            \Log::error('GoogleDriveService: Erro ao criar pasta - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Faz upload de um arquivo para uma pasta específica no Drive.
     *
     * @param string $filePath Caminho completo do arquivo local
     * @param string $fileName Nome do arquivo
     * @param string $folderId ID da pasta no Drive
     * @param string|null $mimeType Tipo MIME do arquivo
     * @return string|null ID do arquivo no Drive, ou null se falhou
     */
    public function uploadFile($filePath, $fileName, $folderId, $mimeType = null)
    {
        if (!$this->isConfigured()) {
            \Log::warning('GoogleDriveService: Serviço não configurado. Upload não realizado.');
            return null;
        }

        if (!$folderId) {
            \Log::warning('GoogleDriveService: Nenhum folder ID fornecido. Upload não realizado.');
            return null;
        }

        try {
            if (!$mimeType) {
                $mimeType = mime_content_type($filePath);
            }

            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);

            $content = file_get_contents($filePath);

            $file = $this->driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            \Log::info('GoogleDriveService: Arquivo enviado com sucesso. ID: ' . $file->id);

            return $file->id;
        } catch (\Exception $e) {
            \Log::error('GoogleDriveService: Erro ao enviar arquivo - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Retorna o ID da pasta raiz configurada.
     */
    public function getRootFolderId()
    {
        return $this->rootFolderId;
    }
}
