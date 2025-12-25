<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/styles'); ?>
<?php $this->load->view('admin/includes/side_menu'); ?>

  <div class="tw-p-[20px]">
    <div class="tw-flex-col tw-flex md:tw-flex-row md:tw-items-center tw-mb-[20px] tw-gap-[10px]">
      <div class="tw-text-[20px] tw-font-bold tw-flex-auto"><?php echo isset($is_edit) && $is_edit ? 'Edit Question of the Day' : 'Add Question of the Day'; ?></div>
      <div class="tw-flex tw-items-center tw-gap-[10px]">
        <a href="<?php echo base_url('admin/question-of-the-day'); ?>" class="btn btn-secondary bw-btn">
          <i class="fi fi-rr-arrow-left"></i> Back to List
        </a>
      </div>
    </div>

     <?php if (isset($validation_errors) && $validation_errors): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $validation_errors; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?> 

    <div class="bw-card tw-p-[20px]">
      <form action="<?php echo base_url('admin/question-of-the-day/save'); ?>" method="post" enctype="multipart/form-data">
        <?php if (isset($is_edit) && $is_edit && isset($question['id'])): ?>
          <input type="hidden" name="id" value="<?php echo $question['id']; ?>" />
        <?php endif; ?>
        <div class="row g-3">
          <div class="col-md-12">
            <div>
              <label class="form-label tw-font-bold">Question Description <span class="tw-text-red-500">*</span></label>
              <textarea name="question_description" id="question_description" class="form-control bw-form-control tinymce-editor" rows="5"><?php echo isset($question['question_description']) ? htmlspecialchars($question['question_description']) : ''; ?></textarea>
              <?php if (form_error('question_description')): ?>
                <div class="text-danger small mt-1"><?php echo form_error('question_description'); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-12">
            <div>
              <label class="form-label tw-font-bold">Category</label>
              <input type="text" name="category" class="form-control bw-form-control" placeholder="Enter category" value="<?php echo isset($question['category']) ? htmlspecialchars($question['category']) : ''; ?>" />
              <?php if (form_error('category')): ?>
                <div class="text-danger small mt-1"><?php echo form_error('category'); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-6">
            <div>
              <label class="form-label tw-font-bold">Post Date <span class="tw-text-red-500">*</span></label>
              <input
                type="date"
                name="question_date"
                class="form-control bw-form-control"
                value="<?php 
                  $date_value = '';
                  if (isset($question) && is_array($question) && isset($question['question_date']) && !empty($question['question_date'])) {
                    $date_value = $question['question_date'];
                  } elseif (isset($_POST['question_date']) && !empty($_POST['question_date'])) {
                    $date_value = $_POST['question_date'];
                  }
                  echo $date_value;
                ?>"
                min="<?php echo date('Y-m-d'); ?>"
                required
              />
              <?php if (form_error('question_date')): ?>
                <div class="text-danger small mt-1"><?php echo form_error('question_date'); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-6">
            <div>
              <label class="form-label tw-font-bold">Status <span class="tw-text-red-500">*</span></label>
              <select name="status" class="form-select bw-form-control" required>
                <option value="active" <?php echo (isset($question['status']) && $question['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo (isset($question['status']) && $question['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                <option value="draft" <?php echo (isset($question['status']) && $question['status'] == 'draft') ? 'selected' : (!isset($question['status']) ? 'selected' : ''); ?>>Draft</option>
              </select>
              <?php if (form_error('status')): ?>
                <div class="text-danger small mt-1"><?php echo form_error('status'); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-12">
            <div>
              <label class="form-label tw-font-bold">Options (for Multiple Choice) <span class="tw-text-red-500">*</span></label>
              <div class="tw-space-y-3">
                <div class="tw-flex tw-items-start tw-gap-3">
                  <div class="tw-flex-1">
                    <label class="form-label tw-text-sm tw-mb-1 tw-font-bold">Option 1 <span class="tw-text-red-500">*</span></label>
                    <textarea name="option_1" id="option_1" class="form-control bw-form-control tinymce-editor" rows="3" placeholder="Enter option 1"><?php echo isset($question['option_1']) ? htmlspecialchars($question['option_1']) : ''; ?></textarea>
                    <?php if (form_error('option_1')): ?>
                      <div class="text-danger small mt-1"><?php echo form_error('option_1'); ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="form-check tw-mt-[30px]">
                    <input class="form-check-input" type="radio" name="correct_answer" value="1" id="correct_1" <?php echo (isset($question['correct_answer']) && (int)$question['correct_answer'] === 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="correct_1">Correct</label>
                  </div>
                </div>
                <div class="tw-flex tw-items-start tw-gap-3">
                  <div class="tw-flex-1">
                    <label class="form-label tw-text-sm tw-mb-1 tw-font-bold">Option 2 <span class="tw-text-red-500">*</span></label>
                    <textarea name="option_2" id="option_2" class="form-control bw-form-control tinymce-editor" rows="3" placeholder="Enter option 2"><?php echo isset($question['option_2']) ? htmlspecialchars($question['option_2']) : ''; ?></textarea>
                    <?php if (form_error('option_2')): ?>
                      <div class="text-danger small mt-1"><?php echo form_error('option_2'); ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="form-check tw-mt-[30px]">
                    <input class="form-check-input" type="radio" name="correct_answer" value="2" id="correct_2" <?php echo (isset($question['correct_answer']) && (int)$question['correct_answer'] === 2) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="correct_2">Correct</label>
                  </div>
                </div>
                <div class="tw-flex tw-items-start tw-gap-3">
                  <div class="tw-flex-1">
                    <label class="form-label tw-text-sm tw-mb-1 tw-font-bold">Option 3</label>
                    <textarea name="option_3" id="option_3" class="form-control bw-form-control tinymce-editor" rows="3" placeholder="Enter option 3"><?php echo isset($question['option_3']) ? htmlspecialchars($question['option_3']) : ''; ?></textarea>
                    <?php if (form_error('option_3')): ?>
                      <div class="text-danger small mt-1"><?php echo form_error('option_3'); ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="form-check tw-mt-[30px]">
                    <input class="form-check-input" type="radio" name="correct_answer" value="3" id="correct_3" <?php echo (isset($question['correct_answer']) && (int)$question['correct_answer'] === 3) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="correct_3">Correct</label>
                  </div>
                </div>
                <div class="tw-flex tw-items-start tw-gap-3">
                  <div class="tw-flex-1">
                    <label class="form-label tw-text-sm tw-mb-1 tw-font-bold">Option 4</label>
                    <textarea name="option_4" id="option_4" class="form-control bw-form-control tinymce-editor" rows="3" placeholder="Enter option 4"><?php echo isset($question['option_4']) ? htmlspecialchars($question['option_4']) : ''; ?></textarea>
                    <?php if (form_error('option_4')): ?>
                      <div class="text-danger small mt-1"><?php echo form_error('option_4'); ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="form-check tw-mt-[30px]">
                    <input class="form-check-input" type="radio" name="correct_answer" value="4" id="correct_4" <?php echo (isset($question['correct_answer']) && (int)$question['correct_answer'] === 4) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="correct_4">Correct</label>
                  </div>
                </div>
                <?php if (form_error('correct_answer')): ?>
                  <div class="text-danger small mt-1"><?php echo form_error('correct_answer'); ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div>
              <label class="form-label tw-font-bold">Explanation Type <span class="tw-text-red-500">*</span></label>
              <small class="form-text text-muted tw-d-block tw-mb-2">
                <strong>Note:</strong> Explanation can be provided as <strong>text</strong>, <strong>video upload</strong>, or <strong>YouTube video link</strong>. One of them is required.
              </small>
              <div class="tw-mb-3">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="video_type" id="video_type_text" value="none" <?php echo (!isset($question['video_type']) || $question['video_type'] == 'text' || $question['video_type'] == 'none') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="video_type_text">Text</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="video_type" id="video_type_upload" value="upload" <?php echo (isset($question['video_type']) && $question['video_type'] == 'upload') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="video_type_upload">Video</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="video_type" id="video_type_youtube" value="youtube" <?php echo (isset($question['video_type']) && $question['video_type'] == 'youtube') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="video_type_youtube">YouTube</label>
                </div>
              </div>
              <?php if (form_error('video_type')): ?>
                <div class="text-danger small mt-1"><?php echo form_error('video_type'); ?></div>
              <?php endif; ?>

              <!-- Text Explanation Field -->
              <div id="text_explanation_field" class="tw-mb-3" style="display: <?php echo (!isset($question['video_type']) || $question['video_type'] == 'text' || $question['video_type'] == 'none') ? 'block' : 'none'; ?>;">
                <label class="form-label tw-font-bold">Explanation <span class="tw-text-red-500">*</span></label>
                <textarea name="explanation" id="explanation" class="form-control bw-form-control tinymce-editor" rows="4" placeholder="Explanation for the answer"><?php echo isset($question['explanation']) ? htmlspecialchars($question['explanation']) : ''; ?></textarea>
                <?php if (form_error('explanation')): ?>
                  <div class="text-danger small mt-1"><?php echo form_error('explanation'); ?></div>
                <?php endif; ?>
              </div>

              <!-- Upload Video Field -->
              <div id="video_upload_field" class="tw-mb-3" style="display: <?php echo (isset($question['video_type']) && $question['video_type'] == 'upload') ? 'block' : 'none'; ?>;">
                <label class="form-label tw-font-bold">Upload Video File <span class="tw-text-red-500">*</span></label>
                <input type="file" name="video_file" id="video_file" class="form-control bw-form-control" accept="video/*">
                <small class="form-text text-muted">Accepted formats: MP4, AVI, MOV, WMV, FLV, WEBM, MKV (Max: 50MB)</small>
                <!-- <small class="form-text text-muted d-block mt-1">
                  <strong>Note:</strong> If upload fails, check your PHP settings (upload_max_filesize and post_max_size in php.ini)
                </small> -->
                <?php if (form_error('video_file')): ?>
                  <div class="text-danger small mt-1"><?php echo form_error('video_file'); ?></div>
                <?php endif; ?>
                <?php if (isset($is_edit) && $is_edit && isset($question['video_file']) && !empty($question['video_file'])): ?>
                  <div class="tw-mt-2">
                    <small class="text-muted">Current video: </small>
                    <a href="<?php echo base_url($question['video_file']); ?>" target="_blank" class="text-primary">View Current Video</a>
                    <small class="text-muted"> (Upload a new file to replace)</small>
                  </div>
                <?php endif; ?>
              </div>

              <!-- YouTube Link Field -->
              <div id="youtube_link_field" class="tw-mb-3" style="display: <?php echo (isset($question['video_type']) && $question['video_type'] == 'youtube') ? 'block' : 'none'; ?>;">
                <label class="form-label tw-font-bold">YouTube Link <span class="tw-text-red-500">*</span></label>
                <input type="text" name="youtube_link" id="youtube_link" class="form-control bw-form-control" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo isset($question['youtube_link']) ? htmlspecialchars($question['youtube_link']) : ''; ?>">
                <small class="form-text text-muted">Enter the full YouTube URL</small>
                <?php if (form_error('youtube_link')): ?>
                  <div class="text-danger small mt-1"><?php echo form_error('youtube_link'); ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div class="tw-flex tw-items-center tw-gap-[10px] tw-mt-[20px] tw-pt-[20px] tw-border-0 tw-border-t tw-border-solid tw-border-gray-200">
          <button type="submit" class="btn btn-primary bw-btn">
            <i class="fi fi-sr-check"></i> Save Question
          </button>
          <a href="<?php echo base_url('admin/question-of-the-day'); ?>" class="btn btn-secondary bw-btn">
            <i class="fi fi-rr-cross"></i> Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<?php $this->load->view('admin/includes/scripts'); ?>

<!-- Summernote WYSIWYG Editor -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

<style>
/* Fix Summernote modal z-index for fullscreen mode */
.note-modal {
  z-index: 10000 !important;
}

.note-modal-backdrop {
  z-index: 9999 !important;
}

/* Fix Summernote fullscreen mode z-index */
.note-editor.note-frame.fullscreen {
  z-index: 9998 !important;
}

/* Fix Summernote modal close button */
.note-modal .modal-header {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 20px;
  border-bottom: 1px solid #e9ecef;
}

.note-modal .modal-header .close {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  margin: 0;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
  border: none;
  font-size: 24px;
  line-height: 1;
  color: #6c757d;
  cursor: pointer;
  opacity: 0.7;
  transition: all 0.2s ease;
  z-index: 10;
}

.note-modal .modal-header .close:hover {
  opacity: 1;
  color: #000;
  background: #f8f9fa;
  border-radius: 4px;
}

.note-modal .modal-header .close:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.note-modal .modal-header .close span {
  font-size: 28px;
  font-weight: 300;
  line-height: 1;
}

.note-modal .modal-title {
  margin: 0;
  font-weight: 600;
  font-size: 18px;
  color: #212529;
  flex: 1;
}
</style>

<script>
$(document).ready(function() {
  // Initialize Summernote on all textarea elements with class tinymce-editor
  $('.tinymce-editor').summernote({
    height: 300,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      // ['fontname', ['fontname']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ],
    callbacks: {
      onImageUpload: function(files) {
        // Upload image when inserted
        uploadImage(files[0], $(this));
      },
      onInit: function() {
        // Fix close button after modal is shown
        $(document).on('shown.bs.modal', '.note-modal', function() {
          var $closeBtn = $(this).find('.modal-header .close');
          if ($closeBtn.length) {
            // Ensure close button works
            $closeBtn.off('click').on('click', function(e) {
              e.preventDefault();
              $(this).closest('.note-modal').modal('hide');
            });
          }
        });
      }
    }
  });

  // Fix close button for all Summernote modals
  $(document).on('click', '.note-modal .modal-header .close', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).closest('.note-modal').modal('hide');
    return false;
  });

  // Function to handle image upload
  function uploadImage(file, editor) {
    var formData = new FormData();
    formData.append('file', file);

    $.ajax({
      url: '<?php echo base_url("admin/question-of-the-day/upload-image"); ?>',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.location) {
          // Insert image into editor
          editor.summernote('insertImage', response.location);
        } else if (response.error) {
          alert('Error uploading image: ' + response.error);
        }
      },
      error: function(xhr, status, error) {
        alert('Error uploading image: ' + error);
      }
    });
  }

  // Handle explanation type radio button changes
  $('input[name="video_type"]').on('change', function() {
    var selectedType = $(this).val();
    
    // Hide all fields first
    $('#text_explanation_field').hide();
    $('#video_upload_field').hide();
    $('#youtube_link_field').hide();
    
    // Remove required attributes from all fields
    $('#explanation').prop('required', false);
    $('#video_file').prop('required', false);
    $('#youtube_link').prop('required', false);
    
    // Show relevant field based on selection
    if (selectedType === 'none') {
      $('#text_explanation_field').show();
      $('#explanation').prop('required', true);
    } else if (selectedType === 'upload') {
      $('#video_upload_field').show();
      $('#video_file').prop('required', true);
    } else if (selectedType === 'youtube') {
      $('#youtube_link_field').show();
      $('#youtube_link').prop('required', true);
    }
  });

  // Initialize on page load
  var initialVideoType = $('input[name="video_type"]:checked').val();
  if (initialVideoType === 'none' || !initialVideoType) {
    $('#text_explanation_field').show();
    $('#explanation').prop('required', true);
  } else if (initialVideoType === 'upload') {
    $('#video_upload_field').show();
    $('#video_file').prop('required', true);
  } else if (initialVideoType === 'youtube') {
    $('#youtube_link_field').show();
    $('#youtube_link').prop('required', true);
  }
});
</script>

<?php $this->load->view('admin/includes/footer'); ?>

