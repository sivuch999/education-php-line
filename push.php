<?php
    require("config.php");
    // require("connect.php");
    
    if ( isset($_POST["lineId"]) && !empty($_POST["lineId"]) ) {
        $payload["headers"] = ["Content-Type: application/json", "Authorization: Bearer {$accessToken}"];
        $payload["body"]["to"] = "Ua18249e67884f3e1eecbeaab3e5534e7";
        $payload["body"]["messages"][0]["type"] = "text";
        $payload["body"]["messages"][0]["text"] = "เหลืออีก ".$_POST["queue"]." จะถึงคิวของท่าน";
        pushMessage($payload);


        // $sql = "
        //     SELECT
        //         *
        //     FROM (
        //         SELECT
        //             q.Q,
        //             customer.*,
        //             CASE WHEN MOD(ROW_NUMBER() OVER(ORDER BY q.Q ASC), 3) = 0 THEN ROW_NUMBER() OVER(ORDER BY q.Q ASC) ELSE NULL END AS mod_row
        //         FROM
        //             q
        //         INNER JOIN customer ON q.customer_ID = customer.customer_ID
        //         WHERE
        //             q.Q >= {$_POST["queue"]}
        //         ORDER BY
        //             q.Q ASC
        //     ) AS subOne
        //     WHERE
        //         mod_row IS NOT NULL
        // ";
        // $result = $conn->query($sql);
        // if ($result->num_rows > 0) {
        //     $i = 0;
        //     while ($row = mysqli_fetch_array($result)) {
        //         $payload["body"]["to"] = $row["customer_name"];
        //         $payload["body"]["messages"][0]["type"] = "text";
        //         $payload["body"]["messages"][0]["text"] = "เหลืออีก {$row["mod_row"]} จะถึงคิวของท่าน";
        //         pushMessage($payload);
        //         $i++;
        //     }
        // }
    }

    echo json_encode(array("code" => 200, "status" => "OK"));
    http_response_code(200);

    function pushMessage($payload){
       $strUrl = "https://api.line.me/v2/bot/message/push";
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $strUrl);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $payload["headers"]);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload["body"]));
       curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
       $result = curl_exec($ch);
       curl_close ($ch);
    }

?>