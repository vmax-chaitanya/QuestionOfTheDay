<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#3b82f6" />
    <title>Brainwiz</title> 
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/admin/images/favicon.png'); ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/metismenujs/dist/metismenujs.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/styles.css'); ?>" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        prefix: "tw-",
        corePlugins: {
          preflight: false,
        },
        theme: {
          extend: {
            colors: {
              primary: "var(--bw-primary)",
            },
          },
        },
      };
    </script>
    <style type="text/tailwindcss">
      @layer components {
        .badge {
          &.text-bg-primary {
            @apply !tw-bg-blue-400;
          }
          &.text-bg-danger {
            @apply !tw-bg-red-400;
          }
        }
        .menu-swiper {
          @apply tw-relative tw-mb-[20px] tw-px-[10px] bw-card;
          .swiper {
            .swiper-slide {
              @apply tw-w-auto;
              &:not(:last-child) {
                @apply tw-border-0 tw-border-r tw-border-solid tw-border-gray-200/70;
              }
              a {
                @apply tw-flex tw-items-center tw-justify-center tw-px-[20px] tw-h-[50px] tw-no-underline tw-text-gray-800;
                &::before {
                  @apply tw-content-[''] tw-absolute tw-bottom-0 tw-left-1/2 tw-w-[50px] tw-h-[3px] tw-bg-gray-800 tw-rounded-full -tw-translate-x-1/2 tw-hidden;
                }
                &:hover {
                  @apply tw-bg-gray-50 tw-cursor-pointer;
                }
                &.active {
                  &::before {
                    @apply tw-block;
                  }
                }
              }
            }
          }
          [class*="menu-swiper-nav"] {
            @apply tw-absolute tw-z-[5] tw-top-1/2 -tw-translate-y-1/2 tw-size-[30px] tw-rounded-full tw-flex tw-items-center tw-justify-center bw-card;
            &.menu-swiper-nav-prev {
              @apply -tw-left-[15px];
            }
            &.menu-swiper-nav-next {
              @apply -tw-right-[15px];
            }
            &.swiper-button-disabled {
              @apply tw-hidden;
            }
          }
        }
        [data-layout="sidebar"] {
          @apply tw-fixed tw-z-[999] tw-left-0 tw-top-0 tw-bottom-0 tw-bg-gray-50 tw-shadow-[1px_0px_10px] tw-shadow-black/20 md:tw-shadow-[inset_-3px_0_3px] md:tw-shadow-black/5  tw-w-[300px] md:tw-w-[70px] tw-transition-all tw-duration-300 tw-ease-out tw-overflow-hidden tw-flex tw-flex-col;
          > div {
            @apply tw-flex-auto tw-flex tw-flex-col tw-min-h-0;
          }
          [data-layout="logo"] {
            @apply tw-flex tw-items-center tw-px-[23px] tw-py-[15px] tw-pb-0 tw-no-underline;
            img {
              @apply tw-h-[30px] tw-transition-all tw-duration-300 tw-ease-out;
              clip-path: polygon(0% 0%, 18% 0%, 18% 100%, 0% 100%);
            }
            [data-layout="sidebar-toggle"] {
              @apply tw-ml-auto tw-flex md:tw-hidden;
            }
          }
          & + [data-layout="sidebar-backdrop"] {
            @apply tw-fixed tw-left-0 tw-top-0 tw-bg-black/50 tw-w-full tw-h-full tw-z-[99] tw-backdrop-blur-lg tw-block md:tw-hidden tw-transition-all tw-duration-300 tw-ease-out;
          }
          [data-layout="menu"] {
            @apply tw-flex-auto tw-min-h-0;
            > ul {
              @apply !tw-p-[23px];
              &.metismenu {
                > li {
                  > a {
                    &.has-arrow {
                      &:after {
                        @apply tw-hidden tw-origin-center -tw-translate-y-[50%] -tw-rotate-[135deg];
                      }
                    }
                  }
                  &.mm-active {
                    > a {
                      &.has-arrow {
                        &:after {
                          @apply -tw-rotate-[45deg];
                        }
                      }
                    }
                  }
                }
              }
            }
            ul {
              @apply tw-list-none tw-p-0 tw-m-0 tw-flex tw-gap-[10px] tw-flex-col;
              > li {
                > a {
                  @apply tw-flex tw-gap-[20px] tw-items-center tw-text-gray-800 tw-font-semibold tw-no-underline tw-h-[40px] tw-cursor-pointer;
                  > i {
                    @apply tw-flex-none tw-size-[24px] tw-flex tw-items-center tw-justify-center tw-text-[20px] tw-opacity-30;
                  }
                  > span {
                    @apply tw-hidden tw-truncate tw-flex-auto;
                  }
                }
                &:hover,
                &.active,
                &.mm-active,
                &:has(ul > li.active) {
                  > a {
                    i {
                      @apply tw-opacity-100;
                    }
                    @apply tw-text-primary;
                  }
                }
              }
              ul {
                @apply tw-border-0 tw-border-l-2 tw-border-solid tw-border-gray-300 tw-pl-[30px] tw-ml-[12px] tw-gap-[0px];
              }
            }
          }
          &.compact {
            @apply -tw-translate-x-[120%] md:-tw-translate-x-0;
            &:hover {
              @apply tw-shadow-[1px_0px_10px] tw-shadow-black/20;
            }
            & + [data-layout="sidebar-backdrop"] {
              @apply tw-invisible tw-opacity-0;
            }
          }
          &.classic {
            @apply -tw-translate-x-0 md:-tw-translate-x-0;
            & + [data-layout="sidebar-backdrop"] {
              @apply tw-visible tw-opacity-100;
            }
          }
          &.compact:hover,
          &.classic {
            @apply tw-w-[300px];
            [data-layout="logo"] {
              img {
                clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%);
              }
            }
            [data-layout="menu"] {
              @apply tw-overflow-y-auto;
              > ul {
                &.metismenu {
                  li {
                    > a {
                      &.has-arrow {
                        &:after {
                          @apply tw-block;
                        }
                      }
                    }
                  }
                }
              }
              ul {
                > li {
                  > a {
                    > span {
                      @apply tw-block;
                    }
                  }
                }
              }
            }
          }
        }
        .bw-modal {
          .modal-header {
            @apply tw-bg-gray-50;
          }
          .modal-footer {
            @apply tw-border-0;
          }
        }
        .bw-upload {
          @apply tw-p-0 tw-m-0 tw-flex tw-flex-col;
          input {
            @apply tw-hidden;
          }
          > div {
            @apply tw-flex tw-flex-col tw-items-center tw-bg-gray-100 hover:tw-bg-gray-50 tw-cursor-pointer tw-border-2 tw-border-dashed tw-border-gray-300 tw-p-[20px] tw-rounded-lg;
            p {
              @apply tw-m-0 tw-text-gray-400 tw-text-[12px];
            }
          }
        }
        .bw-select-multi {
          & + * {
            .ts-control {
              @apply tw-h-[40px] tw-border-gray-300 tw-rounded-[10px];
            }
            .ts-dropdown {
              @apply bw-card tw-overflow-hidden;
            }
          }
        }
        .bw-form-control {
          @apply tw-min-h-[40px] tw-rounded-[10px] tw-border-gray-300;
          &.form-control-sm {
            @apply tw-min-h-[32px];
          }
          &.form-control-lg {
            @apply tw-min-h-[50px];
          }
        }
        .bw-btn {
          @apply tw-inline-flex tw-justify-center tw-items-center tw-gap-[10px] tw-min-h-[40px] tw-rounded-lg tw-px-[20px];
          &.btn-sm {
            @apply tw-min-h-[32px]  tw-px-[10px];
          }
          &.btn-lg {
            @apply tw-min-h-[50px]  tw-px-[25px];
          }
        }
        .btn-icon {
          @apply tw-flex-none tw-flex tw-items-center tw-justify-center tw-rounded-full tw-size-[40px] tw-p-0 [&.btn-default:hover]:tw-bg-gray-200;
          &.btn-sm {
            @apply tw-size-[32px];
          }
        }
        .bw-card {
          @apply tw-bg-white tw-border tw-border-solid tw-border-gray-200 tw-rounded-xl tw-shadow-[0_1px_4px] tw-shadow-black/15;
        }
        .fi {
          @apply tw-leading-[0px] tw-align-middle;
        }
        .bw-table {
          @apply tw-whitespace-nowrap;
          thead {
            tr {
              th,
              td {
                @apply tw-px-[20px] tw-py-[10px] tw-uppercase tw-text-gray-400 tw-text-[14px];
              }
              &:first-child {
                th,
                td {
                  @apply tw-bg-gray-50;
                }
              }
            }
          }
          tbody {
            tr {
              th,
              td {
                @apply tw-px-[20px] tw-py-[10px];
              }
              &:last-child {
                th,
                td {
                  @apply tw-border-0;
                }
              }
            }
          }
        }
      }
      .bw-dropdown {
        @apply tw-w-[240px] bw-card;
        .dropdown-item {
          @apply tw-min-h-[38px] tw-flex tw-items-center tw-gap-[15px];
          i {
            @apply tw-text-primary;
          }
        }
      }
      .bw-dropdown-filter {
        @apply tw-p-0 !tw-w-[350px] [&.show]:tw-flex-col tw-flex-col;
        .bw-dropdown-filter-header {
          @apply tw-border-0 tw-border-b tw-border-solid tw-border-gray-300 tw-min-h-[40px] tw-flex tw-items-center tw-px-[20px];
          .bw-dropdown-filter-title {
            @apply tw-flex-auto  tw-text-[18px] tw-font-bold;
          }
          .bw-dropdown-filter-close {
            @apply -tw-mr-[10px];
          }
        }
        .bw-dropdown-filter-body {
          @apply tw-flex tw-flex-col tw-p-[20px];
        }
        .bw-dropdown-filter-footer {
          @apply tw-border-0 tw-border-t tw-border-solid tw-border-gray-300 tw-min-h-[40px] tw-flex tw-items-center tw-px-[20px] tw-py-[10px];
        }
      }
      .bw-search {
        @apply tw-relative;
        i {
          @apply tw-absolute tw-left-[10px] tw-top-1/2 -tw-translate-y-1/2;
        }
        .form-control {
          @apply tw-pl-[40px];
        }
      }
      .floating-label {
        @apply tw-relative;
        label {
          @apply tw-pointer-events-none	 tw-text-[14px] tw-px-[2px] tw-transition-all tw-duration-300 tw-ease-out tw-absolute tw-top-1/2 -tw-translate-y-1/2 tw-left-[10px] tw-bg-white;
        }
        &:has(textarea) {
          label {
            @apply tw-top-[20%];
          }
        }
        &:has(input:focus, input:not(:placeholder-shown)) {
          label {
            @apply tw-top-0 tw-text-[12px];
          }
        }
      }
    </style>
  </head>
  <body>
    
