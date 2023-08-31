<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

?>

<main>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Membership Details</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Member Type:</strong> <?php echo $_SESSION['member'] == 0 ? 'Free' : 'Premium'; ?></p>
                        <p><strong>Username:</strong> <?php echo $_SESSION['username']; ?></p>
                        <p><strong>API Key:</strong> <?php echo $_SESSION['apikey']; ?></p>
                        <?php if ($_SESSION['member'] == 1) : ?>
                            <p><strong>Membership Expiry:</strong> <?php 
                            if(isset($_SESSION['premiumDate']) && $_SESSION['premiumDate'] != null){
                                print_r ($_SESSION['premiumDate']);
                            }else{
                                echo 'Unable to retrieve that information right now';
                            }
                            
                            
                            ?></p>
                        <?php else : ?>
                            <p><strong>Membership Expiry:</strong> Not Applicable</p>
                            <a href="?action=upgradeProfile" class="btn btn-primary">Upgrade to Premium</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>