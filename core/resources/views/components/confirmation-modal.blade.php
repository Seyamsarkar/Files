<div class="modal fade" id="confirmationModal" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="question"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark close-btn" data-bs-dismiss="modal" type="button">@lang('No')</button>
                    <button class="btn btn--primary submit-btn" type="submit">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.confirmationBtn', function() {
                var modal = $('#confirmationModal');
                let data = $(this).data();
                if (data.submit_btn) {
                    modal.find('.submit-btn').addClass(`${data.submit_btn}`);
                    modal.find('.close-btn').removeClass(`btn btn--dark`);
                    modal.find('.close-btn').addClass(`btn btn--danger btn-sm`);
                }
                modal.find('form').attr('action', `${data.action}`);
                modal.find('.question').text(`${data.question}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
