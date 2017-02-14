<?php $cnt = 0;
$query = mysql_query('SELECT * FROM `messages`');
while ($row1 = mysql_fetch_array($query)) { 
    $vasfromid1 = "SELECT * FROM `messages` where id =".$row1['id'];
    $refromid1=mysql_query($vasfromid1);
    $wefromid1=mysql_fetch_assoc($refromid1);

    $vasuser1 = "SELECT * FROM `user` where id =".$wefromid1['FromId'];
    $reuser1=mysql_query($vasuser1);
    $weuser1=mysql_fetch_assoc($reuser1);
    if($weuser1['UserType']=='User'){
        if($wefromid1['IsRead']=='0')
        {
            $cnt++;
            $_SESSION['read'] = $cnt;
        }
    } 
    
    } 
    if($cnt=='0')
    {
        $_SESSION['read'] = $cnt;
    }
   
    $vassubroles_Set = mysql_query("SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = ".$_SESSION['user_info']['id']." AND roles.ParentId = 40 )");                
    $resubroles_Sett=mysql_num_rows($vassubroles_Set);
   
?>
    <?php
require_once('classes/class.database.php');
require_once('inc/functions.php');
$vasroles = "SELECT  roles.id as roleid , roles.RoleName as rolename , user.UserType as usertype FROM `userroles` INNER JOIN roles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE userroles.UserId = " . $_SESSION['user_info']['id'];
$reroles  = mysql_query($vasroles);
$weroles  = mysql_fetch_assoc($reroles); //$weroles=mysql_fetch_assoc($reroles);//print_r($weroles);exit;//echo $weroles['rolename'];
?>                        <ul id="main-nav">  <!-- Accordion Menu -->                                <li>                    <a href="index.php" class="nav-top-item no-submenu"> <!-- Add the class "no-submenu" to menu items with no sub menu -->                        Dashboard                    </a>                       </li>                <?php
if (mysql_num_rows($reroles) != 0) {
?>                <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 12 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>                    <li>                    <a href="#" class="nav-top-item">                        Admins                    </a>                    <ul>                        <?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
?>                        <li><a href="                        <?php
            if ($wesubroles['RoleName'] == 'Add new Admin') {
?>                        users_edit.php                        <?php
            } else {
?>                        users.php                        <?php
            }
?>                        "><?= $wesubroles['RoleName'] ?></a></li>                        <li><a href="customers.php">View Users</a></li>                                                <?php
        }
?>                    </ul>                </li>                <?php
    }
?>                 <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 1 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>                    <li>                    <a href="#" class="nav-top-item">                        Products                    </a>                    <ul>                    <li> <a href="product.php?catid=all">All Products</a></li>                         <?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
?>                        <li><a href="                        <?php
            if ($wesubroles['RoleName'] == 'Manage CellPhones') {
?>                        product.php?catid=1                        <?php
            } elseif ($wesubroles['RoleName'] == 'Manage Computers') {
?>                        product.php?catid=2                        <?php
            } elseif ($wesubroles['RoleName'] == 'Manage Tablets') {
?>                        product.php?catid=3                        <?php
            } elseif ($wesubroles['RoleName'] == 'Manage iPod') {
?>                        product.php?catid=23                        <?php
            } elseif ($wesubroles['RoleName'] == 'Manage Watches') {
?>                        product.php?catid=5                        <?php
            } elseif ($wesubroles['RoleName'] == 'Gadgets') {
?>                        product.php?catid=24                        <?php
            }
?>                        "><?= $wesubroles['RoleName'] ?></a></li>                        <?php
        }
?>                    </ul>                </li>                <?php
    }
?>                <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 2 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>
	<li>
		<a href="#" class="nav-top-item"> Orders </a>
		<ul> 
		<?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
            $key = explode(" ", strtolower($wesubroles['RoleName']));
?>                    <li><a href="<?php
            if ($wesubroles['RoleName'] == 'View Order Transactions') {
?>                    ordertransaction.php?sort=DatePaid&desc <?php
            }
			else if ($wesubroles['RoleName'] == 'Returned Completed') {
?>                    order.php?key=returncompleted&sort=DatePaid&desc                    <?php
            }
			 else {
?>                    order.php?key=<?php
                if ($key[0] == 'view') {
                    echo "all";
                } else {
                    echo $key[0];
                }
?>&sort=OrderDate&desc                    <?php
            }
