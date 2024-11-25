<div class="modal fade" id="modal-message-form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-message" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">Add Message</h3>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Hidden ID field -->
                        <input type="hidden" id="id" name="id">

                        <!-- Title Input -->
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <!-- Message Input with Character Counter -->
                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea class="form-control" rows="4" name="message" id="message" maxlength="160" required></textarea>
                            <small id="char-counter" class="text-muted">160 characters remaining</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const messageInput = document.getElementById('message');
    const charCounter = document.getElementById('char-counter');
    const maxLength = 160;

    messageInput.addEventListener('input', function () {
        const remaining = maxLength - messageInput.value.length;
        charCounter.textContent = `${remaining} characters remaining`;
        charCounter.classList.toggle('text-danger', remaining < 0);
    });
});

</script>