<div class="tw-bg-cover tw-bg-right-top	tw-bg-[url(https://images.unsplash.com/photo-1634655377962-e6e7b446e7e9?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D)] tw-min-h-screen tw-flex tw-justify-end">
  <div class="tw-flex-none tw-w-[500px] tw-max-w-full tw-shadow-[-5px_0_20px] tw-shadow-black/5 tw-backdrop-blur-lg tw-bg-gradient-to-tr tw-from-white/90 tw-to-white/30 tw-flex tw-flex-col tw-justify-center tw-py-[40px] tw-px-[20px] lg:tw-p-[40px]">
      <div class="tw-w-[350px] tw-max-w-full tw-mx-auto">
        <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/admin/images/logo.svg'); ?>" class="tw-h-[30px] tw-max-w-full tw-mb-[20px]" /></a>
          
 
<form action="<?php echo base_url('admin/AuthController/login'); ?>" method="post">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger tw-mb-[20px]" role="alert">
      <?php echo $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>
  
  <div class="floating-label tw-mb-[20px]">
    <input type="text" name="username" id="username" class="form-control bw-form-control" placeholder=" " required />
    <label for="username">Username</label>
  </div>
  <div class="floating-label tw-mb-[20px]">
    <input type="password" name="password" id="password" class="form-control bw-form-control" placeholder=" " required />
    <label for="password">Password</label>
  </div>
  <div class="tw-flex tw-flex-col tw-gap-[10px]"> 
    <button type="submit" class="btn btn-primary bw-btn tw-self-start">Sign In <i class="fi fi-rr-angle-right"></i></button> 
  </div>
</form>
 
      </div>
  </div>
  </div>
</div>

    <script src="<?php echo base_url('assets/admin/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/metismenujs"></script>
    <script src="<?php echo base_url('assets/admin/js/scripts.js'); ?>"></script>
    
  </body>
</html>