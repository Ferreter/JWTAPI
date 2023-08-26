<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

?>


    <main>
        <h1 class="text-center mt-5"> Login</h1>

        <section class="col-md-6 mx-auto">
            <?php
            if(isset($_GET['error'])){
                echo "<span class='error'>Error: ".$_GET['error']."</span>";
            }
            ?>
            <form action="" method="POST">
                <input type="hidden" name="action" value="login">  
                <div class="form-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control" id="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input name="password" type="password" class="form-control" id="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
            </form>

            <p class="mt-3">Don't have an account? <a href="?action=show_register" id="sign">Sign up</a></p>
        </section>
    </main>