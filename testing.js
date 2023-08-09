
// Read the JSON file
fetch('C:\Users\AG\Desktop\robs_stuff\API_Project\ahs.json')
    .then(response => response.json())
    .then(data => {
        // Iterate over each object in the JSON array
        data.forEach(obj => {
            // Convert the object to JSON string
            const json = JSON.stringify(obj);

            // Send the POST request with credentials
            fetch('https://adminc.pro-matters.com/api/V2/Authentication', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + createToken('rsdAdmin', 'APIpassword23', '7b4c94dc-b43e-4a2d-833a-58a6456148a2', '1807430a-9b0c-4a10-8ad8-341616d058fa') // Edit with new credentials as needed
                },
                body: json
            })
                .then(response => response.json())
                .then(responseData => {
                    // Handle the response data
                    console.log(responseData);
                })
                .catch(error => {
                    // Handle any errors that occurred during the POST request
                    console.error('Error:', error);
                });
        });
    })
    .catch(error => {
        // Handle any errors that occurred while reading the JSON file
        console.error('Error:', error);
    });

// Function to create the authentication token
function createToken(username, password, subscriberId, consumerId) {
    const credentials = username + ':' + password;
    const encodedCredentials = btoa(credentials);
    const token = subscriberId + ':' + consumerId;

    return encodedCredentials + ':' + btoa(token);
}