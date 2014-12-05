<!-- Logo & Navigation starts -->

<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <!-- Logo -->
                <div class="logo">
                    <h1><a href="index-2.html">Plutos Computers</a></h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-5">
                <!-- Navigation menu -->
                <div class="navi">
                    <div id="ddtopmenubar" class="mattblackmenu">
                        <ul>
                            <li><a href="index-2.html">Pocetna</a></li>
                            <li><a href="#" rel="ddsubmenu1">O nama</a>
                                <ul id="ddsubmenu1" class="ddsubmenustyle">
                                    <li><a href="account.html">My Account</a></li>
                                    <li><a href="viewcart.html">View Cart</a></li>
                                    <li><a href="checkout.html">Checkout</a></li>
                                    <li><a href="wishlist.html">Wish List</a></li>
                                    <li><a href="orderhistory.html">Order History</a></li>
                                    <li><a href="editprofile.html">Edit Profile</a></li>
                                    <li><a href="confirmation.html">Confirmation</a></li>
                                </ul>
                            </li>
                            <li><a href="#" rel="ddsubmenu1">Brandovi</a>
                                <ul id="ddsubmenu1" class="ddsubmenustyle">
                                    <li><a href="404.html">404</a></li>
                                    <li><a href="faq.html">FAQ</a></li>
                                    <li><a href="blog.html">Blog</a></li>
                                    <li><a href="careers.html">Careers</a>
                                    <li><a href="support.html">Support</a></li>
                                    <li><a href="aboutus.html">About</a></li>
                                </ul>
                            </li>
                            <li><a href="#" rel="ddsubmenu1">Kako kupiti</a>
                                <ul id="ddsubmenu1" class="ddsubmenustyle">
                                    <li><a href="items.html">Desktop</a></li>
                                    <li><a href="items.html">Laptop</a></li>
                                    <li><a href="items.html">NetBook</a></li>
                                    <li><a href="items.html">All-In-One PC</a>
                                    <li><a href="items.html">Alienware</a></li>
                                </ul>
                            </li>
                            <li><a href="contactus.html">Kontakt</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Dropdown NavBar -->
                <div class="navis"></div>

            </div>

            <div class="col-md-3 col-sm-4">
                <div class="kart-links">
                    <a href="login.html">Login</a>
                    <a href="register.html">Signup</a>
                    <a data-toggle="modal" href="#shoppingcart">
                        <?php if (Cart::totalItems(true) > 0): ?>
                            <i class="icon-shopping-cart"></i>
                            <span class="total-items"><?php echo Cart::totalItems(true); ?></span>/
                            <span class="total-quantity"><?php echo Cart::totalItems(); ?></span> -
                            <span class="total-price"><?php echo Cart::total(false); ?></span>
                        <?php else: ?>
                            <i class="icon-shopping-cart"></i> Korpa je prazna
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logo & Navigation ends -->

<div class="clearfix"></div>