<template>
  <!-- Modal -->
  <div
    class="modal fade"
    id="confirmAction"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modelTitleId"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm action</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">Esti sigur in aceasta actiune?</div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Close
          </button>
          <button
            @click="$emit('listAction', confirmActionName)"
            type="button"
            class="btn btn-primary"
            data-bs-dismiss="modal"
          >
            Da
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["confirmActionName"],
};
</script>