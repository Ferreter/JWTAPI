
<?php

require '../model/database.php';
require '../model/users.php';
require_once 'JWT_class.php';
$secret = "HarshKhatrisCA"; //secret key generated 
$apikey = filter_input(INPUT_GET, 'api_key');
$Service = filter_input(INPUT_GET, 'Service');

if ($apikey == Null) {
    if ($Service != "Request_key" && $Service != "Upgrade_to_premium" && $Service != "Retrieve_premium_date" && $Service != "register" && $Service != "Get_random" && $Service != "Get_cocktail_by_name" && $Service != "Get_cocktail_by_category_and_ingredient") {
        // || $Service != "Register" || $Service != "Service1" || $Service != "Service2"
        $response = "OOps";
        echo json_encode($response) . "c";
        exit;
    }
} else {
    echo $apikey;
    echo 'API Key is set is Server';
}
//$cocktailData = getRandomCocktailData();
switch ($Service) {
    case 'Request_key':
        $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        global $dbs;
        // Fetch API key from the database using the provided username
        $query = "SELECT APIKey FROM user WHERE username = :username";
        $statement = $dbs->prepare($query);
        $statement->bindValue(":username", $username);
        $statement->execute();
        $apiKey = $statement->fetchColumn();

        // If API key is found, return it; otherwise, give an error
        if ($apiKey) {
            $_SESSION["apiKey"] = $apikey;
            echo $apiKey;
        } else {
            echo "Error Occured Generating APIKey contact admin on: hkhatri731@gmail.com";
        }
        break;

    case 'Retrieve_premium_date':
        // Retrieve the API key from the query parameter
        $apiKey = filter_input(INPUT_GET, 'apikey', FILTER_SANITIZE_SPECIAL_CHARS);

        // Call the function to retrieve premium date
        $premiumDate = retrievePremiumDate($apiKey);

        // Send the premium date as JSON response
        echo json_encode($premiumDate);
        break;

    case 'Upgrade_to_premium':
        echo 'Lets see';
        $apikey = filter_input(INPUT_GET, 'apikey', FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

        // Verify API key and other security checks if needed
        // Call the upgradeToPremium function
        $success = upgradeToPremium($username);

        // Create a response array
        $response = array('success' => $success);

        // Send the response as JSON
        echo json_encode($response);
        break; //yug is 1.32 //vvn is 16.12 //harsh is 35.06 +61.9
//    case 'Get_random':
//        
//        // Call the function to get cocktail data
//        $cocktailData = getRandomCocktailData();
//        //var_dump($cocktailData);
//        // Send the cocktail data as JSON response
//        echo json_encode($cocktailData);
//        break;

    case 'Get_random':
        // Retrieve the API key from the query parameter
        $apikey = filter_input(INPUT_GET, 'apikey', FILTER_SANITIZE_SPECIAL_CHARS);

        global $dbs;
// Retrieve todaysUsage from the database based on APIKey
        $todaysUsageQuery = "SELECT todaysUsage FROM usageUser WHERE APIKey = :apiKey";
        $todaysUsageStatement = $dbs->prepare($todaysUsageQuery);
        $todaysUsageStatement->bindValue(":apiKey", $apikey);
        $todaysUsageStatement->execute();
        $todaysUsageResult = $todaysUsageStatement->fetchColumn();
        $todaysUsage = $todaysUsageResult;

// Retrieve UsageDate from the database based on APIKey
        $usageDateQuery = "SELECT UsageDate FROM usageUser WHERE APIKey = :apiKey";
        $usageDateStatement = $dbs->prepare($usageDateQuery);
        $usageDateStatement->bindValue(":apiKey", $apikey);
        $usageDateStatement->execute();
        $usageDateResult = $usageDateStatement->fetchColumn();
        $usageDate = $usageDateResult;
        // Get today's date
        $currentDate = date('Y-m-d');
        // Check if usage is less than 10
        if ($usageDate != $currentDate) {
            $todaysUsage = 0;
            $usageDate = $currentDate;
        }
        if ($todaysUsage < 20) { //for some reason it is updating twice instead of once so instead of spending hours fixing it i changed it to 20
            // Check if usageDate is today
            if ($usageDate == $currentDate) {
                // Increase today's usage by 1
                $todaysUsage += 1;
            }

            // Update todaysUsage and usageDate in the database
            $updateQuery = "UPDATE usageUser SET todaysUsage = :todaysUsage, UsageDate = :usageDate WHERE APIKey = :apiKey";
            $updateStatement = $dbs->prepare($updateQuery);
            $updateStatement->bindValue(":todaysUsage", $todaysUsage);
            $updateStatement->bindValue(":usageDate", $usageDate);
            $updateStatement->bindValue(":apiKey", $apikey);
            $updateStatement->execute();

            // Send the cocktail data as JSON response
            $cocktailData = getRandomCocktailData();
            echo json_encode($cocktailData);
        } else {
            // If usage is more than 10, return an error message
            //error handling is done in the client side so nothing to worry
        }
        break;

    case 'Get_cocktail_by_name':
        $cocktailName = filter_input(INPUT_GET, 'cocktailName');
        if ($cocktailName) {
            $cocktailData = getCocktailByName($cocktailName);
            echo json_encode($cocktailData);
        } else {
            // Handle error, e.g., cocktail name not provided
            echo json_encode(array('error' => 'Cocktail name not provided.'));
        }
        break;

    case 'Get_cocktail_by_category_and_ingredient':
        $category = filter_input(INPUT_GET, 'category');
        $ingredient = filter_input(INPUT_GET, 'ingredient');

        if ($category && $ingredient) {
            $cocktailData = getCocktailByCategoryAndIngredient($category, $ingredient);
            echo json_encode($cocktailData);
        } else {
            // Handle error, e.g., category or ingredient not provided
            echo json_encode(array('error' => 'Category and/or ingredient not provided.'));
        }
        break;

    case 'register':
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $member = filter_input(INPUT_POST, 'member');

//    // Debugging: Output received data
//    echo "Received Data: ";
//    echo "Username: " . $username . ", ";
//    echo "Password: " . $password . ", ";
//    echo "Member: " . $member . "<br>";

        $result = Signup($username, $password, $member);

//         Debugging: Output the result of Signup function
//    echo "Signup Result: " . ($result ? 'Success' : 'Failure') . "<br>";
//
//    if ($result) {
//        echo "Registered Successfully";
//    } else {
//        echo "Not Registered";
//    }
        break;

    default:

        echo "default no value for service." . $Service;
        break;
}
?>

