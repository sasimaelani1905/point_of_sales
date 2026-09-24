<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <li class="nav-item">
          <a href="../home_superadmin" class="nav-link <?php if ($hal == 'beranda_admin'){
                echo 'active';
              } ?>
              ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Beranda</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../admin_data_user/" class="nav-link <?= $aktif = ($hal == 'admin_user')? 'active' :''?>">
            <i class="nav-icon fas fa-user"></i>
            <p>User</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../admin_data_supplier/" class="nav-link <?= $aktif = ($hal == 'admin_supplier')? 'active' :''?>">
            <i class="nav-icon fas fa-users"></i>
            <p>Supplier</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../admin_data_barang/" class="nav-link <?= $aktif = ($hal == 'admin_barang')? 'active' :''?>">
            <i class="nav-icon fas fa-pallet"></i>
            <p>Barang</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../admin_data_barang_konsinyasi/" class="nav-link <?= $aktif = ($hal == 'admin_konsinyasi')? 'active' :''?>">
            <i class="nav-icon fas fa-cubes"></i>
            <p>Barang Konsinyasi</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../admin_nota_beli/" class="nav-link <?= $aktif = ($hal == 'nota_beli')? 'active' :''?>">
            <i class="nav-icon fas fa-receipt"></i>
            <p>Nota Beli</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../admin_nota_jual/" class="nav-link <?= $aktif = ($hal == 'nota_jual')? 'active' :''?>">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>Nota Jual</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../admin_nota_konsinyasi/" class="nav-link <?= $aktif = ($hal == 'nota_konsinyasi')? 'active' :''?>">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Nota Jual Konsinyasi</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../ganti_pw_superadmin/" class="nav-link <?= $aktif = ($hal == 'ganti_pw_admin')? 'active' :''?>">
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