?>                    "><?= $wesubroles['RoleName'] ?></a></li>                    <?php
        }
?>                                            </ul>                </li>                <?php
    }
?>                
<?php
    $vassubroles = "SELECT * FROM `roles` LEFT JOIN userroles on userroles.RoleId = roles.id 
	WHERE ( userroles.UserId = ".$_SESSION['user_info']['id']." AND roles.ParentId = 44 )";
    $resubroles  = mysql_query($vassubroles);
	if (mysql_num_rows($resubroles) == 0) {
	} else { ?>
		 <li>
		 	<a href="#" class="nav-top-item">Coupon</a> 
			<ul>
				 <?php
				 	while ($wesubroles = mysql_fetch_array($resubroles))
					{
						 if ($wesubroles['RoleName'] == 'Add Coupon') { ?>
							<li><a href="coupon-add.php"><?= $wesubroles['RoleName'] ?></a></li>
						<?php }
						if ($wesubroles['RoleName'] == 'Manage Coupon') { ?>
							<li><a href="coupon-manage.php"><?= $wesubroles['RoleName'] ?></a></li>
						<?php }
						?>
				<?php } ?>
			</ul>
		</li>
	<?php
	}
?>      


<?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 6 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>                    <li>                    <a href="#" class="nav-top-item">                        Site Pages                    </a>                                        <ul>                        <?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
?>                        <li><a href="sitepages.php"><?= $wesubroles['RoleName'] ?></a></li>                        <?php
        }
?>                    </ul>                </li>                <?php
    }
?>                <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 10 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>                    <li>                    <a href="#" class="nav-top-item">                        Newsletter                    </a>                                        <ul>                        <?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
?>                        <li><a href="email_subscriber.php?sort=EmailAddress"><?= $wesubroles['RoleName'] ?></a></li>                        <?php
        }
?>                    </ul>                </li>                <?php
    }
?>                <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 4 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>                    <li>                    <a href="#" class="nav-top-item">                        Contact Us                     </a>                                        <ul>                        <?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
?>                        <li><a href="manage_requests.php?sort=Date&desc"><?= $wesubroles['RoleName'] ?></a></li>                        <?php
        }
?>                    </ul>                </li>                <?php
    }
?>                <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 7 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>                <li>                    <a href="#" class="nav-top-item">                        Testimonials                    </a>                                        <ul>                        <?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
?>                        <li><a href="testimonials.php?sort=Date&desc"><?= $wesubroles['RoleName'] ?></a></li>                        <?php
        }
?>                    </ul>                </li>                <?php
    }
?>                <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 8 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
    } else {
?>                <li>                    <a href="#" class="nav-top-item">                        Import Database                    </a>                    <ul>                        <li><a href="import.php">Import Database</a></li>                    </ul>                </li>                                                                 <li>                    <a href="#" class="nav-top-item">                        Press Release                    </a>                    <ul>                        <li><a href="pressrelease.php">press Release</a></li>                    </ul>                </li>                <?php
    }
?>                <?php
    $vassubroles = "SELECT * FROM `roles` INNER JOIN userroles on userroles.RoleId = roles.id INNER JOIN user on userroles.UserId = user.id WHERE ( userroles.UserId = " . $_SESSION['user_info']['id'] . " AND roles.ParentId = 38 )";
    $resubroles  = mysql_query($vassubroles);
?>                <?php
    if (mysql_num_rows($resubroles) == 0) {
?>                                <?php
    } else {
?>                <li>                    <a href="#" class="nav-top-item">                        Messages <?php
        if ($_SESSION['read'] > 0) {
?> <span style="color: red;">(<?= $_SESSION['read'] ?>)</span> <?php
        } else {
?> (<?= $_SESSION['read'] ?>) <?php
        }
?>                    </a>                                        <ul>                        <?php
        while ($wesubroles = mysql_fetch_array($resubroles)) {
?>                        <li><a href="                        <?php
            if ($wesubroles['RoleName'] == 'Inbox') {
?>                        inbox.php?sort=Date&desc                        <?php
            } else {
?>                        outbox.php?sort=Date&desc                        <?php
            }
?>                        "><?= $wesubroles['RoleName'] ?></a></li>                        <?php
        }
?>                    </ul><?php
        if ($resubroles_Sett > 0) {
?><li><a href="#" class="nav-top-item">Settings</a><ul><li><a href="settings.php">Settings</a></li></ul></li> <?php
        }
?>                </li>                <?php
    }
