<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<main>
    <div class="container mt-5">
        <h1>Search for Cocktails by Category and Ingredient</h1>
        <p>Refine your cocktail search by specifying both a category and an ingredient. 
            Use the dropdown menu to select a category, and choose an ingredient from the list below. 
            Click the "Search" button to find cocktails that belong to the selected category and feature the chosen ingredient. 
            You'll get a list of cocktails that match both criteria, allowing you to explore unique combinations.</p>

        <form class="row g-3" method="POST" action="">
            <div class="col-md-6">
                <label for="category">Select Ingredient:</label>
                <select class="form-control" name="cocktailIngredient">
                    <option value="">Select an Ingredient</option>
                    <?php
                    $ingredients = array(
                        "Light rum", "Applejack", "Gin", "Dark rum", "Sweet Vermouth",
                        "Strawberry schnapps", "Scotch", "Apricot brandy", "Triple sec", "Southern Comfort",
                        "Orange bitters", "Brandy", "Lemon vodka", "Blended whiskey", "Dry Vermouth",
                        "Amaretto", "Tea", "Champagne", "Coffee liqueur", "Bourbon",
                        "Tequila", "Vodka", "Añejo rum", "Bitters", "Sugar",
                        "Kahlua", "demerara Sugar", "Dubonnet Rouge", "Watermelon", "Lime juice",
                        "Irish whiskey", "Apple brandy", "Carbonated water", "Cherry brandy", "Creme de Cacao",
                        "Grenadine", "Port", "Coffee brandy", "Red wine", "Rum",
                        "Grapefruit juice", "Ricard", "Sherry", "Cognac", "Sloe gin",
                        "Apple juice", "Pineapple juice", "Lemon juice", "Sugar syrup", "Milk",
                        "Strawberries", "Chocolate syrup", "Yoghurt", "Mango", "Ginger",
                        "Lime", "Cantaloupe", "Berries", "Grapes", "Kiwi",
                        "Tomato juice", "Cocoa powder", "Chocolate", "Heavy cream", "Galliano",
                        "Peach Vodka", "Ouzo", "Coffee", "Spiced rum", "Water",
                        "Espresso", "Angelica root", "Orange", "Cranberries", "Johnnie Walker",
                        "Apple cider", "Everclear", "Cranberry juice", "Egg yolk", "Egg",
                        "Grape juice", "Peach nectar", "Lemon", "Firewater", "Lemonade",
                        "Lager", "Whiskey", "Absolut Citron", "Pisco", "Irish cream",
                        "Ale", "Chocolate liqueur", "Midori melon liqueur", "Sambuca", "Cider",
                        "Sprite", "7-Up", "Blackberry brandy", "Peppermint schnapps", "Creme de Cassis"
                    );

                    $categories = array(
                        "Ordinary Drink", "Cocktail", "Shake", "Other / Unknown",
                        "Cocoa", "Shot", "Coffee / Tea", "Homemade Liqueur",
                        "Punch / Party Drink", "Beer", "Soft Drink"
                    );

                    foreach ($ingredients as $ingredient) {
                        echo "<option value=\"" . $ingredient . "\">" . $ingredient . "</option>";
                    }
                    ?>
                </select>
                <label for="category">Select Category:</label>
                <select class="form-control" name="category" id="category">
                    <option value="">Select an Ingredient</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    <?php endforeach; ?>
                </select>
                <br> 
                <button type="submit" class="btn btn-primary" name="fetch_cocktail_by_ingredient">Search</button>
            </div>
            <div class="col-md-6">
            </div>
        </form>

    </div>
    <br>
    <br>
    <br>

    <!-- Display cocktail data -->
    <div class="container-fluid mt-3">
        <?php if (isset($cocktailData) && !empty($cocktailData)): ?>
            <div class="row">
                <?php foreach ($cocktailData as $cocktail): ?>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <img src="<?php echo $cocktail['thumb']; ?>" class="img-fluid rounded-start" alt="Cocktail Image">
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $cocktail['name']; ?></h5>
                                        <p class="card-text"><?php echo $cocktail['category'] . '/' . $cocktail['alcoholic']; ?></p>
                                        <p class="card-text"><?php echo "Glass : " . $cocktail['glass']; ?></p>
                                        <p class="card-text"><?php echo $cocktail['instructions']; ?></p>
                                        <ul class="list-group">
                                            <?php foreach ($cocktail['ingredients'] as $ingredient): ?>
                                                <li class="list-group-item"><?php echo $ingredient['measure'] . ' ' . $ingredient['ingredient']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No cocktails found, maybe try something ....</p>
        <?php endif; ?>
    </div>
</main>

