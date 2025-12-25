<?php $this->load->view('home/includes/header'); ?>

        <section class="d-question-section">
            <div class="container">
                <div class="question-main">
                    <div class="question-lft">
                        <div class="d-question-hd">
                            <div class="q-main">
                                <div class="q-icon-img">
                                    <img src="<?php echo base_url('assets/home/images/q-icon.png'); ?>" alt="User" />
                                </div>
                                <h3>
                                    Question of the Day
                                    <span class="q-tag"> Improve your IQ – day by day </span>
                                </h3>
                            </div>
                            <div class="q-day-hd">
                                <?php if (!empty($today_question)): ?>
                                    <span class="p-date">
                                        <i class="fa-solid fa-calendar-days"></i>
                                        <?php echo date('d-m-Y', strtotime($today_question['question_date'])); ?>
                                    </span>
                                    <?php if (!empty($today_question['category'])): ?>
                                    <span class="p-date">
                                        <i class="fa-regular fa-building"></i>
                                        <?php echo !empty($today_question['category']) ? htmlspecialchars($today_question['category']) : ''; ?>
                                    </span>
                                <?php endif; ?>

                                <?php else: ?>
                                    <span class="p-date">
                                        <i class="fa-solid fa-calendar-days"></i>
                                        <?php echo date('d-m-Y'); ?>
                                    </span>
                                <?php endif; ?>
                                <a href="#" class="share-q" id="shareBtn"><i class="fa-solid fa-up-right-from-square"></i> Share</a>
                            </div>
                        </div>
                      
                        <section class="poll">
                            <?php if (!empty($today_question)): ?>
                                <form method="post" action="<?php echo base_url('submit-answer'); ?>">
                                    <input type="hidden" name="question_id" value="<?php echo (int)$today_question['id']; ?>" />
                                    <input type="hidden" name="return_url" value="<?php echo current_url(); ?>" />

                                <h2>
                                    <?php
                                        // question_description may contain HTML from editor
                                        echo $today_question['question_description'];
                                    ?>
                                </h2>

                                <?php
                                    $selected_option = !empty($user_response) ? (int)$user_response['selected_option'] : null;
                                    $user_name_value = !empty($user_response['user_name']) ? htmlspecialchars($user_response['user_name']) : '';
                                    $user_expl_value = !empty($user_response['user_explanation']) ? htmlspecialchars($user_response['user_explanation']) : '';
                                    $is_wrong_answer = !empty($user_response) && isset($user_response['is_correct']) && (int)$user_response['is_correct'] === 0;
                                ?>

                               
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <?php
                                        $option_key = 'option_' . $i;
                                        if (empty($today_question[$option_key])) {
                                            continue;
                                        }
                                    ?>
                                    <label class="option">
                                        <input
                                            type="radio"
                                            name="answer"
                                            value="<?php echo $i; ?>"
                                            <?php echo !empty($has_attempted) ? 'disabled' : ''; ?>
                                            <?php echo ($selected_option === $i) ? 'checked' : ''; ?>
                                        />
                                        <span class="checkmark">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <p><?php echo $today_question[$option_key]; ?></p>
                                    </label>
                                <?php endfor; ?>
                                <!-- Hidden name field used by popup -->
                                <div class="user-details" style="display:none;">
                                    <label class="user-name-label">
                                        <span>Your name</span>
                                        <input
                                            type="text"
                                            name="user_name"
                                            class="user-name-input"
                                            placeholder="Enter your name"
                                            value="<?php echo $user_name_value; ?>"
                                            <?php echo !empty($has_attempted) ? 'disabled' : ''; ?>
                                        />
                                    </label>
                                </div>
                                <textarea
                                    class="explanation"
                                    name="user_explanation"
                                    placeholder="Explain why you chose this answer..."
                                    <?php echo !empty($has_attempted) ? 'disabled' : ''; ?>
                                ><?php echo $user_expl_value; ?></textarea>
                                <?php
                            $qod_error   = $this->session->flashdata('qod_error');
                            $qod_success = $this->session->flashdata('qod_success');
                            $qod_info    = $this->session->flashdata('qod_info');
                        ?>
                        <?php if ($qod_error || $qod_success || $qod_info): ?>
                            <div class="qod-alert-wrapper">
                                <?php if ($qod_success): ?>
                                    <div class="qod-alert qod-alert-success">
                                        <span class="qod-alert-emoji">🎉</span>
                                        <span class="qod-alert-text"><?php echo $qod_success; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($qod_error): ?>
                                    <div class="qod-alert qod-alert-error">
                                        <span class="qod-alert-emoji">⚠️</span>
                                        <span class="qod-alert-text"><?php echo $qod_error; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($qod_info): ?>
                                    <div class="qod-alert qod-alert-info">
                                        <span class="qod-alert-emoji">ℹ️</span>
                                        <span class="qod-alert-text"><?php echo $qod_info; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                                <div class="q-btn-group">
                                    <button
                                        type="submit"
                                        class="submit-btn"
                                        <?php echo !empty($has_attempted) ? 'disabled' : ''; ?>
                                    >
                                        Submit <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                    <?php if (!empty($has_attempted)): ?>
                                        <button type="button" class="explain-btn">
                                            Explanation <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="explanation-box" hidden>
                                    <?php
                                        // Determine what types of explanation we have
                                        $video_type   = !empty($today_question['video_type']) ? $today_question['video_type'] : 'none';
                                        $raw_text_expl = isset($today_question['explanation']) ? $today_question['explanation'] : '';
                                        $has_text_expl = !empty(trim(strip_tags($raw_text_expl)));
                                        $has_video = ($video_type === 'upload' && !empty($today_question['video_file'])) ||
                                                     ($video_type === 'youtube' && !empty($today_question['youtube_link']));

                                        // Show heading based on explanation type
                                        if ($has_text_expl && !$has_video):
                                    ?>
                                        <h4>Explanation</h4>
                                    <?php elseif ($has_text_expl && $has_video): ?>
                                        <h4>Text &amp; Video Explanation</h4>
                                    <?php elseif ($has_video): ?>
                                        <h4>Video Explanation</h4>
                                    <?php endif; ?>

                                    <?php if ($has_text_expl): ?>
                                        <div class="text-explanation">
                                            <?php
                                                // explanation may contain HTML from editor
                                                echo $today_question['explanation'];
                                            ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                        // Video Solution Section
                                        if ($has_video):
                                            // Function to extract YouTube video ID from various URL formats
                                            if (!function_exists('get_youtube_video_id')) {
                                                function get_youtube_video_id($url) {
                                                    if (empty($url)) {
                                                        return null;
                                                    }
                                                    
                                                    // Remove any whitespace
                                                    $url = trim($url);
                                                    
                                                    // Pattern 1: youtube.com/watch?v=VIDEO_ID
                                                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                                                        return $matches[1];
                                                    }
                                                    
                                                    // Pattern 2: youtube.com/watch?feature=...&v=VIDEO_ID
                                                    if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                                                        return $matches[1];
                                                    }
                                                    
                                                    // Pattern 3: Parse URL and get 'v' parameter
                                                    $parsed = parse_url($url);
                                                    if (isset($parsed['query'])) {
                                                        parse_str($parsed['query'], $query);
                                                        if (isset($query['v']) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $query['v'])) {
                                                            return $query['v'];
                                                        }
                                                    }
                                                    
                                                    // Pattern 4: Check if it's already just a video ID
                                                    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
                                                        return $url;
                                                    }
                                                    
                                                    return null;
                                                }
                                            }
                                            
                                            if ($video_type === 'youtube'):
                                                $youtube_id = get_youtube_video_id($today_question['youtube_link']);
                                                if ($youtube_id):
                                    ?>
                                    <div class="video-solution-wrapper">
                                        <div class="video-solution-header">
                                            <h5 class="video-solution-title">
                                                <i class="fa-solid fa-video"></i> Video Solution
                                            </h5>
                                            <button class="video-rotate-btn" type="button" aria-label="Rotate video">
                                                <i class="fa-solid fa-rotate"></i>
                                            </button>
                                        </div>
                                        <div class="youtube-video-container video-container-rotatable" id="youtube-player" data-youtube-id="<?php echo htmlspecialchars($youtube_id); ?>">
                                            <iframe 
                                                src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtube_id); ?>?rel=0&modestbranding=1&showinfo=0" 
                                                allowfullscreen 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                class="youtube-iframe"
                                            ></iframe>
                                        </div>
                                    </div>
                                    <?php
                                                endif;
                                            elseif ($video_type === 'upload' && !empty($today_question['video_file'])):
                                                $video_url = base_url($today_question['video_file']);
                                    ?>
                                    <div class="video-solution-wrapper">
                                        <div class="video-solution-header">
                                            <h5 class="video-solution-title">
                                                <i class="fa-solid fa-video"></i> Video Solution
                                            </h5>
                                            <button class="video-rotate-btn" type="button" aria-label="Rotate video">
                                                <i class="fa-solid fa-rotate"></i>
                                            </button>
                                        </div>
                                        <div class="video-container-rotatable">
                                            <video class="plyr-video" id="uploaded-video-player" playsinline controls>
                                                <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                    <?php
                                            endif;
                                        endif;
                                    ?>
                                </div>
                                </form>
                            <?php else: ?>
                                <h2>No Question of the Day is scheduled for today.</h2>
                            <?php endif; ?>
                        </section>
                        <section class="comments-list">
                            <h5>Recent Comments</h5>
                            <?php if (!empty($recent_comments)): ?>
                                <?php foreach ($recent_comments as $comment): ?>
                                    <?php
                                        $name = !empty($comment['user_name']) ? htmlspecialchars($comment['user_name']) : 'User';
                                        $created_at = !empty($comment['created_at']) ? new DateTime($comment['created_at']) : null;
                                        $now = new DateTime();
                                        $daysAgo = $created_at ? $now->diff($created_at)->days : 0;
                                        if ($daysAgo === 0) {
                                            $time_label = 'Today';
                                        } elseif ($daysAgo === 1) {
                                            $time_label = '1 day ago';
                                        } else {
                                            $time_label = $daysAgo . ' days ago';
                                        }
                                        $comment_text = !empty($comment['user_explanation'])
                                            ? htmlspecialchars($comment['user_explanation'])
                                            : 'No explanation provided.';
                                    ?>
                                    <?php
                                        $response_id = (int)$comment['id'];
                                        $likes_count = isset($comment['likes_count']) ? (int)$comment['likes_count'] : 0;
                                        $dislikes_count = isset($comment['dislikes_count']) ? (int)$comment['dislikes_count'] : 0;
                                        $user_vote = isset($comment['user_vote']) ? $comment['user_vote'] : null;
                                        $like_active = ($user_vote === 'like') ? 'active' : '';
                                        $dislike_active = ($user_vote === 'dislike') ? 'active' : '';
                                    ?>
                                    <div class="comment-card" data-comment-id="<?php echo $response_id; ?>">
                                        <div class="comment-header">
                                            <img src="<?php echo base_url('assets/home/images/user1.jpg'); ?>" alt="User" class="avatar" />
                                            <div>
                                                <h4 class="username"><?php echo $name; ?></h4>
                                                <p class="time"><?php echo $time_label; ?></p>
                                            </div>
                                        </div>
                                        <p class="comment-text">
                                            <?php echo nl2br($comment_text); ?>
                                        </p>
                                        <div class="comment-actions">
                                            <button class="like-btn <?php echo $like_active; ?>" type="button" data-response-id="<?php echo $response_id; ?>" data-action="like">
                                                <i class="fa-solid fa-thumbs-up"></i>
                                                <span><?php echo $likes_count; ?></span>
                                            </button>
                                            <button class="dislike-btn <?php echo $dislike_active; ?>" type="button" data-response-id="<?php echo $response_id; ?>" data-action="dislike">
                                                <i class="fa-solid fa-thumbs-down"></i>
                                                <span><?php echo $dislikes_count; ?></span>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No comments yet for this question. Be the first to answer!</p>
                            <?php endif; ?>
                        </section>
                    </div>
                    <div class="question-rht">
                        <div class="info-card">
                            <h3 class="card-title">Previous Questions</h3>
                            <div class="pq-list">
                                <?php if (!empty($previous_questions)): ?>
                                    <?php foreach ($previous_questions as $prev): ?>
                                        <a href="<?php echo base_url('question/' . $prev['id']); ?>" class="question">
                                            <div class="qp-lft">
                                                <p>
                                                    <?php echo $prev['question_description']; ?>
                                                </p>
                                                <div class="previous-q-c">
                                                    <span>
                                                        <i class="fa-regular fa-comments"></i>
                                                        <?php
                                                            $answers_count = isset($prev['answers_count']) ? (int)$prev['answers_count'] : 0;
                                                            echo str_pad($answers_count, 2, '0', STR_PAD_LEFT);
                                                        ?>
                                                        Answers
                                                    </span>
                                                    <span>
                                                        <i class="fa-regular fa-calendar-days"></i>
                                                        <?php echo date('d-m-Y', strtotime($prev['question_date'])); ?>
                                                    </span>
                                                    <span class="p-cmny">
                                                        <i class="fa-regular fa-building"></i>
                                                        <?php echo !empty($prev['category']) ? htmlspecialchars($prev['category']) : 'General'; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="qp-rht">
                                                <small><i class="fa-solid fa-chevron-right"></i></small>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No previous questions available.</p>
                                <?php endif; ?>
                            </div>

                            <?php if (isset($prev_total_pages) && $prev_total_pages > 1): ?>
                                <div class="pagination">
                                    <?php if ($prev_current_page > 1): ?>
                                        <a class="pagination-btn first" href="<?php echo base_url('?page=1'); ?>">
                                            « First
                                        </a>
                                    <?php endif; ?>

                                    <?php
                                        // Simple windowed pagination
                                        $start = max(1, $prev_current_page - 2);
                                        $end = min($prev_total_pages, $prev_current_page + 2);
                                    ?>
                                    <?php for ($p = $start; $p <= $end; $p++): ?>
                                        <a
                                            class="pagination-btn <?php echo $p == $prev_current_page ? 'active' : ''; ?>"
                                            href="<?php echo base_url('?page=' . $p); ?>"
                                        >
                                            <?php echo $p; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <?php if ($prev_current_page < $prev_total_pages): ?>
                                        <a
                                            class="pagination-btn last"
                                            href="<?php echo base_url('?page=' . $prev_total_pages); ?>"
                                        >
                                            » Last
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="j-cmnty">
                            <img src="<?php echo base_url('assets/home/images/join-wtsup.gif'); ?>" alt="User" class="" />
                            <h2>Activate daily notifications and <br />never miss a thing</h2>
                            <a href="https://whatsapp.com/channel/0029VbApoVQHrDZdMRVDoD3p" target="_blank">Join Channel <i class="fa-brands fa-whatsapp"></i></a
                            ><span class="ntfn-on">Enable notification</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Share Popup Modal -->
        <div id="shareModal" class="share-modal">
            <div class="share-modal-content">
                <div class="share-modal-header">
                    <h3>Challenge with your friends</h3>
                    <button class="share-modal-close" id="closeShareModal">&times;</button>
                </div>
                <div class="share-modal-body">
                    <button class="share-option-btn" id="copyUrlBtn">
                        <i class="fa-solid fa-link"></i>
                        <span>Copy URL</span>
                    </button>
                    <a href="#" class="share-option-btn" id="whatsappShareBtn" target="_blank">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>Share on WhatsApp</span>
                    </a>
                </div>
                <div class="share-modal-footer">
                    <span id="copySuccessMsg" class="copy-success-msg" style="display: none;">URL copied to clipboard!</span>
                </div>
            </div>
        </div>

        <!-- Name Capture Popup Modal -->
        <div id="nameModal" class="share-modal">
            <div class="share-modal-content">
                <div class="share-modal-header">
                    <h3>Your name</h3>
                    <button class="share-modal-close" id="closeNameModal">&times;</button>
                </div>
                <div class="share-modal-body">
                    <label class="user-name-label">
                        <span>Your name</span>
                        <input
                            type="text"
                            id="popupUserName"
                            class="user-name-input"
                            placeholder="Enter your name (optional)"
                        />
                    </label>
                </div>
                <div class="share-modal-footer name-modal-footer">
                    <button class="explain-btn name-modal-btn" id="nameSkipBtn">
                        <span>Skip</span>
                    </button>
                    <button class="submit-btn name-modal-btn" id="nameSaveBtn">
                        <span>Continue</span>
                    </button>
                </div>
            </div>
        </div>

<?php $this->load->view('home/includes/footer'); ?>
