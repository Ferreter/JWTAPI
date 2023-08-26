<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>

<main>
    <h1 class="text-center mt-5">Register </h1>

    <section class="col-md-6 mx-auto">
        <form action="" method="POST">
            <input type="hidden" name="action" value="register">  
            <div class="form-group">
                <label for="username">Username</label>
                <input name="username" type="text" class="form-control" id="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input name="password" type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="member">Member Type</label>
                <select name="member" class="form-control" id="member" required>
                    <option value="0">Free</option>
                    <option value="1">Premium</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="register">Register</button>
        </form> 
    </section>
</main>