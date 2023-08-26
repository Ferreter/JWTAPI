<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<main>
<div class="container mt-5">
    <h1>Search for Cocktails by Name</h1>
    <p>Use the search bar below to find cocktails by their names. Simply enter the name of the cocktail 
        you're looking for and click the "Search" button. If there's a cocktail with the provided name, its details will be displayed.</p>

    <form class="row g-3" method="POST" action="?action=fetch_cocktail_by_name">
        <div class="col-md-6">
            <input type="text" class="form-control" name="cocktailName" placeholder="Enter Cocktail Name">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary" name="fetch_cocktail_by_name">Search</button>
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

