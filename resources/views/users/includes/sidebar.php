
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            <?php
                    $img = $admin->avatar ;
                    if($img){
                      ?>
                      <div class="user_profile_img" style="background-image:url('<?php echo asset('public/svg/'.$admin->avatar);?>')"></div>
                      <?php

                    }else{
                      ?>
                      <div class="user_profile_img" style="background-image:url('<?php echo asset('public/images/default/user_icon.png');?>')"></div>
                      <?php
                        // $img = asset('public/images/admini/profile_pic/demo.png');
                    }
                    ?>
          <!-- <img src="<?= $img;?>" class="img-circle" alt="User Image"> -->
        </div>
        <div class="pull-left info">
          <h4>User Dashboard </h4>
          <a href="#"></a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">

          <span class="input-group-btn">
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->

      <ul class="sidebar-menu" data-widget="tree">
          <li class="<?= ($tab == 'dashboard' ? 'active treeview menu-open' : '') ?>">
            <a href="<?= asset('userdashboard') ?>">
            <i class="fa fa-cc-stripe"></i> <span>Dashboard</span>
          </a>

        </li>
        <li class="<?= ($tab == 'subscription' ? 'active treeview menu-open' : '') ?>">
            <a href="<?= asset('usersubscription') ?>">
            <i class="fa fa-cc-stripe"></i> <span>Subscription</span>
          </a>

        </li>

         <li class="<?= ($tab == 'add_subscription_view' ? 'active treeview menu-open' : '') ?>">
            <a href="<?= asset('add_subscription_view') ?>">
            <i class="fa  fa-credit-card"></i> <span>Add Subscription</span>
          </a>

        </li>



         <li class="<?= ($tab == 'user_edit_profile' ? 'active treeview menu-open' : '') ?>">
            <a href="<?= asset('edit_user_profile') ?>">
            <i class="fa fa-edit"></i> <span>Edit User</span>
          </a>
         </li>

          <li class="<?= ($tab == 'user_services' ? 'active treeview menu-open' : '') ?>">
            <a href="<?= asset('get_services') ?>">
            <i class="fa  fa-truck"></i> <span>Services</span>
          </a>
         </li>

          <li class="<?= ($tab == 'user_payment_method' ? 'active treeview menu-open' : '') ?>">
            <a href="<?= asset('get_payment_method') ?>">
            <i class="fa  fa-truck"></i> <span>Payment Method</span>
          </a>
         </li>

          <?php if(isset(Auth::user()->getSubscription->stripe_plan) && (env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN') == Auth::user()->getSubscription->stripe_plan)) {?>
          <li class="<?= ($tab == 'user_family_members' ? 'active treeview menu-open' : '') ?>">
            <a href="<?= asset('user_family_members') ?>">
            <i class="fa  fa-truck"></i> <span>Family Members</span>
          </a>
         </li>
          <?php } ?>


      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

