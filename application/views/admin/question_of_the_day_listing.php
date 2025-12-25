<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/styles'); ?>
<?php $this->load->view('admin/includes/side_menu'); ?>

   
  <div class="tw-p-[20px]">
    
<div>
  <!-- <div class="menu-swiper">
    <div class="swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide"><a href="courses.html">Courses</a></div>
        <div class="swiper-slide"><a href="#" class="active">Colleges</a></div>
        <div class="swiper-slide"><a href="students.html">Students</a></div>
      </div>
    </div>
    <div class="menu-swiper-nav-prev"><i class="fi fi-sr-angle-left"></i></div>
    <div class="menu-swiper-nav-next"><i class="fi fi-sr-angle-right"></i></div>
  </div> -->
  <div class="tw-flex-col tw-flex md:tw-flex-row md:tw-items-center tw-mb-[20px] tw-gap-[10px]">
    <div class="tw-text-[20px] tw-font-bold tw-flex-auto">Question of the Day</div>
    <form method="get" action="<?php echo base_url('admin/question-of-the-day'); ?>" class="bw-search">
      <i class="fi fi-rr-search"></i>
      <input type="text" name="search" class="form-control bw-form-control" placeholder="Search" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" />
    </form>
    <div class="tw-flex tw-items-center tw-gap-[10px]">
      <a href="<?php echo base_url('admin/question-of-the-day/add'); ?>" class="btn btn-primary btn-icon"><i class="fi fi-sr-plus"></i></a>
    </div>
  </div>
  
  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $this->session->flashdata('success'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $this->session->flashdata('error'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <div class="table-responsive bw-card">
    <table class="table table-hover bw-table tw-m-0">
      <thead>
        <tr>
          <th>S.No</th>
          <th>Question</th>
          <th>Category</th>
          <th>Post Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($questions)): ?>
          <?php $sno = ($current_page - 1) * $per_page + 1; ?>
          <?php foreach ($questions as $question): ?>
            <tr>
              <td><?php echo $sno++; ?></td>
              <td>
                <?php 
                  $question_text = strip_tags($question['question_description']);
                  echo strlen($question_text) > 100 ? substr($question_text, 0, 100) . '...' : $question_text;
                ?>
              </td>
              <td><?php echo !empty($question['category']) ? htmlspecialchars($question['category']) : '-'; ?></td>
              <td><?php echo date('d M Y', strtotime($question['question_date'])); ?></td>
              <td>
                <?php
                  $status_class = 'text-bg-primary';
                  if ($question['status'] == 'inactive') {
                    $status_class = 'text-bg-danger';
                  } elseif ($question['status'] == 'draft') {
                    $status_class = 'text-bg-secondary';
                  }
                ?>
                <span class="badge rounded-pill <?php echo $status_class; ?>"><?php echo ucfirst($question['status']); ?></span>
              </td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-default bw-btn" type="button" data-bs-toggle="dropdown">Actions <i class="fi fi-sr-angle-small-down"></i></button>
                  <ul class="dropdown-menu dropdown-menu-end bw-dropdown">
                    <li>
                      <a class="dropdown-item" href="<?php echo base_url('admin/question-of-the-day/edit/' . $question['id']); ?>"><i class="fi fi-sr-edit"></i> Edit</a>
                    </li>
                    <li>
                      <a class="dropdown-item text-danger" href="<?php echo base_url('admin/question-of-the-day/delete/' . $question['id']); ?>" onclick="return confirm('Are you sure you want to delete this question?');"><i class="fi fi-sr-trash"></i> Delete</a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center">No questions found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="tw-flex-col tw-flex md:tw-flex-row tw-items-center tw-gap-[10px] tw-mt-[10px]">
    <div class="tw-flex tw-items-center tw-gap-[10px] tw-mr-auto">
      <form method="get" action="<?php echo base_url('admin/question-of-the-day'); ?>" class="tw-flex tw-items-center tw-gap-[10px]">
        <?php if (isset($filters['search'])): ?>
          <input type="hidden" name="search" value="<?php echo htmlspecialchars($filters['search']); ?>" />
        <?php endif; ?>
        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
          <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25</option>
          <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
          <option value="100" <?php echo $per_page == 100 ? 'selected' : ''; ?>>100</option>
        </select>
        <span class="tw-whitespace-nowrap">
          Showing <?php echo $total > 0 ? ($offset + 1) : 0; ?> to <?php echo min($offset + $per_page, $total); ?> of <?php echo $total; ?> entries
        </span>
      </form>
    </div>
    <?php if ($total > $per_page): ?>
      <nav>
        <ul class="pagination bw-card tw-m-0">
          <?php
            $total_pages = ceil($total / $per_page);
            $query_string = http_build_query(array_merge($filters, array('per_page' => $per_page)));
          ?>
          <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo $current_page > 1 ? base_url('admin/question-of-the-day?' . $query_string . '&page=' . ($current_page - 1)) : '#'; ?>"><i class="fi fi-br-angle-left"></i></a>
          </li>
          <?php
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            for ($i = $start_page; $i <= $end_page; $i++):
          ?>
            <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
              <a class="page-link" href="<?php echo base_url('admin/question-of-the-day?' . $query_string . '&page=' . $i); ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo $current_page < $total_pages ? base_url('admin/question-of-the-day?' . $query_string . '&page=' . ($current_page + 1)) : '#'; ?>"><i class="fi fi-br-angle-right"></i></a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</div>

  </div>
</div>
</div>

<?php $this->load->view('admin/includes/scripts'); ?>
<?php $this->load->view('admin/includes/footer'); ?>