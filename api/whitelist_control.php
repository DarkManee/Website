<?php 

    header('Content-Type: application/json');
    $requestData = json_decode(file_get_contents('php://input') , true);
    $whitelistData = json_decode(file_get_contents("whitelist.json") , true);

    function checkForHWID($whitelistData, $hwid)
    {
        foreach ($whitelistData as $whitelistIndex => $whitelistNode)
        {
            if ($whitelistNode["HWID"] == $hwid)
            {
                $tempNode = $whitelistNode;
                $tempNode["NodeIndex"] = $whitelistIndex;
                return $tempNode;
            }
        }
    }

    function checkDiscordWhitelist($whitelistData, $discordId)
    {
        foreach ($whitelistData as $whitelistIndex => $whitelistNode)
        {
            if ($whitelistNode["Discord"] == $discordId)
            {
                $tempNode = $whitelistNode;
                $tempNode["NodeIndex"] = $whitelistIndex;
                return $tempNode;
            }
        }
    }

    if ($requestData["action"] == "checkWhitelist")
    {
        if ($requestData["type"] == "HWID")
        {
            if (checkForHWID($whitelistData, $requestData["input"]))
            {
                echo json_encode(["Status" => "success", "Response" => checkForHWID($whitelistData, $requestData["input"]) ]);
            }
            else
            {
                echo json_encode(["Status" => "fail", "Response" => "Unable to find any whitelisted user under the identifier " . $requestData["input"] . "."]);
            }
        }
        elseif ($requestData["type"] == "Discord")
        {
            if (checkDiscordWhitelist($whitelistData, $requestData["input"]))
            {
                echo json_encode(["Status" => "success", "Response" => checkDiscordWhitelist($whitelistData, $requestData["input"]) ]);
            }
            else
            {
                echo json_encode(["Status" => "fail", "Response" => "Unable to find any whitelisted user under the Discord ID " . $requestData["input"] . "."]);
            }
        }
    }
    elseif ($requestData["action"] == "modifyWhitelist")
    {
        $temporaryJSON = json_decode(file_get_contents("whitelist.json") , true);
        if ($requestData["type"] && $requestData["input"])
        {
            if ($requestData["type"] == "blacklist")
            {
                $userData = checkForHWID($whitelistData, $requestData["input"]);
                $userNode = $temporaryJSON[$userData["NodeIndex"]];
                if (!$userNode["Blacklisted"])
                {
                    $temporaryJSON[$userData["NodeIndex"]]["Blacklisted"] = true;
                    file_put_contents("whitelist.json", json_encode($temporaryJSON));
                    echo json_encode(["Status" => "success", "Response" => "The user with HWID " . $requestData["input"] . " has been blacklisted."]);
                }
                else
                {
                    echo json_encode(["Status" => "fail", "Response" => "The user with HWID " . $requestData["input"] . " is already blacklisted."]);
                }
            }
            elseif ($requestData["type"] == "unblacklist")
            {
                $userData = checkForHWID($whitelistData, $requestData["input"]);
                $userNode = $temporaryJSON[$userData["NodeIndex"]];
                if ($userNode["Blacklisted"])
                {
                    $temporaryJSON[$userData["NodeIndex"]]["Blacklisted"] = false;
                    file_put_contents("whitelist.json", json_encode($temporaryJSON));
                    echo json_encode(["Status" => "success", "Response" => "The user with HWID " . $requestData["input"] . " has been unblacklisted."]);
                }
                else
                {
                    echo json_encode(["Status" => "fail", "Response" => "The user with HWID " . $requestData["input"] . " is not blacklisted."]);
                }
            }
            elseif ($requestData["type"] == "changehwid")
            {
                $inputArray = $requestData["input"];
                json_encode($inputArray);
                $userData = checkForHWID($whitelistData, $inputArray["HWID"]);
                $userNode = $temporaryJSON[$userData["NodeIndex"]];

                if ($userData && $userNode)
                {
                    if ($inputArray)
                    {
                        $temporaryJSON[$userData["NodeIndex"]]["HWID"] = $inputArray["NewHWID"];
                        file_put_contents("whitelist.json", json_encode($temporaryJSON));
                        echo json_encode(["Status" => "success", "Response" => "Successfully changed users HWID."]);
                    }
                    else
                    {
                        echo json_encode(["Status" => "fail", "Response" => "Invalid parameters."]);
                    }
                }
            }
            elseif ($requestData["type"] == "changediscordid")
            {
                $inputArray = $requestData["input"];
                json_encode($inputArray);
                $userData = checkForHWID($whitelistData, $inputArray["HWID"]);
                $userNode = $temporaryJSON[$userData["NodeIndex"]];

                if ($userData && $userNode)
                {
                    if ($inputArray)
                    {
                        $temporaryJSON[$userData["NodeIndex"]]["Discord"] = $inputArray["NewDiscordID"];
                        file_put_contents("whitelist.json", json_encode($temporaryJSON));
                        echo json_encode(["Status" => "success", "Response" => "Successfully changed users Discord ID."]);
                    }
                    else
                    {
                        echo json_encode(["Status" => "fail", "Response" => "Invalid parameters."]);
                    }
                }
            }
            elseif ($requestData["type"] == "addtowhitelist")
            {
                $inputArray = $requestData["input"];
                json_encode($inputArray);

                if ($inputArray)
                {
                    array_push($temporaryJSON, ["HWID" => $inputArray["HWID"], "Blacklisted" => false, "Discord" => $inputArray["Discord"]]);
                    file_put_contents("whitelist.json", json_encode($temporaryJSON));
                    echo json_encode(["Status" => "success", "Response" => "Successfully added data to whitelist."]);
                }
                else
                {
                    echo json_encode(["Status" => "fail", "Response" => "Invalid parameters."]);
                }

            }
        }
    }

?>