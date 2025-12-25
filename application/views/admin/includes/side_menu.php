<div class="md:tw-pl-[70px] md:[&:has([data-layout='sidebar'].classic)]:!tw-pl-[300px] tw-transition-all tw-duration-300 tw-ease-out">
<div data-layout="sidebar">
  <div>
    <a data-layout="logo">
      <img src="<?php echo base_url('assets/admin/images/logo.svg'); ?>" alt="logo" />
      <button type="button" class="btn btn-icon" data-layout="sidebar-toggle"><i class="fi fi-rr-cross"></i></button>
    </a>
    
    <div data-layout="menu">
    <ul>
      
      <!-- <li class="">
        <a href="dashboard.html" class=""><i class="fi fi-sr-table-columns"></i><span>Dashboard</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=" has-arrow "><i class="fi fi-sr-inbox-full"></i><span>Offerings</span></a>
         
    <ul>
      
      <li class="active">
        <a href="colleges.html" class=""><span>Colleges</span></a>
        
      </li>
      
      <li class="">
        <a href="attendance.html" class=""><span>Attendance</span></a>
        
      </li>
      
    </ul>
     
      </li>
      
      <li class="">
        <a href="" class=""><i class="fi fi-sr-document"></i><span>Practice Test</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><i class="fi fi-sr-duplicate"></i><span>Test Series</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><i class="fi fi-sr-graduation-cap"></i><span>Courses</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><i class="fi fi-sr-unlock"></i><span>Employee Access</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=" has-arrow "><i class="fi fi-sr-megaphone"></i><span>Marketing</span></a>
         
    <ul>
      
      <li class="">
        <a href="" class=""><span>Send Mails</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><span>Notifications</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><span>Coupons</span></a>
        
      </li>
      
    </ul>
     
      </li>
      
      <li class="">
        <a href="" class=" has-arrow "><i class="fi fi-sr-browser"></i><span>Website</span></a>
         
    <ul>
      
      <li class="">
        <a href="" class=""><span>Pages</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><span>Blogs</span></a>
        
      </li>
      
    </ul>
     
      </li>
      
      <li class="">
        <a href="" class=" has-arrow "><i class="fi fi-sr-chart-pie-alt"></i><span>Reports</span></a>
         
    <ul>
      
      <li class="">
        <a href="" class=""><span>Users</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><span>Sales</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><span>Orders</span></a>
        
      </li>
      
    </ul>
     
      </li>
      
      <li class="">
        <a href="" class=""><i class="fi fi-sr-headset"></i><span>Support</span></a>
        
      </li>
      
      <li class="">
        <a href="" class=""><i class="fi fi-sr-settings"></i><span>Settings</span></a>
        
      </li> -->
      
      <li class="">
        <a href="<?php echo base_url('admin/question-of-the-day'); ?>" class=""><i class="fi fi-sr-calendar"></i><span>Question of the day</span></a>
        
      </li>
      
    </ul>
    </div>
  </div>
</div>
<div data-layout="sidebar-backdrop"></div>
<div class="tw-flex tw-flex-col">
  <div class="tw-flex tw-items-center tw-h-[62px] tw-px-[15px] tw-bg-white  tw-border-0 tw-border-b tw-border-solid  tw-border-gray-200 tw-sticky tw-top-0 tw-z-[9]">
   <button type="button" class="btn btn-icon"  data-layout="sidebar-toggle"><i class="fi fi-rr-sidebar tw-text-[20px]"></i></button>
   <div class="dropdown tw-ml-auto">
      <button class="btn !tw-border-gray-300 tw-py-0 tw-h-[40px] tw-flex tw-items-center tw-gap-[5px]" type="button" data-bs-toggle="dropdown">
         <i class="fi fi-sr-circle-user tw-text-[24px] tw-text-gray-500"></i>
         <span class="tw-align-middle"><?php echo $this->session->userdata('admin_name') ? $this->session->userdata('admin_name') : 'Admin'; ?></span> 
         <i class="fi fi-sr-angle-small-down"></i>
      </button>
      <ul class="dropdown-menu bw-dropdown">
        <li><a class="dropdown-item" href="#"><i class="fi fi-sr-user"></i> Profile</a></li>
        <li><a class="dropdown-item" href="#"><i class="fi fi-sr-key"></i> Change Password</a></li>  
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="<?php echo base_url('admin/logout'); ?>"><i class="fi fi-sr-sign-out-alt"></i> Logout</a></li>
      </ul>
    </div>  
</div>
