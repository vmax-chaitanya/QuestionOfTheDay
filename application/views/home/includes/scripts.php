        <!-- Plyr.js Video Player -->
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

        <script>
    // Function to initialize video players
    function initializeVideoPlayers() {
        // Check if Plyr is loaded (only needed for uploaded videos)
        if (typeof Plyr === 'undefined') {
            console.warn('Plyr is not loaded yet, will retry...');
            setTimeout(initializeVideoPlayers, 200);
            return;
        }
        
        // YouTube videos use direct iframe embed, no initialization needed
        // They will work automatically when the page loads

        // Initialize uploaded video player if exists and not already initialized
        const uploadedVideo = document.getElementById('uploaded-video-player');
        if (uploadedVideo && !uploadedVideo.hasAttribute('data-plyr-initialized')) {
            // Check if parent is visible (explanation box)
            const explanationBox = uploadedVideo.closest('.explanation-box');
            const isVisible = !explanationBox || !explanationBox.hidden;
            
            // Only initialize if visible or if we're forcing initialization
            if (isVisible || explanationBox === null) {
                uploadedVideo.setAttribute('data-plyr-initialized', 'true');
                
                try {
                    const player = new Plyr('#uploaded-video-player', {
                        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen'],
                        settings: ['quality', 'speed'],
                        ratio: '16:9'
                    });
                    
                    // Store player instance for potential cleanup
                    uploadedVideo.plyrInstance = player;
                } catch (error) {
                    console.error('Error initializing uploaded video player:', error);
                    // Remove the initialized flag so we can retry
                    uploadedVideo.removeAttribute('data-plyr-initialized');
                }
            }
        }
    }

    // Initialize Plyr video players on page load
    // Wait for Plyr to be fully loaded
    if (typeof Plyr !== 'undefined') {
        document.addEventListener('DOMContentLoaded', function() {
            // Small delay to ensure DOM is fully ready
            setTimeout(initializeVideoPlayers, 100);
        });
    } else {
        // If Plyr loads asynchronously, wait for it
        window.addEventListener('load', function() {
            setTimeout(initializeVideoPlayers, 200);
        });
    }

    const radios = document.querySelectorAll('input[name="answer"]');
    const textarea = document.querySelector(".explanation");
    const submitBtn = document.querySelector(".submit-btn");
    const explainBtn = document.querySelector(".explain-btn");
    const explanationBox = document.querySelector(".explanation-box");
    const userDetails = document.querySelector(".user-details");
    const hiddenUserNameInput = document.querySelector(".user-name-input");

    // Name popup elements
    const nameModal = document.getElementById("nameModal");
    const closeNameModal = document.getElementById("closeNameModal");
    const nameSaveBtn = document.getElementById("nameSaveBtn");
    const nameSkipBtn = document.getElementById("nameSkipBtn");
    const popupUserName = document.getElementById("popupUserName");

    const pollForm = document.querySelector(".poll form");

    // Always start with explanation box hidden on page load
    if (explanationBox) {
        explanationBox.hidden = true;
    }

    // Enable textarea and submit button after selecting an option
    radios.forEach((radio) => {
        radio.addEventListener("change", () => {
            if (textarea) textarea.disabled = false;
            if (submitBtn) submitBtn.disabled = false;
        });
    });

    // Intercept submit to show name popup only if explanation has text
    if (submitBtn && pollForm && nameModal) {
        submitBtn.addEventListener("click", (e) => {
            // If already disabled (e.g., already submitted), let default behavior happen
            if (submitBtn.disabled) {
                return;
            }

            const hasExplanation =
                textarea && textarea.value && textarea.value.trim().length > 0;

            // If no explanation text, submit normally without popup
            if (!hasExplanation) {
                return;
            }

            // Otherwise, show name popup and delay real submit
            e.preventDefault();
            nameModal.style.display = "flex";
            if (popupUserName) {
                // Prefill from hidden input if available
                popupUserName.value =
                    hiddenUserNameInput && hiddenUserNameInput.value
                        ? hiddenUserNameInput.value
                        : "";
                popupUserName.focus();
            }
        });
    }

    const closeNamePopup = () => {
        if (nameModal) {
            nameModal.style.display = "none";
        }
    };

    if (closeNameModal) {
        closeNameModal.addEventListener("click", () => {
            closeNamePopup();
        });
    }

    if (nameModal) {
        nameModal.addEventListener("click", (e) => {
            if (e.target === nameModal) {
                closeNamePopup();
            }
        });
    }

    const submitFormWithName = (nameValue) => {
        if (hiddenUserNameInput) {
            hiddenUserNameInput.value = nameValue || "";
        }
        closeNamePopup();
        if (pollForm) {
            pollForm.submit();
        }
    };

    if (nameSaveBtn) {
        nameSaveBtn.addEventListener("click", () => {
            const value = popupUserName ? popupUserName.value.trim() : "";
            submitFormWithName(value);
        });
    }

    if (nameSkipBtn) {
        nameSkipBtn.addEventListener("click", () => {
            submitFormWithName("");
        });
    }

    if (submitBtn && explainBtn && explanationBox) {
        // Update button icon based on explanation box visibility
        const updateButtonIcon = () => {
            const icon = explainBtn.querySelector('i');
            if (icon) {
                icon.className = explanationBox.hidden 
                    ? 'fa-solid fa-chevron-down' 
                    : 'fa-solid fa-chevron-up';
            }
        };
        
        // Set initial icon state
        updateButtonIcon();
        
        // After successful submission, the server can show a message.
        // The explanation button will simply toggle the explanation box.
        explainBtn.addEventListener("click", () => {
            explanationBox.hidden = !explanationBox.hidden;
            updateButtonIcon();
            
            if (!explanationBox.hidden) {
                explanationBox.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
                // Reinitialize video players when explanation box is shown
                // Small delay to ensure element is fully visible
                setTimeout(() => {
                    initializeVideoPlayers();
                }, 100);
            }
        });
    }

    // Video rotation functionality for mobile
    document.querySelectorAll('.video-rotate-btn').forEach((rotateBtn) => {
        rotateBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const videoWrapper = this.closest('.video-solution-wrapper');
            const videoContainer = videoWrapper.querySelector('.video-container-rotatable');
            
            if (videoContainer) {
                const isRotated = videoContainer.classList.contains('rotated');
                
                if (isRotated) {
                    // Close rotation
                    videoContainer.classList.remove('rotated');
                    document.body.style.overflow = '';
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.className = 'fa-solid fa-rotate';
                    }
                    this.style.position = '';
                    this.style.top = '';
                    this.style.right = '';
                    this.style.zIndex = '';
                } else {
                    // Open rotation
                    videoContainer.classList.add('rotated');
                    document.body.style.overflow = 'hidden';
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.className = 'fa-solid fa-rotate-left';
                    }
                    // Position button at top right when rotated
                    this.style.position = 'fixed';
                    this.style.top = '20px';
                    this.style.right = '20px';
                    this.style.zIndex = '10000';
                }
            }
        });
    });

    // Close rotated video when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.video-container-rotatable') && !e.target.closest('.video-rotate-btn')) {
            document.querySelectorAll('.video-container-rotatable.rotated').forEach((container) => {
                container.classList.remove('rotated');
                const rotateBtn = container.closest('.video-solution-wrapper')?.querySelector('.video-rotate-btn');
                if (rotateBtn) {
                    const icon = rotateBtn.querySelector('i');
                    if (icon) {
                        icon.className = 'fa-solid fa-rotate';
                    }
                    rotateBtn.style.position = '';
                    rotateBtn.style.top = '';
                    rotateBtn.style.right = '';
                    rotateBtn.style.zIndex = '';
                }
                document.body.style.overflow = '';
            });
        }
    });

    // Close rotation on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.video-container-rotatable.rotated').forEach((container) => {
                container.classList.remove('rotated');
                const rotateBtn = container.closest('.video-solution-wrapper')?.querySelector('.video-rotate-btn');
                if (rotateBtn) {
                    const icon = rotateBtn.querySelector('i');
                    if (icon) {
                        icon.className = 'fa-solid fa-rotate';
                    }
                }
                document.body.style.overflow = '';
            });
        }
    });

    // Like / Dislike functionality with AJAX
    document.querySelectorAll(".comment-card").forEach((card) => {
        const likeBtn = card.querySelector(".like-btn");
        const dislikeBtn = card.querySelector(".dislike-btn");
        
        if (!likeBtn || !dislikeBtn) {
            return; // Skip if buttons don't exist
        }
        
        const likeCount = likeBtn.querySelector("span");
        const dislikeCount = dislikeBtn.querySelector("span");
        const responseId = likeBtn.getAttribute("data-response-id");

        // Handle like button click
        if (likeBtn && likeCount && responseId) {
            likeBtn.addEventListener("click", function() {
                const btn = this;
                const wasActive = btn.classList.contains("active");
                
                // Disable button during request
                btn.disabled = true;
                
                // Make AJAX call
                fetch("<?php echo base_url('toggle-comment-like'); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        response_id: responseId,
                        action_type: "like"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    btn.disabled = false;
                    if (data.success) {
                        // Update counts
                        if (likeCount) {
                            likeCount.textContent = data.likes || 0;
                        }
                        if (dislikeCount) {
                            dislikeCount.textContent = data.dislikes || 0;
                        }
                        
                        // Update active states
                        if (data.user_action === "like") {
                            btn.classList.add("active");
                            if (dislikeBtn) {
                                dislikeBtn.classList.remove("active");
                            }
                        } else {
                            btn.classList.remove("active");
                        }
                    } else {
                        alert(data.message || "Failed to update like. Please try again.");
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    console.error("Error:", error);
                    alert("An error occurred. Please try again.");
                });
            });
        }

        // Handle dislike button click
        if (dislikeBtn && dislikeCount && responseId) {
            dislikeBtn.addEventListener("click", function() {
                const btn = this;
                const wasActive = btn.classList.contains("active");
                
                // Disable button during request
                btn.disabled = true;
                
                // Make AJAX call
                fetch("<?php echo base_url('toggle-comment-like'); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        response_id: responseId,
                        action_type: "dislike"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    btn.disabled = false;
                    if (data.success) {
                        // Update counts
                        if (likeCount) {
                            likeCount.textContent = data.likes || 0;
                        }
                        if (dislikeCount) {
                            dislikeCount.textContent = data.dislikes || 0;
                        }
                        
                        // Update active states
                        if (data.user_action === "dislike") {
                            btn.classList.add("active");
                            if (likeBtn) {
                                likeBtn.classList.remove("active");
                            }
                        } else {
                            btn.classList.remove("active");
                        }
                    } else {
                        alert(data.message || "Failed to update dislike. Please try again.");
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    console.error("Error:", error);
                    alert("An error occurred. Please try again.");
                });
            });
        }
    });

    // Load More Comments functionality
    const viewMoreBtn = document.getElementById("viewMoreCommentsBtn");
    if (viewMoreBtn) {
        viewMoreBtn.addEventListener("click", function() {
            const btn = this;
            const questionId = btn.getAttribute("data-question-id");
            const currentOffset = parseInt(btn.getAttribute("data-offset")) || 15;
            
            // Disable button during request
            btn.disabled = true;
            btn.textContent = "Loading...";
            
            // Make AJAX call
            fetch("<?php echo base_url('load-more-comments'); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    question_id: questionId,
                    offset: currentOffset
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = "View More";
                
                if (data.success && data.comments && data.comments.length > 0) {
                    // Get the comments list container
                    const commentsList = document.querySelector(".comments-list");
                    const viewMoreWrapper = document.querySelector(".view-more-comments-wrapper");
                    
                    // Render new comments
                    data.comments.forEach(function(comment) {
                        const commentCard = createCommentCard(comment);
                        if (commentsList && viewMoreWrapper) {
                            commentsList.insertBefore(commentCard, viewMoreWrapper);
                        }
                    });
                    
                    // Update offset for next load (always increment by 15, not by returned count)
                    const newOffset = currentOffset + 15;
                    btn.setAttribute("data-offset", newOffset);
                    
                    // Hide button if no more comments
                    if (!data.has_more) {
                        if (viewMoreWrapper) {
                            viewMoreWrapper.style.display = "none";
                        }
                    }
                    
                    // Re-initialize like/dislike handlers for new comments
                    initializeLikeDislikeHandlers();
                } else {
                    // No more comments or error
                    if (viewMoreWrapper) {
                        viewMoreWrapper.style.display = "none";
                    }
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.textContent = "View More";
                console.error("Error:", error);
                alert("An error occurred while loading comments. Please try again.");
            });
        });
    }
    
    // Function to create a comment card element
    function createCommentCard(comment) {
        const card = document.createElement("div");
        card.className = "comment-card";
        card.setAttribute("data-comment-id", comment.id);
        
        // Calculate time label
        const createdDate = new Date(comment.created_at);
        const now = new Date();
        const daysAgo = Math.floor((now - createdDate) / (1000 * 60 * 60 * 24));
        let timeLabel = "Today";
        if (daysAgo === 1) {
            timeLabel = "1 day ago";
        } else if (daysAgo > 1) {
            timeLabel = daysAgo + " days ago";
        }
        
        const name = comment.user_name || "User";
        const commentText = comment.user_explanation || "";
        const likesCount = comment.likes_count || 0;
        const dislikesCount = comment.dislikes_count || 0;
        const likeActive = (comment.user_vote === "like") ? "active" : "";
        const dislikeActive = (comment.user_vote === "dislike") ? "active" : "";
        
        card.innerHTML = `
            <div class="comment-header">
                <img src="<?php echo base_url('assets/home/images/user1.jpg'); ?>" alt="User" class="avatar" />
                <div>
                    <h4 class="username">${escapeHtml(name)}</h4>
                    <p class="time">${timeLabel}</p>
                </div>
            </div>
            <p class="comment-text">${escapeHtml(commentText).replace(/\n/g, '<br>')}</p>
            <div class="comment-actions">
                <button class="like-btn ${likeActive}" type="button" data-response-id="${comment.id}" data-action="like">
                    <i class="fa-solid fa-thumbs-up"></i>
                    <span>${likesCount}</span>
                </button>
                <button class="dislike-btn ${dislikeActive}" type="button" data-response-id="${comment.id}" data-action="dislike">
                    <i class="fa-solid fa-thumbs-down"></i>
                    <span>${dislikesCount}</span>
                </button>
            </div>
        `;
        
        return card;
    }
    
    // Function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Function to initialize like/dislike handlers for comment cards
    function initializeLikeDislikeHandlers() {
        document.querySelectorAll(".comment-card").forEach((card) => {
            // Skip if already initialized
            if (card.hasAttribute("data-handlers-initialized")) {
                return;
            }
            card.setAttribute("data-handlers-initialized", "true");
            
            const likeBtn = card.querySelector(".like-btn");
            const dislikeBtn = card.querySelector(".dislike-btn");
            
            if (!likeBtn || !dislikeBtn) {
                return;
            }
            
            const likeCount = likeBtn.querySelector("span");
            const dislikeCount = dislikeBtn.querySelector("span");
            const responseId = likeBtn.getAttribute("data-response-id");

            // Handle like button click
            if (likeBtn && likeCount && responseId) {
                likeBtn.addEventListener("click", function() {
                    const btn = this;
                    btn.disabled = true;
                    
                    fetch("<?php echo base_url('toggle-comment-like'); ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: new URLSearchParams({
                            response_id: responseId,
                            action_type: "like"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        btn.disabled = false;
                        if (data.success) {
                            if (likeCount) {
                                likeCount.textContent = data.likes || 0;
                            }
                            if (dislikeCount) {
                                dislikeCount.textContent = data.dislikes || 0;
                            }
                            
                            if (data.user_action === "like") {
                                btn.classList.add("active");
                                if (dislikeBtn) {
                                    dislikeBtn.classList.remove("active");
                                }
                            } else {
                                btn.classList.remove("active");
                            }
                        } else {
                            alert(data.message || "Failed to update like. Please try again.");
                        }
                    })
                    .catch(error => {
                        btn.disabled = false;
                        console.error("Error:", error);
                        alert("An error occurred. Please try again.");
                    });
                });
            }

            // Handle dislike button click
            if (dislikeBtn && dislikeCount && responseId) {
                dislikeBtn.addEventListener("click", function() {
                    const btn = this;
                    btn.disabled = true;
                    
                    fetch("<?php echo base_url('toggle-comment-like'); ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: new URLSearchParams({
                            response_id: responseId,
                            action_type: "dislike"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        btn.disabled = false;
                        if (data.success) {
                            if (likeCount) {
                                likeCount.textContent = data.likes || 0;
                            }
                            if (dislikeCount) {
                                dislikeCount.textContent = data.dislikes || 0;
                            }
                            
                            if (data.user_action === "dislike") {
                                btn.classList.add("active");
                                if (likeBtn) {
                                    likeBtn.classList.remove("active");
                                }
                            } else {
                                btn.classList.remove("active");
                            }
                        } else {
                            alert(data.message || "Failed to update dislike. Please try again.");
                        }
                    })
                    .catch(error => {
                        btn.disabled = false;
                        console.error("Error:", error);
                        alert("An error occurred. Please try again.");
                    });
                });
            }
        });
    }

    // Disable right-click and text selection
    document.addEventListener("contextmenu", (event) => event.preventDefault());
    document.addEventListener("selectstart", (event) => event.preventDefault());

    // Share Popup Functionality
    const shareBtn = document.getElementById("shareBtn");
    const shareModal = document.getElementById("shareModal");
    const closeShareModal = document.getElementById("closeShareModal");
    const copyUrlBtn = document.getElementById("copyUrlBtn");
    const whatsappShareBtn = document.getElementById("whatsappShareBtn");
    const copySuccessMsg = document.getElementById("copySuccessMsg");

    // Open share modal
    if (shareBtn) {
        shareBtn.addEventListener("click", (e) => {
            e.preventDefault();
            shareModal.style.display = "flex";
        });
    }

    // Close share modal
    if (closeShareModal) {
        closeShareModal.addEventListener("click", () => {
            shareModal.style.display = "none";
            copySuccessMsg.style.display = "none";
        });
    }

    // Close modal when clicking outside
    if (shareModal) {
        shareModal.addEventListener("click", (e) => {
            if (e.target === shareModal) {
                shareModal.style.display = "none";
                copySuccessMsg.style.display = "none";
            }
        });
    }

    // Copy URL functionality
    if (copyUrlBtn) {
        copyUrlBtn.addEventListener("click", async () => {
            const currentUrl = window.location.href;
            try {
                await navigator.clipboard.writeText(currentUrl);
                copySuccessMsg.style.display = "block";
                setTimeout(() => {
                    copySuccessMsg.style.display = "none";
                }, 2000);
            } catch (err) {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = currentUrl;
                textArea.style.position = "fixed";
                textArea.style.opacity = "0";
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand("copy");
                    copySuccessMsg.style.display = "block";
                    setTimeout(() => {
                        copySuccessMsg.style.display = "none";
                    }, 2000);
                } catch (err) {
                    alert("Failed to copy URL. Please copy manually: " + currentUrl);
                }
                document.body.removeChild(textArea);
            }
        });
    }

    // WhatsApp share functionality
    if (whatsappShareBtn) {
        whatsappShareBtn.addEventListener("click", (e) => {
            const currentUrl = encodeURIComponent(window.location.href);
            const message = encodeURIComponent("Check out this Question of the Day!");
            whatsappShareBtn.href = `https://wa.me/?text=${message}%20${currentUrl}`;
        });
    }

    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const headerRightGroup = document.querySelector('.header-right-group');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const navDropdowns = document.querySelectorAll('.nav-dropdown');
    const body = document.body;

    function closeMobileMenu() {
        if (mobileMenuToggle) mobileMenuToggle.classList.remove('active');
        if (headerRightGroup) headerRightGroup.classList.remove('active');
        body.classList.remove('menu-open');
    }

    if (mobileMenuToggle && headerRightGroup) {
        // Toggle mobile menu
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenuToggle.classList.toggle('active');
            headerRightGroup.classList.toggle('active');
            body.classList.toggle('menu-open');
        });

        // Close menu when clicking overlay
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', function() {
                closeMobileMenu();
            });
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = headerRightGroup.contains(event.target);
            const isClickOnToggle = mobileMenuToggle.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnToggle && headerRightGroup.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        // Handle dropdown toggles on mobile
        navDropdowns.forEach(function(dropdown) {
            const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
            if (dropdownToggle) {
                dropdownToggle.addEventListener('click', function(e) {
                    if (window.innerWidth <= 992) {
                        e.preventDefault();
                        dropdown.classList.toggle('active');
                    }
                });
            }
        });

        // Close menu on window resize if it's larger than mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                closeMobileMenu();
                navDropdowns.forEach(function(dropdown) {
                    dropdown.classList.remove('active');
                });
            }
        });
    }
</script>