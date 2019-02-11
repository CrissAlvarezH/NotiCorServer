<?php

class HiloEnviarNotiToTopic extends Thread {
    private $accion;
    private $cuerpoMsj;
    private $topico;

    public function __contruct($accion, $cuerpoMsj, $topico) {
        $this->accion = $accion;
        $this->cuerpoMsj = $cuerpoMsj;
        $this->topico = $topico;
    }

    public function run() {
        self::enviarNoti($this->accion, $this->cuerpoMsj, $this->topico);
    }

    private static function enviarNoti($accion, $cuerpoMsj, $topico) {
        $jsonFormateado = str_replace('"', "'", json_encode($cuerpoMsj));
        
        error_log('accion: '.$accion. ', cuerpo: '. $jsonFormateado.' topico: '.$topico);

        $curl = curl_init();

        curl_setopt_array($curl, array(
                CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '{
                    "to" : "/topics/'. $topico .'",
                    "data" : {
                        "accion"  :  "'. $accion .'",
                        "cuerpo"  :  "'. $jsonFormateado .'"
                    },
                    "priority": "high"
                }',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: key=AIzaSyBgmTkX6hwtNoZ4aLSVfakdT3EQF7i_2Rc",
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return true;
        }
    }
}

?>