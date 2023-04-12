<?php
    $requestData = json_decode(file_get_contents('php://input') , true);
    $requestedAction = $requestData["action"];

    function verifyKey($input)
    {
        $availableKeys = json_decode(file_get_contents("available-keys.json") , true);
        foreach ($availableKeys as $keyValue)
        {
            if ($keyValue == $input)
            {
                return true;
            }
        }
    }

    function checkIfActive($input)
    {
        $activeKeys = json_decode(file_get_contents("active-keys.json") , true);
        foreach ($activeKeys as $keyIndex => $keyValue)
        {
            if ($keyValue["key"] == $input)
            {
                return true;
            }
        }
    }

    if ($requestedAction == "redeem")
    {
        $infoTable = $requestData["input"];
        json_encode($infoTable);
        $requestedKey = $infoTable["key"];
        $customerInfo = $infoTable["customer-info"];
        json_encode($customerInfo);

        if (!checkIfActive($requestedKey))
        {
            if (verifyKey($requestedKey))
            {
                $tempAvailable = json_decode(file_get_contents("available-keys.json") , true);
                $tempActive = json_decode(file_get_contents("active-keys.json") , true);
                array_push($tempActive, ["key" => $requestedKey, "active" => true, "user-info" => ["HWID" => $customerInfo["HWID"], "DiscordName" => $customerInfo["discord-name"] . "#" . $customerInfo["discord-descriminator"], "DiscordID" => $customerInfo["discord-id"]]]);
                file_put_contents("active-keys.json", json_encode($tempActive));
                unset($tempAvailable[array_search($requestedKey, $tempAvailable) ]);
                file_put_contents("available-keys.json", json_encode($tempAvailable));
                echo json_encode(["Status" => "success", "Response" => "Successfully redeemed key!"]);
            }
            else
            {
                echo json_encode(["Status" => "fail", "Response" => "Invalid key."]);
            }
        }
        else
        {
            echo json_encode(["Status" => "fail", "Response" => "This key has already been redeemed."]);
        }

    }
    elseif ($requestedAction == "getkey")
    {
        $availableKeys = json_decode(file_get_contents("available-keys.json") , true);
        echo json_encode(["Status" => "success", "Response" => $availableKeys[ array_rand($availableKeys, 1) ]]);
    }
?>