?>                              <?php
} else {
?>              <li>                    <a href="#" class="nav-top-item">                        Admins                    </a>                    <ul>                                              <li><a href="users_edit.php">Add new Admin</a></li>                        <li><a href="users.php">View Admins</a></li>                        <li><a href="customers.php">View Users</a></li>                                                                  </ul>                </li>                                <li>                    <a href="#" class="nav-top-item">                        Products                    </a>                    <ul>                        <li><a href="product.php?catid=all">All Products</a></li>                          <li><a href="product.php?catid=1">Manage CellPhones</a></li>                        <li><a href="product.php?catid=2">Manage Computers</a></li>                        <li><a href="product.php?catid=3">Manage Tablets</a></li>                        <li><a href="product.php?catid=23">Manage iPod</a></li>                        <li><a href="product.php?catid=5">Manage Watches</a></li>                        <li><a href="product.php?catid=24">Gadget</a></li>                                                                  </ul>                </li>                                    <li>                    <a href="#" class="nav-top-item">                        Orders                    </a>                    <?php
    $vassubroles = "SELECT * FROM `roles` WHERE ParentId = 2";
    $resubroles  = mysql_query($vassubroles); //$wesubroles=mysql_fetch_assoc($resubroles);                    //print_r($wesubroles);                    
?>                      <ul>                        <?php
    while ($wesubroles = mysql_fetch_array($resubroles)) {
        $key = explode(" ", strtolower($wesubroles['RoleName']));
?>                                                <li><a href="                        <?php
        if ($wesubroles['RoleName'] == 'View Order Transactions') {
?>                        ordertransaction.php?sort=DatePaid&desc                        <?php
        } else {
?>                        order.php?key=<?php
            if ($key[0] == 'view') {
                echo "all";
            } else {
                echo $key[0];
            }
?>&sort=OrderDate&desc                        <?php
        }
?>                        "><?= $wesubroles['RoleName'] ?></a></li>                        <?php
    }
?>                    </ul>                </li>                                <li>                    <a href="#" class="nav-top-item">                        Site Pages                    </a>                    <ul>                                                <li><a href="sitepages.php">View Pages</a></li>                    </ul>                </li>                                <li>                    <a href="#" class="nav-top-item">                        Newsletter                    </a>                    <ul>                                                <li><a href="email_subscriber.php?sort=EmailAddress">Email Subscribers</a></li>                    </ul>                </li>                                <li>                    <a href="#" class="nav-top-item">                        Contact Us                     </a>                    <ul>                        <li><a href="manage_requests.php?sort=Date&desc">Manage Requests</a></li>                    </ul>                </li>                                <li>                    <a href="#" class="nav-top-item">                        Testimonials                    </a>                    <ul>                                                <li><a href="testimonials.php?sort=Date&desc">View Testimonials</a></li>                    </ul>                </li>                                <li>                    <a href="#" class="nav-top-item">                        Messages <?php
    if ($_SESSION['read'] > 0) {
?> <span style="color: red;">(<?= $_SESSION['read'] ?>)</span> <?php
    } else {
?> (<?= $_SESSION['read'] ?>) <?php
    }
?>                    </a>                    <ul>                        <!--<li><a href="sitepages_edit.php">Add New Page</a></li>-->                        <li><a href="inbox.php?sort=Date&desc">Inbox</a></li>                        <li><a href="outbox.php?sort=Date&desc">Outbox</a></li>                    </ul>                </li>                                <li>                    <a href="#" class="nav-top-item">                        Import Database                    </a>                    <ul>                        <!--<li><a href="sitepages_edit.php">Add New Page</a></li>-->                                                <li><a href="import.php">Import Database</a></li>                    </ul>                </li>                                <?php
    if ($resubroles_Sett > 0) {
?><li><a href="#" class="nav-top-item">Settings</a><ul><li><a href="settings.php">Settings</a></li></ul></li> <?php
    }
?>              <?php
}
?>                            </ul> <!-- End #main-nav -->                </div></div> <!-- End #sidebar -->okkkk