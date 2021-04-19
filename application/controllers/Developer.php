<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Developer extends CI_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->library('session');
        //$this -> load -> model('person');
        $this -> load -> model('admin_model');
        //$this -> is_logged_in();
    }

    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}


    public function angular_view_welcome_developer(){
        ?>
        <div class="d-flex col-12">
            <div class="col-2">
                <div class="nav-side-menu">
                    <div class="brand">Brand Logo</div>
                    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

                    <div class="menu-list">

                        <ul id="menu-content" class="menu-content collapse out">
                            <li  data-toggle="collapse" data-target="#products" class="collapsed active">
                                <a href="#"><i class="fa fa-gift fa-lg"></i> UI Elements <span class="arrow"></span></a>
                            </li>
                            <ul class="sub-menu collapse" id="products">
                                <li class="active"><a href="#" ng-click="setDivision('topDiv')">Top Div</a></li>
                                <li><a href="#" ng-click="setDivision('div2')">Error Fix</a></li>
                                <li><a href="#">Buttons</a></li>
                                <li><a href="#">Tabs & Accordions</a></li>
                                <li><a href="#">Typography</a></li>
                                <li><a href="#">FontAwesome</a></li>
                                <li><a href="#">Slider</a></li>
                                <li><a href="#">Panels</a></li>
                                <li><a href="#">Widgets</a></li>
                                <li><a href="#">Bootstrap Model</a></li>
                            </ul>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-10" id="developer-div">
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isDivisionSet('topDiv')">
                        <div class="card">
                            <div class="card-head">
                               Top Div header
                            </div>
                            <div class="card-body">
                                Top Div
                            </div>
                        </div>
                    </div>
                    <div ng-show="isDivisionSet('div2')">
                        <div class="card">
                            <div class="card-head">
                                Error Fixing
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }



}
?>