<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>

<?php

session_start();

/*
 * Including Header and Nav bar
 */
include_once '../model/header.php';
include_once '../view/nav.php';
require_once '../model/database.php';
require_once '../model/user.php';
/*
 * Cheaking if api key exists
 */
if (isset($_SESSION['apikey'])) {
    
} else {
    $api_key = null;
}
/*
 * Make user login 
 */
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL)
        $action = 'show_login';
}

// Check if the user is already logged in
if (isset($_SESSION["login"])) {
    if ($action == 'show_login' || $action == 'show_register') {
        // Redirect the user to a different page or display an error message
        header("location: ?action=home"); // Redirect to a suitable page
        exit; // Stop further execution of the script
    }
}

//deny 
if (isset($_SESSION["member"])) {
    if ($_SESSION["member"] == 0) {
        if ($action == 'fetch_cocktail_by_name' || $action == 'fetch_cocktail_by_category_and_ingredient') {
            // Redirect the user to a different page or display an error message
            header("location: ?action=home");
            exit;
        }
    }
}

switch ($action) {
    /*
     * Decided no need for home and can just put it in request_token and user can see all the text and everything else in there too
     */
    case 'home':
        header("location: ?action=request_token");
        break;
    case 'updateProfile':
        include "../view/updateMember.php";
        break;
    case 'upgradeProfile':
        upgradeToPremium($_SESSION["username"]);

        $username = $_SESSION["username"];
        $api = trim($_SESSION["apikey"]);

        $basicUrl = "http://localhost/Repeat/JWTServer/controller/index.php";
        $Service = "?Service=Upgrade_to_premium&apikey=$api" . "&username=" . urlencode($username);

        
// Set up cURL to call the server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $basicUrl . $Service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);


        header("location: ?action=logout"); //logout caus session variables can be reseted 
        break;
    case'show_register':
        include "../view/register.php";
        break;
    case'show_login':
        include "../view/login.php";
        break;
    case 'register';

        $basicUrl = "http://localhost/Repeat/JWTServer/controller/index.php";
        $Service = "?Service=register"; // The endpoint for the server

        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $member = filter_input(INPUT_POST, 'member');

// Build the data array to be sent as POST fields
        $data = array(
            'username' => $username,
            'password' => $password,
            'member' => $member
        );

// Initialize CURL
        $ch = curl_init();

// Set CURL options
        curl_setopt($ch, CURLOPT_URL, $basicUrl . $Service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        curl_setopt($ch, CURLOPT_POST, true); // Set as POST request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Set POST data

        $reply = curl_exec($ch);
        curl_close($ch);

        if ($reply == true) {
            Signup($username, $password, $member);

            header("Location: ?action=show_login");
            echo "Registered"; // You might want to use swal or similar for alerts
        } else {
            echo "Not Registered"; // You might want to use swal or similar for alerts
        }


// Debugging information
//        echo "Reply: " . $reply . "<br>";
//        echo "Username: " . $username . "<br>";
//        echo "Password: " . $password . "<br>";
//        echo "Member: " . $member . "<br>";

        break;
    case 'login':

        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        if (check_user($username, $password)) {
            $_SESSION["login"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
            $_SESSION["member"] = getMembeType($username);

            header("location: ?action=home");
        } else {
            echo "wrong username or password please retry";
            include "../view/login.php";
        }
        break;
    case 'request_token':
        $basicUrl = "http://localhost/Repeat/JWTServer/controller/index.php";

        $Service = "?Service=Request_key&username=" . urlencode($_SESSION["username"]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $basicUrl . $Service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $reply = curl_exec($ch);
        curl_close($ch);

        // Store the received API key in session
        $_SESSION["apikey"] = $reply;
        include "../view/home.php";
        echo "<p class = container>Your API key:", $_SESSION["apikey"], "</p>";
        $api = trim($_SESSION["apikey"]); //for some reason there was a space so i removed it  using trm only took me 1 hour to figure it out :)

        $basicUrl = "http://localhost/Repeat/JWTServer/controller/index.php";
        $service = "?Service=Retrieve_premium_date&apikey=$api";
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $basicUrl . $service);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch1, CURLOPT_HEADER, FALSE);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $response = curl_exec($ch1);
        curl_close($ch1);
        $premiumDate = json_decode($response, true);

        // Store premium date in session
        if ($premiumDate !== null) {

            $_SESSION["premiumDate"] = $premiumDate;
        } else {
            echo '<br>Unable to retrieve premium date';
        }

        break;

    case 'fetch_random_cocktail':
        $basicUrl = "http://localhost/Repeat/JWTServer/controller/index.php";
        $api = trim($_SESSION["apikey"]); //for some reason there was a space so i removed it  using trm only took me 1 hour to figure it out :)
        // Append the API key to the service URL
        $Service = "?Service=Get_random&apikey=$api";
        // Set up cURL to call the server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $basicUrl . $Service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response
        $cocktailData = json_decode($response, true);
        include '../view/randomCocktail.php';
        break;

    case 'search_by_name':
        include '../view/cocktailbyName.php';
        break;

    case 'fetch_cocktail_by_name':
        $basicUrl = "http://localhost/Repeat/JWTServer/controller/index.php";
        //$cocktailName = "Margarita"; // Replace with the desired cocktail name
        $cocktailName = filter_input(INPUT_POST, 'cocktailName', FILTER_SANITIZE_SPECIAL_CHARS);

        // If the cocktail name is empty, set a default value
        if (empty($cocktailName)) {
            $cocktailName = "harsh"; // No cocktail starts with my name sadly so i stick to this :( 
        }
        $Service = "?Service=Get_cocktail_by_name&cocktailName=" . urlencode($cocktailName);

        // Fetch cocktail data using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $basicUrl . $Service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response
        $cocktailData = json_decode($response, true);

        include '../view/cocktailbyName.php';

        break;

    case 'fetch_cocktail_by_category_and_ingredient':
        $basicUrl = "http://localhost/Repeat/JWTServer/controller/index.php";
        // Get user input for category and ingredient
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
        $ingredient = filter_input(INPUT_POST, 'ingredient', FILTER_SANITIZE_SPECIAL_CHARS);

        // Set default values if inputs are empty
        if (empty($category)) {
            $category = "Ordinary Drink"; // Default category
        }
        if (empty($ingredient)) {
            $ingredient = "Vodka"; // Default ingredient
        }

        $Service = "?Service=Get_cocktail_by_category_and_ingredient&category=" . urlencode($category) . "&ingredient=" . urlencode($ingredient);

        // Fetch cocktail data using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $basicUrl . $Service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response
        $cocktailData = json_decode($response, true);

        // Include the appropriate view file to display the cocktail data
        include '../view/cocktailByCategoryAndIngredient.php';

        break;

    case 'logout':
        session_start();
        session_unset();
        session_destroy();
        header("location: ?action=show_login");
        break;

    default:
        echo '<main class=" text-center mt-3">';
        echo '<h4 class="alert-heading">Uh-oh! Familiar Error Code: 404</h4>';
        echo '<p>Oops! You\'ve wandered into unfamiliar territory. Please use the navigation to find your way back to civilization.</p>';
        echo '</main>';
        break;
}

include_once '../view/footer.php';
