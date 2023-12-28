                   
                
                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                </ul>
                <!-- END BREADCRUMB -->                
                 <div class="col-md-3">
                            
                            <div class="panel panel-default">
                                <div class="panel-body profile" style="background: url('https://s-media-cache-ak0.pinimg.com/originals/4f/6d/05/4f6d052bb1b26150115888ea06d4c106.jpg') center center no-repeat;">
                                    <div class="profile-image">
                                        <img src="<?php echo base_url();?>assets/images/member-photos/<?php echo $foto; ?>" alt="Nadia Ali"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name"><b><?php echo ucwords($nama); ?></b></div>
                                        <div class="profile-data-title" style="color: #FFF;"><?php echo ucwords($jabatan); ?></div>
                                    </div>
                                </div>
                                <div class="panel-body list-group border-bottom">
                                    <a href="#" class="list-group-item active"><span class="fa fa-bar-chart-o"></span> Informasi</a>
                                    <a href="#" data-toggle="modal" data-target="#modal_no_head" class="list-group-item"><span class="fa fa-list-alt"></span> NIP : <?php echo $nip; ?></a>                          
                                    <a href="#" class="list-group-item"><span class="fa fa-users"></span> Unit : <?php echo ucwords($unit); ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-bookmark"></span> Jabatan : <?php echo ucwords($jabatan); ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-envelope"></span> Email : <?php echo $email; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-phone"></span> Telepon : <?php echo $telepon; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-home"></span> Alamat : <?php echo $alamat; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-male"></span> Gender : <?php echo $jenis_kelamin; ?></a>
                                </div>
                                
                            </div>                            
                            
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-8">
                                <!-- START Area CHART -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Statistik Jam Kerja</h3>                                
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
                                        <h3 class="panel-title">Statistik Kehadiran</h3>                                
                                    </div>
                                    <div class="panel-body">
                                        <div id="morris-donut-example" style="height: 200px;"></div>
                                    </div>
                                </div>
                                <!-- END DONUT CHART -->                        

                            </div>
                            <div class="row">
                                <table class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Waktu Datang</th>
                                                    <th>Waktu istirahat</th>
                                                    <th>Waktu Kembali</th>
                                                    <th>Waktu Pulang</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>     
                                                </tr>
                                            </thead>
                                                 
                                            <tbody>
                                                <?php foreach ($dt as $key => $value) { ?>
                                                <tr>
                                                    <th><?php echo $value->created_absensi ?></th>
                                                    <th><?php echo $value->waktu_datang ?></th>
                                                    <th><?php echo $value->waktu_istirahat ?></th>
                                                    <th><?php echo $value->waktu_kembali ?></th>
                                                    <th><?php echo $value->waktu_pulang ?></th>
                                                    <th><?php echo $value->keterangan ?></th>
                                                    <th><a href="<?php echo base_url(); ?>Pegawai/Log/<?php echo $value->nip ?>"><button class=" btn btn-primary">Beri Keterangan</button></a></th>
                                                </tr>
                                                 <?php } ?>
                                            </tbody>
                                        </table>                       
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
           
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->
        
        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='<?php echo base_url();?>assets/js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
         <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/morris/morris.min.js"></script>
        <!-- END THIS PAGE PLUGINS-->        
        
        <!-- START TEMPLATE -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/settings.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins.js"></script>        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/actions.js"></script>
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->

        <script>
            $(document).ready(function(){
                var morrisCharts = function() {

                    Morris.Area({
                        element: 'morris-area-example',
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
                        element: 'morris-donut-example',
                        data: [
                            {label: "Hadir", value: 12},
                            {label: "Tidak Hadir", value: 30},
                            {label: "Kesiangan", value: 20}
                        ],
                        colors: ['#95B75D', '#1caf9a', '#FEA223']
                    });

                }();
            });
        </script>
    </body>
</html>






