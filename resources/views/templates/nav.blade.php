<!--HEADER-->
<header>
    <div id="csi-header" class="csi-header csi-banner-header">
        <div class="header-top">
            <div class="header-top-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="contact">
                                <ul class="list-inline">
                                    <li><i class="fa fa-clock-o" aria-hidden="true"></i> Opening Hours:  {{ENV('APP_OPENING_TIMES')}}</li>
                                    <li><i class="fa fa-phone" aria-hidden="true"></i> Call Us: {{ENV('APP_PHONE_NUMBER')}} </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="right-menu">
                                <ul class="list-inline">
                                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-facebook-f" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--//.header-top-->
        <div class="csi-header-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <nav class="navbar navbar-default csi-navbar">
                            <div class="container">
                                <nav class="navbar navbar-default csi-navbar">
                                    <div class="csicontainer">
                                        <div class="navbar-header">
                                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                                    data-target=".navbar-collapse">
                                                <span class="sr-only">Toggle navigation</span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                            </button>
                                            <div class="csi-logo">
                                                <a href="index.html">
                                                    <img src="http://placehold.it/170x70" alt="Logo"/>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="collapse navbar-collapse">
                                            <ul class="nav navbar-nav csi-nav">
                                                <li class="dropdown">
                                                    <a href="{{URL::to('/')}}" class="active">Home </a>
                                                </li>
                                                <li><a class="csi-scroll" href="{{URL::to('reviews')}}">Reviews</a></li>
                                                <li><a class="csi-scroll" href="{{URL::to('contact')}}">Contact Us</a></li>
                                                <li><a class="csi-scroll" href="{{URL::to('about')}}">About Us</a></li>
                                                <li><a class="csi-btn csi-scroll" href="{{URL::to('order')}}">Order Now</a></li>
                                            </ul>
                                        </div>
                                        <!--/.nav-collapse -->
                                    </div>
                                </nav>
                            </div>
                            <!-- /.container -->
                        </nav>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
        <!-- //.INNER-->
    </div>
</header>
<!--HEADER END-->