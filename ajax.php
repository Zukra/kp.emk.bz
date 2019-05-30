<?
require __DIR__ . '/config/init.php';

use App\Helpers;


$result = [];
$action = $_REQUEST["action"] ?? '';

if ($action == 'send') {
    $config = $container->get("config");
    $params = [
        "request" => $_REQUEST,
        "config"  => $config,
    ];

    if ($data = Helpers::getData($params)) {
        $params = ['config' => $config, 'data' => $data];
        Helpers::sendToTelegram($params);
        Helpers::sendEmail($params);

        $result = [
            'status' => 'success',
            'msg'    => ['sent to telegram & email']
        ];
    }
}
echo json_encode($result);
