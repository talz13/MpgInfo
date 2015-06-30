<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/funcs.php';
?>
            <div class="footer">
                <ul>
                    <li><a href="<?= buildLocalPath('/') ?>">Main Page</a></li>
                    <li><a href="<?= buildLocalPath('/submit/addMileage.php') ?>">Add Mileage</a></li>
                    <li><a href="<?= buildLocalPath('/viewResults.php') ?>">View Data</a></li>
                    <li><a href="<?= buildLocalPath('/viewVehicle.php') ?>">View Vehicle</a></li>
                    <li><a href="<?= buildLocalPath('/submit/addVehicle.php') ?>">Add Vehicle</a></li>
                    <li><a href="<?= buildLocalPath('/submit/editVehicle.php') ?>">Edit Vehicle</a></li>
                    <li><a href="<?= buildLocalPath('/testing/') ?>">Google Charts</a></li>
                </ul>
            </div>
        </div>
    </body>
</html>