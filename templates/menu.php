<nav class="tw blue navbar navbar-expand-lg navbar-expand-md bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" style="font-weight:bold;font-size:28px;" head-value="<?php echo $headValue??'';?>"><?php echo $headDisplay??'';?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" aria-current="page" href="total.php">Total</a></li>
                <li class="nav-item"><a class="nav-link" aria-current="page" href="pending.php">Pending</a></li>
                <li class="nav-item"><a class="nav-link" aria-current="page" href="paymentCount.php">paymentCount</a></li>
                <li class="nav-item"><a class="nav-link" aria-current="page" href="grouped.php">Grouped</a></li>
                <li class="nav-item"><a class="nav-link" aria-current="page" href="input.php">Input</a></li>
                <li class="nav-item"><a class="nav-link" aria-current="page" href="goals.php">Goals</a></li>
            </ul>
        </div>
    </div>
</nav>