<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

    
    ?>

<main>
    <?php if ($cocktailData !== null): ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <img src="<?php echo $cocktailData['thumb']; ?>" class="img-fluid rounded-start" alt="Cocktail Image">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $cocktailData['name']; ?></h5>
                                    <p class="card-text"><?php echo $cocktailData['category'] . '/' . $cocktailData['alcoholic']; ?></p>
                                    <p class="card-text"><?php echo "Glass : " . $cocktailData['glass']; ?></p>
                                    <p class="card-text"><?php echo $cocktailData['instructions']; ?></p>
                                    <ul class="list-group">
                                        <?php foreach ($cocktailData['ingredients'] as $ingredient): ?>
                                            <li class="list-group-item"><?php echo $ingredient['measure'] . ' ' . $ingredient['ingredient']; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                Daily limit reached. Please try again tomorrow.
            </div>
        </div>
    <?php endif; ?>
</main>


