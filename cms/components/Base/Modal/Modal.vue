<script setup lang="ts">
export interface Props {
  fullHeight?: boolean;
  width?: string;
  backdrop?: "" | "static";
  showCloseIcon?: boolean;
  modalWrapperClass?: string;
  modalHeaderClass?: string;
  modalBodyClass?: string;
  modalFooterClass?: string;
  backdropClass?: string;
}

const props = withDefaults(defineProps<Props>(), {
  fullHeight: true,
  width: "800px",
  backdrop: "",
  showCloseIcon: true,
  modalWrapperClass: "",
  modalHeaderClass: "",
  modalBodyClass: "",
  modalFooterClass: "",
  backdropClass: "",
});

const emit = defineEmits<{
  "backdrop-click": [];
}>();

const isOpen = defineModel();
const bodyHeight = computed(() => (props.fullHeight ? "75vh" : "auto"));
const modalContainer = ref<HTMLElement>();
function backdropClick() {
  emit("backdrop-click");
  if (props.backdrop === "static") {
    modalContainer.value?.classList.add("tw-shake");
    const styleTimeout = setTimeout(() => {
      modalContainer.value?.classList.remove("tw-shake");
      clearTimeout(styleTimeout);
    }, 1000);
    return;
  }
  isOpen.value = !isOpen.value;
}

onMounted(() => {
  watch(isOpen, (newValue) => {
    if (newValue) {
      document.body.classList.add("overflow-hidden");
    } else {
      document.body.classList.remove("overflow-hidden");
    }
  });
});
</script>

<template>
  <div>
    <teleport to="body">
      <transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="isOpen"
          @click.self="backdropClick"
          class="fixed inset-0 transform transition-all z-50 bg-gray-900 !bg-opacity-50 pt-12"
          :class="[props.backdropClass]"
        >
          <transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div
              v-if="isOpen"
              ref="modalContainer"
              class="overflow-x-hidden overflow-y-auto z-40 outline-none focus:outline-none justify-center flex px-2 duration-50 transition-all"
              @click.self="backdropClick"
            >
              <div
                class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white dark:bg-gray-800 dark:text-gray-300 outline-none focus:outline-none max-h-[80vh]"
                :class="[props.modalWrapperClass]"
                :style="{ width: props.width }"
              >
                <slot name="header">
                  <div
                    class="flex items-center justify-between p-5 border-b border-solid border-slate-200 dark:border-gray-700 rounded-t px-6"
                    :class="[props.modalHeaderClass]"
                  >
                    <slot name="title"></slot>
                    <slot name="headerCloseButton">
                      <button
                        v-if="props.showCloseIcon"
                        class="duration-200 p-1 ml-auto transition-opacity bg-transparent border-0 text-black opacity-30 hover:opacity-60 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                        @click="isOpen = !isOpen"
                      >
                        <Icon name="ic:sharp-close" />
                      </button>
                    </slot>
                  </div>
                </slot>
                <div
                  class="relative p-6 flex-auto overflow-y-auto"
                  :class="[props.modalBodyClass]"
                  :style="{ height: bodyHeight }"
                >
                  <slot name="default"></slot>
                </div>
                <div
                  class="flex items-center p-6 border-t dark:border-gray-700 border-solid border-slate-200 rounded-b"
                  :class="[props.modalFooterClass]"
                >
                  <slot name="footer"></slot>
                </div>
              </div>
            </div>
          </transition>
        </div>
      </transition>
    </teleport>
  </div>
</template>
