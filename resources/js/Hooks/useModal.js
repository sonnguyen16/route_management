import { ref, onMounted, onUnmounted } from 'vue';

export function useModal(modalId) {
    const modalInstance = ref(null);

    function showModal() {
        if (modalInstance.value) {
            modalInstance.value.show();
        }
    }

    function hideModal() {
        if (modalInstance.value) {
            modalInstance.value.hide();
        }
    }

    onMounted(() => {
        modalInstance.value = new bootstrap.Modal(document.getElementById(modalId), {
            backdrop: 'static'
        });
    });

    onUnmounted(() => {
        modalInstance.value = null;
    });

    return {
        modalInstance,
        showModal,
        hideModal
    };
}
