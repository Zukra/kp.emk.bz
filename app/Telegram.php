<?php

namespace App;


class Telegram {
    static $urlBasic = "https://api.telegram.org/bot";

    /**
     * Выводим в чат
     * @param $options = ['token', 'chat_id', 'text', 'parse_mode', 'disable_web_page_preview', 'disable_notification']
     * @return bool|mixed
     */
    public static function sendMessage($options) {
        if (! empty($options['text'])) {
            $params = [
                'token'                    => $options['token'] ?? '',
                "chat_id"                  => $options['chat_id'] ?? '',
                "text"                     => $options['text'],
                "parse_mode"               => $options["parse_mode"] ?? "HTML",
                "disable_web_page_preview" => $options["disable_web_page_preview"] ?? true,
                "disable_notification"     => $options["disable_notification"] ?? true
            ];

            return static::query("sendMessage", $params);
        }

        return false;
    }

    public static function getUpdates($token = '') {
        return static::query("getUpdates", ['token' => $token]);
    }

    private static function query($method, $params = []) {
        $token = $params['token'];
        unset($params['token']);

        $url = static::$urlBasic . $token . "/" . $method;
        $url .= (! empty($params) ? "?" . http_build_query($params) : "");

        return json_decode(file_get_contents($url), true);
    }
}