                   
                
                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Charts</a></li>
                    <li class="active">Morris</li>
                </ul>
                <!-- END BREADCRUMB -->                
                 <div class="col-md-3">
                            
                            <div class="panel panel-default">
                                <div class="panel-body profile" style="background: url('<?php echo base_url();?>assets/assets/images/gallery/music-4.jpg') center center no-repeat;">
                                    <div class="profile-image">
                                        <img src="<?php echo base_url();?>assets/assets/images/users/user3.jpg" alt="Nadia Ali"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name">Nadia Ali</div>
                                        <div class="profile-data-title" style="color: #FFF;">Singer-Songwriter</div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left twitter"><span class="fa fa-twitter"></span></a>
                                        <a href="#" class="profile-control-right facebook"><span class="fa fa-facebook"></span></a>
                                    </div>                                    
                                </div>                                
                                <div class="panel-body">                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button class="btn btn-info btn-rounded btn-block"><span class="fa fa-check"></span> Following</button>
                                        </div>
                                        <div class="col-md-6">
                                            <button class="btn btn-primary btn-rounded btn-block"><span class="fa fa-comments"></span> Chat</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body list-group border-bottom">
                                    <a href="#" class="list-group-item active"><span class="fa fa-bar-chart-o"></span> Informasi</a>
                                    <a href="#" data-toggle="modal" data-target="#modal_no_head" class="list-group-item"><span class="fa fa-list-alt"></span> NIP</a>                          
                                    <a href="#" class="list-group-item"><span class="fa fa-users"></span> Unit</a>
                                    <a href="#" class="list-group-item"><span class="fa fa-bookmark"></span> Jabatan</a>
                                    <a href="#" class="list-group-item"><span class="fa fa-envelope"></span> Email</a>
                                    <a href="#" class="list-group-item"><span class="fa fa-phone"></span> Telepon</a>
                                    <a href="#" class="list-group-item"><span class="fa fa-home"></span> Alamat</a>
                                    <a href="#" class="list-group-item"><span class="fa fa-male"></span> Gender</a>
                                </div>
                                
                            </div>                            
                            
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-8">
                                <!-- START Area CHART -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Area Chart</h3>                                
                                    </div>
                                    <div class="panel-body">
                                        <div id="morris-area-example" style="height: 200px;"></div>
                                    </div>
                                </div>
                                <!-- END Area CHART -->                        

                            </div>
                             <div class="col-md-4">

                                <!-- START DONUT CHART -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Donut Chart</h3>                                
                                    </div>
                                    <div class="panel-body">
                                        <div id="morris-donut-example" style="height: 200px;"></div>
                                    </div>
                                </div>
                                <!-- END DONUT CHART -->                        

                            </div>    
                        </div>               
                             

                <!-- PAGE CONTENT WRAPPER -->
            
                <!-- END PAGE CONTENT WRAPPER -->                
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->
        
        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>                    
                        <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="float-right">
                            <a href="pages-login.html" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->

        <!-- START PRELOADS -->
        <audio id="audio-alert" src="<?php echo base_url();?>assets/audio/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="<?php echo base_url();?>assets/audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->               
            <script type="text/javascript">
                var morrisCharts = function() {

    Morris.Area({
        element: 'tes2',
        data: [
            { y: '2006', a: 100, b: 90 },
            { y: '2007', a: 75,  b: 65 },
            { y: '2008', a: 50,  b: 40 },
            { y: '2009', a: 75,  b: 65 },
            { y: '2010', a: 50,  b: 40 },
            { y: '2011', a: 75,  b: 65 },
            { y: '2012', a: 100, b: 90 }
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        resize: true,
        lineColors: ['#1caf9a', '#FEA223']
    });

    Morris.Donut({
        element: 'brew',
        data: [
            {label: "Download Sales", value: 12},
            {label: "In-Store Sales", value: 30},
            {label: "Mail-Order Sales", value: 20}
        ],
        colors: ['#95B75D', '#1caf9a', '#FEA223']
    });

}();
            </script>
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->
        
        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='<?php echo base_url();?>assets/js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/morris/morris.min.js"></script>
        <!-- END THIS PAGE PLUGINS-->        
        
        <!-- START TEMPLATE -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/settings.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins.js"></script>        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/actions.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/demo_charts_morris.js"></script>
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->        
    </body>
</html>






