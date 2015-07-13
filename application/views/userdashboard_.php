<?php
	ob_start();
	$this->load->view('common/user/header.php');
?>   
	<div class="header-top">

        <div class="main-logo"></div>

        <a class="blue-button action logout" href="<?php echo base_url('portal/logout')?>">Logout</a>
        <?php if(isCurrentUserAdmin()){ ?>
            <a class="blue-button action admin" href="<?php echo base_url('admin/dashboard')?>">Admin</a>
        <?php } ?>

        </div>

    </div>
    <div class="header_top_bdr"></div>
    <div class="back_container">
    	<div class="main">
            <div class="menu-trigger"></div>
        	<div class="left">
	             <div id="content_1" class="content">                 
                 	<div class="portion">
                    	<h2>Projects</h2>
                        <div class="clear"></div>
                        <div class="details">
                        	 <?php 
								foreach($project_details as $project){									
							  ?>
                        	<div class="project_tile project-button blue-button" id="project-<?php echo $project['id']?>">
                            	<a href="javascript:void(0);">
                            	<div class="sub">
                                    <img src="<?php echo base_url('/images/project_tile_icon')?>.png" alt="" />                                	
                                    <div class="project-name">
                                        <div><?php echo $project['name']?></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php }?>
                            
                        </div>
                    </div>
                
                 </div>
            </div>
            
            <div class="right">
                	<div class="head">                     
                        <div class="clear"></div>
                        <h1 class="project-title">Overview</h1>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="apps_content_back">
                    	<div id="content_2" class="content2">
                        	<div class="content_main">

                                <div id="pro-map">
                                    <h2>ARC - Grip Process Maps</h2>
                                    <a id="start-tutorial" class="blue-button action" href="#"></a>
                                    <div id="pm-timeline">
                                        <a class="grip" href="#" data-grip="1">
                                            <span class="input">
                                                Advise new project opportunity
                                            </span>
                                            GRIP 1
                                            <span class="output">
                                                <ul>
                                                    <li>CRD / RRD</li>
                                                    <li>G2 Deliverables</li>
                                                    <li>CSM Category</li>
                                                    <li>Project Hazard Log</li>
                                                    <li>TSI Applicability</li>
                                                    <li>Agreed Programme</li>
                                                    <li>Confirmed Funding</li>
                                                    <li>Draft Contract for VFL</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <span class="grip-arrow"></span>
                                        <a class="grip" href="#" data-grip="2">
                                            GRIP 2
                                            <span class="output">
                                                <ul>
                                                    <li>Sponsors Instruction</li>
                                                    <li>RRD</li>
                                                    <li>G3 Deliverables</li>
                                                    <li>Updated Hazard Log</li>
                                                    <li>Agreed Programme</li>
                                                    <li>Confirmed Funding</li>
                                                    <li>Land &amp; Consent Strategy</li>
                                                    <li>Posessions Booked</li>
                                                    <li>Project Risk Register</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <span class="grip-arrow"></span>
                                        <a class="grip" href="#" data-grip="3">
                                            GRIP 3
                                            <span class="output">
                                                <ul>
                                                    <li>IP Approval</li>
                                                    <li>G3 Deliverables</li>
                                                    <li>Updated Hazard Log</li>
                                                    <li>Agreed Option</li>
                                                    <li>Agreed Programme</li>
                                                    <li>Confirmed Funding</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <span class="grip-arrow"></span>
                                        <a class="grip" href="#" data-grip="4">
                                            GRIP 4
                                            <span class="output">
                                                <ul>
                                                    <li>VFL Approval (Holland)</li>
                                                    <li>IP Approval</li>
                                                    <li>Accepted AIP</li>
                                                    <li>G5 Deliverables</li>
                                                    <li>DRRD</li>
                                                    <li>Project Hazard Log</li>
                                                    <li>Agreed Programme</li>
                                                    <li>Confirmed Funding</li>
                                                    <li>Project Org'n (named individuals)</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <span class="grip-arrow"></span>
                                        <a class="grip" href="#" data-grip="5">
                                            GRIP 5
                                            <span class="output">
                                                <ul>
                                                    <li>VFL Approval (Holland)</li>
                                                    <li>Accepted AIP</li>
                                                    <li>G5 Deliverables</li>
                                                    <li>Hazard Log</li>
                                                    <li>Agreed Programme</li>
                                                    <li>Confirmed Funding</li>
                                                    <li>Project Org'n (named individuals)</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <span class="grip-arrow"></span>
                                        <a class="grip" href="#" data-grip="6">
                                            GRIP 6
                                            <span class="output">
                                                <ul>
                                                    <li>Construction H&amp;S Plan</li>
                                                    <li>Test Certificates</li>
                                                    <li>Approval of Tests</li>
                                                    <li>Record of Defect</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <span class="grip-arrow"></span>
                                        <a class="grip" href="#" data-grip="7">
                                            GRIP 7
                                            <span class="output">
                                                <ul>
                                                    <li>Completion Certificate</li>
                                                    <li>Project Wide Lessons Learned</li>
                                                    <li>Outstanding defects / dates</li>
                                                    <li>H&amp;S File</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <span class="grip-arrow"></span>
                                        <a class="grip" href="#" data-grip="8">
                                            GRIP 8
                                            <span class="output">
                                                <ul>
                                                    <li>Lessons Learned Shared</li>
                                                    <li>Project Archived</li>
                                                </ul>
                                            </span>
                                        </a>
                                        <br class="clear">
                                        <div id="extra">
                                            <div>
                                                <a class="other" href="#" data-grip="9">VFL Tender</a>
                                                <a class="other" href="#" data-grip="10">VFL Tender Docs</a>
                                                <a class="other" href="#" data-grip="11">VFL Contract Award</a>
                                                <a class="other" href="#" data-grip="12">Design Tender / Contract Award</a>
                                                <a class="other" href="#" data-grip="13">VFL Sub-Contract Placement</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="chart-frame">
                                        <a class="close-frame" href="#" title="Close">&times;</a>
                                        <div id="key">
                                            <h3>Abbreviation Key</h3>
                                            <ul>
                                                <li><span>AFC</span> Anticipated Final Cost</li>
                                                <li><span>AIP</span> Approval in Principle</li>
                                                <li><span>ARC</span> Anglia Route Collaboration</li>
                                                <li><span>ARD</span> Activity Requirements Doc</li>
                                                <li><span>CRD</span> Client Requirements Document</li>
                                                <li><span>CSM</span> Common Safety Method</li>
                                                <li><span>DRAM</span> Director of Rail Asset Management</li>
                                                <li><span>EMP</span> Environmental Management Plan</li>
                                                <li><span>EWR</span> Early Warning Registar</li>
                                                <li><span>GRIP</span> Governance for Rail Investment Projects</li>
                                                <li><span>IP</span> Investment Panel</li>
                                                <li><span>OP</span> Oracle Projects</li>
                                                <li><span>PAR</span> Project Automatic Reporting</li>
                                            </ul>
                                            <ul>
                                                <li><span>PCIP</span> Pre Construction Information Pack</li>
                                                <li><span>PEST</span> Project Estimate</li>
                                                <li><span>PM</span> Project Manager</li>
                                                <li><span>PMB</span> Project Managers Basline Programme</li>
                                                <li><span>PO</span> Purchase Order</li>
                                                <li><span>POAP</span> Project on a Page</li>
                                                <li><span>PPS</span> Pesession Planning System</li>
                                                <li><span>QMP</span> Quality Management Plan</li>
                                                <li><span>RRD</span> Route Requirements Document</li>
                                                <li><span>SLT</span> Senior Leadership Team</li>
                                                <li><span>VFL</span> Volker Fitzpartick Limited</li>
                                                <li><span>VM</span> Value Management</li>
                                                <li><span>WLC</span> Whole Life Cost</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            	
                                <?php load_app_content();
								?>       
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="footer_back">
                        <a class="need-help-link" href="<?php echo base_url('portal/project/9')?>">Need Help?</a>                          
                    </div>
                    
                </div>
        </div>
    </div>
<?php
	$this->load->view("common/user/footer.php");
	ob_flush();
?>