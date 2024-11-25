<div class="modal fade" id="modal-send" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-send" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Send Message</h3>
                </div>

                <div class="modal-body">
                    <div class="box-body">
                        <input type="hidden" id="message_id" name="message_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Group</label>
                                    <select id="group" name="group" class="form-control">
                                        <option disabled>--select group--</option>
                                        <option value="platnum">Platnum</option>
                                        <option value="gold">Gold</option>
                                        <option value="silver">Silver</option>
                                        <option value="bronze">Bronze</option>
                                    </select>

                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->