<?php
    require("config.php");
    require("connect.php");
    
    if ( !empty($contents) ) {
        $request = json_decode($contents, true);
        file_put_contents("log.txt", "\n".$contents, FILE_APPEND);   
        
        $type = $request["events"][0]["type"];
        $replyToken = $request["events"][0]["replyToken"];
        $lineId = $request["events"][0]["source"]["userId"];
        $text = $request["events"][0]["message"]["text"];
        
        if ($type == "follow") {
            $sql = "
                INSERT INTO customer (customer_value, customer_ID, customer_name, customer_phone)
                VALUES(
                    1,
                    1,
                    '$lineId',
                    0
                )
            ";
            $result = $conn->query($sql);
        }

        if ($type == "message" && $text == "คิวของฉัน") {
            $queue = 123;
            $payload["headers"] = ["Content-Type: application/json", "Authorization: Bearer {$accessToken}"];
            $payload["body"]["replyToken"] = $replyToken;
            $payload["body"]["messages"][0] = templateMessage("เหลืออีก 5 จะถึงคิวของท่าน");
            replyMessage($payload);

            // $sql = "
            //     SELECT
            //         *
            //     FROM (
            //         SELECT
            //             q.Q,
            //             customer.*,
            //             ROW_NUMBER() OVER(ORDER BY q.Q ASC) AS num_row,
            //             CASE WHEN MOD(ROW_NUMBER() OVER(ORDER BY q.Q ASC), 3) = 0 THEN ROW_NUMBER() OVER(ORDER BY q.Q ASC) ELSE NULL END AS mod_row
            //         FROM
            //             q
            //         INNER JOIN customer ON q.customer_ID = customer.customer_ID
            //         WHERE
            //             q.Q >= $queue
            //         ORDER BY
            //             q.Q ASC
            //     ) AS subOne
            //     WHERE
            //         subOne.customer_name = '{$lineId}'
            //     ORDER BY
            //         num_row DESC
            //     LIMIT 0,1
            // ";
            // $result = $conn->query($sql);
            // if ($result->num_rows > 0) {
            //     $row = mysqli_fetch_array($result);
            //     $payload["headers"] = ["Content-Type: application/json", "Authorization: Bearer {$accessToken}"];
            //     $payload["body"]["replyToken"] = $replyToken;
            //     $payload["body"]["messages"][0] = templateMessage("เหลืออีก {$row["num_row"]} จะถึงคิวของท่าน");
            //     replyMessage($payload);
            // }
        }
    }

    function replyMessage($payload) {
        $strUrl = "https://api.line.me/v2/bot/message/reply";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $strUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $payload["headers"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload["body"]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($ch);
        curl_close ($ch);
    }

    function templateMessage($message) {
        return array(
            "type" => "text",
            "text" => $message
        );
    }
    http_response_code(200);
?>