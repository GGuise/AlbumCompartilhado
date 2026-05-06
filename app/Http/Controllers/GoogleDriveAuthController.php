<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Drive;
use Illuminate\Http\Request;

class GoogleDriveAuthController extends Controller
{
    /**
     * Redireciona para o Google para autorizar o acesso ao Drive.
     */
    public function redirect()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('APP_URL') . '/admin/google-drive/callback');
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('consent'); // Forçar a emissão de refresh token

        return redirect($client->createAuthUrl());
    }

    /**
     * Callback do Google OAuth - recebe o código e gera o refresh token.
     */
    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('dashboard')->with('status', 'Erro: Autorização negada pelo Google.');
        }

        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('APP_URL') . '/admin/google-drive/callback');
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setAccessType('offline');

        try {
            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            if (isset($token['refresh_token'])) {
                $refreshToken = $token['refresh_token'];

                return view('backend.google_drive_token', [
                    'refresh_token' => $refreshToken,
                    'success' => true
                ]);
            } else {
                return view('backend.google_drive_token', [
                    'refresh_token' => null,
                    'success' => false,
                    'error' => 'Refresh token não retornado. Tente revogar o acesso em myaccount.google.com/permissions e tente novamente.'
                ]);
            }
        } catch (\Exception $e) {
            return view('backend.google_drive_token', [
                'refresh_token' => null,
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
