<?php

namespace Core\Controller;

class IgloohomeController extends Controller
{
    private $basicCode;
    private $token = "";
    private $curl;

    private $pieces = [
        "studio" => [
            "id" => "IGK3076a4bc7",
            "bridge" => "EB1X01e5d59e"
        ],
        "s10" => [
            "id" => "IGK307ee9806",
            "bridge" => "EB1X01e5d59e"
        ],
        "s11" => [
            "id" => "IGK3077b8e26",
            "bridge" => "EB1X01e5d59e"
        ],
        "coworking" => [
            "id" => "IGK31162922f",
            "bridge" => "EB1X01e2fddd"
        ],
    ];

    public function __construct()
    {
        $this->basicCode = $this->getConfig('IGLOOHOME_BASIC_CODE');
        $this->curl = curl_init();
        $this->getToken();
    }

    private function getToken()
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_URL => 'https://auth.igloohome.co/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'grant_type: client_credentials',
                'Authorization: Basic {' . $this->basicCode . '}',
            ],
        ]);

        $response = curl_exec($this->curl);
        curl_close($this->curl);
        $this->token = \json_decode($response)->access_token;
    }

    public function openDoor($salle, $token)
    {
        /** todo change */
        if ($token !== $this->getConfig("tokenSms")) {
            return \false;
        }
        if (!array_key_exists($salle, $this->pieces)) {
            return \false;
        }

        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_URL => 'https://api.igloodeveloper.co/igloohome/devices/'
                . $this->pieces[$salle]['id'] . '/jobs/bridges/'
                . $this->pieces[$salle]['bridge'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
  "jobType": 2
}',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token
            ],
        ]);

        $response = curl_exec($this->curl);
        curl_close($this->curl);
        return true;
    }
}
