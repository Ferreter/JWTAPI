<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">CocktailAPI</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto ">
            <li class="nav-item">
                <a class="nav-link" href="?action=fetch_random_cocktail">Random Cocktail</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="#">Cocktail by Name</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="#">Cocktail by Ingredient </a>
            </li>


            <!-- Check if the user is logged in -->
            <?php if (isset($_SESSION["login"]) && $_SESSION["login"]): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo "Hi, ", $_SESSION["username"]; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=logout">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=show_register">Register</a>
                </li>
            <?php endif; ?>
            </li>


        </ul>
    </div>
</nav>