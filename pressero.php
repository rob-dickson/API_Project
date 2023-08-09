<?php
function pressero_request($path, $method = 'GET', $payload = null, $token = null) {
    $headers = array('Content-Type: application/json');

    if($token != null) {
        array_push($headers, 'Authorization: token '. $token);
    }

    $jsonData = null;
    if($payload != null) {
        $jsonData = json_encode($payload);
        array_push($headers, 'Content-Length: ' . strlen($jsonData));
    }

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, 'https://admin.chi.v6.pressero.com/api' . $path);
    curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($request, CURLOPT_HEADER, true);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    switch(strtoupper($method)) {
        case 'GET':
            curl_setopt($request, CURLOPT_HTTPGET, true);
            break;
        default:
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            break;
    }

    if(strtoupper($method) != 'GET' && $jsonData != null) {
        curl_setopt($request, CURLOPT_POSTFIELDS, $jsonData);
    }

    $rawResponse = curl_exec($request);
    $httpCode = curl_getinfo($request, CURLINFO_HTTP_CODE);

    $jsonResponse = null;
    if(200 <= $httpCode && $httpCode < 300)
    {
        $header_size = curl_getinfo($request, CURLINFO_HEADER_SIZE);
        $responseHeader = substr($rawResponse, 0, $header_size);
        $responseBody = substr($rawResponse, $header_size);
        $jsonResponse = json_decode($responseBody, true);
    } else {
        $jsonResponse = false;
    }
    curl_close($request);
    return $jsonResponse;
}


echo "1. Obtaining Token\n";
$credentials = array(
    'UserName' => 'rdickson@alphagraphics.com',
    'Password' => '1234',
    'SubscriberID' => '7b4c94dc-b43e-4a2d-833a-58a6456148a2',
    'ConsumerID' => '1807430a-9b0c-4a10-8ad8-341616d058fa'
);

$authResponse = pressero_request('/v2/authentication', 'POST', $credentials);
$token = $authResponse["Token"];
echo "Token: ".$token."\n";

static dynamic PresseroRequest(string path, string method = "GET", object payload = null, string token = null)
{
    var client = new System.Net.WebClient();
    client.Headers.Add(System.Net.HttpRequestHeader.ContentType, "application/json");
    if (!string.IsNullOrEmpty(token))
        client.Headers.Add(System.Net.HttpRequestHeader.Authorization, $"token {token}");

    byte[] responseBytes = null;

    try
    {
        var url = $"https://admin.chi.v6.pressero.com/api/{path.TrimStart('/')}";
        if (!"GET".Equals(method, StringComparison.InvariantCultureIgnoreCase))
        {
            byte[] payloadBytes = null;
            if (payload != null)
            {
                var payloadJson = Newtonsoft.Json.JsonConvert.SerializeObject(payload);
                payloadBytes = System.Text.Encoding.UTF8.GetBytes(payloadJson);
            }
            responseBytes = client.UploadData(url, method, payloadBytes);
        }
        else
        {
            responseBytes = client.DownloadData(url);
        }
    }
    catch (System.Net.WebException ex)
    {
        var status = (ex.Response as System.Net.HttpWebResponse)?.StatusCode;
        if (status.HasValue)
            System.Diagnostics.Debug.WriteLine($"Response code from request to {path}: {status}");
    }

    if (responseBytes == null)
    {
        return null;
    }

    var responseJson = System.Text.Encoding.UTF8.GetString(responseBytes);
    return Newtonsoft.Json.JsonConvert.DeserializeObject<dynamic>(responseJson);
}


Console.WriteLine("1. Obtaining Token");
var credentials = new
{
    var UserName => "APIAdmin",
    var Password => "APIpassword",
    var SubscriberID => Guid.Parse("7b4c94dc-b43e-4a2d-833a-58a6456148a2"),
    var ConsumerID => Guid.Parse("1807430a-9b0c-4a10-8ad8-341616d058fa")
};

var authResponse = PresseroRequest("/v2/authentication", "POST", credentials);
string token = authResponse.Token;
Console.WriteLine($"Token: {token}");

echo "2. Fetch Orders\n";
$searchOrderFilter = array(
    'StartDate' => '2019-01-01 00:00:00Z',
    'EndDate' => '2019-03-01 00:00:00Z'
);

$orders = pressero_request('/v2/orders/search?pageNumber=0&pageSize=10', 'POST', $searchOrderFilter, $token);
for($i = 0; $i < $orders['TotalItems']; $i++) {
    $order = $orders['Items'][$i];
    echo $order['OrderNumber'].'-'.$order['OrderItemNumber']."\n";
}

Console.WriteLine("2. Fetch Orders");
var searchOrderFilter = new
{
    StartDate = new DateTime(2019, 01, 01).ToString("u"),
    EndDate = new DateTime(2019, 03, 01).ToString("u")
};

var orders = PresseroRequest("/v2/orders/search?pageNumber=0&pageSize=10", "POST", searchOrderFilter, token);
for (int i = 0; i < (int)orders.TotalItems; i++)
{
    var order = orders.Items[i];
    Console.WriteLine($"{order.OrderNumber}-{order.OrderItemNumber}");
}

echo "3. Read Order Item\n";
$orderNumber = 1;
$orderItemSeq = 2;
$orderItem = pressero_request("/v2/orders/$orderNumber/Item/$orderItemSeq/Details", "GET", null, $token);
echo "Price for Order Item $orderNumber-$orderItemSeq: $" . $orderItem["Price"];

Console.WriteLine("3. Read Order Item");
var orderNumber = 1;
var orderItemSeq = 2;
var orderItem = PresseroRequest($"/v2/orders/{orderNumber}/Item/{orderItemSeq}/Details", "GET", null, token);
Console.WriteLine($"Price for Order Item {orderNumber}-{orderItemSeq}: ${orderItem.Price}");

echo "4. List Users\n";
$users = pressero_request('/users', 'GET', null, $token);
for($i = 0; $i < $users['TotalItems']; $i++) {
    $user = $users['Items'][$i];
    echo "[".$user["UserId"]."] ".$user['FullName']." (".$user['Email'].")\n";
}

Console.WriteLine("4. List Users");
var users = PresseroRequest($"/users", "GET", null, token);
for (int i = 0; i < (int)users.TotalItems; i++)
{
    var user = users.Items[i];
    Console.WriteLine($"[{user.UserId}] {user.FullName} ({user.Email})");
}

echo "5. Update User\n";
$userId = "858f2d2a-83e5-49ed-9234-b792c5eb193d"; //Webinar 2019
$updatedUserData = array(
    'LastName' => '2019 - php'
);
pressero_request("/users/$userId", "PUT", $updatedUserData, $token);
$updatedUser = pressero_request("/users/$userId", "GET", null, $token);
echo "[".$updatedUser["UserId"]."] ".$updatedUser['FullName']." (".$updatedUser['Email'].")\n";

Console.WriteLine("5. Update User");
var userId = Guid.Parse("858f2d2a-83e5-49ed-9234-b792c5eb193d"); //Webinar 2019
var updatedUserData = new
{
    LastName = "2019 - dotnet"
};
PresseroRequest($"/users/{userId}", "PUT", updatedUserData, token);
var updatedUser = PresseroRequest($"/users/{userId}", "GET", null, token);
Console.WriteLine($"[{updatedUser.UserId}] {updatedUser.FullName} ({updatedUser.Email})");

