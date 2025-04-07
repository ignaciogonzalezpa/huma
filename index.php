<?php

echo "Hola";

$token = '8024202425:AAHZU-uiizGkjfUweisefXFMx4QSd7G-ENs';
$api_url = "https://api.telegram.org/bot$token/";

// Diccionario de pasillos
$pasillos = [
    "pasillo 1" => ["Carne", "Queso", "Jamón"],
    "pasillo 2" => ["Leche", "Yogurth", "Cereal"],
    "pasillo 3" => ["Bebidas", "Jugos"],
    "pasillo 4" => ["Pan", "Pasteles", "Tortas"],
    "pasillo 5" => ["Detergente", "Lavaloza"]
];

// Obtener actualizaciones
$response = file_get_contents($api_url . "getUpdates?offset=-1");
$updates = json_decode($response, true);

echo "<h1>Depuración: Mensajes Recibidos</h1>";
echo "<pre>";
print_r($updates);
echo "</pre>";

if (empty($updates['result'])) {
    echo "<p>No se encontraron mensajes nuevos.</p>";
} else {
    foreach ($updates['result'] as $update) {
        if (isset($update['message'])) {
            $message = $update['message'];
            $chat_id = $message['chat']['id'];
            $text = strtolower(trim($message['text'] ?? ""));

            echo "<p><strong>Mensaje recibido:</strong> $text</p>";

            $respuesta = "Lo siento, no encontré ese pasillo.";

            foreach ($pasillos as $pasillo => $productos) {
                if (strpos($text, strtolower($pasillo)) !== false) {
                    $respuesta = "En $pasillo puedes encontrar: " . implode(", ", $productos);
                    break;
                }
            }

            if ($text == "hola") {
                $respuesta = "¡Hola! ¿En qué te puedo ayudar?";
            }

            // Enviar respuesta sin botones
            $url = $api_url . "sendMessage?chat_id=$chat_id&text=" . urlencode($respuesta);
            file_get_contents($url);

            echo "<p><strong>Respuesta enviada:</strong> $respuesta</p>";
        }
    }
}

?>
