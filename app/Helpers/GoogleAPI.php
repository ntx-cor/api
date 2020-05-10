<?php
namespace App\Helpers;
class GoogleAPI{

    public static function getClient()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(config('app.google'));
        $client->setScopes(\Google_Service_Drive::DRIVE);
        $accessToken = $client->fetchAccessTokenWithRefreshToken(env('GOOGLE_REFRESH_TOKEN'));
        $client->setAccessToken($accessToken);

        // Load previously authorized credentials from a file.
       /* $credentialsPath = self::expandHomeDirectory('credentials.json');
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }*/
        return $client;
    }

    public static function uploadImage($fileUpload,$name=null,$folderId=null,$folderName=null){
        $client = self::getClient();

        $service = new \Google_Service_Drive($client);

        if(empty($folderId)){
            $folderId=env('GOOGLE_FOLDER_ID');
        }

        //Insert a file
        $file = new \Google_Service_Drive_DriveFile([
            'name'=>$name,
            'parents'=>array($folderId)
        ]);
        $data = file_get_contents($fileUpload);

        $res = $service->files->create($file, array(
            'data' => $data,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));
        return $res;
    }

}
