<nav class="">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <li class="nav-item">
          <a href="../home_kasir" class="nav-link <?php if ($hal == 'beranda_kasir'){
                echo 'active';
              } ?>
              ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Beranda</p>
          </a>
        </li>

         <li class="nav-item">
          <a href="../kasir_nota_beli/" class="nav-link <?= $aktif = ($hal == 'kasir_nota_beli')? 'active' :''?>">
            <i class="nav-icon fas fa-receipt"></i>
            <p>Nota Beli</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../kasir_nota_jual/" class="nav-link <?= $aktif = ($hal == 'kasir_nota_jual')? 'active' :''?>">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>Nota Jual</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../ganti_pw_kasir/" class="nav-link <?= $aktif = ($hal == 'ganti_pw_kasir')? 'active' :''?>">
            <i class="nav-icon fas fa-lock"></i>
            <p>Ganti Password</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../logout.php" class="nav-link">
            <i class="fas fa-sign-out-alt nav-icon"></i>
            <p>Keluar</p>
          </a>
        </li>
  </ul>
</nav>