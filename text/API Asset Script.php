<?php

require 'vendor/autoload.php'; // Include the autoload file from the PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to authenticate and obtain access token
function getAccessToken() {
    // Authentication API endpoint
    $url = "https://admin.chi.v6.pressero.com/api/V2/Authentication";
    
    // Authentication credentials
    $userName = "admin";
    $password = "admin";
    $subscriberId = "00000000-0000-0000-0000-000000000000";
    $consumerId = "00000000-0000-0000-0000-000000000000";
    
    $data = array(
        "UserName" => $userName,
        "Password" => $password,
        "SubscriberId" => $subscriberId,
        "ConsumerID" => $consumerId
    );
    
    $payload = json_encode($data);
    
    $headers = array(
        "Content-Type: application/json"
    );
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    if ($httpCode === 200) {
        $responseData = json_decode($response, true);
        $accessToken = $responseData["Token"];
        return $accessToken;
    } else {
        return null;
    }
}

// getAccessToken()

// console.log($accessToken);


// Function to create an asset
function createAsset($accessToken, $assetData) {
    // Asset creation API endpoint
    $url = "https://admin.chi.v6.pressero.com/api/site/{domain}/Assets";
    
    $payload = json_encode($assetData);
    
    $headers = array(
        "Content-Type: application/json",
        "Authorization: Token " . $accessToken
    );
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    if ($httpCode === 201) {
        $responseData = json_decode($response, true);
        $assetId = $responseData["AssetId"];
        return $assetId;
    } else {
        return null;
    }
}

// Usage example
$accessToken = getAccessToken();

if ($accessToken !== null) {
    // Excel sheet data retrieval loop
    $file = "path/to/your/excel/sheet.xlsx";
    $worksheet = "Sheet1"; // Update with the appropriate worksheet name
    
    // Load the Excel file
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getSheetByName($worksheet);
    
    // Start from the second row (assuming the first row is the header)
    $row = 2;
    
    if ($accessToken !== null) {
        // Excel sheet data retrieval loop
        $file = "path/excel/sheet.xlsx";
        $worksheet = "Sheet1"; // Update with the appropriate worksheet name
        
        // Load the Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getSheetByName($worksheet);
        
        // Start from the second row (assuming the first row is the header)
        $row = 2;
        
        //Array to store succussful and failed assets
        $successes = [];
        $fail= [];

        while (!empty($sheet->getCell("A" . $row)->getValue())) {
            // Retrieve asset data from Excel sheet
            $assetName = $sheet->getCell("A" . $row)->getValue
            $assetDescription = $sheet->getCell("B" . $row)->getValue();
            $assetPath = $sheet->getCell("C" . $row)->getValue();
            $userDownloadable = $sheet->getCell("D" . $row)->getValue();
            $isLocal = $sheet->getCell("E" . $row)->getValue();
            $assetType = $sheet->getCell("F" . $row)->getValue();
            $assetUsage = $sheet->getCell("G" . $row)->getValue();
            $thumbnailPath = $sheet->getCell("H" . $row)->getValue();
            $generateThumbnail = $sheet->getCell("I" . $row)->getValue();
            $groupId = $sheet->getCell("J" . $row)->getValue();
            $productId = $sheet->getCell("K" . $row)->getValue();
            $assetCategoryId = $sheet->getCell("L" . $row)->getValue();
            
            // Construct the asset data array
            $assetData = array(
                "Name" => $assetName,
                "Description" => $assetDescription,
                "Path" => $assetPath,
                "UserDownloadable" => $userDownloadable,
                "IsLocal" => $isLocal,
                "AssetType" => $assetType,
                "AssetUsage" => $assetUsage,
                "ThumbnailPath" => $thumbnailPath,
                "GenerateThumbnailForExternalURL" => $generateThumbnail
            );
            
            // Add optional parameters if not empty
            if (!empty($groupId)) {
                $assetData["GroupId"] = $groupId;
            }
            if (!empty($productId)) {
                $assetData["ProductId"] = $productId;
            }
            if (!empty($assetCategoryId)) {
                $assetData["AssetCategoryId"] = $assetCategoryId;
            }
            
            // Create the asset using the API
            $assetId = createAsset($accessToken, $assetData);
            
            if ($assetId !== null) {
                echo "Asset created successfully. Asset ID: " . $assetId . "\n";
            } else {
                echo "Failed to create asset.\n";
            }
            
            // Move to the next row
            $row++;
        }

         // Output the results
        echo "Successes:\n";
        foreach ($successes as $success) {
            echo $success . "\n";
    }

        echo "\nFailures:\n";
        foreach ($failures as $failure) {
            echo $failure . "\n";
    }
    //output for failed authentication
    } else {
        echo "Authentication failed.\n";
    }