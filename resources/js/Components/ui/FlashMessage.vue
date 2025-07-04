<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const flash = computed(() => page.props.flash);

const messageSuccess = ref("");
const messageError = ref("");

const showSuccess = ref(false);
const showError = ref(false);

watch(
    () => flash.value.success,
    (val) => {
        if (val) {
            // Reset dulu supaya trigger tetap berlaku
            showSuccess.value = false;
            setTimeout(() => {
                messageSuccess.value = val;
                showSuccess.value = true;
                setTimeout(() => {
                    showSuccess.value = false;
                }, 3000);
            }, 50); // kecilkan delay untuk reset cycle
        }
    },
    { immediate: true }
);

watch(
    () => flash.value.error,
    (val) => {
        if (val) {
            showError.value = false;
            setTimeout(() => {
                messageError.value = val;
                showError.value = true;
                setTimeout(() => {
                    showError.value = false;
                }, 3000);
            }, 50);
        }
    },
    { immediate: true }
);
</script>

<template>
    <div class="fixed top-4 right-4 z-50 space-y-2 w-72">
        <transition name="fade" mode="out-in">
            <div
                v-if="showSuccess"
                class="px-4 py-3 rounded-md text-sm bg-green-100 text-green-700 shadow"
            >
                {{ messageSuccess }}
            </div>
        </transition>
        <transition name="fade" mode="out-in">
            <div
                v-if="showError"
                class="px-4 py-3 rounded-md text-sm bg-red-100 text-red-700 shadow"
            >
                {{ messageError }}
            </div>
        </transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
