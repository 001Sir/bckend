<template>
    <transition name="modal-fade">
      <div class="absolute z-50">
        <div
          class="hidden md:block  fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        ></div>
  
        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div
            class="
              flex
              min-h-full
              items-center
              justify-center
              md:p-4
              text-center
              sm:items-center sm:p-0
            "
          >
            <div
              class="
                w-full
                h-screen
                md:w-auto
                md:h-auto
                relative
                transform
                overflow-hidden
                md:rounded-lg
                bg-white
                text-left
                shadow-xl
                transition-all
                md:my-8
              "
            >
              <div class="bg-white px-4 py-10">
                <div class="sm:flex sm:items-start">
                  <div class="w-full mt-3 mx-4 text-center sm:mt-0 sm:text-left">
                    <div class="w-full flex justify-between">
                      <h3
                        class="w-2/3 text-lg text-left font-medium leading-6 text-gray-900"
                        id="modal-title"
                      >
                        <slot name="header"></slot>
                      </h3>
                      <a class="w-1/3 flex justify-end self-end cursor-pointer relative -top-5" @click="closeModal()">
                        <svg
                          class="w-8 h-8 text-grey hover:text-grey-dark"
                          width="32"
                          height="32"
                          viewBox="0 0 24 24"
                        >
                          <path
                            fill="currentColor"
                            d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"
                          />
                        </svg>
                      </a>
                    </div>
  
                    <div class="mt-4">
                      <slot name="body"></slot>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </template>
  
  <script>
  export default {
    name: "Modal",
    methods: {
      closeModal() {
        this.$emit("closeModal");
      },
    },
  };
  </script>
  
  <style>
  .modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal {
    background: #ffffff;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
    border-radius: 10px;
    width: 80%;
  }
  
  .modal-header,
  .modal-footer {
    padding: 15px;
    display: flex;
  }
  
  .modal-header {
    position: relative;
    border-bottom: 1px solid rgb(227, 231, 233);
    color: blue;
    justify-content: space-between;
  }
  
  .modal-footer {
    border-top: 1px solid rgb(227, 231, 233);
    flex-direction: column;
    justify-content: flex-end;
  }
  
  .modal-body {
    position: relative;
    padding: 20px 10px;
  }
  
  .close-btn {
    position: absolute;
    top: 0;
    right: 0;
    border: none;
    font-size: 20px;
    padding: 10px;
    cursor: pointer;
    font-weight: bold;
    color: red;
    background: transparent;
  }
  
  .btn-open-modal {
    color: white;
    background: green;
    border: 1px solid green;
    border-radius: 4px;
    margin: 20px auto;
    padding: 5px;
    width: 40%;
  }
  .modal-fade-enter,
  .modal-fade-leave-to {
    opacity: 0;
  }
  
  .modal-fade-enter-active,
  .modal-fade-leave-active {
    transition: opacity 0.5s ease;
  }
  </style>