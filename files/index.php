<?php
header("Content-Type: application/json; charset=UTF-8");
include 'php/scandir.php';

$instance_param = $_GET['instance'] ?? 'null';

if ($instance_param == '/' || $instance_param[0] == '.') {
    echo json_encode([]);
    exit;
}

if (!file_exists('instances')) {
    echo dirToArray("files");
    exit;
}

if ($instance_param == 'null') {
    $instances_list = scanFolder("instances");
    $instance = array();
    foreach ($instances_list as $value) {
        if (substr($_SERVER['REQUEST_URI'], -1) == '/') {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, -1);
        }

        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?instance=$value";

        // Déterminer l'URL de l'icône
        $icon_path_relative = "files/images/$value/logo.png";
        $icon_path_absolute = "/home/sc1bgah3028/saurfort/launcher/dev/$icon_path_relative";

        if (!file_exists($icon_path_absolute)) {
            $icon_path_relative = "files/images/default.png";
            $icon_path_absolute = "/home/sc1bgah3028/saurfort/launcher/dev/$icon_path_relative";
        }

        $icon_url = "http://$_SERVER[HTTP_HOST]/dev/$icon_path_relative";

        $instance[$value] = array(
            "name" => $value,
            "url" => $url,
            "status" => array(
                "icon" => $icon_url
            )
        );
    }

    include 'php/instances.php';
    echo str_replace("\\", "", json_encode($instance));
    exit;
}

$instance_path = "instances/$instance_param";
if (file_exists($instance_path)) {
    //include 'php/instances.php';

    //$instance_info = $instance[$instance_param];


    echo /*json_encode($instance_info),*/ dirToArray("instances/$instance_param", $instance_param);
} else {
    echo json_encode([]);
}

//echo dirToArray("instances/$instance_param");
?>
