var keyManagementRequest = (requestBody) => {

    /* 
        Key Manager Request: 

            This part of the api can help you automate sales.
            You can redeem and generate keys (grab them from the available keys) from this endpoint.
            Examples of how to use this endpoint of this api are below, after the function definition.

    */

    return new Promise(Resolve => {
        fetch("api/key_manager.php", {
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

// General key management.

    keyManagementRequest({
        "action": "redeem",
        "input": {
            "key": "test_key",
            "customer-info": {
                "HWID": "customer_new_hwid",
                "discord-name": "Not Pnyx",
                "discord-descriminator": "6950",
                "discord-id": "358405207945641984"
            }
        }
    }).then(apiResponse => {
        console.log(apiResponse);
    })

    keyManagementRequest({
        "action": "getkey"
    }).then(apiResponse => {
        console.log(apiResponse);
    })
