
<?php

require '../model/database.php';
require '../model/users.php';
require_once 'JWT_class.php';
$secret = "Harsh";
$api_key = filter_input(INPUT_GET, 'api_key');
$Service = filter_input(INPUT_GET, 'Service');

if ($api_key == Null) {
    if ($Service != "Request_key" && $Service != "register" && $Service != "Get_random" && $Service != "Get_cocktail_by_name" && $Service != "Get_cocktail_by_category_and_ingredient") {
        // || $Service != "Register" || $Service != "Service1" || $Service != "Service2"
        $response = "OOps";
        echo json_encode($response) . "c";
        exit;
    }
}
//$cocktailData = getRandomCocktailData();
switch ($Service) {
    case'Request_key':
        require_once 'JWT_class.php';
        $token = array();
        $id = 20;
        $token['id'] = $id;
        $jwt = JWT::encode($token, 'secret_server_key');
        echo $jwt;
        break;

    case 'Get_random':
        // Call the function to get cocktail data
        $cocktailData = getRandomCocktailData();
        //var_dump($cocktailData);
        // Send the cocktail data as JSON response
        echo json_encode($cocktailData);
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

        // Debugging: Output the result of Signup function
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

