<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function check_user($username, $password) {
    global $dbs;
    $query = "SELECT * FROM user WHERE username = :username AND " . "password = :password";
    $statement = $dbs->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $password);
    try {
        $statement->execute();
    } catch (Exception $ex) {
        header("Location:../view/error.php?msg=" . $ex->getMessage());
        exit();
    }
    $count = $statement->rowCount();
    if ($count != 1) {
        return FALSE;
    }

    return TRUE;
}

function Signup($username, $password, $member) {
    global $dbs;
    try {
        // Generate API Key using JWT
        require_once 'JWT_class.php';
        $token = array();
        $token['id'] = uniqid(); // Generating a unique ID for API Key
        $apiKey = JWT::encode($token, 'secret_server_key');

        // Calculate Premium date based on member type
        $premiumDate = ($member == 1) ? date('Y-m-d', strtotime('+30 days')) : date('Y-m-d');

        $query = "INSERT INTO user (username, password, memberType, APIKey) VALUES (:username, :password, :memberType, :APIKey)";
        $statement = $dbs->prepare($query);
        $statement->bindValue(":username", $username);
        $statement->bindValue(":password", $password);
        $statement->bindValue(":memberType", $member);
        $statement->bindValue(":APIKey", $apiKey); // Insert API Key value
        $statement->execute();

        // Insert usage data into usage table
        $usageQuery = "INSERT INTO usageUser (APIKey, todaysUsage, UsageDate, PremiumDate) VALUES (:APIKey, 0, CURDATE(), :premiumDate)";
        $usageStatement = $dbs->prepare($usageQuery);
        $usageStatement->bindValue(":APIKey", $apiKey);
        $usageStatement->bindValue(":premiumDate", $premiumDate);
        $usageStatement->execute();

        $rowCount = $usageStatement->rowCount();
        if ($rowCount < 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        header("Location:../view/error.php?msg=" . $ex->getMessage());
    }
}

function getRandomCocktailData() {
    $apiUrl = "https://www.thecocktaildb.com/api/json/v1/1/random.php";

    $response = file_get_contents($apiUrl);

    $data = json_decode($response, true);

    $cocktailData = array(); // Store cocktail data in this array

    if (isset($data['drinks']) && is_array($data['drinks'])) {
        $drink = $data['drinks'][0]; // Assuming there's only one drink in the response

        $cocktailData = array(
            'id' => $drink['idDrink'],
            'name' => $drink['strDrink'],
            'category' => $drink['strCategory'],
            'alcoholic' => $drink['strAlcoholic'],
            'glass' => $drink['strGlass'],
            'instructions' => $drink['strInstructions'],
            'thumb' => $drink['strDrinkThumb'],
            'ingredients' => array(),
        );

        // Extract ingredients and measures
        for ($i = 1; $i <= 15; $i++) {
            $ingredientKey = 'strIngredient' . $i;
            $measureKey = 'strMeasure' . $i;

            $ingredient = $drink[$ingredientKey];
            $measure = $drink[$measureKey];

            if (!empty($ingredient)) {
                $cocktailData['ingredients'][] = array(
                    'ingredient' => $ingredient,
                    'measure' => $measure,
                );
            }
        }
    }

    // Debugging: Output neatly organized cocktail data

    return $cocktailData;
}

function getCocktailByName($cocktailName) {
    $apiUrl = "https://www.thecocktaildb.com/api/json/v1/1/search.php?s=" . urlencode($cocktailName);

    $response = file_get_contents($apiUrl);

    $data = json_decode($response, true);

    $cocktails = array(); // Store cocktails data in this array

    if (isset($data['drinks']) && is_array($data['drinks'])) {
        foreach ($data['drinks'] as $drink) {
            $cocktail = array(
                'id' => $drink['idDrink'],
                'name' => $drink['strDrink'],
                'category' => $drink['strCategory'],
                'alcoholic' => $drink['strAlcoholic'],
                'glass' => $drink['strGlass'],
                'instructions' => $drink['strInstructions'],
                'thumb' => $drink['strDrinkThumb'],
                'ingredients' => array(),
            );

            // Extract ingredients and measures
            for ($i = 1; $i <= 15; $i++) {
                $ingredientKey = 'strIngredient' . $i;
                $measureKey = 'strMeasure' . $i;

                $ingredient = $drink[$ingredientKey];
                $measure = $drink[$measureKey];

                if (!empty($ingredient)) {
                    $cocktail['ingredients'][] = array(
                        'ingredient' => $ingredient,
                        'measure' => $measure,
                    );
                }
            }

            $cocktails[] = $cocktail;
        }
    }

    return $cocktails;
}

//// Example usage:
//$cocktailName = "margarita"; // Replace with the desired cocktail name
//$cocktails = getCocktailByName($cocktailName);
//
// Now you can process $cocktails array to display the data
//foreach ($cocktails as $cocktail) {
//    echo "Cocktail Name: " . $cocktail['name'] . "<br>";
//    echo "Category: " . $cocktail['category'] . "<br>";
//    echo "Instructions: " . $cocktail['instructions'] . "<br>";
//    echo "Glass: " . $cocktail['glass'] . "<br>";
//    echo "Ingredients: <br>";
//    foreach ($cocktail['ingredients'] as $ingredient) {
//        echo "- " . $ingredient['measure'] . " " . $ingredient['ingredient'] . "<br>";
//    }
//    echo "<hr>";
//}

function getCocktailByCategoryAndIngredient($category, $ingredient) {
    $categoryApiUrl = "https://www.thecocktaildb.com/api/json/v1/1/filter.php?c=" . urlencode($category);
    $ingredientApiUrl = "https://www.thecocktaildb.com/api/json/v1/1/filter.php?i=" . urlencode($ingredient);

    $categoryResponse = file_get_contents($categoryApiUrl);
    $ingredientResponse = file_get_contents($ingredientApiUrl);

    $categoryData = json_decode($categoryResponse, true);
    $ingredientData = json_decode($ingredientResponse, true);

    $matchingCocktails = array(); // Store matching cocktails in this array

    if (isset($categoryData['drinks']) && isset($ingredientData['drinks'])) {
        foreach ($categoryData['drinks'] as $categoryDrink) {
            foreach ($ingredientData['drinks'] as $ingredientDrink) {
                if ($categoryDrink['idDrink'] === $ingredientDrink['idDrink']) {
                    $matchingCocktails[] = $categoryDrink['idDrink'];
                }
            }
        }
    }

    // Fetch details of matching cocktails
    $cocktails = array();
    foreach ($matchingCocktails as $cocktailId) {
        $cocktailApiUrl = "https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=" . urlencode($cocktailId);
        $cocktailResponse = file_get_contents($cocktailApiUrl);
        $cocktailData = json_decode($cocktailResponse, true);

        if (isset($cocktailData['drinks']) && is_array($cocktailData['drinks'])) {
            foreach ($cocktailData['drinks'] as $drink) {
                $cocktail = array(
                    'id' => $drink['idDrink'],
                    'name' => $drink['strDrink'],
                    'category' => $drink['strCategory'],
                    'alcoholic' => $drink['strAlcoholic'],
                    'glass' => $drink['strGlass'],
                    'instructions' => $drink['strInstructions'],
                    'thumb' => $drink['strDrinkThumb'],
                    'ingredients' => array(),
                );

                // Extract ingredients and measures
                for ($i = 1; $i <= 15; $i++) {
                    $ingredientKey = 'strIngredient' . $i;
                    $measureKey = 'strMeasure' . $i;

                    $ingredient = $drink[$ingredientKey];
                    $measure = $drink[$measureKey];

                    if (!empty($ingredient)) {
                        $cocktail['ingredients'][] = array(
                            'ingredient' => $ingredient,
                            'measure' => $measure,
                        );
                    }
                }

                $cocktails[] = $cocktail; // Add cocktail details to the array
            }
        }
    }

    return $cocktails;
}

// Example usage
//$category = "Ordinary Drink";
//$ingredient = "Vodka";
//
//$matchingCocktails = getCocktailByCategoryAndIngredient($category, $ingredient);
//
//if (!empty($matchingCocktails)) {
//    foreach ($matchingCocktails as $cocktail) {
//        // ... Display other cocktail details ...
//        echo "</div>";
//        echo "Cocktail Name: " . $cocktail['name'] . "<br>";
//        echo "Category: " . $cocktail['category'] . "<br>";
//        echo "Instructions: " . $cocktail['instructions'] . "<br>";
//        echo "Glass: " . $cocktail['glass'] . "<br>";
//        echo "Ingredients: <br>";
//        foreach ($cocktail['ingredients'] as $ingredient) {
//            echo "- " . $ingredient['measure'] . " " . $ingredient['ingredient'] . "<br>";
//        }
//        echo "<hr>";
//    }
//} else {
//    echo "No cocktails found with the specified category and ingredient.";
//}
