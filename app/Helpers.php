<?php

namespace App;


class Helpers {

    public static function getData(array $params) {
        $kpl = $params["request"]["kpl"] ?? "";
        if (empty($kpl)) {
//            $result = ['status' => 'errors', 'msg' => ["not found kpl"]];

            return false;
        }
        $config = $params["config"];
        $url = $config["base_url"] . $kpl;

        $result = file_get_contents($url);
        $result = json_decode(static::removeBom($result), true);

        return $result;
    }

    public static function removeBom(string $str) {
        $bom = pack('H*', 'EFBBBF');
        $result = preg_replace("/^$bom/", '', $str);

        return $result;
    }

    public static function sendToTelegram(array $params) {
        foreach ($params['data'] as $datum) {
            $text = static::getMsg($datum);
            $options = [
                'token'   => $params['config']['token'] ?? '',
                "chat_id" => $datum['id_telegram'] ?? '',
                "text"    => $text,
            ];
            Telegram::sendMessage($options);
        }
    }

    public static function sendEmail(array $params) {
        $subject = $params['config']['mailer']['subject'];
        $headers = $params['config']['mailer']['from'] . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        foreach ($params['data'] as $datum) {
            $message = static::getMsg($datum);
            $message = str_replace("\n", "<br>", $message);
            $to = $datum["manager_email"];
            mail($to, $subject, $message, $headers);
        }
    }

    private static function getMsg($datum) {
        $text = '!!! Ваше коммерческое предложение для '
            . $datum["client"] . " читает кто-то из :\n"
            . implode("\n", $datum["contacts"]);

        return $text;
    }
}