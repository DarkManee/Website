var whitelistControlRequest = (requestBody) => {
    
    /* 
        Whitelist Controller Request: 

            This part of the Whitelist API controls the core parts of the whitelist.
            This includes getting information about a whitelisted user, weather a HWID is whitelisted or not, etc.
            Not to mention, you can remotely add/modify a whitelist from here.
            Examples of how to use this endpoint of this api are below, after the function definition.
    */

    return new Promise(Resolve => {
        fetch("api/whitelist_control.php", {
            method: "POST",
            headers: {
                    "Content-Type": "application/json"
            },
            body: JSON.stringify(requestBody)
        }).then(apiResponse => apiResponse.json().then(jsonResponse => {
            Resolve(jsonResponse);
        }))
    })

}


// Fetching user whitelist information examples.

    whitelistControlRequest({
        "action": "checkWhitelist",
        "type": "HWID",
        "input": "example_hwid"
    }).then(apiResponse => {
        console.log(apiResponse);
    })

    whitelistControlRequest({
        "action": "checkWhitelist",
        "type": "Discord",
        "input": "example_discord"
    }).then(apiResponse => {
        console.log(apiResponse);
    })

// Modifying user whitelist information examples.
      
    whitelistControlRequest({
        "action": "modifyWhitelist",
        "type": "blacklist",
        "input": "example_hwid"
    }).then(apiResponse => {
        console.log(apiResponse);
    })

    whitelistControlRequest({
        "action": "modifyWhitelist",
        "type": "unblacklist",
        "input": "example_hwid"
    }).then(apiResponse => {
        console.log(apiResponse);
    })

    whitelistControlRequest({
        "action": "modifyWhitelist",
        "type": "changehwid",
        "input": {
            "HWID": "example_hwid",
            "NewHWID": "new_example_hwid"
        }
    }).then(apiResponse => {
        console.log(apiResponse);
    })

    whitelistControlRequest({
        "action": "modifyWhitelist",
        "type": "changediscord",
        "input": {
            "HWID": "example_hwid",
            "NewDiscordID": "new_example_discord"
        }
    }).then(apiResponse => {
        console.log(apiResponse);
    })

    whitelistControlRequest({
        "action": "modifyWhitelist",
        "type": "addtowhitelist",
        "input": {
            "HWID": "new_hwid",
            "Discord": "new_discord"
        }
    }).then(apiResponse => {
        console.log(apiResponse);
    })
